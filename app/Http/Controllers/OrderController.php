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
        $tab = $request->query('tab', 'active');

        $query = Order::where('user_id', Auth::id())
            ->with(['items.product.images', 'items.product.series']);

        if ($tab === 'past') {
            $query->whereIn('status', ['completed', 'cancelled']);
        } else {
            $query->whereIn('status', ['pending', 'processing', 'shipped']);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('items.product', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders', 'tab', 'title', 'search'));
    }

    // Halaman Detail Satu Pesanan
    public function show($id)
    {
        $title = 'Order Details';

        // 1. Definisikan $order terlebih dahulu (Lengkap dengan relasi userAddress)
        $order = Order::with(['items.product.images', 'userAddress'])
            ->where('id', $id)
            ->firstOrFail();

        // 2. Baru lakukan pengecekan keamanan
        abort_if($order->user_id !== Auth::id(), 403);

        // --- MOCK DATA TRACKING ---
        $trackingEvents = [
            [
                'date' => '13-10-2025',
                'time' => '18:17',
                'status' => 'Delivered',
                'description' => 'Pesanan tiba di alamat tujuan. Diterima di Teras.',
                'active' => true
            ],
            [
                'date' => '13-10-2025',
                'time' => '17:01',
                'status' => 'Out for Delivery',
                'description' => 'Pesanan dalam proses pengantaran oleh kurir.',
                'active' => false
            ],
            [
                'date' => '13-10-2025',
                'time' => '15:46',
                'status' => 'Courier Assigned',
                'description' => 'Kurir sudah ditugaskan. Pesanan akan segera dikirim.',
                'active' => false
            ],
            [
                'date' => '13-10-2025',
                'time' => '15:08',
                'status' => 'Arrived at Hub',
                'description' => 'Pesanan diproses di lokasi transit Kota Semarang, Semarang Hub.',
                'active' => false
            ],
            [
                'date' => '13-10-2025',
                'time' => '13:13',
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
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load([
            'items.product.series',
            'items.product.images',
            'user',
        ]);

        return view('orders.invoice', compact('order', 'title'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan hanya dapat dibatalkan saat status masih Pending.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
