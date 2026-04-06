@extends('layouts.dashboard')

@section('content')

<div class="w-full pb-20"
     x-data="{ isLoaded: false }"
     x-init="window.addEventListener('load', () => { setTimeout(() => isLoaded = true, 400) })">

    {{-- ======================================== --}}
    {{-- SKELETON                                 --}}
    {{-- ======================================== --}}
    <div x-show="!isLoaded" class="animate-pulse w-full px-4 sm:px-6 lg:px-8 py-8 md:py-10">
        <div class="lg:hidden h-10 w-10 bg-white/5 rounded-lg mb-6"></div>
        <div class="h-4 bg-white/10 rounded w-48 mb-3"></div>
        <div class="h-10 bg-white/5 rounded w-64 mb-2"></div>
        <div class="h-3 bg-white/10 rounded w-32 mb-10"></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 md:gap-6">
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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8 md:py-10">

            {{-- Tombol Hamburger untuk Mobile --}}
            <div class="lg:hidden mb-6">
                <button onclick="toggleSidebar()" class="flex items-center justify-center w-10 h-10 bg-white/5 border border-white/10 rounded-lg text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            {{-- Header & Breadcrumb --}}
            <div class="flex items-end justify-between mb-8 gap-4 flex-wrap">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-white italic uppercase tracking-tight">
                        My Wishlist
                    </h1>
                    <p class="text-gray-500 text-xs uppercase tracking-widest mt-2" id="wishlist-count">
                        {{ $wishlists->count() }} Saved Build{{ $wishlists->count() !== 1 ? 's' : '' }}
                    </p>
                </div>
                @if($wishlists->isNotEmpty())
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-2 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors border border-white/10 hover:border-white/30 px-4 py-2 rounded-lg">
                    <span class="material-symbols-outlined text-sm">grid_view</span>
                    Browse More
                </a>
                @endif
            </div>

            {{-- EMPTY STATE --}}
            <div id="empty-state" class="{{ $wishlists->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center py-20 md:py-32 border border-dashed border-white/10 rounded-2xl bg-white/[0.01] mt-4">
                <div class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-6 shadow-inner">
                    <span class="material-symbols-outlined text-4xl text-gray-600">favorite_border</span>
                </div>
                <h3 class="text-white font-black italic uppercase text-xl md:text-2xl mb-2 tracking-tight">Wishlist Empty</h3>
                <p class="text-gray-500 text-sm mb-8 text-center max-w-xs leading-relaxed">
                    Save your favorite builds so you can find them easily later.
                </p>
                <a href="{{ route('products.index') }}"
                   class="px-8 py-3.5 bg-primary hover:bg-blue-600 text-white font-black uppercase italic text-sm tracking-[0.15em] rounded-xl transition-all hover:translate-y-[-2px] shadow-[0_0_20px_rgba(59,130,246,0.3)] hover:shadow-[0_0_30px_rgba(59,130,246,0.5)]">
                    Browse Rigs
                </a>
            </div>

            {{-- PRODUCT GRID --}}
            <div id="wishlist-grid" class="{{ $wishlists->isEmpty() ? 'hidden' : '' }} grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 md:gap-6 mt-4">
                @foreach($wishlists as $item)
                @php
                    $product      = $item->product;
                    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $imageUrl     = $primaryImage ? $primaryImage->src : 'https://via.placeholder.com/600';
                    $isOutOfStock = $product->track_stock && $product->stock <= 0;
                @endphp

                <div class="wishlist-card group flex flex-col bg-[#0a0a0a] rounded-2xl overflow-hidden border transition-all duration-300 h-full relative {{ $isOutOfStock ? 'border-red-500/20 hover:border-red-500/40 opacity-60 hover:opacity-70' : 'border-white/10 hover:border-primary/50 hover:shadow-[0_10px_40px_rgba(0,0,0,0.5)]' }}">

                    {{-- Tombol Hapus dari Wishlist --}}
                    <button onclick="removeWishlist(this, {{ $product->id }})"
                            class="absolute top-3 right-3 z-20 w-8 h-8 flex items-center justify-center rounded-full bg-black/80 backdrop-blur border border-white/10 text-gray-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-300"
                            title="Remove from wishlist">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>

                    {{-- Gambar --}}
                    <a href="{{ route('products.show', $product->slug) }}" class="block shrink-0 p-3 pb-0">
                        <div class="relative aspect-[4/3] bg-[#111422] rounded-xl overflow-hidden border border-white/5">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy" class="object-cover w-full h-full transition-transform duration-700 {{ $isOutOfStock ? 'grayscale' : 'group-hover:scale-110' }}">
                            <div class="absolute top-3 left-3 text-white text-[10px] md:text-xs font-black px-2.5 py-1 rounded backdrop-blur-md z-10 border border-white/20 uppercase tracking-wider {{ $isOutOfStock ? 'bg-gray-600/80' : 'bg-primary/80' }}">
                                {{ $product->tier ?? 'Custom' }}
                            </div>
                            @if($isOutOfStock)
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center z-10">
                                <span class="flex items-center gap-2 bg-black/80 border border-red-500/50 text-red-400 text-xs font-black px-4 py-2 rounded-lg uppercase tracking-widest shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                                    <span class="material-symbols-outlined text-[16px]">block</span> Stok Habis
                                </span>
                            </div>
                            @endif
                        </div>
                    </a>

                    {{-- Konten --}}
                    <div class="p-4 md:p-5 flex flex-col flex-1">
                        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">verified</span>
                            {{ $product->series->name ?? 'Component' }}
                        </p>

                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="text-white text-base md:text-lg font-bold line-clamp-1 leading-tight mb-2 {{ $isOutOfStock ? '' : 'group-hover:text-primary transition-colors' }}">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <p class="text-gray-400 text-xs mb-4 line-clamp-2 leading-relaxed">
                            {{ $product->short_description }}
                        </p>

                        {{-- Footer Info & Action --}}
                        <div class="pt-4 border-t border-white/5 flex items-end justify-between gap-2 mt-auto">
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Starting at</span>
                                <span class="text-white text-lg font-black tracking-tight">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($isOutOfStock)
                            <button disabled class="px-4 py-2 bg-white/5 border border-white/10 text-gray-500 text-xs font-bold rounded-lg cursor-not-allowed uppercase tracking-wider">
                                Habis
                            </button>
                            @else
                            {{-- 🔥 PERBAIKAN: Tombol AJAX Add to Cart (Bukan Form lagi) 🔥 --}}
                            <button type="button" onclick="addToCartFromWishlist(this, '{{ route('cart.add', $product->id) }}')"
                                class="w-10 h-10 flex items-center justify-center bg-primary/10 hover:bg-primary text-primary hover:text-white border border-primary/20 hover:border-primary rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(59,130,246,0)] hover:shadow-[0_0_15px_rgba(59,130,246,0.4)]">
                                <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                            </button>
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
// Fungsi Hapus Wishlist
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
            card.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity    = '0';
            card.style.transform  = 'scale(0.9) translateY(10px)';

            setTimeout(() => {
                card.remove();
                const remaining = grid.querySelectorAll('.wishlist-card').length;
                if (count) count.textContent = `${remaining} Saved Build${remaining !== 1 ? 's' : ''}`;

                if (remaining === 0) {
                    grid.classList.add('hidden');
                    empty.classList.remove('hidden');
                    empty.classList.add('flex');
                    empty.style.opacity = '0';
                    setTimeout(() => {
                        empty.style.transition = 'opacity 0.5s';
                        empty.style.opacity = '1';
                    }, 50);
                }
            }, 400);

            if (typeof window.showToast === 'function') window.showToast('Removed from wishlist.', 'info');
        }
    } catch (err) {
        console.error('Wishlist error:', err);
        btn.disabled = false;
    }
}

