<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Proses Checkout (Simpan ke DB)
    public function store()
    {
        // 1. Cek User Login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        // 2. Cek Keranjang Kosong
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        // 3. Hitung Total
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // 4. Mulai Transaksi Database (Biar kalau error, gak kesimpen setengah2)
        DB::beginTransaction();

        try {
            // A. Simpan Header Pesanan
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'total_price' => $total,
                'status' => 'pending', // Status awal
            ]);

            // B. Simpan Detail Item
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'], // Harga saat beli (snapshot)
                ]);
            }

            // C. Kosongkan Keranjang
            session()->forget('cart');

            DB::commit(); // Simpan permanen
            
            return redirect()->route('home')->with('success', 'Order placed successfully! ID: ' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua kalau error
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}