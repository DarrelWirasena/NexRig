<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * HELPER: Ambil data cart yang sudah distandarisasi (Object)
     * Sama persis logikanya dengan CartController
     */
    private function getCartData()
    {
        $cartItems = [];

        if (Auth::check()) {
            // A. LOGGED IN: Ambil dari DB
            $dbItems = CartItem::where('user_id', Auth::id())
                             ->with(['product']) // Load product untuk ambil harga/nama real-time
                             ->get();

            foreach ($dbItems as $item) {
                if (!$item->product) continue; // Skip jika produk terhapus

                $cartItems[] = (object) [
                    'product_id' => $item->product_id,
                    'name'       => $item->product->name,
                    'price'      => $item->product->price, // Ambil harga terbaru dari master product
                    'quantity'   => $item->quantity,
                    'image'      => $item->product->images->first()->src ?? 'https://placehold.co/100'
                ];
            }
        } else {
            // B. GUEST: Ambil dari Session
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

    /**
     * Menampilkan Halaman Checkout
     */
    public function index()
    {
        // 1. Ambil data cart terbaru
        $cartItems = $this->getCartData();

        // 2. Cek apakah keranjang kosong
        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // 3. Hitung Total (Server Side Calculation)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        $tax = $subtotal * 0.11;
        $grandTotal = $subtotal + $tax;

        // 4. Data User & Alamat
        // Kita asumsikan Checkout butuh Login. Jika Guest checkout diperbolehkan, logic ini harus di-if
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $address = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'grandTotal', 'address'));
    }

    /**
     * Memproses Transaksi (Place Order)
     */
    public function store(Request $request)
    {
        // Pastikan User Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'full_address'   => 'required|string',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
        ]);

        // 2. Ambil Ulang Data Cart (PENTING: Jangan percaya data total dari request frontend)
        $cartItems = $this->getCartData();

        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        // 3. Hitung Ulang Total Harga (Security Best Practice)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        $grandTotal = $subtotal + ($subtotal * 0.11); // Tax 11%

        // 4. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // A. Simpan / Update Alamat Default jika belum punya
            if ($user->addresses()->doesntExist()) {
                $user->addresses()->create([
                    'label'          => 'Home',
                    'recipient_name' => $request->recipient_name,
                    'phone'          => $request->phone,
                    'city'           => $request->city,
                    'postal_code'    => $request->postal_code,
                    'full_address'   => $request->full_address,
                    'is_default'     => true,
                ]);
            }

            // B. Simpan Order (Snapshot)
            $order = Order::create([
                'user_id'              => $user->id,
                'order_date'           => now(),
                'total_price'          => $grandTotal,
                'status'               => 'pending',
                // Snapshot Alamat Pengiriman
                'shipping_name'        => $request->recipient_name,
                'shipping_phone'       => $request->phone,
                'shipping_address'     => $request->full_address,
                'shipping_city'        => $request->city,
                'shipping_postal_code' => $request->postal_code,
            ]);

            // C. Simpan Item Pesanan
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id, // Akses object property
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,      // Harga saat transaksi terjadi
                ]);
            }

            // D. Hapus Keranjang
            // Hapus dari Database
            CartItem::where('user_id', $user->id)->delete();
            // Hapus dari Session (jaga-jaga)
            session()->forget('cart');

            DB::commit();
            
            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Halaman Sukses
     */
    public function success($id)
    {
        // Cari order, pastikan milik user yang sedang login
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
        
        return view('checkout.success', compact('order'));
    }
}