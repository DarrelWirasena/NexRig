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
        @keyframes infinite-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); } /* KUNCI SEAMLESS */
        }

        .animate-infinite-scroll {
            /* Mainkan angka ini untuk kecepatan. Makin kecil = makin ngebut. */
            /* 30s adalah sweet spot (bisa dibaca tapi dinamis) */
            animation: infinite-scroll 30s linear infinite; 
            display: flex; /* Pastikan flex agar width dihitung benar */
            width: max-content; /* Pastikan container selebar isinya */
        }

        /* Animasi Muncul dari bawah */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0; /* Mulai dari invisible */
        }

        /* Scrollbar styling untuk horizontal scroll mobile */
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(19, 55, 236, 0.5); /* Primary color */
            border-radius: 10px;
        }

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
    @php
        $hypes = [
            "High FPS Guarantee",
            "RTX 50-Series Ready",
            "Liquid Cooled",
            "24/7 Stress Tested",
            "Lifetime Support",
            "Zero Bloatware",
            "Professional Cable Management"
        ];
    @endphp

    <div class="bg-primary text-white py-4 overflow-hidden border-y border-white/10 relative z-20 group">
        {{-- Wrapper Utama --}}
        <div class="flex">
            
            {{-- SET 1 (Original) --}}
            {{-- Perhatikan class 'min-w-full' dan gap di sini --}}
            <div class="animate-infinite-scroll flex items-center gap-16 px-8"> 
                @foreach($hypes as $text)
                    <span class="font-black italic uppercase tracking-widest text-lg opacity-90 whitespace-nowrap">
                        {{ $text }}
                    </span>
                    <span class="text-black text-xl">•</span>
                @endforeach
            </div>

            {{-- SET 2 (Duplicate) --}}
            <div class="animate-infinite-scroll flex items-center gap-16 px-8" aria-hidden="true">
                @foreach($hypes as $text)
                    <span class="font-black italic uppercase tracking-widest text-lg opacity-90 whitespace-nowrap">
                        {{ $text }}
                    </span>
                    <span class="text-black text-xl">•</span>
                @endforeach
            </div>

        </div>
        
        {{-- Fade Effect --}}
        <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-primary to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-primary to-transparent z-10 pointer-events-none"></div>
    </div>
    
   {{-- SECTION 3: BENTO GRID CATEGORIES --}}
