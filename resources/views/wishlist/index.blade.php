@extends('layouts.app')

@section('content')

<div class="bg-[#050505] min-h-screen pb-20"
     x-data="{ isLoaded: false }"
     x-init="window.addEventListener('load', () => { setTimeout(() => isLoaded = true, 400) })">

    {{-- ======================================== --}}
    {{-- SKELETON                                  --}}
    {{-- ======================================== --}}
    <div x-show="!isLoaded" class="animate-pulse max-w-[1440px] mx-auto px-4 md:px-10 py-10">
        <div class="h-4 bg-white/10 rounded w-48 mb-3"></div>
        <div class="h-10 bg-white/5 rounded w-64 mb-2"></div>
        <div class="h-3 bg-white/10 rounded w-32 mb-10"></div>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
            @for($i = 0; $i < 8; $i++)
            <div class="bg-white/5 rounded-xl overflow-hidden border border-white/10 flex flex-col">
                <div class="w-full aspect-[4/3] bg-white/10"></div>
                <div class="p-3 md:p-5 flex flex-col gap-3">
                    <div class="h-3 bg-white/10 rounded w-2/3"></div>
                    <div class="h-4 bg-white/5 rounded w-full"></div>
                    <div class="h-3 bg-white/10 rounded w-4/5"></div>
                    <div class="h-8 bg-white/5 rounded mt-2"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- ======================================== --}}
    {{-- KONTEN ASLI                               --}}
    {{-- ======================================== --}}
    <div x-show="isLoaded" x-cloak x-transition.opacity.duration.700ms class="contents">
        <div class="max-w-[1440px] mx-auto px-4 md:px-10 py-10">

            {{-- Breadcrumb --}}
            <nav class="flex text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">Wishlist</span>
            </nav>

            {{-- Header --}}
            <div class="flex items-end justify-between mb-10 gap-4 flex-wrap">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-white italic uppercase tracking-tight">
                        My Wishlist
                    </h1>
                    <p class="text-gray-500 text-xs uppercase tracking-widest mt-1" id="wishlist-count">
                        {{ $wishlists->count() }} Saved Build{{ $wishlists->count() !== 1 ? 's' : '' }}
                    </p>
                </div>
                @if($wishlists->isNotEmpty())
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-2 text-gray-400 hover:text-white text-xs font-bold uppercase
                          tracking-widest transition-colors border border-white/10 hover:border-white/30
                          px-4 py-2 rounded-lg">
                    <span class="material-symbols-outlined text-sm">grid_view</span>
                    Browse More
                </a>
                @endif
            </div>

            {{-- ── EMPTY STATE ────────────────────────────── --}}
            <div id="empty-state" class="{{ $wishlists->isEmpty() ? 'flex' : 'hidden' }}
                flex-col items-center justify-center py-32 border border-dashed
                border-white/10 rounded-2xl bg-white/[0.01]">
                <div class="w-20 h-20 rounded-full bg-white/5 border border-white/10
                            flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-4xl text-gray-600">favorite_border</span>
                </div>
                <h3 class="text-white font-black italic uppercase text-xl mb-2">Wishlist Empty</h3>
                <p class="text-gray-500 text-sm mb-8 text-center max-w-xs">
                    Save your favorite builds so you can find them easily later.
                </p>
                <a href="{{ route('products.index') }}"
                   class="px-8 py-3 bg-primary hover:bg-blue-600 text-white font-bold uppercase
                          text-sm tracking-widest rounded transition-colors
                          shadow-[0_0_20px_rgba(59,130,246,0.3)]">
                    Browse Rigs
                </a>
            </div>

            {{-- ── PRODUCT GRID ──────────────────────────── --}}
            <div id="wishlist-grid"
                 class="{{ $wishlists->isEmpty() ? 'hidden' : '' }}
                         grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">

                @foreach($wishlists as $item)
                @php
                    $product      = $item->product;
                    $primaryImage = $product->images->where('is_primary', true)->first()
                                    ?? $product->images->first();
                    $imageUrl     = $primaryImage ? $primaryImage->src : 'https://via.placeholder.com/600';
                    $isOutOfStock = $product->track_stock && $product->stock <= 0;
                    $isLowStock   = $product->track_stock && $product->stock > 0 && $product->stock <= 5;
                @endphp

                <div class="wishlist-card group flex flex-col bg-[#1a2036] rounded-xl overflow-hidden
                            border transition-all duration-300 h-full relative
                            {{ $isOutOfStock
                                ? 'border-red-500/20 hover:border-red-500/40 opacity-60 hover:opacity-70'
                                : 'border-[#232948] hover:border-primary hover:shadow-xl hover:shadow-primary/10' }}">

                    {{-- Hapus dari wishlist --}}
                    <button onclick="removeWishlist(this, {{ $product->id }})"
                            class="absolute top-2 right-2 z-20 w-7 h-7 flex items-center justify-center
                                   rounded-full bg-black/60 backdrop-blur text-red-400
                                   hover:bg-red-500 hover:text-white transition-all duration-200"
                            title="Remove from wishlist">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>

                    {{-- Gambar --}}
                    <a href="{{ route('products.show', $product->slug) }}" class="block shrink-0">
                        <div class="relative aspect-square md:aspect-[4/3] bg-black/40 overflow-hidden">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy"
                                 class="object-cover w-full h-full transition-transform duration-500
                                 {{ $isOutOfStock ? 'grayscale' : 'group-hover:scale-105' }}">

                            <div class="absolute top-2 left-2 text-white text-[10px] md:text-xs font-bold
                                        px-2 py-0.5 md:px-2.5 md:py-1 rounded backdrop-blur-sm z-10
                                        {{ $isOutOfStock ? 'bg-gray-600' : 'bg-primary' }}">
                                {{ $product->tier ?? 'Custom' }}
                            </div>

                            @if($isOutOfStock)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                                <span class="flex items-center gap-1.5 bg-black/70 border border-red-500/50
                                             text-red-400 text-[10px] font-bold px-3 py-1.5 rounded-lg
                                             uppercase tracking-widest backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-[14px]">block</span>
                                    Stok Habis
                                </span>
                            </div>
                            @endif
                        </div>
                    </a>

                    {{-- Konten --}}
                    <div class="p-3 md:p-5 flex flex-col flex-1">
                        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">
                            {{ $product->series->name ?? '-' }}
                        </p>

                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="text-white text-sm md:text-lg font-bold line-clamp-1 leading-tight mb-1
                                {{ $isOutOfStock ? '' : 'group-hover:text-primary transition-colors' }}">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <p class="text-gray-500 text-[10px] md:text-sm mb-2
                                  line-clamp-1 md:line-clamp-2 leading-tight">
                            {{ $product->short_description }}
                        </p>

                        {{-- Specs --}}
                        <div class="flex flex-wrap gap-1.5 mb-3 mt-auto">
                            @foreach($product->components->take(3) as $index => $comp)
                            <span class="{{ $index > 1 ? 'hidden md:inline-block' : 'inline-block' }}
                                         px-1.5 py-0.5 md:px-2 md:py-1 rounded bg-[#232948]
                                         text-gray-300 text-[10px] md:text-xs font-medium
                                         border border-white/5 whitespace-nowrap">
                                {{ $comp->name }}
                            </span>
                            @endforeach
                            @if($product->components->count() > 2)
                            <span class="md:hidden text-[10px] text-gray-400 self-center">
                                +{{ $product->components->count() - 2 }}
                            </span>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="pt-2 md:pt-3 border-t border-[#232948] flex items-center
                                    justify-between gap-2 mt-auto">
                            <div class="flex flex-col">
                                <span class="hidden md:block text-gray-500 text-xs">Starting at</span>
                                <span class="text-white text-sm md:text-lg font-bold">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($isOutOfStock)
                            <button disabled
                                class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-700 text-gray-500
                                       text-[10px] md:text-sm font-bold rounded-lg cursor-not-allowed
                                       whitespace-nowrap">
                                Habis
                            </button>
                            @elseif($isLowStock)
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[9px] text-amber-500 font-bold uppercase">
                                    Sisa {{ $product->stock }}
                                </span>
                                <div class="w-16 h-1 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full"
                                         style="width: {{ min(($product->stock / 5) * 100, 100) }}%"></div>
                                </div>
                            </div>
                            @else
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    class="px-3 py-1.5 md:px-4 md:py-2 bg-primary hover:bg-blue-600
                                           text-white text-[10px] md:text-sm font-bold rounded-lg
                                           transition-colors shadow-lg shadow-primary/30 whitespace-nowrap">
                                    Add to Cart
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function removeWishlist(btn, productId) {
    const card  = btn.closest('.wishlist-card');
    const grid  = document.getElementById('wishlist-grid');
    const empty = document.getElementById('empty-state');
    const count = document.getElementById('wishlist-count');

    btn.disabled = true;

    try {
        const res  = await fetch(`/wishlist/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();

        if (!data.wishlisted) {
            // Animasi hilang
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity    = '0';
            card.style.transform  = 'scale(0.95)';

            setTimeout(() => {
                card.remove();

                const remaining = grid.querySelectorAll('.wishlist-card').length;

                // Update counter
                if (count) {
                    count.textContent = `${remaining} Saved Build${remaining !== 1 ? 's' : ''}`;
                }

                // Tampilkan empty state jika kosong
                if (remaining === 0) {
                    grid.classList.add('hidden');
                    empty.classList.remove('hidden');
                    empty.classList.add('flex');
                }
            }, 300);

            if (typeof window.showToast === 'function') {
                window.showToast('Removed from wishlist.', 'info');
            }
        }
    } catch (err) {
        console.error('Wishlist error:', err);
        btn.disabled = false;
    }
}
</script>
@endpush

@endsection