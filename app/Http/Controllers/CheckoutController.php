<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Menampilkan Halaman Checkout
     */
    public function index()
    {
        // 1. Cek apakah keranjang kosong
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('products.index');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 2. Ambil alamat default atau alamat pertama
        $address = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        return view('checkout.index', compact('address'));
    }

    /**
     * Memproses Transaksi (Place Order)
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input (Data ini datang dari Card Hidden Input atau Form Component)
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'full_address'   => 'required|string',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
        ]);

        // 2. LOGIKA OTOMATIS: Jika user belum punya alamat, simpan ke buku alamat
        if ($user->addresses()->doesntExist()) {
            $user->addresses()->create([
                'label'          => $request->label ?? 'Home',
                'recipient_name' => $request->recipient_name,
                'phone'          => $request->phone,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'full_address'   => $request->full_address,
                'is_default'     => true, // Otomatis jadi utama
            ]);
        }

        // 3. Hitung Total Harga
        $cart = session()->get('cart');
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.11; // Pajak 11%
        $grandTotal = $subtotal + $tax;

        // 4. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // A. Simpan Order (Snapshot Alamat)
            $order = Order::create([
                'user_id'              => $user->id,
                'order_date'           => now(),
                'total_price'          => $grandTotal,
                'status'               => 'pending',
                // Data Snapshot (disalin permanen ke tabel orders)
                'shipping_name'        => $request->recipient_name,
                'shipping_phone'       => $request->phone,
                'shipping_address'     => $request->full_address,
                'shipping_city'        => $request->city,
                'shipping_postal_code' => $request->postal_code,
            ]);

            // B. Simpan Item Pesanan
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['price'],
                ]);
            }

            DB::commit();
            
            // C. Hapus Keranjang
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            }
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Halaman Sukses
     */
    public function success($id)
    {
        // Cari order, pastikan milik user yang sedang login
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        return view('checkout.success', compact('order'));
    }
}