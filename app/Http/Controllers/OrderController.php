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
        $title  = 'My Orders';
        
        // Default tab sekarang adalah 'all' (Semua Pesanan)
        $tab    = $request->query('tab', 'all');

        $query = Order::where('user_id', Auth::id())
            ->with(['items.product.images', 'items.product.series']);

        // LOGIKA FILTER STATUS BARU
        if ($tab !== 'all') {
            // Jika tab bukan 'all', filter sesuai parameter tab
            $query->where('status', $tab);
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

        $order = Order::with(['items.product.images', 'userAddress'])
            ->where('id', $id)
            ->firstOrFail();

        abort_if($order->user_id !== Auth::id(), 403);

        // ✅ Gunakan fungsi simulasi, bukan mock data hardcode
        $trackingEvents = $this->generateTrackingTimeline($order);

        return view('orders.show', compact('order', 'trackingEvents', 'title'));
    }

    // Halaman Invoice
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

    // Batalkan Pesanan
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // ✅ UBAH DISINI: Izinkan pembatalan jika status 'pending' atau 'processing'
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan hanya dapat dibatalkan saat status masih Menunggu Pembayaran atau Dikemas.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // ═══════════════════════════════════════════════════════════
    //  GENERATE TRACKING TIMELINE (SIMULASI DC)
    // ═══════════════════════════════════════════════════════════
    private function generateTrackingTimeline(Order $order): array
    {
        // ✅ Ambil kota tujuan dari relasi userAddress
        // Sesuaikan nama kolom jika berbeda (city / kota / city_name)
        $destination = $order->userAddress?->city ?? 'default';

        // Rute DC simulasi berdasarkan kota tujuan
        $dcRoutes = [
            // Jawa
            'Jakarta'    => ['DC Semarang', 'DC Cirebon', 'DC Jakarta Barat', 'DC Jakarta Pusat'],
            'Bandung'    => ['DC Semarang', 'DC Cirebon', 'DC Bandung'],
            'Surabaya'   => ['DC Semarang', 'DC Surabaya'],
            'Yogyakarta' => ['DC Semarang', 'DC Yogyakarta'],
            'Solo'       => ['DC Semarang', 'DC Solo'],
            'Malang'     => ['DC Semarang', 'DC Surabaya', 'DC Malang'],
            'Semarang'   => ['DC Semarang'],
            // Sumatera
            'Medan'      => ['DC Semarang', 'DC Jakarta Pusat', 'DC Medan'],
            'Palembang'  => ['DC Semarang', 'DC Jakarta Pusat', 'DC Palembang'],
            'Pekanbaru'  => ['DC Semarang', 'DC Jakarta Pusat', 'DC Pekanbaru'],
            'Batam'      => ['DC Semarang', 'DC Jakarta Pusat', 'DC Batam'],
            // Kalimantan
            'Balikpapan'  => ['DC Semarang', 'DC Surabaya', 'DC Balikpapan'],
            'Banjarmasin' => ['DC Semarang', 'DC Surabaya', 'DC Banjarmasin'],
            'Pontianak'   => ['DC Semarang', 'DC Jakarta Pusat', 'DC Pontianak'],
            // Sulawesi
            'Makassar'   => ['DC Semarang', 'DC Surabaya', 'DC Makassar'],
            'Manado'     => ['DC Semarang', 'DC Surabaya', 'DC Makassar', 'DC Manado'],
            // Bali & NTB
            'Denpasar'   => ['DC Semarang', 'DC Surabaya', 'DC Denpasar'],
            'Mataram'    => ['DC Semarang', 'DC Surabaya', 'DC Denpasar', 'DC Mataram'],
            // Papua
            'Jayapura'   => ['DC Semarang', 'DC Surabaya', 'DC Makassar', 'DC Jayapura'],
            // Default fallback
            'default'    => ['DC Semarang', 'DC Hub Nasional', "DC {$destination}"],
        ];

        // ✅ Normalisasi: cocokkan meski huruf besar/kecil berbeda
        $matchedKey = collect(array_keys($dcRoutes))
            ->first(fn($key) => strtolower($key) === strtolower($destination));

        $route = $dcRoutes[$matchedKey] ?? $dcRoutes['default'];

        // Hitung berapa DC yang sudah dilalui berdasarkan status
        $statusProgress = [
            'pending'    => 0,   // Belum ada DC dilalui
            'processing' => 1,   // Baru di DC asal (Semarang)
            'shipped'    => -1,  // Semua DC dilalui, dalam perjalanan terakhir
            'completed'  => -1,  // Semua DC + sudah sampai
            'cancelled'  => 0,
        ];

        $passedCount = $statusProgress[$order->status] ?? 0;
        if ($passedCount === -1) $passedCount = count($route);

        // Bangun timeline events
        $events    = [];
        $createdAt = $order->created_at;

        // ── 1. Order Dibuat ──────────────────────────────────────
        $events[] = [
            'status'      => 'done',
            'icon'        => 'shopping_cart',
            'title'       => 'Pesanan Dibuat',
            'description' => 'Pesanan kamu telah diterima dan sedang diverifikasi.',
            'time'        => $createdAt->format('d M Y, H:i'),
            'color'       => 'blue',
        ];

        // ── 2. Diproses ──────────────────────────────────────────
        if (in_array($order->status, ['processing', 'shipped', 'completed'])) {
            $events[] = [
                'status'      => 'done',
                'icon'        => 'inventory_2',
                'title'       => 'Pesanan Diproses',
                'description' => 'Barang sedang dikemas dan siap dikirim ke kurir.',
                // ✅ Pakai ->copy() agar $createdAt tidak termutasi
                'time'        => $createdAt->copy()->addHours(2)->format('d M Y, H:i'),
                'color'       => 'blue',
            ];
        }

       // ── 3. DC yang Dilalui ───────────────────────────────────
        foreach ($route as $index => $dc) {
            $isPassed  = $index < $passedCount;
            $isCurrent = $index === $passedCount - 1 && $order->status === 'shipped';

            // ✅ SKIP jika DC belum dilalui dan bukan current
            // Hanya tampilkan DC yang sudah dilewati atau sedang aktif
            if (!$isPassed && !$isCurrent) {
                continue; // ← lewati, jangan masuk ke $events
            }

            $estimatedTime = $createdAt->copy()->addHours(3 + ($index * 8));

            if ($index === 0) {
                $dcTitle = "Paket Tiba di {$dc}";
                $dcDesc  = "Paket telah diterima di {$dc} dan sedang disortir.";
            } elseif ($index === count($route) - 1) {
                $dcTitle = "Paket Tiba di {$dc} (Tujuan)";
                $dcDesc  = "Paket sudah di DC kota tujuan, segera dikirim ke alamatmu.";
            } else {
                $dcTitle = "Transit di {$dc}";
                $dcDesc  = "Paket sedang dalam proses transit menuju DC berikutnya.";
            }

            $events[] = [
                'status'      => $isPassed ? 'done' : 'current',
                'icon'        => $isCurrent ? 'local_shipping' : 'warehouse',
                'title'       => $dcTitle,
                'description' => $dcDesc,
                'time'        => $estimatedTime->format('d M Y, H:i'),
                'color'       => $isCurrent ? 'yellow' : 'blue',
            ];
        }

        // ── 4. Dalam Pengiriman ke Alamat ────────────────────────
        if (in_array($order->status, ['shipped', 'completed'])) {
            $events[] = [
                'status'      => $order->status === 'completed' ? 'done' : 'current',
                'icon'        => 'two_wheeler',
                'title'       => 'Dalam Pengiriman ke Alamat',
                'description' => 'Kurir sedang menuju alamat pengirimanmu.',
                'time'        => $order->status === 'completed'
                    ? $order->updated_at->format('d M Y, H:i')
                    : 'Sedang berlangsung',
                'color'       => $order->status === 'completed' ? 'blue' : 'yellow',
            ];
        }

        // ── 5. Selesai ───────────────────────────────────────────
        if ($order->status === 'completed') {
            $events[] = [
                'status'      => 'done',
                'icon'        => 'check_circle',
                'title'       => 'Paket Diterima',
                'description' => 'Paket telah sampai dan diterima. Terima kasih sudah berbelanja!',
                'time'        => $order->updated_at->format('d M Y, H:i'),
                'color'       => 'green',
            ];
        }

        return $events;
    }
}