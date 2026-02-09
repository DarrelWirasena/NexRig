@extends('layouts.app')

@section('content')
    {{-- Custom Styles untuk Efek Khusus --}}
    <style>
        .text-glow { text-shadow: 0 0 20px rgba(19, 55, 236, 0.5); }
        .clip-corner { clip-path: polygon(0 0, 100% 0, 100% 85%, 95% 100%, 0 100%); }
        .clip-button { clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #232948 1px, transparent 1px),
                              linear-gradient(to bottom, #232948 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        }
        /* Marquee Animation */
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-scroll { animation: scroll 20s linear infinite; }
    </style>

    {{-- SECTION 1: CINEMATIC HERO --}}
    <div class="relative w-full min-h-screen flex flex-col justify-center items-center overflow-hidden bg-[#050505]">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1624705002806-5d72df8343f7?q=80&w=2500" 
                 class="w-full h-full object-cover opacity-60 mix-blend-luminosity scale-105 animate-pulse" style="animation-duration: 10s"
                 alt="Hero Background">
            <div class="absolute inset-0 bg-gradient-to-b from-[#050505]/30 via-transparent to-[#050505]"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto -mt-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/30 bg-primary/10 backdrop-blur-md mb-6 animate-bounce">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                <span class="text-primary text-xs font-bold tracking-widest uppercase">Next Gen Hardware Ready</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-black text-white tracking-tighter mb-4 leading-[0.9]">
                BEYOND <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-primary to-cyan-400 text-glow">REALITY</span>
            </h1>
            
            <p class="text-gray-400 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Precision-engineered gaming systems designed to dominate. 
                Experience <span class="text-white font-bold">4K Ray Tracing</span> like never before.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('products.index') }}" class="group relative px-8 py-4 bg-primary text-white font-bold text-lg tracking-widest uppercase clip-button transition-all hover:bg-white hover:text-black hover:shadow-[0_0_30px_rgba(19,55,236,0.6)]">
                    Configure Your Rig
                </a>
                <a href="#featured" class="flex items-center gap-2 text-white hover:text-primary transition-colors font-medium uppercase tracking-wider text-sm">
                    <span class="material-symbols-outlined">play_circle</span> Watch Showcase
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 2: INFINITE RUNNING TEXT (Hype Bar) --}}
    <div class="bg-primary text-white py-3 overflow-hidden border-y border-white/10 relative z-20">
        <div class="whitespace-nowrap flex animate-scroll">
            {{-- Duplikat konten untuk efek infinite loop --}}
            @for($i = 0; $i < 10; $i++)
                <span class="mx-8 font-black italic uppercase tracking-widest text-lg flex items-center gap-4 opacity-80">
                    High FPS Guarantee <span class="text-black">•</span> 
                    RTX 4090 Ready <span class="text-black">•</span> 
                    Liquid Cooled <span class="text-black">•</span> 
                    Lifetime Support <span class="text-black">•</span>
                </span>
            @endfor
        </div>
    </div>

    {{-- SECTION 3: BENTO GRID CATEGORIES (Dynamized from Database) --}}
    <div id="featured" class="bg-[#050505] py-24 px-4 sm:px-10">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-2">Curated Series</h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-primary to-transparent"></div>
                </div>
                <p class="text-gray-400 max-w-md text-right md:text-left">
                    From esports dominance to workstation powerhouses, choose the chassis that fits your ambition.
                </p>
            </div>

            {{-- LOGIC: Cek apakah ada cukup data featured --}}
            @if($featured->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 h-auto md:h-[600px]">
                    
                    {{-- ITEM 1: FLAGSHIP (Produk Pertama di Array) --}}
                    @php $p1 = $featured->get(0); @endphp
                    @if($p1)
                    <div class="group md:col-span-2 md:row-span-2 relative rounded-2xl overflow-hidden border border-white/10 bg-[#0a0a0a]">
                        <img src="{{ $p1->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/800' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-80 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 p-8 w-full">
                            <span class="text-primary text-xs font-bold uppercase tracking-widest mb-2 block">Flagship Series</span>
                            <h3 class="text-4xl font-black text-white uppercase italic mb-2">{{ $p1->name }}</h3>
                            <p class="text-gray-300 text-sm mb-6 line-clamp-2">{{ $p1->short_description }}</p>
                            <a href="{{ route('products.show', $p1->slug) }}" class="inline-flex items-center gap-2 text-white border-b border-primary pb-1 hover:text-primary transition-colors">
                                VIEW COLLECTION <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- ITEM 2: BEST SELLER (Produk Kedua) --}}
                    @php $p2 = $featured->get(1); @endphp
                    @if($p2)
                    <div class="group md:col-span-2 relative rounded-2xl overflow-hidden border border-white/10 bg-[#0a0a0a]">
                        <img src="{{ $p2->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/800' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-60 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
                        <div class="absolute inset-0 p-8 flex flex-col justify-center items-start">
                            <span class="bg-white/10 backdrop-blur text-white text-xs font-bold px-2 py-1 rounded mb-2">TRENDING</span>
                            <h3 class="text-2xl font-black text-white uppercase italic">{{ $p2->name }}</h3>
                            <p class="text-gray-400 text-sm mt-1 mb-4">Starting at Rp {{ number_format($p2->price, 0, ',', '.') }}</p>
                            <a href="{{ route('products.show', $p2->slug) }}" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary text-white transition-colors">
                                <span class="material-symbols-outlined">arrow_outward</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- ITEM 3: CREATOR (Produk Ketiga) --}}
                    @php $p3 = $featured->get(2); @endphp
                    @if($p3)
                    <div class="group relative rounded-2xl overflow-hidden border border-white/10 bg-[#0a0a0a]">
                        <img src="{{ $p3->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/800' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-60 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-black/40 hover:bg-transparent transition-colors"></div>
                        <div class="absolute bottom-0 p-6">
                            <h3 class="text-xl font-bold text-white uppercase truncate">{{ $p3->name }}</h3>
                            <p class="text-gray-400 text-xs">{{ $p3->category->name ?? 'Gaming PC' }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- ITEM 4: CUSTOM BUILDER (Tetap Statis agar variatif, atau bisa pakai data ke-4) --}}
                    <div class="group relative rounded-2xl overflow-hidden border border-white/10 bg-[#0a0a0a]">
                        <div class="absolute inset-0 bg-primary/10 group-hover:bg-primary/20 transition-colors"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                            <span class="material-symbols-outlined text-5xl text-primary mb-2">tune</span>
                            <h3 class="text-xl font-bold text-white uppercase">Custom Builder</h3>
                            <p class="text-gray-400 text-xs mt-2">Coming Soon</p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Fallback jika database kosong --}}
                <p class="text-gray-400 text-center">No featured products available yet.</p>
            @endif
        </div>
    </div>

    {{-- SECTION 4: LATEST DEPLOYMENTS (Using Components) --}}
    <div class="bg-[#080808] py-24 px-4 sm:px-10 border-t border-white/5 relative">
        <div class="absolute top-0 left-10 w-px h-24 bg-gradient-to-b from-primary to-transparent"></div>
        <div class="absolute bottom-0 right-10 w-px h-24 bg-gradient-to-t from-primary to-transparent"></div>

        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-primary font-bold text-xl">///</span>
                    <h2 class="text-3xl font-bold text-white uppercase tracking-wider">Latest Deployments</h2>
                </div>
                {{-- Link Lihat Semua --}}
                <a href="{{ route('products.index') }}" class="hidden md:flex items-center gap-2 text-white hover:text-primary transition-colors font-bold uppercase text-sm tracking-wider">
                    View All Systems <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                {{-- MENGGUNAKAN COMPONENT + DATA FEATURED (Max 4 Items) --}}
                @foreach($featured as $product)
                    <x-product-home-card :product="$product" />
                @endforeach
            </div>
            
            {{-- Tombol Mobile --}}
            <div class="mt-16 text-center md:hidden">
                <a href="{{ route('products.index') }}" class="inline-block px-12 py-4 border border-white/20 text-white font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all clip-button">
                    View All Systems
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 5: CTA --}}
    <div class="bg-gradient-to-r from-primary to-blue-900 py-24 px-4 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-black text-white italic uppercase tracking-tighter mb-6">Ready to Ascend?</h2>
            <p class="text-white/80 text-lg mb-10 max-w-xl mx-auto">
                Join thousands of gamers who have upgraded their battle station with NexRig. Built by gamers, for gamers.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('products.index') }}" class="px-8 py-4 bg-black text-white font-bold uppercase tracking-widest hover:scale-105 transition-transform clip-button shadow-2xl">
                    Start Building
                </a>
            </div>
        </div>
    </div>
@endsection