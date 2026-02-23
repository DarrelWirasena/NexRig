@extends('layouts.app')

@section('content')
    {{-- Custom Style --}}
    <style>
        .clip-box { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); }
        .text-glow { text-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Card Intended Use ala Starforge */
        .intended-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0) 100%);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .intended-card:hover {
            border-color: rgba(59, 130, 246, 0.5);
            background: rgba(59, 130, 246, 0.05);
            transform: translateY(-5px);
        }
        .icon-container {
            background: linear-gradient(45deg, #3b82f6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    <div class="bg-[#050505] min-h-screen pb-20">
        
        {{-- BREADCRUMB --}}
        <div class="max-w-[1440px] mx-auto px-4 md:px-10 py-6">
            <nav class="flex text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Catalog</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $product->name }}</span>
            </nav>
        </div>

        <div class="max-w-[1440px] mx-auto px-4 md:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- KOLOM KIRI: GALERI GAMBAR --}}
                <div class="lg:col-span-7">
                    <div class="relative w-full aspect-[4/3] bg-[#0a0a0a] rounded-xl overflow-hidden border border-white/10 mb-4 group">
                        <img id="mainImage" 
                             src="{{ $product->images->where('is_primary', true)->first()->src ?? 'https://via.placeholder.com/800' }}" 
                             alt="{{ $product->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <div class="absolute top-4 left-4 bg-primary/90 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded uppercase tracking-wider">
                            {{ $product->series->name ?? 'Custom Series' }}
                        </div>
                    </div>

                    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                        @foreach($product->images as $img)
                            <button onclick="changeImage('{{ $img->src }}')" 
                                    class="relative w-24 h-24 shrink-0 rounded-lg overflow-hidden border border-white/10 hover:border-primary transition-all focus:ring-2 focus:ring-primary">
                                <img src="{{ $img->src }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO PRODUK --}}
                <div class="lg:col-span-5 flex flex-col h-full">
                    <div class="sticky top-24">
                        
                        {{-- 1. TITLE & SERIES --}}
                        <h1 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tight mb-4">{{ $product->name }}</h1>
                        
                        {{-- [BARU] VARIANT SELECTOR --}}
                        <div class="mb-8">
                            <p class="text-xs text-gray-500 uppercase font-bold mb-2">Select Edition:</p>
                            <div class="flex flex-wrap gap-2">
                                {{-- Loop semua produk dalam Series yang sama --}}
                                @foreach($product->series->products->sortBy('price') as $variant)
                                    <a href="{{ route('products.show', $variant->slug) }}" 
                                       class="px-4 py-2 border rounded transition-all text-sm font-bold uppercase
                                       {{ $variant->id == $product->id 
                                            ? 'bg-white text-black border-white cursor-default'  
                                            : 'bg-transparent text-gray-400 border-white/20 hover:border-primary hover:text-primary' 
                                       }}">
                                        {{-- Tampilkan Tier (Core/Pro/Elite) atau Nama Produk jika tier kosong --}}
                                        {{ $variant->tier ?? $variant->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- 2. TAGS --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($product->intendedUses as $use)
                                <div class="flex items-center gap-1 text-xs font-bold text-gray-400 bg-white/5 px-2 py-1 rounded border border-white/10">
                                    <span class="material-symbols-outlined text-sm text-primary">{{ $use->icon_url }}</span>
                                    {{ $use->title }}
                                </div>
                            @endforeach
                        </div>

                        {{-- 3. PRICE --}}
                        <div class="mb-8">
                            <span class="text-gray-400 text-lg">Current Configuration</span>
                            <div class="text-5xl font-bold text-white">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- 4. DESCRIPTION --}}
                        <p class="text-gray-400 leading-relaxed mb-8 border-l-2 border-primary/50 pl-4">
                            {{ $product->description }}
                        </p>

                        {{-- 5. ADD TO CART --}}
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" onsubmit="addToCartAjax(event, this)" class="bg-[#0a0a0a] p-6 rounded-xl border border-white/10 clip-box mb-8">
                            
                            {{-- WAJIB ADA: Token Keamanan --}}
                            @csrf 

                            <div class="flex items-center justify-between mb-4">
                                <label class="text-sm font-bold text-white uppercase">Quantity</label>
                                <div class="flex items-center bg-white/5 rounded border border-white/10">
                                    <button type="button" onclick="decrement()" class="px-3 py-2 text-white hover:bg-white/10">-</button>
                                    <input type="number" name="quantity" id="qty" value="1" min="1" class="w-12 bg-transparent text-center text-white border-none focus:ring-0 appearance-none">
                                    <button type="button" onclick="increment()" class="px-3 py-2 text-white hover:bg-white/10">+</button>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-primary hover:bg-blue-600 text-white font-bold uppercase tracking-widest transition-all shadow-[0_0_20px_rgba(59,130,246,0.3)] hover:shadow-[0_0_30px_rgba(59,130,246,0.5)] flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined">shopping_cart</span>
                                Add to Cart
                            </button>
                        </form>

                        {{-- 6. ATTRIBUTES --}}
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($product->attributes as $attr)
                                <div class="border border-white/10 p-3 rounded bg-white/5">
                                    <span class="block text-xs text-gray-500 uppercase">{{ $attr->name }}</span>
                                    <span class="block text-white font-medium">{{ $attr->value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

        {{-- SECTION: SPESIFIKASI & BENCHMARK --}}
            {{-- PERBAIKAN: Pastikan ada kata 'div' di sini --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-24">
                
                {{-- TABEL SPESIFIKASI (Kiri) --}}
                <div>
                    <h3 class="text-2xl font-bold text-white uppercase mb-6 flex items-center gap-2">
                        <span class="w-1 h-8 bg-primary block"></span> Technical Specs
                    </h3>
                    <div class="bg-[#0a0a0a] rounded-xl border border-white/10 overflow-hidden">
                        @foreach($product->components as $component)
                            <div class="flex items-center justify-between p-4 border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded bg-white/5 flex items-center justify-center text-primary">
                                        @if($component->type == 'CPU') <span class="material-symbols-outlined">memory</span>
                                        @elseif($component->type == 'GPU') <span class="material-symbols-outlined">videogame_asset</span>
                                        @elseif($component->type == 'RAM') <span class="material-symbols-outlined">developer_board</span>
                                        @elseif($component->type == 'Storage') <span class="material-symbols-outlined">hard_drive</span>
                                        @else <span class="material-symbols-outlined">settings_input_component</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">{{ $component->type }}</p>
                                        <p class="text-white font-bold">{{ $component->name }}</p>
                                    </div>
                                </div>
                                @if($component->pivot->quantity > 1)
                                    <span class="text-xs font-bold bg-primary/20 text-primary px-2 py-1 rounded">x{{ $component->pivot->quantity }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TABEL BENCHMARK (Kanan) --}}
                <div>
                    {{-- HEADER & TOMBOL RESOLUSI --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-white uppercase flex items-center gap-2">
                            <span class="w-1 h-8 bg-primary block"></span> Performance
                        </h3>

                        <div class="flex bg-white/5 p-1 rounded-lg border border-white/10">
                            @foreach(['1080p', '1440p', '4k'] as $res)
                                <button onclick="switchBenchmark('{{ $res }}')" 
                                        id="btn-{{ $res }}"
                                        class="px-4 py-1.5 rounded text-xs font-bold uppercase transition-all
                                        {{ $res === '1080p' ? 'bg-primary text-white shadow-[0_0_15px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:text-white' }}">
                                    {{ $res }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- SIAPKAN DATA --}}
                    @php
                        $groupedBenchmarks = $product->benchmarks->groupBy(function($item) {
                            return strtolower($item->resolution ?? '1080p');
                        });
                    @endphp

                    {{-- LOOPING KONTEN --}}
                    @foreach(['1080p', '1440p', '4k'] as $res)
                        <div id="content-{{ $res }}" class="{{ $res === '1080p' ? 'block' : 'hidden' }} transition-opacity duration-300">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if(isset($groupedBenchmarks[$res]) && $groupedBenchmarks[$res]->count() > 0)
                                    @foreach($groupedBenchmarks[$res] as $benchmark)
                                        
                                        {{-- CARD GAME --}}
                                        <div class="relative h-40 rounded-xl overflow-hidden border border-white/10 group hover:border-primary/50 transition-all">
                                            
                                            <img src="{{ $benchmark->game->src ?? 'https://via.placeholder.com/400x200?text=Game' }}" 
                                                 alt="{{ $benchmark->game->name ?? 'Game' }}" 
                                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/70 to-transparent"></div>

                                            <div class="absolute inset-0 p-5 flex flex-col justify-between">
                                                <div class="relative z-10">
                                                    <h4 class="text-white font-bold text-lg leading-tight">
                                                        {{ $benchmark->game->name ?? 'Unknown Game' }}
                                                    </h4>
                                                    <span class="text-[10px] text-gray-300 uppercase tracking-widest">
                                                        {{ $res }} Ultra
                                                    </span>
                                                </div>

                                                <div class="relative z-10 flex items-end gap-2">
                                                    <span class="text-5xl font-black text-white text-glow shadow-black drop-shadow-md">
                                                        {{ $benchmark->avg_fps }}
                                                    </span>
                                                    <span class="mb-2 px-1.5 py-0.5 rounded bg-primary text-white text-[10px] font-bold uppercase tracking-wider">
                                                        FPS
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                @else
                                    <div class="col-span-2 py-12 text-center border border-dashed border-white/10 rounded-xl bg-white/5">
                                        <span class="material-symbols-outlined text-4xl text-gray-600 mb-2">speed</span>
                                        <p class="text-gray-500 italic text-sm">
                                            No benchmark data available for {{ $res }}.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                  <p class="text-gray-600 text-[10px] mt-6 text-center border-t border-white/5 pt-4">
                        *Performance metrics based on average FPS. Actual results may vary depending on driver version.
                    </p>
                </div> {{-- Penutup Kolom Kanan (Benchmark) --}}

            </div> {{-- [PENTING] TAMBAHKAN INI: Penutup Grid Utama (Specs & Benchmark) --}}

            {{-- SEKARANG SECTION INI SUDAH BENAR-BENAR DI LUAR GRID --}}
            {{-- [DIPINDAHKAN KE LUAR GRID] SECTION: STARFORGE STYLE INTENDED USE --}}
                <section class="mt-32 py-20 border-t border-white/5">
                    <div class="max-w-[1440px] mx-auto">
                        
                        {{-- Deskripsi Header --}}
                        <div class="max-w-3xl mb-16">
                            <h3 class="text-primary font-mono text-xs tracking-[0.4em] uppercase mb-4">/// System Capabilities</h3>
                            <h2 class="text-4xl md:text-5xl font-black text-white italic uppercase mb-6 leading-tight">
                                Intended Use
                            </h2>
                            <p class="text-gray-400 text-lg leading-relaxed">
                                The <span class="text-white font-bold">{{ $product->series->name ?? 'Navigator' }} Series</span> features PCs that are fine-tuned for the performance needed for a competitive edge. Ideal for higher resolution or higher frame rates.
                            </p>
                        </div>

                        {{-- Grid Card --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($product->intendedUses as $use)
                                <div class="group p-8 rounded-2xl bg-gradient-to-br from-white/[0.03] to-transparent border border-white/5 hover:border-primary/50 transition-all duration-500">
                                    <div class="w-14 h-14 mb-8 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-primary/10 group-hover:border-primary/50 transition-all">
                                        <span class="material-symbols-outlined text-3xl text-primary group-hover:scale-110 transition-transform">
                                            {{ $use->icon_url }}
                                        </span>
                                    </div>
                                    <h4 class="text-white font-black uppercase italic tracking-wider text-xl mb-3">
                                        {{ $use->title }}
                                    </h4>
                                    <p class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-400 transition-colors">
                                        {{ $use->description }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
        
        </div> {{-- Penutup Wrapper Utama (max-w-[1440px]) --}}
    </div> {{-- Penutup Background Hitam Utama (bg-[#050505]) --}}
    
    <script>
        function changeImage(url) {
            document.getElementById('mainImage').src = url;
        }
        function increment() {
            let input = document.getElementById('qty');
            input.value = parseInt(input.value) + 1;
        }
        function decrement() {
            let input = document.getElementById('qty');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
        
        function switchBenchmark(resolution) {
            // 1. Sembunyikan semua konten
            ['1080p', '1440p', '4k'].forEach(res => {
                document.getElementById('content-' + res).classList.add('hidden');
                document.getElementById('content-' + res).classList.remove('block');
                
                // Reset style tombol (Jadi abu-abu)
                const btn = document.getElementById('btn-' + res);
                btn.className = 'px-4 py-1.5 rounded text-xs font-bold uppercase transition-all text-gray-400 hover:text-white';
            });

            // 2. Tampilkan konten yang dipilih
            const activeContent = document.getElementById('content-' + resolution);
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');

            // 3. Highlight tombol aktif (Jadi Biru / Primary dengan Shadow Biru)
            const activeBtn = document.getElementById('btn-' + resolution);
            activeBtn.className = 'px-4 py-1.5 rounded text-xs font-bold uppercase transition-all bg-primary text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] scale-105';
        }
    </script>
@endsection