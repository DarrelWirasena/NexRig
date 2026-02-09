<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Halaman Daftar Riwayat Belanja
    public function index()
    {
        // Ambil order milik user yang sedang login saja
        $orders = Order::where('user_id', Auth::id())
                    ->latest() // Urutkan dari yang terbaru
                    ->get();

        return view('orders.index', compact('orders'));
    }

    // Halaman Detail Satu Pesanan
    public function show($id)
    {
        // Ambil order beserta item dan produknya
        $order = Order::with(['items.product.images'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id()) // PENTING: User A gak boleh liat order User B
                    ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}