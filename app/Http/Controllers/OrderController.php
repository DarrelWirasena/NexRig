<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Halaman Daftar Riwayat Belanja
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $title = 'My Orders';
        // 1. Ambil parameter tab dari URL (default 'active')
        $tab = $request->query('tab', 'active');

        // 2. Query dasar (User yang sedang login)
        $query = Order::where('user_id', auth()->id())
                      ->with(['items.product.images', 'items.product.series']); // Eager load biar cepat

        // 3. Filter berdasarkan Tab
        if ($tab === 'past') {
            // Past Orders: Yang sudah selesai atau batal
            $query->whereIn('status', ['completed', 'cancelled']);
        } else {
            // Active Orders: Yang masih jalan
            $query->whereIn('status', ['pending', 'processing', 'shipped']);
        }

        // 4. Filter berdasarkan Search (ID Pesanan)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                // Search by order ID
                $q->where('id', 'like', '%' . $request->search . '%')
                // OR by product name inside order items
                ->orWhereHas('items.product', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // 5. Ambil data
        $orders = $query->latest()->get();
        // 6. Return ke view dengan variabel tab
        return view('orders.index', compact('orders', 'tab', 'title', 'search'));
    }

    // Halaman Detail Satu Pesanan
    public function show($id)
    {   
        $title = 'Order Details';
        // Ambil order beserta item dan produknya
        $order = Order::with(['items.product.images'])
            ->where('id', $id)
            ->firstOrFail(); // Kalau order tidak ada → 404

abort_if($order->user_id !== Auth::id(), 403); // Kalau bukan pemilik → 403
                    
        // --- MOCK DATA TRACKING (Nanti bisa diganti ambil dari database) ---
        $trackingEvents = [
            [
                'date' => '13-10-2025', 'time' => '18:17',
                'status' => 'Delivered',
                'description' => 'Pesanan tiba di alamat tujuan. Diterima di Teras.',
                'active' => true // Status paling atas (terbaru)
            ],
            [
                'date' => '13-10-2025', 'time' => '17:01',
                'status' => 'Out for Delivery',
                'description' => 'Pesanan dalam proses pengantaran oleh kurir.',
                'active' => false
            ],
            [
                'date' => '13-10-2025', 'time' => '15:46',
                'status' => 'Courier Assigned',
                'description' => 'Kurir sudah ditugaskan. Pesanan akan segera dikirim.',
                'active' => false
            ],
            [
                'date' => '13-10-2025', 'time' => '15:08',
                'status' => 'Arrived at Hub',
                'description' => 'Pesanan diproses di lokasi transit Kota Semarang, Semarang Hub.',
                'active' => false
            ],
            [
                'date' => '13-10-2025', 'time' => '13:13',
                'status' => 'Departed from Facility',
                'description' => 'Pesanan dikirim dari lokasi sortir Kota Semarang Semarang DC ke Kota Semarang, Semarang Hub via darat.',
                'active' => false
            ],
        ];

        return view('orders.show', compact('order', 'trackingEvents', 'title'));
    }
     public function invoice(Order $order)
    {   
        $title = 'Invoice';
        // Security: hanya pemilik order atau admin
        abort_if($order->user_id !== auth()->id(), 403);

        // Eager load semua relasi yang dibutuhkan invoice
        $order->load([
            'items.product.series',
            'items.product.images',
            'user',
        ]);

        // Return view invoice (layout khusus, bukan dashboard)
        return view('orders.invoice', compact('order', 'title'));
    }

}