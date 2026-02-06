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

    // 2. Tambah Barang ke Keranjang
    public function store(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);
        $cart = session()->get('cart', []);

        // Ambil gambar utama, atau placeholder jika tidak ada
        $image = $product->images->where('is_primary', true)->first();
        $imageUrl = $image ? $image->image_url : 'https://placehold.co/100';

        // Cek apakah ada request jumlah spesifik? (Misal dari input form)
        // Kalau tidak ada, default nambah 1
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
        
        // Redirect back dengan pesan sukses
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

    // 4. Hapus Barang
    public function destroy($id)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Product removed successfully');
    }
}