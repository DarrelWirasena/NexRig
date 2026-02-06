<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. Tampilkan Halaman Keranjang
    public function index()
    {
        // Ambil data cart dari session, kalau kosong return array kosong
        $cart = session()->get('cart', []);
        
        // Hitung total harga
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

        // Cek gambar utama untuk thumbnail di cart
        $image = $product->images->where('is_primary', true)->first();
        $imageUrl = $image ? $image->image_url : 'https://placehold.co/100';

        // Jika produk sudah ada di cart, tambah quantity-nya
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika belum ada, masukkan data baru
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $imageUrl
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // 3. Hapus Barang dari Keranjang
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