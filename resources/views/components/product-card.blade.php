@props(['name', 'price', 'description', 'image', 'rating' => null, 'badge' => null, 'specs' => [], 'product' => null])

@php
    $isOutOfStock = $product && $product->track_stock && $product->stock <= 0;
    $isLowStock   = $product && $product->track_stock && $product->stock > 0 && $product->stock <= 5;

    // Cek apakah produk sudah di-wishlist user yang sedang login
    $isWishlisted = false;
    if ($product && auth()->check()) {
        $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                            ->where('product_id', $product->id)
                            ->exists();
    }
@endphp

<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border transition-all duration-300 h-full shadow-sm
    {{ $isOutOfStock
        ? 'border-red-500/20 hover:border-red-500/40 opacity-60 hover:opacity-70'
        : 'border-gray-200 dark:border-[#232948] hover:border-primary hover:shadow-xl hover:shadow-primary/10' }}">

    {{-- IMAGE SECTION --}}
    <div class="relative aspect-square md:aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden shrink-0">
        <img src="{{ $image }}" alt="{{ $name }}" loading="lazy"
            class="object-cover w-full h-full transition-transform duration-500
            {{ $isOutOfStock ? 'grayscale' : 'group-hover:scale-105' }}">

        {{-- Badge tier --}}
        @if($badge)
        <div class="absolute top-2 left-2 md:top-3 md:left-3 text-white text-[10px] md:text-xs font-bold px-2 py-0.5 md:px-2.5 md:py-1 rounded backdrop-blur-sm shadow-md z-10
            {{ $isOutOfStock ? 'bg-gray-600' : 'bg-primary' }}">
            {{ $badge }}
        </div>
        @endif

        {{-- ── WISHLIST BUTTON FLOATING ── --}}
        @if($product)
        @auth
        <button
            onclick="toggleWishlistCard(event, this, {{ $product->id }})"
            data-product-id="{{ $product->id }}"
            data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
            class="wishlist-card-btn absolute top-2 right-2 md:top-3 md:right-3 z-20
                   w-7 h-7 md:w-8 md:h-8 rounded-full
                   flex items-center justify-center
                   backdrop-blur-md border transition-all duration-200
                   {{ $isWishlisted
                       ? 'bg-red-500/20 border-red-500/50 text-red-400'
                       : 'bg-black/40 border-white/20 text-white/50
                          opacity-0 group-hover:opacity-100' }}
                   hover:scale-110 hover:bg-red-500/30 hover:border-red-500/60 hover:text-red-400">
            <span class="material-symbols-outlined text-[15px] md:text-[17px]"
                  style="font-variation-settings: 'FILL' {{ $isWishlisted ? '1' : '0' }}">
                favorite
            </span>
        </button>
        @else
        {{-- Belum login: arahkan ke login --}}
        <button
           onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('login') }}';"
           class="absolute top-2 right-2 md:top-3 md:right-3 z-20
                  w-7 h-7 md:w-8 md:h-8 rounded-full
                  flex items-center justify-center
                  backdrop-blur-md border border-white/20 bg-black/40
                  text-white/50 opacity-0 group-hover:opacity-100
                  hover:scale-110 hover:bg-red-500/20 hover:border-red-500/40 hover:text-red-400
                  transition-all duration-200">
            <span class="material-symbols-outlined text-[15px] md:text-[17px]"
                  style="font-variation-settings: 'FILL' 0">
                favorite
            </span>
        </button>
        @endauth
        @endif

        {{-- Overlay stok habis --}}
        @if($isOutOfStock)
        <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
            <span class="flex items-center gap-1.5 bg-black/70 border border-red-500/50 text-red-400 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-widest backdrop-blur-sm">
                <span class="material-symbols-outlined text-[14px]">block</span>
                Stok Habis
            </span>
        </div>
        @endif
    </div>

    {{-- CONTENT SECTION --}}
    <div class="p-3 md:p-5 flex flex-col flex-1">

        {{-- HEADER --}}
        <div class="mb-1">
            <h3 class="text-gray-900 dark:text-white text-sm md:text-lg font-bold transition-colors line-clamp-1 leading-tight
                {{ $isOutOfStock ? '' : 'group-hover:text-primary' }}">
                {{ $name }}
            </h3>
        </div>

        {{-- DESCRIPTION --}}
        <p class="text-gray-500 dark:text-[#929bc9] text-[10px] md:text-sm mb-2 line-clamp-1 md:line-clamp-2 leading-tight">
            {{ $description }}
        </p>

        {{-- SPECS --}}
        <div class="flex flex-wrap gap-1.5 md:gap-2 mb-3 md:mb-6 mt-auto">
            @foreach($specs as $index => $spec)
            <span class="{{ $index > 1 ? 'hidden md:inline-block' : 'inline-block' }}
                         px-1.5 py-0.5 md:px-2 md:py-1 rounded
                         bg-gray-100 dark:bg-[#232948]
                         text-gray-700 dark:text-gray-300
                         text-[10px] md:text-xs font-medium
                         border border-gray-200 dark:border-white/5 whitespace-nowrap">
                {{ $spec }}
            </span>
            @endforeach
            @if(count($specs) > 2)
            <span class="md:hidden text-[10px] text-gray-400 self-center">+{{ count($specs) - 2 }}</span>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="pt-2 md:pt-4 border-t border-gray-200 dark:border-[#232948] flex items-center justify-between gap-2">
            <div class="flex flex-col">
                <span class="hidden md:block text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
                <span class="text-gray-900 dark:text-white text-sm md:text-lg font-bold">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </span>
            </div>

            @if($isOutOfStock)
            <button disabled
                class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-700 text-gray-500 text-[10px] md:text-sm font-bold rounded-lg cursor-not-allowed whitespace-nowrap">
                Habis
            </button>
            @else
            @if($isLowStock)
            <div class="flex flex-col items-end gap-1">
                <span class="text-[9px] text-amber-500 font-bold uppercase">Sisa {{ $product->stock }}</span>
                <div class="w-16 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ min(($product->stock / 5) * 100, 100) }}%"></div>
                </div>
            </div>
            @else
            <button class="px-3 py-1.5 md:px-4 md:py-2 bg-primary hover:bg-blue-600 text-white text-[10px] md:text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30 whitespace-nowrap">
                Buy
            </button>
            @endif
            @endif
        </div>
    </div>
</div>