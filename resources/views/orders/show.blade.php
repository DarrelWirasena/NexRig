@extends('layouts.dashboard')

@section('content')
    {{-- Leaflet.js CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Map dark filter */
        .leaflet-tile { filter: brightness(0.45) saturate(0.6) hue-rotate(200deg); }
        .leaflet-container { background: #0a0a0a; }

        /* Timeline Animation */
        @keyframes timeline-in {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .timeline-item { animation: timeline-in 0.4s ease both; }

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

        {{-- 🔥 BANNER UX DENGAN LIVE COUNTDOWN TIMER 🔥 --}}
        @if ($order->status === 'pending')
            <div class="mb-8 p-5 rounded-xl bg-amber-500/10 border border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-amber-500">timer</span>
                    </div>
                    <div>
                        <h4 class="text-amber-500 font-bold text-sm">Menunggu Pembayaran</h4>
                        <div class="text-xs text-amber-500/80 mt-1 flex items-center gap-2">
                            Sisa waktu pembayaran: <p class="text-xs text-amber-500/80
                            <span id="countdown-timer" class="font-mono font-bold text-white text-sm bg-amber-500/20 px-2 py-0.5 rounded tracking-widest border border-amber-500/30">
                                --:--
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.index') }}"
                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                            Order <span class="text-blue-600">#{{ $order->id }}</span>
                        </h1>
                        @php
                            $statusData = match ($order->status) {
                                'pending'    => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-500/10 text-amber-500 border-amber-500/20'],
                                'processing' => ['label' => 'Dikemas',             'class' => 'bg-blue-500/10 text-blue-500 border-blue-500/20'],
                                'shipped'    => ['label' => 'Dikirim',             'class' => 'bg-purple-500/10 text-purple-500 border-purple-500/20'],
                                'completed'  => ['label' => 'Selesai',             'class' => 'bg-green-500/10 text-green-500 border-green-500/20'],
                                'cancelled'  => ['label' => 'Dibatalkan',          'class' => 'bg-red-500/10 text-red-500 border-red-500/20'],
                                default      => ['label' => $order->status,        'class' => 'bg-gray-500/10 text-gray-500 border-gray-500/20'],
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusData['class'] }}">
                            {{ $statusData['label'] }}
                        </span>
                    </div>
                    <p class="text-gray-400 text-xs mt-1 font-mono">Dibuat pada
                        {{ $order->created_at->format('d M Y, H:i') }}</p>
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
                        <div
                            class="absolute top-4 left-4 z-[40] flex items-center gap-2 px-3 py-1.5 bg-black/80 backdrop-blur border border-white/10 rounded-full">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Live Route Tracking</span>
                        </div>

                        {{-- Gradient fade --}}
                        <div
                            class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#0a0a0a] to-transparent z-[40] pointer-events-none">
                        </div>
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
                        @endphp

                        @if ($order->status === 'cancelled')
                            <div class="bg-red-500/5 border border-red-500/20 rounded-xl p-6 flex items-center gap-4">
                                <span class="material-symbols-outlined text-red-400 text-3xl">cancel</span>
                                <div>
                                    <p class="text-sm font-bold text-red-400">Pesanan Dibatalkan / Waktu Habis</p>
                                    <p class="text-xs text-gray-500 mt-1">Pesanan ini telah dibatalkan (atau waktu pembayaran habis) dan tidak diproses.</p>
                                </div>
                            </div>
                        @else
                            <div class="relative">
                                {{-- Garis vertikal --}}
                                <div class="absolute left-[19px] top-0 bottom-0 w-0.5 bg-white/5"></div>

                                <div class="space-y-0">
                                    @foreach ($trackingEvents as $event)
                                        @php
                                            $colorMap = [
                                                'blue' => ['bg' => 'bg-blue-600', 'ring' => 'ring-blue-500/30', 'text' => 'text-blue-400', 'badge' => 'bg-blue-600/10 text-blue-400 border-blue-500/20'],
                                                'green' => ['bg' => 'bg-green-600', 'ring' => 'ring-green-500/30', 'text' => 'text-green-400', 'badge' => 'bg-green-600/10 text-green-400 border-green-500/20'],
                                                'yellow' => ['bg' => 'bg-yellow-500', 'ring' => 'ring-yellow-400/30', 'text' => 'text-yellow-400', 'badge' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'],
                                                'gray' => ['bg' => 'bg-white/10', 'ring' => 'ring-white/5', 'text' => 'text-gray-500', 'badge' => 'bg-white/5 text-gray-500 border-white/10'],
                                            ];
                                            $c = $colorMap[$event['color']] ?? $colorMap['gray'];
                                            $isPending = $event['status'] === 'pending';
                                            $isCurrent = $event['status'] === 'current';
                                        @endphp

                                        <div class="relative flex gap-5 pb-8 last:pb-0 group">
                                            {{-- Dot icon --}}
                                            <div class="relative z-10 shrink-0">
                                                <div class="w-10 h-10 rounded-full {{ $c['bg'] }} ring-4 {{ $c['ring'] }}
                                                    flex items-center justify-center transition-transform duration-300
                                                    group-hover:scale-110 {{ $isPending ? 'opacity-25' : '' }}">
                                                    <span class="material-symbols-outlined text-white text-[17px]">{{ $event['icon'] }}</span>
                                                </div>
                                                @if ($isCurrent)
                                                    <div class="absolute inset-0 rounded-full {{ $c['bg'] }} opacity-40 animate-ping"></div>
                                                @endif
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 pt-1.5 pb-2">
                                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                                    <span class="text-sm font-bold {{ $isPending ? 'text-gray-600' : 'text-white' }}">{{ $event['title'] }}</span>
                                                    @if ($isCurrent)
                                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border {{ $c['badge'] }}">Sekarang</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs {{ $isPending ? 'text-gray-700' : 'text-gray-400' }} mb-1.5 leading-relaxed">{{ $event['description'] }}</p>
                                                <span class="text-[11px] {{ $c['text'] }} font-medium flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">schedule</span>{{ $event['time'] }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- 3. ORDER ITEMS --}}
                <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center">
                        <h3 class="font-bold text-white">Rincian Perangkat</h3>
                        <span class="text-xs text-gray-500 font-bold uppercase">{{ $order->items->count() }} Item</span>
                    </div>
                    <div class="divide-y divide-white/5">
                        @foreach ($order->items as $item)
                            <div class="p-6 flex gap-6 items-center group hover:bg-white/[0.02] transition-colors">
                                <div class="w-20 h-20 bg-[#050014] rounded-lg border border-white/10 flex items-center justify-center shrink-0 overflow-hidden">
                                    @if ($item->product->images->first())
                                        <img src="{{ $item->product->images->where('is_primary', true)->first()->src }}" class="w-full h-full object-cover">
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
                                <div class="text-right flex flex-col items-end gap-2">
                                    <span class="block text-white font-bold">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                    @if($order->status === 'completed')
                                        <a href="{{ route('products.show', $item->product->slug) }}#review" 
                                           class="text-[10px] px-3 py-1.5 bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/30 rounded flex items-center gap-1 transition-colors whitespace-nowrap font-bold uppercase tracking-wider">
                                            <span class="material-symbols-outlined text-[12px]">rate_review</span> Ulas Produk
                                        </a>
                                    @endif
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

                    {{-- 🔥 TAMBAHAN UX: JIKA BELUM BAYAR MUNCULKAN TOMBOL BAYAR SEKARANG 🔥 --}}
                    @if ($order->status === 'pending')
                        <div class="space-y-3">
                            {{-- Jika status belum bayar dan punya snap token dari midtrans, munculkan tombol lanjutkan bayar --}}
                            @if($order->snap_token)
                            <button type="button" id="pay-button" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                                <span class="material-symbols-outlined text-sm" id="pay-icon">payment</span> 
                                <span id="pay-text">Lanjutkan Pembayaran</span>
                            </button>
                            @endif

                            <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                                class="w-full py-2.5 bg-red-600/10 hover:bg-red-600/20 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-red-300 text-sm font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">cancel</span> Batalkan Pesanan
                            </button>
                        </div>
                    @elseif($order->status === 'processing')
                        <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                            class="w-full py-2.5 bg-red-600/10 hover:bg-red-600/20 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-red-300 text-sm font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">cancel</span> Batalkan Pesanan
                        </button>
                    @elseif($order->status === 'cancelled')
                        <div
                            class="w-full py-2.5 border border-red-500/20 text-red-500/60 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined text-sm">cancel</span> Pesanan Dibatalkan
                        </div>
                    @elseif($order->status === 'completed')
                        <div
                            class="w-full py-2.5 bg-green-500/10 border border-green-500/20 text-green-500 text-sm font-bold rounded-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Pesanan Selesai
                        </div>
                        <p class="text-[11px] text-gray-500 text-center mt-3 leading-relaxed">
                            Terima kasih telah berbelanja! Jangan lupa bagikan pengalamanmu dengan memberikan ulasan pada produk.
                        </p>
                    @else
                        <div
                            class="w-full py-2.5 bg-white/[0.03] border border-white/10 text-gray-600 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined text-sm">block</span> Batalkan Pesanan
                        </div>
                        <p class="text-[11px] text-gray-600 text-center mt-2">Tidak dapat dibatalkan — pesanan sedang dikirim.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (in_array($order->status, ['pending', 'processing']))
        <div id="cancelModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                onclick="document.getElementById('cancelModal').classList.add('hidden')"></div>
            <div class="relative bg-[#0f0f0f] border border-red-500/30 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <div class="flex flex-col items-center text-center mb-6">
                    <div
                        class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-red-400 text-2xl">error</span>
                    </div>
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">Batalkan Pesanan?</h3>
                    <p class="text-gray-400 text-sm mt-2">
                        Order <span class="text-white font-bold">#{{ $order->id }}</span> akan dibatalkan dan <span
                            class="text-red-400 font-bold">tidak dapat dikembalikan</span>.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')"
                        class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-bold rounded-lg transition-all">
                        Kembali
                    </button>
                    {{-- Form untuk menembak ke Controller --}}
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full py-2.5 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded-lg transition-all">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== SCRIPT PETA & RUTING (OSRM GEOJSON) ====== --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let destLat = {{ $order->shipping_latitude ?? 'null' }};
            let destLng = {{ $order->shipping_longitude ?? 'null' }};
            const fallbackCity = "{{ $order->shipping_city ?? 'Jakarta' }}";
            const status = '{{ $order->status }}';

            async function initMap() {
                if (destLat === null || destLng === null) {
                    try {
                        const searchRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(fallbackCity + ', Indonesia')}`);
                        const searchData = await searchRes.json();
                        if (searchData.length > 0) {
                            destLat = parseFloat(searchData[0].lat);
                            destLng = parseFloat(searchData[0].lon);
                        } else {
                            destLat = -6.2088; destLng = 106.8456;
                        }
                    } catch (e) {
                        destLat = -6.2088; destLng = 106.8456;
                    }
                } else {
                    destLat = parseFloat(destLat); destLng = parseFloat(destLng);
                }

                const origin = { lat: -6.9932, lng: 110.4229 };
                const dest = { lat: destLat, lng: destLng };

                const map = L.map('deliveryMap', { zoomControl: true, attributionControl: false, scrollWheelZoom: true, dragging: true, });
                map.zoomControl.setPosition('bottomright');
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

                function makeIcon(type) {
                    const configs = {
                        warehouse: { color: '#1a6fff', border: '#0a3a8a', svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M22 9V7h-2V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2h2v-2h-2v-2h2v-2h-2V9h2zm-4 10H4V5h14v14zM6 13h5v4H6zm6-6h3v3h-3zM6 7h5v5H6zm6 4h3v6h-3z"/></svg>` },
                        home: { color: '#16a34a', border: '#14532d', svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>` },
                        truck: { color: '#dc2626', border: '#7f1d1d', svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>` },
                    };
                    const c = configs[type];
                    const isTruck = type === 'truck';
                    const html = isTruck ? `<div style="position: relative; display: flex; flex-direction: column; align-items: center;"><div style="background: ${c.color}; border: 2.5px solid ${c.border}; border-radius: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">${c.svg}</div></div>` : `<div style="position: relative; display: flex; flex-direction: column; align-items: center;"><div style="background: ${c.color}; border: 2.5px solid ${c.border}; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">${c.svg}</div><div style="width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 10px solid ${c.border}; margin-top: -1px;"></div></div>`;
                    return L.divIcon({ className: '', html, iconSize: [40, isTruck ? 40 : 52], iconAnchor: [20, isTruck ? 20 : 52], popupAnchor: [0, -52], });
                }

                async function drawRoute() {
                    const url = `https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${dest.lng},${dest.lat}?overview=full&geometries=geojson`;
                    try {
                        const res = await fetch(url);
                        const data = await res.json();
                        let coords = [];
                        if (data.code === 'Ok' && data.routes.length > 0) {
                            coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                        } else {
                            coords = [ [origin.lat, origin.lng], [dest.lat, dest.lng] ];
                        }

                        let activePath = []; let pendingPath = []; let truckPos = origin;
                        if (status === 'pending' || status === 'processing' || status === 'cancelled') {
                            truckPos = origin; pendingPath = coords;
                        } else if (status === 'shipped') {
                            const midPoint = Math.floor(coords.length / 2);
                            truckPos = { lat: coords[midPoint][0], lng: coords[midPoint][1] };
                            activePath = coords.slice(0, midPoint + 1);
                            pendingPath = coords.slice(midPoint);
                        } else if (status === 'completed') {
                            truckPos = dest; activePath = coords;
                        }

                        if (activePath.length > 0) {
                            L.polyline(activePath, { color: '#2563eb', weight: 5, opacity: 0.9 }).addTo(map);
                        }
                        if (pendingPath.length > 0) {
                            L.polyline(pendingPath, { color: '#6b7280', weight: 4, opacity: 0.6, dashArray: '8 8' }).addTo(map);
                        }

                        L.marker([origin.lat, origin.lng], { icon: makeIcon('warehouse') }).addTo(map);
                        L.marker([dest.lat, dest.lng], { icon: makeIcon('home') }).addTo(map);
                        if (status !== 'cancelled' && status !== 'completed') {
                            L.marker([truckPos.lat, truckPos.lng], { icon: makeIcon('truck'), zIndexOffset: 1000 }).addTo(map);
                        }
                        map.fitBounds(coords, { padding: [50, 50] });
                    } catch (error) {
                        console.error('Gagal mengambil rute:', error);
                    }
                }
                drawRoute();
            }
            initMap();
        });
    </script>

    {{-- Dark popup overrides --}}
    <style>
        .leaflet-popup-content-wrapper, .leaflet-popup-tip { background: transparent !important; box-shadow: none !important; padding: 0 !important; }
        .leaflet-popup-content { margin: 0 !important; }
        .leaflet-dark-popup .leaflet-popup-content-wrapper { background: transparent; }
        .leaflet-tooltip { background: #111 !important; border: 1px solid #374151 !important; color: #e5e7eb !important; font-size: 11px !important; font-weight: bold !important; border-radius: 6px !important; padding: 4px 10px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important; }
        .leaflet-tooltip-bottom::before { border-bottom-color: #374151 !important; }
    </style>

    {{-- 🔥 TAMBAHAN SCRIPT MIDTRANS PEMBAYARAN ULANG & LIVE COUNTDOWN 🔥 --}}
    @if ($order->status === 'pending')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            // 1. LOGIKA COUNTDOWN TIMER BERDASARKAN DATABASE
            const createdAtStr = "{{ $order->created_at->format('Y-m-d H:i:s') }}";
            const createdAt = new Date(createdAtStr.replace(' ', 'T') + 'Z'); 
            const expiryTime = new Date(createdAt.getTime() + 15 * 60000); // 15 menit

            const timerDisplay = document.getElementById('countdown-timer');
            const payBtn = document.getElementById('pay-button');
            const payText = document.getElementById('pay-text');
            const payIcon = document.getElementById('pay-icon');

            const timerInterval = setInterval(function() {
                const now = new Date();
                const distance = expiryTime - now;

                if (distance <= 0) {
                    clearInterval(timerInterval);
                    
                    if(timerDisplay) {
                        timerDisplay.innerHTML = "EXPIRED";
                        timerDisplay.classList.replace('text-white', 'text-red-500');
                        timerDisplay.classList.replace('bg-amber-500/20', 'bg-red-500/20');
                        timerDisplay.classList.replace('border-amber-500/30', 'border-red-500/30');
                    }
                    
                    if(payBtn) {
                        payBtn.disabled = true;
                        payBtn.classList.remove('bg-blue-600', 'hover:bg-blue-500');
                        payBtn.classList.add('bg-white/5', 'text-gray-500', 'cursor-not-allowed', 'border', 'border-white/10');
                        payText.innerText = "Waktu Habis";
                        payIcon.innerText = "block";
                    }

                    // Reload halaman agar backend (OrderController) resmi mengeksekusi pembatalan
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000); // reload dalam 2 detik
                    return;
                }

                // Format MM:SS
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const m = minutes < 10 ? "0" + minutes : minutes;
                const s = seconds < 10 ? "0" + seconds : seconds;

                if(timerDisplay) {
                    timerDisplay.innerHTML = m + ":" + s;

                    // Berkedip merah jika di bawah 3 menit
                    if (distance < 180000) {
                        timerDisplay.classList.add('text-red-400');
                        timerDisplay.classList.replace('bg-amber-500/20', 'bg-red-500/20');
                        timerDisplay.classList.replace('border-amber-500/30', 'border-red-500/30');
                    }
                }
            }, 1000);

            // 2. LOGIKA TOMBOL MIDTRANS
            @if($order->snap_token)
                if(payBtn) {
                    payBtn.onclick = function() {
                        // Jangan izinkan klik jika waktu sudah habis
                        if (new Date() >= expiryTime) return;

                        snap.pay('{{ $order->snap_token }}', {
                            onSuccess: function(result) {
                                window.location.href = "{{ route('checkout.success', $order->id) }}";
                            },
                            onPending: function(result) {
                                console.log('Menunggu konfirmasi');
                            },
                            onError: function(result) {
                                alert("Gagal memproses pembayaran");
                            },
                            onClose: function() {
                                console.log('Pop up midtrans ditutup');
                            }
                        });
                    };
                }
            @endif
        </script>
    @endif
@endsection