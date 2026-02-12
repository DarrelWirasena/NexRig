<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. Tampilkan Halaman Cart
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('cart.index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    // 2. Tambah Barang ke Keranjang (DIPERBARUI)
    public function store(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);
        $cart = session()->get('cart', []);

        // Ambil gambar utama
        $image = $product->images->where('is_primary', true)->first();
        $imageUrl = $image ? $image->image_url : 'https://placehold.co/100';

        $quantityToAdd = $request->input('quantity', 1);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantityToAdd;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantityToAdd,
                "price" => $product->price,
                "image" => $imageUrl
            ];
        }

        session()->put('cart', $cart);

        // ======================================================
        // [BARU] CEK APAKAH INI REQUEST AJAX?
        // ======================================================
        if ($request->wantsJson() || $request->ajax()) {
            
            // 1. Render HTML item keranjang terbaru
            // Pastikan file resources/views/components/mini-cart-items.blade.php SUDAH ADA
            $cartHtml = view('components.mini-cart-items', ['cart' => $cart])->render();
            
            // 2. Hitung Subtotal Baru
            $subtotal = 0;
            foreach($cart as $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }

            // 3. Kirim respon JSON ke JavaScript
            return response()->json([
                'success' => true,
                'cartHtml' => $cartHtml,
                'subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.')
            ]);
        }
        // ======================================================
        
        // Fallback: Jika bukan AJAX (Browser biasa/JavaScript mati), lakukan redirect biasa
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // 3. (BARU) Update Jumlah Barang di Keranjang
    // Fitur ini penting agar user bisa ubah qty dari 1 jadi 5 di halaman cart
    public function update(Request $request)
    {
        if($request->id && $request->quantity) {
            $cart = session()->get('cart');
            
            // Pastikan jumlah tidak kurang dari 1
            $newQuantity = max(1, intval($request->quantity));
            
            $cart[$request->id]["quantity"] = $newQuantity;
            
            session()->put('cart', $cart);
            
            return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }

    // 4. Hapus Barang (DIPERBARUI)
    public function destroy(Request $request, $id)
    {
        $cart = session()->get('cart', []); // Default array kosong

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if ($request->wantsJson() || $request->ajax()) {
            // 1. Render partial view sisa item atau empty state
            $cartHtml = view('components.mini-cart-items', ['cart' => $cart])->render();
            
            // 2. Hitung Subtotal
            $subtotal = 0;
            foreach($cart as $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }

            return response()->json([
                'success' => true,
                'cartHtml' => $cartHtml,
                'subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                // KONTEKS TAMBAHAN: Kirim jumlah item tersisa
                'cartCount' => count($cart) 
            ]);
        }

        return redirect()->back()->with('success', 'Product removed successfully');
    }
}