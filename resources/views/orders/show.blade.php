@extends('layouts.dashboard')

@section('content')

{{-- Leaflet.js CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Map dark filter */
    .leaflet-tile {
        filter: brightness(0.45) saturate(0.6) hue-rotate(200deg);
    }
    .leaflet-container {
        background: #0a0a0a;
    }
    /* Timeline Animation */
    @keyframes timeline-in {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .timeline-item {
        animation: timeline-in 0.4s ease both;
    }
    /* Truck Map Pulse */
    .truck-pulse {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        animation: pulse-blue 2s infinite;
    }
    @keyframes pulse-blue {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
</style>

<div class="max-w-6xl mx-auto pb-20">

    {{-- HEADER --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                        Order <span class="text-blue-600">#{{ $order->id }}</span>
                    </h1>
                    @php
                    $statusColor = match($order->status) {
                        'pending'    => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                        'processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                        'shipped'    => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                        'completed'  => 'bg-green-500/10 text-green-500 border-green-500/20',
                        'cancelled'  => 'bg-red-500/10 text-red-500 border-red-500/20',
                        default      => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                    };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusColor }}">
                        {{ $order->status }}
                    </span>
                </div>
                <p class="text-gray-400 text-xs mt-1 font-mono">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
            class="px-5 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm font-bold text-white transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">download</span> Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== KOLOM KIRI ===== --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- INTERACTIVE MAP + TIMELINE --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">

                {{-- ── A. LEAFLET MAP (Dengan Rute Jalan Asli) ── --}}
                <div class="relative w-full" style="height: 350px;">
                    <div id="deliveryMap" class="w-full h-full z-0"></div>

                    {{-- Live Badge --}}
                    <div class="absolute top-4 left-4 z-[40] flex items-center gap-2 px-3 py-1.5 bg-black/80 backdrop-blur border border-white/10 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        <span class="text-[10px] font-bold text-white uppercase tracking-widest">Live Route Tracking</span>
                    </div>

                    {{-- Gradient fade --}}
                    <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#0a0a0a] to-transparent z-[40] pointer-events-none"></div>
                </div>

                {{-- ── B. TIMELINE PENGIRIMAN DINAMIS ── --}}
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500 text-lg">timeline</span>
                            Status Pengiriman
                        </h3>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg">
                            <span class="material-symbols-outlined text-sm text-blue-400">local_shipping</span>
                            <div>
                                <p class="text-[10px] text-white font-bold">NEXRIG EXPRESS</p>
                                <p class="text-[10px] text-gray-500 font-mono">NX-{{ $order->id }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                    $status = $order->status;
                    $history = [];

                    // Membangun timeline mundur (dari kejadian terbaru ke terlama) sesuai status
                    if ($status === 'cancelled') {
                        $history[] = [
                            'title' => 'Pesanan Dibatalkan',
                            'desc'  => 'Pesanan ini telah dibatalkan oleh sistem atau pengguna.',
                            'icon'  => 'cancel', 'color' => 'text-red-500', 'bg' => 'bg-red-500/20 border-red-500/50'
                        ];
                    } else {
                        if (in_array($status, ['completed'])) {
                            $history[] = [
                                'title' => 'Paket Tiba di Tujuan',
                                'desc'  => 'Paket telah sampai di alamat pengiriman dengan selamat.',
                                'icon'  => 'home_pin', 'color' => 'text-green-400', 'bg' => 'bg-green-500/20 border-green-500/50'
                            ];
                        }
                        if (in_array($status, ['shipped', 'completed'])) {
                            $history[] = [
                                'title' => 'Sedang Pengiriman',
                                'desc'  => 'Kurir kami sedang dalam perjalanan menuju alamat pengiriman.',
                                'icon'  => 'local_shipping', 'color' => 'text-blue-400', 'bg' => 'bg-blue-600 border-blue-400 truck-pulse'
                            ];
                        }
                        if (in_array($status, ['pending', 'processing', 'shipped', 'completed'])) {
                            $history[] = [
                                'title' => 'Paket Sedang Disiapkan',
                                'desc'  => 'Tim kami sedang merakit dan mengemas pesananmu di gudang.',
                                'icon'  => 'inventory_2', 'color' => 'text-amber-400', 'bg' => 'bg-[#1a1a1a] border-white/20'
                            ];
                        }
                        if (in_array($status, ['pending', 'processing', 'shipped', 'completed'])) {
                            $history[] = [
                                'title' => 'Pesanan Diterima',
                                'desc'  => 'Pesanan berhasil dibuat dan sedang menunggu konfirmasi pembayaran.',
                                'icon'  => 'receipt_long', 'color' => 'text-gray-400', 'bg' => 'bg-[#111] border-white/10'
                            ];
                        }
                    }
                    @endphp

                    <div class="relative">
                        {{-- Garis penghubung vertikal --}}
                        <div class="absolute left-[19px] top-2 bottom-2 w-px bg-white/10"></div>
                        <div class="space-y-0">
                            @foreach($history as $i => $item)
                            @php $isFirst = ($i === 0); @endphp
                            <div class="relative flex gap-5 pb-8 last:pb-0 timeline-item" style="animation-delay: {{ $i * 0.1 }}s">
                                
                                {{-- Ikon --}}
                                <div class="relative z-10 shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all {{ $isFirst ? $item['bg'] : 'bg-[#111] border-white/10' }}">
                                        <span class="material-symbols-outlined text-sm {{ $isFirst ? 'text-white' : $item['color'] }}">
                                            {{ $item['icon'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Teks --}}
                                <div class="flex-1 pt-1.5 min-w-0">
                                    <h4 class="text-sm font-bold {{ $isFirst ? 'text-white' : 'text-gray-400' }} mb-1">
                                        {{ $item['title'] }}
                                    </h4>
                                    <p class="text-xs {{ $isFirst ? 'text-gray-400' : 'text-gray-600' }} leading-relaxed">
                                        {{ $item['desc'] }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ORDER ITEMS --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex justify-between items-center">
                    <h3 class="font-bold text-white">Rincian Perangkat</h3>
                    <span class="text-xs text-gray-500 font-bold uppercase">{{ $order->items->count() }} Item</span>
                </div>
                <div class="divide-y divide-white/5">
                    @foreach($order->items as $item)
                    <div class="p-6 flex gap-6 items-center group hover:bg-white/[0.02] transition-colors">
                        <div class="w-20 h-20 bg-[#050014] rounded-lg border border-white/10 flex items-center justify-center shrink-0 overflow-hidden">
                            @if($item->product->images->first())
                            <img src="{{ $item->product->images->where('is_primary', true)->first()->src }}"
                                class="w-full h-full object-cover">
                            @else
                            <span class="material-symbols-outlined text-gray-700">image</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-white font-bold mb-1 group-hover:text-blue-500 transition-colors">
                                <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                            </h4>
                            <div class="text-xs text-gray-500 mb-2">{{ $item->product->series->name ?? 'Component' }}</div>
                            <div class="flex items-center gap-4 text-sm">
                                <span class="text-gray-400">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                <span class="text-gray-600">x</span>
                                <span class="text-white font-bold">{{ $item->quantity }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-white font-bold">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ===== KOLOM KANAN ===== --}}
        <div class="space-y-6">

            {{-- 1. ORDER SUMMARY --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold text-white mb-6">Ringkasan Pembayaran</h3>
                @php
                $subtotal = $order->total_price / 1.11;
                $taxAmount = $order->total_price - $subtotal;
                @endphp
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Pengiriman</span>
                        <span class="text-green-500 font-bold">GRATIS</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Pajak PPN (11%)</span>
                        <span class="text-white">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total</span>
                    <span class="text-2xl font-black text-blue-500">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- 2. METODE PEMBAYARAN DINAMIS --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-sm">payments</span>
                    Metode Pembayaran
                </h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-8 bg-blue-500/10 border border-blue-500/20 rounded flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-blue-400">
                            {{ $order->payment_type == 'qris' ? 'qr_code_scanner' : 'account_balance' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-white font-bold uppercase tracking-wide">
                            {{ str_replace('_', ' ', $order->payment_type ?? 'Bank Transfer') }}
                        </p>
                        <p class="text-xs text-gray-500">Auto Verification via Midtrans</p>
                    </div>
                </div>
            </div>

            {{-- 3. ALAMAT PENGIRIMAN --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-sm">location_on</span>
                    Alamat Pengiriman
                </h3>
                <div class="space-y-1 text-sm">
                    <p class="text-white font-bold">{{ $order->shipping_name ?? auth()->user()->name }}</p>
                    <p class="text-gray-400 leading-relaxed">
                        {{ $order->shipping_address ?? 'Alamat tidak ditemukan' }}<br>
                        {{ $order->shipping_city ?? '' }} {{ $order->shipping_postal_code ?? '' }}
                    </p>
                    <p class="text-gray-400 mt-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[14px]">call</span>
                        {{ $order->shipping_phone ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- 4. ORDER ACTION --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold text-white mb-4">Aksi</h3>

                @if($order->status === 'pending')
                <button onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                    class="w-full py-2.5 bg-red-600/10 hover:bg-red-600/20 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-red-300 text-sm font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">cancel</span> Batalkan Pesanan
                </button>
                @elseif($order->status === 'cancelled')
                <div class="w-full py-2.5 border border-red-500/20 text-red-500/60 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                    <span class="material-symbols-outlined text-sm">cancel</span> Pesanan Dibatalkan
                </div>
                @else
                <div class="w-full py-2.5 bg-white/[0.03] border border-white/10 text-gray-600 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                    <span class="material-symbols-outlined text-sm">block</span> Batalkan Pesanan
                </div>
                <p class="text-[11px] text-gray-600 text-center mt-2">
                    Tidak dapat dibatalkan — pesanan sedang diproses.
                </p>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- ====== SCRIPT PETA & RUTING (OSRM GEOJSON) ====== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Konversi koordinat dari PHP, fallback ke Jakarta jika kosong agar peta tidak error
        const destLat = parseFloat({{ $order->shipping_latitude ?? -6.2088 }});
        const destLng = parseFloat({{ $order->shipping_longitude ?? 106.8456 }});
        const status = '{{ $order->status }}';

        // Titik awal Gudang NexRig (Semarang)
        const origin = { lat: -6.9932, lng: 110.4229 };
        const dest = { lat: destLat, lng: destLng };

        // Aktifkan zoom control dan scroll wheel
        const map = L.map('deliveryMap', {
            zoomControl: true, // Ubah menjadi true agar tombol + / - muncul
            attributionControl: false,
            scrollWheelZoom: true, // Ubah menjadi true agar bisa zoom pakai scroll mouse
            dragging: true, // Pastikan dragging aktif agar map bisa digeser
        });

        // Memindahkan posisi tombol zoom ke kanan bawah agar tidak tertutup badge Live Tracking
        map.zoomControl.setPosition('bottomright');

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

        // Fungsi pembuat icon HTML marker
        function makeIcon(emoji, isTruck = false) {
            const bg = isTruck ? '#2563eb' : '#111';
            const border = isTruck ? '#60a5fa' : '#374151';
            const extraClass = isTruck ? 'truck-pulse' : '';
            return L.divIcon({
                className: '',
                html: `<div class="${extraClass}" style="background:${bg};border:2px solid ${border};border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:18px;">${emoji}</div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
            });
        }

        // Ambil rute asli via jalan raya (OSRM public API - GeoJSON)
        async function drawRoute() {
            const url = `https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${dest.lng},${dest.lat}?overview=full&geometries=geojson`;
            
            try {
                const res = await fetch(url);
                const data = await res.json();
                
                let coords = [];
                if (data.code === 'Ok' && data.routes.length > 0) {
                    // OSRM mengirim format [lng, lat], kita balik menjadi [lat, lng] untuk Leaflet
                    coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                } else {
                    // Jika gagal ditarik garis lurus saja
                    coords = [[origin.lat, origin.lng], [dest.lat, dest.lng]];
                }

                let activePath = [];
                let pendingPath = [];
                let truckPos = origin;

                // Logika Posisi Truk & Warna Garis
                if (status === 'pending' || status === 'processing' || status === 'cancelled') {
                    // Masih digudang
                    truckPos = origin;
                    pendingPath = coords; // Semua jalan masih warna abu-abu
                } else if (status === 'shipped') {
                    // Sedang di jalan (Kita taruh posisi truk pas di titik TENGAH rute jalan raya)
                    const midPoint = Math.floor(coords.length / 2);
                    truckPos = { lat: coords[midPoint][0], lng: coords[midPoint][1] };
                    
                    activePath = coords.slice(0, midPoint + 1); // Jalan yang sudah dilalui warna Biru
                    pendingPath = coords.slice(midPoint);       // Sisa jalan warna Abu-abu putus-putus
                } else if (status === 'completed') {
                    // Sudah sampai
                    truckPos = dest;
                    activePath = coords; // Semua jalan diwarnai Biru
                }

                // Menggambar Garis Rute di Peta
                if (activePath.length > 0) {
                    L.polyline(activePath, { color: '#2563eb', weight: 5, opacity: 0.9 }).addTo(map);
                }
                if (pendingPath.length > 0) {
                    L.polyline(pendingPath, { color: '#6b7280', weight: 4, opacity: 0.6, dashArray: '8 8' }).addTo(map);
                }

                // Memasang Marker
                L.marker([origin.lat, origin.lng], { icon: makeIcon('🏭') }).addTo(map); // Gudang
                L.marker([dest.lat, dest.lng], { icon: makeIcon('📍') }).addTo(map);     // Rumah
                
                // Menampilkan ikon truk jika sedang dikirim atau diproses
                if (status !== 'cancelled' && status !== 'completed') {
                    L.marker([truckPos.lat, truckPos.lng], { icon: makeIcon('🚛', true), zIndexOffset: 1000 }).addTo(map);
                }

                // Sesuaikan kamera peta agar mencakup seluruh rute
                map.fitBounds(coords, { padding: [50, 50] });

            } catch (error) {
                console.error('Gagal mengambil rute:', error);
            }
        }

        drawRoute();
    });
</script>
@endsection