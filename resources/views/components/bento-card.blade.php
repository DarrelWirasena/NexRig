@props([
    'product' => null, 
    'type' => 'standard', 
    'title' => '', 
    'image' => ''
])

@php
   
   // Dasar class: Pakai aspect-square agar simetris
    $classes = "group relative overflow-hidden border border-white/5 bg-[#0a0a0a] rounded-lg md:rounded-2xl transition-all duration-500 hover:border-primary/50 aspect-square md:aspect-auto";
    
    switch ($type) {
        case 'flagship':
            // Mobile: Makan 1 kolom tapi 2 baris kebawah (Tinggi)
            // Desktop: Makan 2 kolom 2 baris
            $classes .= " col-span-2 md:col-span-2 md:row-span-2";
            break;
            
        case 'bestseller':
            // Mobile: Makan 1 kolom (Akan berada di sebelah Flagship)
            // Desktop: Makan 2 kolom lebar
            $classes .= " col-span-2 md:col-span-2";
            break;
            
        case 'standard':
            // Mobile: Makan 1 kolom (Akan berada di bawah Bestseller)
            $classes .= " col-span-2 md:col-span-1";
            break;
            
        case 'custom':
            // Mobile: Makan 1 kolom (Akan berada di sebelah Standard)
            $classes .= " col-span-2 md:col-span-1";
            break;
    }

    $url = ($type === 'custom') ? '#' : route('products.show', $product->slug ?? '');
    $imageUrl = $image ?: ($product ? ($product->images->where('is_primary', true)->first()->image_url ?? null) : null);

    // 2. Logika URL: Custom build tidak punya slug produk
    $url = ($type === 'custom') ? '#' : route('products.show', $product->slug ?? '');
    
    // 3. Logika Gambar: Jika custom, kita bisa beri placeholder atau gambar spesifik
    $imageUrl = $image;
    if ($type !== 'custom' && $product) {
        $imageUrl = $product->images->where('is_primary', true)->first()->image_url ?? null;
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

    {{-- KONTEN --}}
    <div class="absolute inset-0 p-3 md:p-8 flex flex-col justify-end z-10">
        
        @if($type === 'flagship')
            {{-- Label Flagship --}}
            <span class="text-primary text-[clamp(10px,2vw,14px)] font-bold uppercase tracking-[0.3em] mb-2 block animate-pulse">
                Flagship
            </span>
            
            {{-- Nama Produk: Hapus 'truncate', gunakan 'line-clamp' jika ingin membatasi baris --}}
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

            {{-- Gunakan 'line-clamp-2' agar jika judul sangat panjang, maksimal hanya 2 baris --}}
            <h3 class="text-[clamp(16px,4vw,30px)] font-black text-white uppercase italic leading-tight break-words line-clamp-2 group-hover:text-primary transition-colors">
                {{ $product->name ?? 'Popular' }}
            </h3>
        @elseif($type === 'custom')
            {{-- Bagian ini Statis, tidak butuh data $product --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                {{-- Container Icon: Min 56px (hampir w-14), Preferred 15vw, Max 100px --}}
                <div class="w-[clamp(56px,15vw,100px)] h-[clamp(56px,15vw,100px)] rounded-2xl bg-white/5 flex items-center justify-center mb-4 border border-white/10 group-hover:border-primary/50 transition-all duration-500 shadow-2xl shadow-primary/10">
                    
                    {{-- Icon: Min 28px sampai 60px --}}
                    <span class="material-symbols-outlined text-[clamp(28px,8vw,60px)] text-primary group-hover:rotate-180 transition-transform duration-700">
                        tune
                    </span>
                </div>

                {{-- Title: Min 14px (text-sm), Preferred 4vw, Max 32px (text-3xl) --}}
                <h3 class="text-[clamp(14px,4vw,32px)] font-black text-white uppercase italic tracking-tighter leading-none">
                    Custom Build
                </h3>

                {{-- Subtitle: Min 9px sampai 16px --}}
                <p class="text-gray-500 text-[clamp(9px,2vw,16px)] uppercase mt-2 font-bold tracking-[0.2em]">
                    Coming Soon
                </p>
            </div>

        @else
           {{-- Standar Series Card --}}
            <h3 class="text-[clamp(14px,3vw,24px)] font-bold text-white uppercase italic leading-tight break-words line-clamp-2 group-hover:text-primary transition-colors">
                {{ $product->name ?? 'Series' }}
            </h3>
            <p class="text-gray-500 text-[clamp(9px,1.5vw,13px)] uppercase tracking-wider font-medium mt-1">
                {{ $product->series->category->name ?? 'Gaming PC' }}
            </p>
        @endif

    </div>
</div>