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
        // 1. 🔥 PENYAPU RANJAU (AUTO-CANCEL) SEBELUM DATA DITAMPILKAN 🔥
        // Cari semua order milik user ini yang masih 'pending'
        $pendingOrders = \App\Models\Order::with('items.product')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'pending')
            ->get();

        foreach ($pendingOrders as $pendingOrder) {
            $paymentType = strtolower($pendingOrder->payment_type ?? '');
            
            // Logika deteksi 15 Menit (QRIS/E-Wallet) vs 24 Jam (VA/Kosong)
            $isFastPayment = \Illuminate\Support\Str::contains($paymentType, ['qris', 'gopay', 'shopeepay']);
            $durationInMinutes = $isFastPayment ? 15 : (24 * 60); 
            
            $expiresAt = $pendingOrder->created_at->addMinutes($durationInMinutes);

            // Jika waktu saat ini sudah melewati batas waktu kedaluwarsa
            if (now()->greaterThanOrEqualTo($expiresAt)) {
                // Batalkan pesanan
                $pendingOrder->update(['status' => 'cancelled']);
                
                // Kembalikan stok produk ke semula
                foreach ($pendingOrder->items as $item) {
                    if ($item->product && $item->product->track_stock) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
        }

        // 2. KODE ASLI KAMU UNTUK MENAMPILKAN DATA 
        $search = $request->input('search');
        $title  = 'My Orders';
        
        // Default tab sekarang adalah 'all' (Semua Pesanan)
        $tab    = $request->query('tab', 'all');

        $query = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->with(['items.product.images', 'items.product.series']);

        // LOGIKA FILTER STATUS BARU
        if ($tab !== 'all') {
            // Jika tab bukan 'all', filter sesuai parameter tab
            $query->where('status', $tab);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('items.product', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
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

        if ($order->status === 'pending') {
            $pType = strtolower($order->payment_type ?? '');
            $isBankTransfer = \Illuminate\Support\Str::contains($pType, ['transfer', 'va', 'virtual', 'echannel']);
            
            $durationInMinutes = $isBankTransfer ? (24 * 60) : 15;
            $expiresAt = $order->created_at->addMinutes($durationInMinutes);
            
            if (now()->greaterThanOrEqualTo($expiresAt)) {
                $order->update(['status' => 'cancelled']);
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                $order->status = 'cancelled';
            }
        }

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
    // Batalkan Pesanan
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Izinkan pembatalan jika status 'pending' atau 'processing'
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan hanya dapat dibatalkan saat status masih Menunggu Pembayaran atau Dikemas.');
        }

        // Ubah status jadi batal
        $order->update(['status' => 'cancelled']);

        // 🔥 KEMBALIKAN STOK BARANG KE GUDANG 🔥
        // Kita load items-nya, lalu kembalikan stoknya sejumlah quantity yang dibeli
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

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