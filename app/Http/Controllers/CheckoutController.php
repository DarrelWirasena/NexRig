<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Menampilkan Halaman Checkout
    public function index()
    {
        // Cek cart kosong
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('products.index');
        }
        return view('checkout.index');
    }

    // Proses Checkout (Simpan ke DB)
    public function store(Request $request)
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

        // [BARU] 3. Validasi Input Alamat
        // Pastikan input form dari checkout/index.blade.php valid
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        // 4. Hitung Total
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // 5. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // A. Simpan Header Pesanan (Order)
            // [MODIFIKASI] Menambahkan data pengiriman ke tabel orders
            // Pastikan kolom-kolom ini sudah ada di migration 'orders' Anda
            // Jika belum ada, jalankan migration 'add_shipping_details_to_orders_table' dulu
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'total_price' => $total,
                'status' => 'pending', 
                // Data Shipping
                'shipping_name' => $request->name,
                'shipping_phone' => $request->phone,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_postal_code' => $request->postal_code,
            ]);

            // B. Simpan Detail Item (OrderItem)
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            // C. Kosongkan Keranjang
            session()->forget('cart');

            DB::commit(); // Simpan permanen
            
            // [MODIFIKASI PENTING] Redirect ke Halaman Success, bukan Home
            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua kalau error
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    // [METODE BARU] Menampilkan Halaman Order Success
    public function success($id)
    {
        // Ambil order berdasarkan ID, pastikan milik user yang sedang login (Security)
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}