<div id="featured" class="scroll-trigger opacity-0 bg-[#050505] py-24 px-4 sm:px-10">
    <div class="max-w-[1440px] mx-auto">
        {{-- Header Section (Tetap) --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-2">Curated Series</h2>
                <div class="h-1 w-20 bg-gradient-to-r from-primary to-transparent"></div>
            </div>
            <p class="text-gray-400 max-w-md text-left md:text-right">
                From esports dominance to workstation powerhouses, choose the chassis that fits your ambition.
            </p>
        </div>

        {{-- Logika Keamanan Data --}}
        @if($featured->count() > 0)
            {{-- 
                GRID SYSTEM (4 Kolom Konsisten):
                - grid-cols-4: Memaksa 4 kolom terkecil sekalipun di HP.
                - md:grid-rows-2: Baris kaku hanya di desktop.
                - h-auto: Agar tidak tumpang tindih di mobile.
            --}}
            <div class="grid grid-cols-4 md:grid-rows-2 gap-2 md:gap-4 h-auto md:h-[600px]">
                
                {{-- ITEM 1: FLAGSHIP --}}
                @if($featured->has(0))
                    <x-bento-card :product="$featured->get(0)" type="flagship" />
                @endif

                {{-- ITEM 2: BEST SELLER --}}
                @if($featured->has(1))
                    <x-bento-card :product="$featured->get(1)" type="bestseller" />
                @endif

                {{-- ITEM 3: STANDARD --}}
                @if($featured->has(2))
                    <x-bento-card :product="$featured->get(2)" type="standard" />
                @endif

                {{-- ITEM 4: CUSTOM (Selalu Muncul sebagai Placeholder) --}}
                <x-bento-card type="custom" />
            </div>
        @else
            {{-- Tampilan jika data kosong --}}
            <div class="text-center py-20 border border-dashed border-white/20 rounded-xl bg-white/5">
                <span class="material-symbols-outlined text-4xl text-gray-600 mb-2">dns</span>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No featured systems deployed yet.</p>
            </div>
        @endif
    </div>
</div>

   {{-- NEW SECTION: THE NEXRIG DNA --}}
    <div class="scroll-trigger opacity-0 bg-background-dark py-24 px-4 relative overflow-hidden">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-[1440px] mx-auto relative z-10">
            <div class="flex flex-col md:flex-row gap-16 items-center">
                {{-- Text Side --}}
                <div class="w-full md:w-1/2">
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase mb-6 leading-tight">
                        Built different. <br>
                        <span class="text-primary">Wired for perfection.</span>
                    </h2>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        Every NexRig isn't just assembled; it's crafted. We spend hours on cable management, thermal optimization, and 24-hour stress testing so you can just plug and play.
                    </p>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="bg-primary/20 p-2 rounded text-primary">
                                <span class="material-symbols-outlined">cable</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold uppercase">Obsessive Cable Management</h4>
                                <p class="text-gray-500 text-sm">Hidden cables, velcro straps, and clean aesthetics.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-primary/20 p-2 rounded text-primary">
                                <span class="material-symbols-outlined">verified_user</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold uppercase">2-Year Comprehensive Warranty</h4>
                                <p class="text-gray-500 text-sm">If something breaks, we fix it. No questions asked.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-primary/20 p-2 rounded text-primary">
                                <span class="material-symbols-outlined">thermometer</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold uppercase">Thermal Optimization</h4>
                                <p class="text-gray-500 text-sm">Airflow tuned for maximum cooling and silence.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Image Side (Abstract Representation of Order) --}}
                <div class="w-full md:w-1/2 relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-[#0a0a0a]">
                         {{-- Ganti URL ini dengan foto PC bagian dalam yang rapi --}}
                        <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=1200" class="w-full h-auto object-cover opacity-80 hover:opacity-100 transition-opacity duration-500">
                    </div>
                    {{-- Decorative Elements --}}
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-2xl"></div>
                    <div class="absolute -top-5 -left-5 w-20 h-20 border border-primary/30"></div>
                </div>
            </div>
        </div>
    </div>

