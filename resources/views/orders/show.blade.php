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

    /* Custom marker pulse */
    @keyframes pulse-ring {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }

        100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }

    @keyframes timeline-in {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .timeline-item {
        animation: timeline-in 0.4s ease both;
    }

    /* Truck move animation */
    @keyframes truck-slide {
        0% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }

        100% {
            transform: translateX(-4px);
        }
    }

    .truck-anim {
        animation: truck-slide 2s ease-in-out infinite;
    }

    /* Route line dash animation */
    .route-dash {
        stroke-dasharray: 8 6;
        animation: dash-move 1.2s linear infinite;
    }

    @keyframes dash-move {
        to {
            stroke-dashoffset: -14;
        }
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
                    'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                    'processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                    'shipped' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                    'completed' => 'bg-green-500/10 text-green-500 border-green-500/20',
                    'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20',
                    };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusColor }}">
                        {{ $order->status }}
                    </span>
                </div>
                <p class="text-gray-400 text-xs mt-1 font-mono">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
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

            {{-- 1. ORDER STEPPER --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-symbols-outlined text-8xl text-white truck-anim">local_shipping</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-8 relative z-10">Order Status</h3>
                <div class="relative z-10">
                    @php
                    $steps = ['pending', 'processing', 'shipped', 'completed'];
                    $currentStepIndex = array_search($order->status, $steps);
                    if ($order->status == 'cancelled') $currentStepIndex = -1;
                    @endphp
                    <div class="flex items-center justify-between relative">
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-white/10 rounded-full -z-10"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 rounded-full -z-10 transition-all duration-1000"
                            style="width: {{ $currentStepIndex >= 0 ? ($currentStepIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>
                        @foreach($steps as $index => $step)
                        @php
                        $isActive = $index <= $currentStepIndex;
                            $isCurrent=$index===$currentStepIndex;
                            @endphp
                            <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 transition-all
                                    {{ $isActive ? 'bg-blue-600 border-[#0a0a0a] text-white shadow-[0_0_15px_#2563eb]' : 'bg-[#1a1a1a] border-[#0a0a0a] text-gray-600' }}">
                                <span class="material-symbols-outlined text-sm">
                                    {{ match($step) {
                                            'pending'    => 'receipt_long',
                                            'processing' => 'deployed_code',
                                            'shipped'    => 'local_shipping',
                                            'completed'  => 'check_circle',
                                        } }}
                                </span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $isActive ? 'text-blue-400' : 'text-gray-600' }}">
                                {{ $step }}
                            </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 2. INTERACTIVE MAP + TIMELINE --}}
        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">

            {{-- ── A. LEAFLET MAP ── --}}
            <div class="relative w-full" style="height: 300px;">
                <div id="deliveryMap" class="w-full h-full"></div>

                {{-- Live Badge --}}
                <div class="absolute top-4 left-4 z-[999] flex items-center gap-2 px-3 py-1.5 bg-black/80 backdrop-blur border border-white/10 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Live Tracking</span>
                </div>

                {{-- ETA Badge --}}
                <div class="absolute top-4 right-4 z-[999] px-3 py-1.5 bg-black/80 backdrop-blur border border-blue-500/30 rounded-full">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Est. Arrival</p>
                    <p class="text-xs font-black text-blue-400">
                        {{ \Carbon\Carbon::now()->addDays(2)->format('d M Y') }}
                    </p>
                </div>

                {{-- Gradient fade bottom --}}
                <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-[#0a0a0a] to-transparent z-[998] pointer-events-none"></div>
            </div>

            {{-- ── B. DC TRANSIT CHECKPOINTS ── --}}
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500 text-lg">near_me</span>
                        Shipment Journey
                    </h3>
                    {{-- Courier badge --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg">
                        <span class="material-symbols-outlined text-sm text-blue-400">inventory_2</span>
                        <div>
                            <p class="text-[10px] text-white font-bold">JNE REG</p>
                            <p class="text-[10px] text-gray-500 font-mono">JD{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                {{-- DYNAMIC CHECKPOINTS - DC Transit History --}}
                @php
                $status = $order->status;

                // Urutan dari atas ke bawah (Terbaru ke Terlama)
                $checkpoints = [
                [
                'active' => ($status === 'completed'),
                'current' => ($status === 'completed'),
                'icon' => 'check_circle',
                'status' => 'Pesanan Selesai',
                'location' => 'Alamat Tujuan',
                'description' => 'Paket telah berhasil dikirim dan diterima oleh penerima.',
                'time' => $order->updated_at->format('H:i'),
                'date' => $order->updated_at->format('d M Y'),
                'badge' => 'Delivered',
                'badge_color' => 'text-green-400 bg-green-500/10 border-green-500/20',
                ],
                [
                'active' => in_array($status, ['shipped', 'completed']),
                'current' => ($status === 'shipped'),
                'icon' => 'local_shipping',
                'status' => 'Paket dalam pengiriman',
                'location' => 'Menuju alamat tujuan',
                'description' => 'Paket sudah diserahkan ke kurir dan sedang dalam perjalanan.',
                'time' => '10:00',
                'date' => $order->updated_at->format('d M Y'),
                'badge' => 'On The Way',
                'badge_color' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                ],
                [
                'active' => in_array($status, ['processing', 'shipped', 'completed']),
                'current' => ($status === 'processing'),
                'icon' => 'inventory_2',
                'status' => 'Pesanan Diproses',
                'location' => 'Gudang NexRig',
                'description' => 'Pesanan sedang dipacking dan menunggu penjemputan oleh kurir.',
                'time' => '08:00',
                'date' => $order->created_at->format('d M Y'),
                'badge' => 'Packing',
                'badge_color' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                ],
                [
                'active' => true, // Selalu aktif karena ini tahap pertama
                'current' => ($status === 'pending'),
                'icon' => 'receipt_long',
                'status' => 'Pesanan Dikonfirmasi',
                'location' => 'Sistem',
                'description' => 'Pesanan #' . $order->id . ' berhasil dibuat.',
                'time' => $order->created_at->format('H:i'),
                'date' => $order->created_at->format('d M Y'),
                'badge' => 'Confirmed',
                'badge_color' => 'text-gray-400 bg-gray-500/10 border-gray-500/20',
                ],
                ];

                // Jika Dibatalkan, timpa semua riwayat
                if ($status === 'cancelled') {
                $checkpoints = [[
                'active' => true, 'current' => true, 'icon' => 'cancel',
                'status' => 'Pesanan Dibatalkan', 'location' => 'Sistem',
                'description' => 'Proses pengiriman dibatalkan oleh pengguna atau sistem.',
                'time' => $order->updated_at->format('H:i'), 'date' => $order->updated_at->format('d M Y'),
                'badge' => 'Cancelled', 'badge_color' => 'text-red-400 bg-red-500/10 border-red-500/20',
                ]];
                }
                @endphp

                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-[19px] top-2 bottom-2 w-px bg-white/10"></div>

                    <div class="space-y-0">
                        @foreach($checkpoints as $i => $cp)
                        <div class="relative flex gap-5 pb-8 last:pb-0 timeline-item" style="animation-delay: {{ $i * 0.08 }}s">

                            {{-- Icon Circle --}}
                            <div class="relative z-10 shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all
                                        {{ $cp['active'] 
                                            ? ($cp['current'] 
                                                ? 'bg-blue-600 border-blue-400 shadow-[0_0_12px_rgba(37,99,235,0.5)]' 
                                                : 'bg-[#1a1a1a] border-white/20') 
                                            : 'bg-[#0f0f0f] border-white/5' }}">
                                    <span class="material-symbols-outlined text-sm
                                            {{ $cp['active'] 
                                                ? ($cp['current'] ? 'text-white' : 'text-gray-300') 
                                                : 'text-gray-700' }}">
                                        {{ $cp['icon'] }}
                                    </span>
                                </div>
                                {{-- Pulse ring for current --}}
                                @if($cp['current'])
                                <div class="absolute inset-0 rounded-full border-2 border-blue-500 opacity-60"
                                    style="animation: pulse-ring 1.5s ease-out infinite;"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pt-1.5 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold {{ $cp['active'] ? 'text-white' : 'text-gray-600' }}">
                                            {{ $cp['status'] }}
                                        </h4>
                                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest rounded-full border {{ $cp['active'] ? $cp['badge_color'] : 'text-gray-600 bg-gray-500/5 border-gray-500/10' }}">
                                            {{ $cp['badge'] }}
                                        </span>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold {{ $cp['active'] ? 'text-gray-300' : 'text-gray-700' }}">{{ $cp['time'] }}</p>
                                        <p class="text-[10px] {{ $cp['active'] ? 'text-gray-500' : 'text-gray-700' }} uppercase font-bold tracking-wider">{{ $cp['date'] }}</p>
                                    </div>
                                </div>

                                {{-- Location chip --}}
                                <div class="flex items-center gap-1 mb-2">
                                    <span class="material-symbols-outlined text-[12px] {{ $cp['active'] ? 'text-blue-500' : 'text-gray-700' }}">location_on</span>
                                    <span class="text-[11px] font-bold {{ $cp['active'] ? 'text-blue-400/80' : 'text-gray-700' }} uppercase tracking-wider">
                                        {{ $cp['location'] }}
                                    </span>
                                </div>

                                <p class="text-xs {{ $cp['active'] ? 'text-gray-400' : 'text-gray-700' }} leading-relaxed">
                                    {{ $cp['description'] }}
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
                <h3 class="font-bold text-white">Items Ordered</h3>
                <span class="text-xs text-gray-500 font-bold uppercase">{{ $order->items->count() }} Items</span>
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
            <h3 class="font-bold text-white mb-6">Order Summary</h3>
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
                    <span class="text-gray-400">Shipping</span>
                    <span class="text-green-500 font-bold">FREE</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Tax (11%)</span>
                    <span class="text-white">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Grand Total</span>
                <span class="text-2xl font-black text-blue-500">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- 2. SHIPPING DETAILS --}}
        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
            <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-sm">location_on</span>
                Shipping Details
            </h3>
            <div class="space-y-1 text-sm">
                <p class="text-white font-bold">{{ $order->shipping_name ?? auth()->user()->name }}</p>
                <p class="text-gray-400 leading-relaxed">
                    {{ $order->shipping_address ?? 'Address not saved' }}<br>
                    {{ $order->shipping_city ?? '' }} {{ $order->shipping_postal_code ?? '' }}
                </p>
                <p class="text-gray-400 mt-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[14px]">call</span>
                    {{ $order->shipping_phone ?? '-' }}
                </p>
            </div>
        </div>

        {{-- 3. PAYMENT INFO --}}
        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
            <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-sm">payments</span>
                Payment Method
            </h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-6 bg-white/10 rounded flex items-center justify-center">
                    <span class="material-symbols-outlined text-xs text-white">account_balance</span>
                </div>
                <div>
                    <p class="text-sm text-white font-medium">Bank Transfer</p>
                    <p class="text-xs text-gray-500">Manual Verification</p>
                </div>
            </div>
        </div>

        {{-- 4. ORDER ACTION --}}
        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
            <h3 class="font-bold text-white mb-4">Order Action</h3>

            @if($order->status === 'pending')
            <button onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                class="w-full py-2.5 bg-red-600/10 hover:bg-red-600/20 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-red-300 text-sm font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">cancel</span>
                Batalkan Pesanan
            </button>
            <p class="text-[11px] text-gray-600 text-center mt-2">Pembatalan hanya bisa dilakukan saat status Pending</p>

            @elseif($order->status === 'cancelled')
            <div class="w-full py-2.5 border border-red-500/20 text-red-500/60 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                <span class="material-symbols-outlined text-sm">cancel</span>
                Pesanan Dibatalkan
            </div>
            <p class="text-[11px] text-gray-600 text-center mt-2">Pesanan ini telah dibatalkan</p>

            @else
            <div class="w-full py-2.5 bg-white/[0.03] border border-white/10 text-gray-600 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed select-none">
                <span class="material-symbols-outlined text-sm">block</span>
                Batalkan Pesanan
            </div>
            <p class="text-[11px] text-gray-600 text-center mt-2">
                Tidak dapat dibatalkan — pesanan sudah
                <span class="text-amber-500/70 capitalize">{{ $order->status }}</span>
            </p>
            @endif
        </div>

        {{-- 5. NEED HELP? --}}
        <div class="p-6 rounded-xl bg-gradient-to-br from-blue-900/50 to-transparent border border-blue-500/20 text-center">
            <p class="text-sm text-blue-200 mb-4">Having trouble with this order?</p>
            <button class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all">
                Contact Support
            </button>
        </div>

    </div>
</div>
</div>

{{-- CANCEL MODAL --}}
@if($order->status === 'pending')
<div id="cancelModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
        onclick="document.getElementById('cancelModal').classList.add('hidden')"></div>
    <div class="relative bg-[#0f0f0f] border border-red-500/30 rounded-2xl p-8 w-full max-w-md shadow-2xl">
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-red-400 text-2xl">error</span>
            </div>
            <h3 class="text-lg font-black text-white uppercase tracking-tight">Batalkan Pesanan?</h3>
            <p class="text-gray-400 text-sm mt-2">
                Order <span class="text-white font-bold">#{{ $order->id }}</span> akan dibatalkan dan
                <span class="text-red-400 font-bold">tidak dapat dikembalikan</span>.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('cancelModal').classList.add('hidden')"
                class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-bold rounded-lg transition-all">
                Kembali
            </button>
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

{{-- ====== LEAFLET JS ====== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {


        @php
        $destLat = $order -> shipping_latitude ?? ($order -> userAddress -> latitude ?? -6.9932);
        $destLng = $order -> shipping_longitude ?? ($order -> userAddress -> longitude ?? 110.4229);
        $destLabel = collect([$order -> shipping_address, $order -> shipping_city, $order -> shipping_postal_code]) -> filter() -> implode(', ') ? : 'Tujuan Pengiriman';
        @endphp

        const orderStatus = '{{ $order->status }}';
        let truckLat, truckLng, truckIcon, truckLabel;


        if (orderStatus === 'pending' || orderStatus === 'processing') {
            truckLat = -6.9932;
            truckLng = 110.4229;
            truckIcon = '🏭';
            truckLabel = 'Persiapan Gudang';
        } else if (orderStatus === 'completed') {
            truckLat = {
                {
                    $destLat
                }
            }; // Truk sudah tiba di lokasi
            truckLng = {
                {
                    $destLng
                }
            };
            truckIcon = '✅';
            truckLabel = 'Paket Tiba';
        } else {
            // Shipped: Truk bergerak di tengah jalan
            truckLat = (-7.0051 + {
                {
                    $destLat
                }
            }) / 2;
            truckLng = (110.4381 + {
                {
                    $destLng
                }
            }) / 2;
            truckIcon = '🚛';
            truckLabel = 'Kurir On The Way';
        }

        const POINTS = [{
                lat: -6.9932,
                lng: 110.4229,
                label: 'Gudang NexRig',
                desc: 'Gudang Pusat',
                icon: '🏭',
                active: true,
                isCurrent: (orderStatus === 'processing')
            },
            {
                lat: -7.0051,
                lng: 110.4381,
                label: 'DC Hub',
                desc: 'Transit',
                icon: '📦',
                active: (orderStatus !== 'pending'),
                isCurrent: false
            },
            {
                lat: truckLat,
                lng: truckLng,
                label: truckLabel,
                desc: 'Posisi paket saat ini',
                icon: truckIcon,
                active: true,
                isCurrent: true
            },
            {
                lat: {
                    {
                        $destLat
                    }
                },
                lng: {
                    {
                        $destLng
                    }
                },
                label: '{{ $order->shipping_name ?? "Penerima" }}',
                desc: '{{ addslashes($destLabel) }}',
                icon: '📍',
                active: (orderStatus === 'completed'),
                isCurrent: false,
            },
        ];

        const map = L.map('deliveryMap', {
            center: [{
                {
                    $destLat
                }
            }, {
                {
                    $destLng
                }
            }],
            zoom: 14,
            zoomControl: false,
            attributionControl: false,
            scrollWheelZoom: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);
        // ── Custom icon builder ──
        function makeIcon(emoji, isActive = false, isCurrent = false) {
            const bg = isCurrent ? '#1d4ed8' : (isActive ? '#1a1a1a' : '#111');
            const border = isCurrent ? '#60a5fa' : (isActive ? '#4b5563' : '#1f2937');
            const glow = isCurrent ? '0 0 18px rgba(37,99,235,0.75)' : '0 2px 8px rgba(0,0,0,0.6)';
            return L.divIcon({
                className: '',
                html: `<div style="
                background:${bg};border:2px solid ${border};border-radius:50%;
                width:36px;height:36px;display:flex;align-items:center;
                justify-content:center;font-size:16px;box-shadow:${glow};
                transition:all .3s;
            ">${emoji}</div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -20],
            });
        }

        // ── Pasang marker ──
        let currentMarker = null;
        POINTS.forEach(p => {
            const m = L.marker([p.lat, p.lng], {
                icon: makeIcon(p.icon, p.active, p.isCurrent),
                zIndexOffset: p.isCurrent ? 1000 : 0,
            }).addTo(map);

            m.bindTooltip(p.label, {
                direction: 'top',
                offset: [0, -20]
            });

            if (p.isCurrent) {
                m.bindPopup(`
                <div style="background:#0a0a0a;color:#fff;border:1px solid #2563eb;
                     border-radius:10px;padding:10px 14px;min-width:170px;font-family:monospace;">
                    <p style="font-size:11px;font-weight:bold;color:#60a5fa;margin:0 0 4px;">🚛 ON THE WAY</p>
                    <p style="font-size:12px;margin:0 0 2px;font-weight:bold;">${p.label}</p>
                    <p style="font-size:10px;color:#9ca3af;margin:0;">Area Semarang Timur</p>
                    <hr style="border-color:#1f2937;margin:8px 0;">
                    <p style="font-size:10px;color:#6b7280;margin:0;">Est. tiba: <span style="color:#34d399;font-weight:bold;">{{ \Carbon\Carbon::now()->addDays(2)->format('d M Y') }}</span></p>
                </div>
            `, {
                    className: 'leaflet-dark-popup'
                });
                m.openPopup();
                currentMarker = m;
            }

            // Popup untuk marker tujuan (alamat pelanggan)
            if (!p.active && !p.isCurrent) {
                m.bindPopup(`
                <div style="background:#0a0a0a;color:#fff;border:1px solid #374151;
                     border-radius:10px;padding:10px 14px;min-width:180px;font-family:monospace;">
                    <p style="font-size:11px;font-weight:bold;color:#9ca3af;margin:0 0 4px;">📍 TUJUAN</p>
                    <p style="font-size:12px;margin:0 0 4px;font-weight:bold;">{{ $order->shipping_name ?? 'Penerima' }}</p>
                    <p style="font-size:10px;color:#6b7280;margin:0;line-height:1.5;">{{ addslashes($destLabel) }}</p>
                    @if($order->shipping_phone)
                    <hr style="border-color:#1f2937;margin:6px 0;">
                    <p style="font-size:10px;color:#6b7280;margin:0;">{{ $order->shipping_phone }}</p>
                    @endif
                </div>
            `, {
                    className: 'leaflet-dark-popup'
                });
            }
        });

        /**
         * ── OSRM Routing (gratis, no API key) ──
         * 
         * Kita fetch rute jalan dari OSRM public API untuk SETIAP segmen:
         *   Segmen 1 (abu-abu/belum dilalui) : current → dest
         *   Segmen 2 (biru/sudah dilalui)    : origin → transit → current
         *
         * OSRM hanya menerima 2 waypoint per request, jadi kita fetch
         * beberapa segmen lalu gabungkan koordinatnya.
         */

        // Decode polyline geometry dari OSRM (format encoded polyline)
        function decodePolyline(str, precision = 5) {
            let index = 0,
                lat = 0,
                lng = 0,
                result = [];
            const factor = Math.pow(10, precision);
            while (index < str.length) {
                let shift = 0,
                    result_b = 0,
                    b;
                do {
                    b = str.charCodeAt(index++) - 63;
                    result_b |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                lat += (result_b & 1) ? ~(result_b >> 1) : (result_b >> 1);
                shift = 0;
                result_b = 0;
                do {
                    b = str.charCodeAt(index++) - 63;
                    result_b |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                lng += (result_b & 1) ? ~(result_b >> 1) : (result_b >> 1);
                result.push([lat / factor, lng / factor]);
            }
            return result;
        }

        // Fetch satu segmen rute dari OSRM
        async function fetchSegment(from, to) {
            const url = `https://router.project-osrm.org/route/v1/driving/` +
                `${from.lng},${from.lat};${to.lng},${to.lat}` +
                `?overview=full&geometries=polyline`;
            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.code === 'Ok' && data.routes.length > 0) {
                    return decodePolyline(data.routes[0].geometry);
                }
            } catch (e) {
                /* fallback ke garis lurus */ }
            // Fallback: garis lurus jika OSRM gagal
            return [
                [from.lat, from.lng],
                [to.lat, to.lng]
            ];
        }

        // Fetch semua segmen sekaligus lalu gambar
        async function drawRoutes() {
            const [p0, p1, p2, p3] = POINTS; // origin, transit, current, dest

            const [seg01, seg12, seg23] = await Promise.all([
                fetchSegment(p0, p1),
                fetchSegment(p1, p2),
                fetchSegment(p2, p3),
            ]);

            let activePath = [];
            let pendingPath = [];

            // Logika pewarnaan rute berdasarkan status
            if (orderStatus === 'pending' || orderStatus === 'processing') {
                activePath = [];
                pendingPath = [...seg01, ...seg12, ...seg23]; // Semuanya belum dilalui
            } else if (orderStatus === 'shipped') {
                activePath = [...seg01, ...seg12]; // Sudah dilalui sampai posisi kurir
                pendingPath = seg23; // Sisa perjalanan kurir
            } else if (orderStatus === 'completed') {
                activePath = [...seg01, ...seg12, ...seg23]; // Seluruh rute sudah dilalui
                pendingPath = [];
            }

            // Gambar garis abu-abu (belum dilalui)
            if (pendingPath.length > 0) {
                L.polyline(pendingPath, {
                    color: '#374151',
                    weight: 3,
                    opacity: 0.5,
                    dashArray: '7 6'
                }).addTo(map);
            }

            // Gambar garis biru (sudah dilalui)
            if (activePath.length > 0) {
                L.polyline(activePath, {
                    color: '#1e3a8a',
                    weight: 7,
                    opacity: 0.4
                }).addTo(map);
                L.polyline(activePath, {
                    color: '#2563eb',
                    weight: 4,
                    opacity: 0.95
                }).addTo(map);
            }

            // Fit map ke semua titik
            const allCoords = [...activePath, ...pendingPath];
            if (allCoords.length > 0) map.fitBounds(allCoords, {
                padding: [50, 50]
            });
        }

        drawRoutes();
    });
</script>

{{-- Dark popup / tooltip overrides --}}
<style>
    .leaflet-popup-content-wrapper,
    .leaflet-popup-tip {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .leaflet-popup-content {
        margin: 0 !important;
    }

    .leaflet-dark-popup .leaflet-popup-content-wrapper {
        background: transparent;
    }

    .leaflet-tooltip {
        background: #111 !important;
        border: 1px solid #374151 !important;
        color: #e5e7eb !important;
        font-size: 11px !important;
        font-weight: bold !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
    }

    .leaflet-tooltip-bottom::before {
        border-bottom-color: #374151 !important;
    }
</style>

@endsection