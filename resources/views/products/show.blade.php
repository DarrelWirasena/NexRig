@extends('layouts.app')

@section('content')
    {{-- Custom Style untuk Halaman Detail --}}
    <style>
        .clip-box { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); }
        .text-glow { text-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        /* Hide scrollbar for gallery */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
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
                    {{-- Main Image --}}
                    <div class="relative w-full aspect-[4/3] bg-[#0a0a0a] rounded-xl overflow-hidden border border-white/10 mb-4 group">
                        <img id="mainImage" 
                             src="{{ $product->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/800' }}" 
                             alt="{{ $product->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        {{-- Series Badge --}}
                        <div class="absolute top-4 left-4 bg-primary/90 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded uppercase tracking-wider">
                            {{ $product->series->name ?? 'Custom Series' }}
                        </div>
                    </div>

                    {{-- Thumbnails --}}
                    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                        @foreach($product->images as $img)
                            <button onclick="changeImage('{{ $img->image_url }}')" 
                                    class="relative w-24 h-24 shrink-0 rounded-lg overflow-hidden border border-white/10 hover:border-primary transition-all focus:ring-2 focus:ring-primary">
                                <img src="{{ $img->image_url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO PRODUK & CART --}}
                <div class="lg:col-span-5 flex flex-col h-full">
                    <div class="sticky top-24">
                        <h1 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tight mb-2">{{ $product->name }}</h1>
                        
                        {{-- Tags / Intended Uses --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($product->intendedUses as $use)
                                <div class="flex items-center gap-1 text-xs font-bold text-gray-400 bg-white/5 px-2 py-1 rounded border border-white/10">
                                    <span class="material-symbols-outlined text-sm text-primary">{{ $use->icon_url }}</span>
                                    {{ $use->title }}
                                </div>
                            @endforeach
                        </div>

                            <div class="mb-8">
                            <span class="text-gray-400 text-lg">Starting at</span>
                            {{-- Hapus text-transparent dan bg-clip..., ganti jadi text-white --}}
                            <div class="text-5xl font-bold text-white">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>
                        {{-- Description --}}
                        <p class="text-gray-400 leading-relaxed mb-8 border-l-2 border-primary/50 pl-4">
                            {{ $product->description }}
                        </p>

                        {{-- ADD TO CART FORM --}}
                        <form action="{{ route('cart.add', $product->id) }}" method="GET" class="bg-[#0a0a0a] p-6 rounded-xl border border-white/10 clip-box">
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
                            
                            @if(session('success'))
                                <div class="mt-3 text-green-400 text-xs text-center font-bold">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </form>

                        {{-- Attributes (Warranty, OS, etc) --}}
                        <div class="grid grid-cols-2 gap-4 mt-8">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-24">
                
                {{-- TABEL SPESIFIKASI --}}
                <div>
                    <h3 class="text-2xl font-bold text-white uppercase mb-6 flex items-center gap-2">
                        <span class="w-1 h-8 bg-primary block"></span> Technical Specs
                    </h3>
                    <div class="bg-[#0a0a0a] rounded-xl border border-white/10 overflow-hidden">
                        @foreach($product->components as $component)
                            <div class="flex items-center justify-between p-4 border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded bg-white/5 flex items-center justify-center text-primary">
                                        {{-- Logic Icon Sederhana berdasarkan Tipe --}}
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

                {{-- GRAFIK BENCHMARK --}}
                <div>
                    <h3 class="text-2xl font-bold text-white uppercase mb-6 flex items-center gap-2">
                        <span class="w-1 h-8 bg-green-500 block"></span> Performance
                    </h3>
                    <div class="bg-[#0a0a0a] rounded-xl border border-white/10 p-6">
                        <p class="text-gray-500 text-sm mb-6">Average FPS at 1440p High Settings</p>
                        
                        @forelse($product->benchmarks as $benchmark)
                            <div class="mb-6 last:mb-0">
                                <div class="flex justify-between text-white text-sm font-bold mb-1">
                                    <span>{{ $benchmark->name }}</span>
                                    <span class="text-primary">{{ $benchmark->pivot->avg_fps }} FPS</span>
                                </div>
                                {{-- Bar Chart --}}
                                <div class="w-full bg-white/10 rounded-full h-2">
                                    {{-- Rumus lebar: (FPS / 300) * 100 --}}
                                    <div class="bg-gradient-to-r from-primary to-cyan-400 h-2 rounded-full relative" 
                                         style="width: {{ min(($benchmark->pivot->avg_fps / 240) * 100, 100) }}%">
                                         <div class="absolute right-0 -top-1 w-2 h-4 bg-white shadow-[0_0_10px_white]"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No benchmark data available for this unit.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Javascript Sederhana untuk Galeri & Qty --}}
    <script>
        // Ganti Gambar Utama
        function changeImage(url) {
            document.getElementById('mainImage').src = url;
        }

        // Counter Quantity
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
    </script>
@endsection