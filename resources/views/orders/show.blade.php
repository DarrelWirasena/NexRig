@extends('layouts.dashboard')

@section('content')

<div class="max-w-6xl mx-auto pb-20">

    {{-- HEADER: Back Button & Title --}}
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
                    {{-- Status Badge --}}
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

        <a href="{{ route('orders.invoice', $order->id) }}"
      target="_blank"
        class="px-5 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm font-bold text-white transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">download</span> Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI (2/3): Progress & Items --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- 1. ORDER TRACKER (Stepper) --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-symbols-outlined text-8xl text-white">local_shipping</span>
                </div>

                <h3 class="text-lg font-bold text-white mb-8 relative z-10">Order Status</h3>

                <div class="relative z-10">
                    {{-- Logic Status Steps --}}
                    @php
                        $steps = ['pending', 'processing', 'shipped', 'completed'];
                        $currentStepIndex = array_search($order->status, $steps);
                        if ($order->status == 'cancelled') $currentStepIndex = -1; 
                    @endphp

                    <div class="flex items-center justify-between relative">
                        {{-- Connecting Line Background --}}
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-white/10 rounded-full -z-10"></div>
                        
                        {{-- Connecting Line Active (Blue) --}}
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 rounded-full -z-10 transition-all duration-1000"
                             style="width: {{ $currentStepIndex >= 0 ? ($currentStepIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>

                        @foreach($steps as $index => $step)
                            @php
                                $isActive = $index <= $currentStepIndex;
                                $isCurrent = $index === $currentStepIndex;
                            @endphp
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 transition-all
                                    {{ $isActive ? 'bg-blue-600 border-[#0a0a0a] text-white shadow-[0_0_15px_#2563eb]' : 'bg-[#1a1a1a] border-[#0a0a0a] text-gray-600' }}">
                                    <span class="material-symbols-outlined text-sm">
                                        {{ match($step) {
                                            'pending' => 'receipt_long',
                                            'processing' => 'deployed_code',
                                            'shipped' => 'local_shipping',
                                            'completed' => 'check_circle',
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

            {{-- [BARU] LIVE TRACKING & MAP --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">
                
                {{-- A. STATIC MAP SECTION --}}
                <div class="relative w-full h-64 bg-[#111] overflow-hidden group">
                    {{-- Placeholder Map Image (Dark Mode Style) --}}
                    <img src="https://api.mapbox.com/styles/v1/mapbox/dark-v10/static/110.418, -6.993,13,0/800x400?access_token=YOUR_TOKEN_HERE" 
                         onerror="this.src='https://via.placeholder.com/800x400/1a1a1a/333?text=MAP+VIEW'"
                         alt="Delivery Map" 
                         class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity duration-700 grayscale">
                    
                    {{-- Map Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[#0a0a0a]"></div>

                    {{-- Animated Pin --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
                        <div class="relative">
                            <span class="w-4 h-4 bg-blue-500 rounded-full animate-ping absolute opacity-75"></span>
                            <span class="w-4 h-4 bg-blue-600 rounded-full relative z-10 border-2 border-white shadow-[0_0_20px_#2563eb]"></span>
                        </div>
                        <div class="mt-2 px-3 py-1 bg-black/80 backdrop-blur border border-white/10 rounded-full text-[10px] font-bold text-white uppercase tracking-wider">
                            Semarang Hub
                        </div>
                    </div>
                </div>

                {{-- B. TIMELINE SECTION --}}
                <div class="p-8">
                    <h3 class="font-bold text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500">near_me</span>
                        Shipment Journey
                    </h3>

                    <div class="relative pl-4 border-l border-white/10 space-y-8">
                        @foreach($trackingEvents as $event)
                            <div class="relative pl-8 group">
                                {{-- Timeline Dot --}}
                                <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full border-2 transition-all duration-300
                                    {{ $event['active'] 
                                        ? 'bg-blue-600 border-blue-400 shadow-[0_0_10px_#2563eb] scale-125' 
                                        : 'bg-[#0a0a0a] border-gray-600 group-hover:border-gray-400' 
                                    }}">
                                </div>

                                {{-- Content --}}
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-1 sm:gap-4">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold {{ $event['active'] ? 'text-blue-400' : 'text-white' }}">
                                            {{ $event['status'] }}
                                        </h4>
                                        <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                            {{ $event['description'] }}
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold text-gray-300">{{ $event['time'] }}</p>
                                        <p class="text-[10px] text-gray-600 uppercase font-bold tracking-wider">{{ $event['date'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Show More Button (Dummy) --}}
                        <div class="relative pl-8 pt-2">
                            <button class="text-xs font-bold text-blue-600 hover:text-blue-400 flex items-center gap-1 transition-colors">
                                <span>View Full History</span>
                                <span class="material-symbols-outlined text-sm">expand_more</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- 2. ORDER ITEMS --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex justify-between items-center">
                    <h3 class="font-bold text-white">Items Ordered</h3>
                    <span class="text-xs text-gray-500 font-bold uppercase">{{ $order->items->count() }} Items</span>
                </div>
                
                <div class="divide-y divide-white/5">
                    @foreach($order->items as $item)
                        <div class="p-6 flex gap-6 items-center group hover:bg-white/[0.02] transition-colors">
                            {{-- Image --}}
                            <div class="w-20 h-20 bg-[#050014] rounded-lg border border-white/10 flex items-center justify-center shrink-0 overflow-hidden">
                                @if($item->product->images->first())
                                    <img src="{{ $item->product->images->where('is_primary', true)->first()->src }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-700">image</span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">
                                <h4 class="text-white font-bold mb-1 group-hover:text-blue-500 transition-colors">
                                    <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h4>
                                <div class="text-xs text-gray-500 mb-2">
                                    {{ $item->product->series->name ?? 'Component' }}
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="text-gray-400">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="text-gray-600">x</span>
                                    <span class="text-white font-bold">{{ $item->quantity }}</span>
                                </div>
                            </div>

                            {{-- Total per item --}}
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

        {{-- KOLOM KANAN (1/3): Summary & Info --}}
        <div class="space-y-6">
            
            {{-- 1. ORDER SUMMARY --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold text-white mb-6">Order Summary</h3>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-white">Rp {{ number_format($order->total_price - ($order->total_price * 0.11), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Shipping</span>
                        <span class="text-green-500 font-bold">FREE</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tax (11%)</span>
                        <span class="text-white">Rp {{ number_format($order->total_price * 0.11, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Grand Total</span>
                    <span class="text-2xl font-black text-blue-500 text-glow">
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

            {{-- 4. NEED HELP? --}}
            <div class="p-6 rounded-xl bg-gradient-to-br from-blue-900/50 to-transparent border border-blue-500/20 text-center">
                <p class="text-sm text-blue-200 mb-4">Having trouble with this order?</p>
                <button class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all">
                    Contact Support
                </button>
            </div>

        </div>

    </div>
</div>

@endsection