// 🔥 FUNGSI AJAX ADD TO CART & BUKA MINI CART 🔥
async function addToCartFromWishlist(btn, actionUrl) {
    const originalIcon = btn.innerHTML;
    
    // Ubah ikon jadi loading spinner sementara
    btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>`;
    btn.disabled = true;

    try {
        const formData = new FormData();
        formData.append('quantity', 1);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Tembak URL yang sudah digenerate oleh Laravel
        const res = await fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        });
        
        const data = await res.json();

        if (res.ok && data.success) {
            // Beri centang sukses
            btn.innerHTML = `<span class="material-symbols-outlined text-[20px] text-green-400">check</span>`;
            
            // Perbarui Mini Cart UI (jika fungsinya ada di file js kamu)
            if (typeof window.updateMiniCartUI === 'function') {
                window.updateMiniCartUI(data.data);
            }

            // BUKA MINI CART SECARA PAKSA
            const miniCartOverlay = document.getElementById('miniCartOverlay');
            const miniCart = document.getElementById('miniCart');

            if (miniCartOverlay && miniCart) {
                miniCartOverlay.classList.remove('hidden');
                setTimeout(() => {
                    miniCartOverlay.classList.remove('opacity-0');
                    miniCart.classList.remove('translate-x-full');
                }, 10);
            } else if (typeof window.toggleMiniCart === 'function') {
                window.toggleMiniCart();
            }

            // Kembalikan tombol normal setelah 2 detik
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
            }, 2000);

            if (typeof window.showToast === 'function') window.showToast('Item ditambahkan ke keranjang!', 'success');
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    } catch (err) {
        console.error('Cart error:', err);
        btn.innerHTML = originalIcon;
        btn.disabled = false;
        
        if (typeof window.showToast === 'function') {
            window.showToast(err.message, 'error');
        } else {
            alert(err.message);
        }
    }
}
</script>
@endpush

@endsection