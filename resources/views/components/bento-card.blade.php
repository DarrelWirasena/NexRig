@props([
    'product' => null,
    'type'    => 'standard',
    'title'   => '',
    'image'   => ''
])

@php
    $classes = "group relative overflow-hidden border border-white/5 bg-[#0a0a0a] rounded-lg md:rounded-2xl transition-all duration-500 hover:border-primary/50 aspect-square md:aspect-auto";

    switch ($type) {
        case 'flagship':
            $classes .= " col-span-2 md:col-span-2 md:row-span-2";
            break;
        case 'bestseller':
            $classes .= " col-span-2 md:col-span-2";
            break;
        case 'standard':
            $classes .= " col-span-2 md:col-span-1";
            break;
        case 'custom':
            $classes .= " col-span-2 md:col-span-1";
            break;
    }

    $url = ($type === 'custom') ? '#' : route('products.show', $product->slug ?? '');

    $imageUrl = $image;
    if ($type !== 'custom' && $product) {
        $imageUrl = $product->images->where('is_primary', true)->first()->src ?? null;
    }

    // Logika stok — hanya untuk produk nyata (bukan custom)
    $stockBadge   = null;  // null = tidak tampilkan badge
    $isLowStock   = false;
    $LOW_STOCK_THRESHOLD = 5;

    if ($type !== 'custom' && $product && $product->track_stock) {
        if ($product->stock <= 0) {
            $stockBadge = 'HABIS';
        } elseif ($product->stock <= $LOW_STOCK_THRESHOLD) {
            $stockBadge = 'SISA ' . $product->stock;
            $isLowStock = true;
        }
    }
@endphp

<div class="{{ $classes }}">
    {{-- Link Aktif (Kecuali Custom) --}}
    @if($type !== 'custom')
    <a href="{{ $url }}" class="absolute inset-0 z-20">
        <span class="sr-only">View Detail</span>
    </a>
    @endif

    {{-- Background Image --}}
    @if($imageUrl)
    <img src="{{ $imageUrl }}"
        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-50 group-hover:opacity-80"
        alt="Bento Image">
    @endif

    {{-- Overlay Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-t from-[#050014] via-[#050014]/40 to-transparent"></div>

    {{-- Badge stok habis saja di atas gambar (fallback) --}}
    @if($stockBadge && !$isLowStock)
    <div class="absolute top-3 right-3 z-30">
        <span class="flex items-center gap-1 bg-red-500/20 border border-red-500/50 text-red-400 text-[10px] font-bold px-2 py-1 rounded tracking-widest uppercase backdrop-blur-sm">
            <span class="material-symbols-outlined text-[12px]">block</span>
            {{ $stockBadge }}
        </span>
    </div>
    @endif

    {{-- KONTEN --}}
    <div class="absolute inset-0 p-3 md:p-8 flex flex-col justify-end z-10">

        @if($type === 'flagship')
        <span class="text-primary text-[clamp(10px,2vw,14px)] font-bold uppercase tracking-[0.3em] mb-2 block animate-pulse">
            Flagship
        </span>
        <h3 class="text-[clamp(18px,5vw,48px)] font-black text-white uppercase italic leading-[0.9] mb-4 break-words">
            {{ $product->name ?? 'Next Gen' }}
        </h3>
        <div class="flex items-center gap-2 text-white border-b border-primary w-max pb-1 text-[clamp(9px,1.5vw,12px)] font-bold group-hover:text-primary transition-colors cursor-pointer">
            VIEW COLLECTION <span class="material-symbols-outlined text-[clamp(12px,1.8vw,16px)]">arrow_forward</span>
        </div>

        @elseif($type === 'bestseller')
        <span class="bg-primary/20 backdrop-blur text-primary border border-primary/30 text-[clamp(9px,1.5vw,12px)] font-bold px-2 py-1 rounded mb-2 inline-block w-max tracking-widest">
            TRENDING
        </span>
        <h3 class="text-[clamp(16px,4vw,30px)] font-black text-white uppercase italic leading-tight break-words line-clamp-2 group-hover:text-primary transition-colors">
            {{ $product->name ?? 'Popular' }}
        </h3>
        @if($isLowStock)
        @endif

        @elseif($type === 'custom')
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
            <div class="w-[clamp(56px,15vw,100px)] h-[clamp(56px,15vw,100px)] rounded-2xl bg-white/5 flex items-center justify-center mb-4 border border-white/10 group-hover:border-primary/50 transition-all duration-500 shadow-2xl shadow-primary/10">
                <span class="material-symbols-outlined text-[clamp(28px,8vw,60px)] text-primary group-hover:rotate-180 transition-transform duration-700">
                    tune
                </span>
            </div>
            <h3 class="text-[clamp(14px,4vw,32px)] font-black text-white uppercase italic tracking-tighter leading-none">
                Custom Build
            </h3>
            <p class="text-gray-500 text-[clamp(9px,2vw,16px)] uppercase mt-2 font-bold tracking-[0.2em]">
                Coming Soon
            </p>
        </div>

        @else
        <h3 class="text-[clamp(14px,3vw,24px)] font-bold text-white uppercase italic leading-tight break-words line-clamp-2 group-hover:text-primary transition-colors">
            {{ $product->name ?? 'Series' }}
        </h3>
        <p class="text-gray-500 text-[clamp(9px,1.5vw,13px)] uppercase tracking-wider font-medium mt-1">
            {{ $product->series->category->name ?? 'Gaming PC' }}
        </p>
        @endif

    </div>
</div>