<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans webhook notification
     */
    public function webhook(Request $request)
    {
        try {
            // Configure Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Get notification data from Midtrans
            $notif = $request->all();

            // Verify signature for security
            if (!$this->verifySignature($notif)) {
                Log::warning('Invalid Midtrans signature', ['order_id' => $notif['order_id'] ?? null]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Find order by Midtrans order_id
            $order = Order::where('midtrans_order_id', $notif['order_id'])->first();

            if (!$order) {
                Log::warning('Order not found for Midtrans webhook', ['order_id' => $notif['order_id']]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Map Midtrans status to order status
            $transactionStatus = $notif['transaction_status'] ?? null;
            $paymentType = $notif['payment_type'] ?? null;

            // Update order status based on transaction status
            $newStatus = $this->mapStatus($transactionStatus);

            if ($newStatus) {
                $order->update(['status' => $newStatus]);

                Log::info('Order status updated from webhook', [
                    'order_id' => $order->id,
                    'midtrans_order_id' => $notif['order_id'],
                    'transaction_status' => $transactionStatus,
                    'new_status' => $newStatus,
                    'payment_type' => $paymentType,
                ]);
            }

            // Always return 200 OK to acknowledge receipt
            return response()->json(['status' => 'ok'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Still return 200 to prevent Midtrans from retrying
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    /**
     * Verify Midtrans signature for security
     */
    private function verifySignature($notif): bool
    {
        $orderId = $notif['order_id'] ?? null;
        $statusCode = $notif['status_code'] ?? null;
        $grossAmount = $notif['gross_amount'] ?? null;
        $signature = $notif['signature_key'] ?? null;
        $serverKey = config('midtrans.server_key');

        if (!$orderId || !$statusCode || !$grossAmount || !$signature) {
            return false;
        }

        // Calculate expected signature
        // Format: SHA512(order_id + status_code + gross_amount + server_key)
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Compare signatures (timing-safe comparison)
        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Map Midtrans transaction status to order status
     */
    private function mapStatus($transactionStatus): ?string
    {
        return match ($transactionStatus) {
            // Payment successful
            'settlement' => 'processing',

            // Payment still pending
            'pending' => 'pending',

            // Payment failed / cancelled
            'deny', 'cancel', 'expire' => 'cancelled',

            // Other statuses - don't update
            default => null,
        };
    }
}
