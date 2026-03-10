<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    private function getCartData()
    {
        $cartItems = [];

        if (Auth::check()) {
            $dbItems = CartItem::where('user_id', Auth::id())
                ->with(['product.images'])
                ->get();

            foreach ($dbItems as $item) {
                if (!$item->product) continue;
                $cartItems[] = (object) [
                    'product_id' => $item->product_id,
                    'name'       => $item->product->name,
                    'price'      => $item->product->price,
                    'quantity'   => $item->quantity,
                    'image'      => $item->product->images->first()->src ?? 'https://placehold.co/100'
                ];
            }
        } else {
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $productId => $details) {
                $cartItems[] = (object) [
                    'product_id' => $productId,
                    'name'       => $details['name'],
                    'price'      => $details['price'],
                    'quantity'   => $details['quantity'],
                    'image'      => $details['image']
                ];
            }
        }

        return $cartItems;
    }

    public function index()
    {
        $title = 'Checkout';
        $cartItems = $this->getCartData();

        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        $tax        = $subtotal * config('shop.tax_rate');
        $grandTotal = $subtotal + $tax;

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->first();

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'grandTotal', 'address', 'title'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'recipient_name' => 'required',
            'phone'          => 'required',
            'full_address'   => 'required',
            'city'           => 'required',
            'postal_code'    => 'required',
            'payment_type'   => 'required',
        ]);

        $cartItems = $this->getCartData();
        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal   = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        $grandTotal = $subtotal + ($subtotal * config('shop.tax_rate', 0.11));

        // ── Ambil alamat default yang dipilih user ──────────────
        $address = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->first();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'              => $user->id,
                'order_date'           => now(),
                'total_price'          => $grandTotal,
                'status'               => 'pending',

                // Relasi ke alamat
                'user_address_id'      => $address?->id,

                // Snapshot data pengiriman
                'shipping_name'        => $request->recipient_name,
                'shipping_phone'       => $request->phone,
                'shipping_address'     => $request->full_address,
                'shipping_city'        => $request->city,
                'shipping_postal_code' => $request->postal_code,

                // ── Koordinat dari alamat terpilih ──────────────
                // Dipakai untuk live tracking map di order detail
                'shipping_latitude'    => $address?->latitude,
                'shipping_longitude'   => $address?->longitude,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);
            }

            DB::commit();

            // ── Midtrans ────────────────────────────────────────
            Config::$serverKey    = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $enabled_payments = $request->payment_type == 'bank_transfer'
                ? ['bca_va', 'bni_va', 'bri_va', 'mandiri_va']
                : ['qris', 'gopay'];

            $midtransOrderId = 'NX-' . $order->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => (int) round($grandTotal),
                ],
                'customer_details' => [
                    'first_name' => $request->recipient_name,
                    'email'      => $user->email,
                ],
                'enabled_payments' => $enabled_payments,
            ];

            $snapToken = Snap::getSnapToken($params);

            // Store Midtrans order ID in database
            $order->update(['midtrans_order_id' => $midtransOrderId]);

            $title = 'Awaiting Payment';
            return view('checkout.pay', compact('title', 'snapToken', 'order'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gateway Error: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $title = 'Order Success';
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        CartItem::where('user_id', Auth::id())->delete();
        session()->forget('cart');

        return view('checkout.success', compact('order', 'title'));
    }
}
