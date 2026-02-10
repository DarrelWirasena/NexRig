@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto pb-20">
    
    {{-- HEADER HALAMAN --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                My <span class="text-blue-600">Orders</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2">Track your rigs, upgrades, and component shipments.</p>
        </div>
        
        {{-- SEARCH BAR --}}
        <div class="relative w-full md:w-64">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">search</span>
            <input type="text" placeholder="Search Order ID..." class="w-full pl-10 pr-4 py-2 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600">
        </div>
    </div>

    {{-- FILTER TABS --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <div class="flex bg-[#0a0a0a] p-1 rounded-xl border border-white/10 w-full sm:w-auto">
            <button class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-bold transition-all bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                Active Orders
            </button>
            <button class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-white hover:bg-white/5">
                Past History
            </button>
        </div>
    </div>

    {{-- ORDER LIST --}}
    <div class="space-y-6">
        
        @forelse($orders as $order)
            <div class="bg-[#0a0a0a] rounded-xl border border-white/10 overflow-hidden transition-all hover:border-blue-600/50 group relative">
                {{-- Decor --}}
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-600/5 rounded-bl-full -mr-10 -mt-10 transition-all group-hover:bg-blue-600/10"></div>

                <div class="p-6 flex flex-col lg:flex-row lg:items-center gap-6 relative z-10">
                    
                    {{-- GAMBAR PRODUK UTAMA (Ambil item pertama) --}}
                    <div class="size-24 rounded-lg bg-[#050014] flex items-center justify-center shrink-0 overflow-hidden border border-white/10">
                        @if($order->items->first() && $order->items->first()->product->images->first())
                            <img src="{{ $order->items->first()->product->images->where('is_primary', true)->first()->image_url }}" 
                                 alt="Product Image" 
                                 class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                        @else
                            <span class="material-symbols-outlined text-gray-600 text-4xl">deployed_code</span>
                        @endif
                    </div>

                    {{-- DETAIL ORDER --}}
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h3 class="text-lg font-bold text-white group-hover:text-blue-500 transition-colors">
                                Order #{{ $order->id }}
                            </h3>
                            
                            {{-- STATUS BADGE (Logic Warna) --}}
                            @php
                                $statusColor = match($order->status) {
                                    'pending' => 'text-amber-500 border-amber-500/30 bg-amber-500/10',
                                    'processing' => 'text-blue-400 border-blue-400/30 bg-blue-400/10',
                                    'shipped' => 'text-purple-400 border-purple-400/30 bg-purple-400/10',
                                    'completed' => 'text-green-400 border-green-400/30 bg-green-400/10',
                                    'cancelled' => 'text-red-400 border-red-400/30 bg-red-400/10',
                                    default => 'text-gray-400 border-gray-400/30 bg-gray-400/10',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 text-[10px] font-black rounded border uppercase tracking-wider {{ $statusColor }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-gray-600">calendar_today</span>
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-white font-bold">
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        {{-- LIST ITEM KECIL --}}
                        <div class="pt-2 flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                                <span class="text-[10px] bg-white/5 border border-white/10 px-2 py-1 rounded text-gray-400">
                                    {{ $item->product->name }} x{{ $item->quantity }}
                                </span>
                            @endforeach
                            @if($order->items->count() > 3)
                                <span class="text-[10px] text-gray-500 self-center">+{{ $order->items->count() - 3 }} more</span>
                            @endif
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                        <a href="{{ route('orders.show', $order->id) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)] flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- EMPTY STATE --}}
            <div class="text-center py-20 bg-[#0a0a0a] border border-dashed border-white/10 rounded-xl">
                <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">package_2</span>
                <h3 class="text-xl font-bold text-white mb-2">No orders found</h3>
                <p class="text-gray-500 text-sm mb-6">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-white text-black font-bold rounded hover:bg-gray-200 transition-colors">
                    Browse Catalog
                </a>
            </div>
        @endforelse

    </div>

    {{-- SUPPORT BANNER --}}
    <div class="mt-12 rounded-xl bg-gradient-to-r from-blue-900 to-[#0a0a0a] border border-blue-500/30 p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="relative z-10 text-center md:text-left">
            <h4 class="text-xl font-bold uppercase tracking-tight italic text-white">Need assistance?</h4>
            <p class="text-blue-200 text-sm">Our support squad is available 24/7 for order inquiries.</p>
        </div>
        <button class="relative z-10 px-8 py-3 bg-white text-blue-900 font-bold rounded-lg text-sm hover:scale-105 transition-all shadow-xl whitespace-nowrap">
            CONTACT SUPPORT
        </button>
    </div>

</div>

@endsection