{{-- SECTION 4: LATEST DEPLOYMENTS --}}
{{-- Section terluar tanpa max-width agar hitamnya full kanan-kiri di PC --}}
<div class="scroll-trigger opacity-0 w-full bg-[#080808] py-16 md:py-24 border-t border-white/5 relative">
    
    {{-- Decorative Lines (PC Only) --}}
    <div class="hidden md:block absolute top-0 left-10 w-px h-24 bg-gradient-to-b from-primary to-transparent"></div>
    <div class="hidden md:block absolute bottom-0 right-10 w-px h-24 bg-gradient-to-t from-primary to-transparent"></div>

    {{-- 1. HEADER: Terpisah agar alignment max-width-nya aman --}}
    <div class="max-w-[1440px] mx-auto px-4 md:px-10 mb-8 md:mb-12">
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div class="flex items-center gap-4">
                <span class="text-primary font-bold text-xl animate-pulse">///</span>
                <h2 class="text-2xl md:text-3xl font-bold text-white uppercase tracking-wider">Latest Deployments</h2>
            </div>
            
            <a href="{{ route('products.index') }}" class="group hidden md:flex items-center gap-2 text-white hover:text-primary transition-colors font-bold uppercase text-sm tracking-wider">
                View All Systems <span class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-2">arrow_forward</span>
            </a>
        </div>
    </div>

    {{-- 2. SLIDER AREA: Full width untuk scroll yang lancar --}}
    <div class="relative w-full">
        {{-- 
            Container Flex:
            - md:max-w-[1440px] md:mx-auto: Di PC tetap rapi di tengah.
            - px-4 md:px-10: Memberi jarak awal kartu agar sejajar judul.
            - overflow-x-auto: Mengaktifkan swipe.
        --}}
        <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-8 px-4 md:px-10 md:grid md:grid-cols-3 lg:grid-cols-4 custom-scrollbar max-w-[1440px] mx-auto md:overflow-visible">
            
            @foreach($featured as $index => $product)
                {{-- 
                    Kartu:
                    - w-[80vw]: Jangan 85 agar kartu ke-2 lebih terlihat sebagai indikasi scroll.
                    - shrink-0: Mencegah kartu gepeng.
                --}}
                <div class="w-[80vw] sm:w-[350px] md:w-auto shrink-0 snap-start">
                    <x-product-home-card :product="$product" />
                </div>
            @endforeach

            {{-- Spacer Akhir agar kartu terakhir tidak mepet kanan di HP --}}
            <div class="w-4 shrink-0 md:hidden"></div>
        </div>

        {{-- Indikator Fade Kanan (Mobile Only) --}}
        <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#080808] to-transparent pointer-events-none md:hidden z-20"></div>
    </div>

    {{-- Mobile View All Button --}}
    <div class="max-w-[1440px] mx-auto px-4 mt-4 md:hidden">
        <a href="{{ route('products.index') }}" class="w-full inline-block py-4 border border-white/20 text-white font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all clip-button text-center">
            View All Systems
        </a>
    </div>
</div>
    
{{-- SECTION 5 ALTERNATIF: BATTLESTATION GALLERY --}}
<section class="scroll-trigger opacity-0 bg-[#080808] py-24 border-t border-white/5"> 
    <div class="max-w-[1440px] mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic">Deployed <span class="text-primary">&</span> Operational</h2>
            <p class="text-gray-500 mt-4 uppercase tracking-[0.2em] text-xs font-bold">NexRig setup around the world</p>
        </div>

        {{-- Grid Foto --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1547082299-de196ea013d6?q=80&w=600" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #021</span>
                </div>
            </div>
            {{-- Ulangi untuk foto lainnya --}}
            <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b?q=80&w=600" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #044</span>
                </div>
            </div>
            <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=600" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #089</span>
                </div>
            </div>
            <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=600" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #102</span>
                </div>
            </div>
        </div>
    </div>
</section>

    {{-- SECTION 5: CTA --}}
    <div class="scroll-trigger opacity-0 bg-gradient-to-r from-primary to-blue-900 py-24 px-4 text-center relative overflow-hidden">
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


    {{-- Script Animasi Scroll --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Opsi Observer (Kapan animasi dimulai?)
        const observerOptions = {
            root: null, // Viewport browser
            rootMargin: '0px',
            threshold: 0.1 // Animasi jalan ketika 10% bagian kartu sudah terlihat
        };

        // 2. Fungsi Callback (Apa yang dilakukan saat terlihat?)
        const observerCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Tambahkan class animasi
                    entry.target.classList.add('animate-fade-in-up');
                    
                    // Hapus opacity-0 agar tidak bentrok dengan animasi
                    entry.target.classList.remove('opacity-0');
                    
                    // Stop memantau elemen ini (biar animasi cuma sekali seumur hidup)
                    observer.unobserve(entry.target);
                }
            });
        };

        // 3. Inisialisasi Observer
        const observer = new IntersectionObserver(observerCallback, observerOptions);

        // 4. Targetkan semua elemen dengan class .scroll-trigger
        const hiddenElements = document.querySelectorAll('.scroll-trigger');
        hiddenElements.forEach((el) => observer.observe(el));
    });
</script>

@endsection