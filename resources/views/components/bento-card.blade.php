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
            <span class="text-primary text-[7px] md:text-xs font-bold uppercase tracking-widest mb-1 block">Flagship</span>
            <h3 class="text-[12px] md:text-5xl font-black text-white uppercase italic leading-none truncate">
                {{ $product->name ?? 'Next Gen' }}
            </h3>
            <div class="hidden md:flex items-center gap-2 text-white border-b border-primary w-max mt-4 pb-1 text-xs font-bold group-hover:text-primary transition-colors">
                VIEW COLLECTION <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>

        @elseif($type === 'bestseller')
            <span class="bg-primary/20 backdrop-blur text-primary border border-primary/30 text-[6px] md:text-[10px] font-bold px-1 py-0.5 rounded mb-1 inline-block w-max">TRENDING</span>
            <h3 class="text-[10px] md:text-3xl font-black text-white uppercase italic truncate">
                {{ $product->name ?? 'Popular' }}
            </h3>

        @elseif($type === 'custom')
            {{-- Bagian ini Statis, tidak butuh data $product --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-2">
                <div class="w-8 h-8 md:w-16 md:h-16 rounded-lg bg-white/5 flex items-center justify-center mb-1 border border-white/10 group-hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-lg md:text-5xl text-primary group-hover:rotate-90 transition-transform duration-500">tune</span>
                </div>
                <h3 class="text-[8px] md:text-xl font-bold text-white uppercase tracking-tighter">Custom Build</h3>
                <p class="text-gray-500 text-[6px] md:text-xs uppercase mt-1">Coming Soon</p>
            </div>

        @else
            <h3 class="text-[10px] md:text-xl font-bold text-white uppercase truncate group-hover:text-primary transition-colors">
                {{ $product->name ?? 'Series' }}
            </h3>
            <p class="text-gray-500 text-[6px] md:text-xs uppercase">{{ $product->series->category->name ?? 'Gaming PC' }}</p>
        @endif

    </div>
</div>