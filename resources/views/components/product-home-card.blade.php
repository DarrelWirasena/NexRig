@props(['product'])

@php
    $LOW_STOCK_THRESHOLD = 5;
    $isOutOfStock  = $product->track_stock && $product->stock <= 0;
    $isLowStock    = $product->track_stock && $product->stock > 0 && $product->stock <= $LOW_STOCK_THRESHOLD;
@endphp

<div class="group relative bg-[#121212] border border-white/5 hover:border-primary/50 transition-all duration-500 flex flex-col clip-corner hover:shadow-[0_0_30px_rgba(19,55,236,0.15)] h-full">

    {{-- Link transparan (mobile) --}}
    @if(!$isOutOfStock)
    <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-10 md:hidden">
        <span class="sr-only">View {{ $product->name }}</span>
    </a>
    @endif

    {{-- IMAGE CONTAINER --}}
    <div class="relative aspect-square md:aspect-[4/5] overflow-hidden bg-black/50">
        {{-- Decorative Corners --}}
        <div class="hidden md:block absolute top-4 left-4 w-2 h-2 border-t border-l border-white/30 z-20 transition-all group-hover:w-16 group-hover:h-16 group-hover:border-primary/50"></div>
        <div class="hidden md:block absolute top-4 right-4 w-2 h-2 border-t border-r border-white/30 z-20 transition-all group-hover:w-16 group-hover:h-16 group-hover:border-primary/50"></div>

        <img src="{{ $product->images->where('is_primary', true)->first()->src ?? 'https://via.placeholder.com/500x600' }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-700 {{ $isOutOfStock ? 'grayscale opacity-40' : 'group-hover:scale-110 group-hover:contrast-110' }}"
            alt="{{ $product->name }}">

        {{-- Badge stok habis (pojok kiri atas) — hanya untuk out of stock --}}
        @if($isOutOfStock)
        <div class="absolute top-3 left-3 z-30">
            <span class="flex items-center gap-1 bg-red-500/20 border border-red-500/50 text-red-400 text-[10px] font-bold px-2 py-1 rounded tracking-widest uppercase backdrop-blur-sm">
                <span class="material-symbols-outlined text-[12px]">block</span>
                Stok Habis
            </span>
        </div>
        @endif

        {{-- OVERLAY HOVER --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 p-4 z-30 pointer-events-none group-hover:pointer-events-auto">

            {{-- Tombol VIEW SPECS --}}
            <a href="{{ route('products.show', $product->slug) }}"
                class="w-full max-w-[200px] py-3 border border-white/30 text-white font-bold uppercase tracking-widest text-[10px] md:text-xs hover:border-white hover:bg-white hover:text-black transition-all text-center">
                View Specs
            </a>

            {{-- Tombol ADD TO CART --}}
            @if($isOutOfStock)
            {{-- Disabled — stok habis --}}
            <div class="w-full max-w-[200px]">
                <button disabled
                    class="w-full py-3 bg-white/10 text-gray-500 font-bold uppercase tracking-widest text-[10px] md:text-xs cursor-not-allowed flex items-center justify-center gap-2">
                    <span>Stok Habis</span>
                    <span class="material-symbols-outlined text-sm">remove_shopping_cart</span>
                </button>
            </div>
            @else
            {{-- Active — bisa add to cart --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST" onsubmit="addToCartAjax(event, this)" class="w-full max-w-[200px] no-global-loader">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                    class="w-full py-3 bg-primary text-white font-bold uppercase tracking-widest text-[10px] md:text-xs hover:bg-blue-600 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-primary/50">
                    <span>Add to Cart</span>
                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                </button>
            </form>
            @endif

        </div>
    </div>

    {{-- INFO SECTION --}}
    <div class="p-4 md:p-5 flex-1 flex flex-col border-t border-white/5 bg-[#121212] group-hover:bg-[#161b30] transition-colors duration-300">
        <div class="flex justify-between items-start mb-1 md:mb-2">
            <h3 class="text-white font-bold text-base md:text-lg uppercase italic truncate group-hover:text-primary transition-colors {{ $isOutOfStock ? 'opacity-50' : '' }}">
                {{ $product->name }}
            </h3>
        </div>

        <div class="flex flex-wrap gap-2 mb-3 md:mb-6">
            <span class="text-[9px] md:text-[10px] uppercase font-bold px-2 py-1 bg-white/5 text-gray-400 rounded border border-white/5">
                {{ $product->series->category->name ?? 'Gaming PC' }}
            </span>
        </div>

        {{-- Low stock indicator di info section --}}
        @if($isLowStock)
        <div class="mb-3 md:mb-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[9px] uppercase font-bold text-amber-500 tracking-wider">Stok Tersisa</span>
                <span class="text-[9px] font-bold text-amber-400">{{ $product->stock }} unit</span>
            </div>
            <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: {{ min(($product->stock / 5) * 100, 100) }}%"></div>
            </div>
        </div>
        @endif

        <div class="mt-auto pt-3 md:pt-4 border-t border-white/5 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[9px] md:text-[10px] text-gray-500 uppercase font-bold tracking-wider">Starting From</span>
                <span class="text-white font-bold text-base md:text-lg {{ $isOutOfStock ? 'opacity-50' : '' }}">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            {{-- Mobile arrow (hanya kalau bisa dibeli) --}}
            @if(!$isOutOfStock)
            <div class="md:hidden text-primary">
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
            @endif
        </div>
    </div>
</div>