<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $title     = 'Checkout';
        $cartItems = $this->cartService->getCartData();

        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        // ── Validasi stok di halaman checkout (tampilkan warning awal) ────────
        $stockErrors = $this->cartService->validateStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                ->with('error', implode(' ', $stockErrors));
        }

        $subtotal = array_reduce($cartItems, fn($c, $i) => $c + $i->price * $i->quantity, 0);

        $discount = 0;
        $couponSession = session('coupon');
        if ($couponSession) {
            $coupon = \App\Models\Coupon::find($couponSession['id']);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon');
            }
        }

        $discountedSubtotal = $subtotal - $discount;
        $tax        = $discountedSubtotal * config('shop.tax_rate', 0.11);
        $grandTotal = $discountedSubtotal + $tax;

        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->first();

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'grandTotal', 'address', 'title', 'discount'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate(['payment_type' => 'required|string']);

        // ── Simpan / ambil alamat ─────────────────────────────────────────────
        if ($request->has('is_default') && $request->is_default == 1) {
            $request->validate([
                'recipient_name' => 'required|string',
                'phone'          => 'required|string',
                'full_address'   => 'required|string',
                'province_name'  => 'required|string',
                'city_name'      => 'required|string',
                'district_name'  => 'required|string',
                'village_name'   => 'required|string',
                'postal_code'    => 'required|string',
            ]);

            $address = $user->addresses()->create([
                'label'          => $request->label ?? 'Home',
                'recipient_name' => $request->recipient_name,
                'phone'          => $request->phone,
                'province'       => $request->province_name,
                'city'           => $request->city_name,
                'district'       => $request->district_name,
                'village'        => $request->village_name,
                'postal_code'    => $request->postal_code,
                'full_address'   => $request->full_address,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'is_default'     => true,
            ]);
        } else {
            $address = $user->addresses()->where('is_default', true)->first()
                ?? $user->addresses()->first();
        }

        if (!$address) {
            return back()->with('error', 'Valid shipping address is required.');
        }

        // ── Kalkulasi cart ────────────────────────────────────────────────────
        $cartItems = $this->cartService->getCartData();

        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        // ── Validasi stok FINAL (double-check sebelum transaksi) ──────────────
        $stockErrors = $this->cartService->validateStock();
        if (!empty($stockErrors)) {
            return back()->with('error', implode(' ', $stockErrors));
        }

        $subtotal   = array_reduce($cartItems, fn($c, $i) => $c + $i->price * $i->quantity, 0);
        // Apply coupon discount
        $discount = 0;
        $couponId = null;
        $couponSession = session('coupon');

        if ($couponSession) {
            $coupon = \App\Models\Coupon::find($couponSession['id']);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
            } else {
                session()->forget('coupon');
            }
        }

        $grandTotal = ($subtotal - $discount) + (($subtotal - $discount) * config('shop.tax_rate', 0.11));

        // ── Transaksi DB ──────────────────────────────────────────────────────
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'              => $user->id,
                'order_date'           => now(),
                'total_price'          => $grandTotal,
                'status'               => 'pending',
                'user_address_id'      => $address->id,
                'shipping_name'        => $address->recipient_name,
                'shipping_phone'       => $address->phone,
                'shipping_address'     => $address->full_address,
                'shipping_city'        => $address->city,
                'shipping_postal_code' => $address->postal_code,
                'shipping_latitude'    => $address->latitude,
                'shipping_longitude'   => $address->longitude,
                'coupon_id'       => $couponId,
                'discount_amount' => $discount,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);
            }

            // ── Kurangi stok setelah order items tersimpan ────────────────────
            $this->cartService->decrementStockForCart();

            $this->cartService->clearCart();

            DB::commit();

            if ($couponId) {
                \App\Models\Coupon::where('id', $couponId)->increment('used_count');
                session()->forget('coupon');
            }
            // ── Integrasi Midtrans ─────────────────────────────────────────────
            Config::$serverKey    = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $enabled_payments = $request->payment_type == 'bank_transfer'
                ? ['bca_va', 'bni_va', 'bri_va', 'mandiri_va']
                : ['qris', 'gopay'];

            $midtransOrderId = 'NX-' . $order->id . '-' . time();

           // Deteksi cerdas untuk semua jenis Virtual Account & Transfer Bank
            $pType = strtolower($request->payment_type ?? '');
            $isBankTransfer = \Illuminate\Support\Str::contains($pType, ['transfer', 'va', 'virtual', 'echannel']);
            
            $expiryDuration = $isBankTransfer ? (24 * 60) : 15;

            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => (int) round($grandTotal),
                ],
                'customer_details' => [
                    'first_name' => $address->recipient_name,
                    'email'      => $user->email,
                    'phone'      => $address->phone,
                ],
                'enabled_payments' => $enabled_payments,
                'expiry' => [
                    'start_time' => $order->created_at->format('Y-m-d H:i:s O'),
                    'unit'       => 'minute',
                    'duration'   => $expiryDuration
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $order->update([
                'midtrans_order_id' => $midtransOrderId,
                'snap_token'        => $snapToken
            ]);

            $title = 'Awaiting Payment';
            return view('checkout.pay', compact('title', 'snapToken', 'order'));
        } catch (\RuntimeException $e) {
            // RuntimeException dari decrementStock = stok habis di detik terakhir (race condition)
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gateway Error: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);

        // Pastikan order milik user yang sedang login
        abort_if($order->user_id !== Auth::id(), 403);

        // Jika statusnya masih pending, ubah menjadi processing (Dikemas)
        if ($order->status === 'pending') {
            $order->update([
                'status' => 'processing'
            ]);
        }

        return view('checkout.success', compact('order'));
    }
}
