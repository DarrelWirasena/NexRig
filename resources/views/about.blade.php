@extends('layouts.app')

@section('content')
    {{-- Custom Styles --}}
    <style>
        .clip-diagonal { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
        .clip-card { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
        .text-outline { -webkit-text-stroke: 1px rgba(255, 255, 255, 0.1); color: transparent; }
        
        .scanline {
            width: 100%;
            height: 100px;
            z-index: 10;
            background: linear-gradient(0deg, rgba(0,0,0,0) 0%, rgba(59, 130, 246, 0.1) 50%, rgba(0,0,0,0) 100%);
            opacity: 0.1;
            position: absolute;
            bottom: 100%;
            animation: scanline 8s linear infinite;
            pointer-events: none;
        }
        @keyframes scanline {
            0% { bottom: 100%; }
            100% { bottom: -100%; }
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }
    </style>

    <div class="bg-[#050505] min-h-screen text-white overflow-hidden font-sans selection:bg-blue-500 selection:text-white">

        {{-- SECTION 1: HERO MANIFESTO --}}
        <div class="relative py-40 px-4 flex items-center justify-center overflow-hidden min-h-[85vh]">
            <div class="absolute inset-0 z-0">
                {{-- Background Image --}}
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1624705002806-5d72df19c3ad?q=80&w=2070')] bg-cover bg-center opacity-20 grayscale mix-blend-luminosity"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/90 to-transparent"></div>
                <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
                <div class="scanline"></div>
            </div>

            <div class="relative z-10 text-center max-w-5xl mx-auto px-4">
                <div class="flex justify-center mb-8">
                    <span class="inline-flex items-center gap-2 py-1 px-4 rounded-full border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-bold tracking-[0.3em] uppercase animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        System Manifesto v.2.0
                    </span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black uppercase italic tracking-tighter leading-none mb-8 drop-shadow-2xl">
                    Engineered for <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-white to-gray-500">Dominance.</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-light">
                    Kami bukan sekadar toko komputer. Kami adalah laboratorium performa. 
                    Setiap NexRig dirakit dengan presisi bedah untuk satu tujuan: 
                    <strong class="text-white">Menghancurkan batasan FPS.</strong>
                </p>
            </div>
        </div>

        {{-- SECTION 2: STATS STRIP --}}
        <div class="border-y border-white/5 bg-[#0a0a0a] py-12 relative z-10">
            <div class="max-w-[1440px] mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/5">
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">500+</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Rigs Deployed</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">0.1%</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">RMA Rate</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">24/7</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Elite Support</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">100%</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Performance</span>
                </div>
            </div>
        </div>

        {{-- SECTION 3: ORIGIN SEQUENCE (STORY) --}}
        <div class="py-24 px-4 relative overflow-hidden">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Text Content --}}
                <div class="order-2 lg:order-1">
                    <h2 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-6">The Origin Sequence</h2>
                    <div class="space-y-6 text-gray-400 leading-relaxed text-sm md:text-base">
                        <p>
                            NexRig lahir di tengah kekacauan "Silicon Shortage" tahun 2020. Saat itu, gamer dipaksa memilih antara PC pre-built yang mahal dengan komponen murahan, atau merakit sendiri dengan harga komponen yang tidak masuk akal.
                        </p>
                        <p>
                            Kami menolak kedua pilihan itu.
                        </p>
                        <p>
                            Dimulai dari sebuah garasi kecil dengan 3 teknisi obsesif, kami mulai merakit PC dengan filosofi sederhana: <span class="text-white font-bold">Rakit seolah-olah itu milik sendiri.</span> Tanpa kabel semrawut. Tanpa power supply "bom waktu". Tanpa kompromi.
                        </p>
                        <div class="pt-4">
                            <div class="h-1 w-20 bg-blue-600"></div>
                        </div>
                    </div>
                </div>

                {{-- Image Visual --}}
                <div class="order-1 lg:order-2 relative">
                    <div class="absolute -inset-4 bg-blue-600/20 blur-3xl rounded-full opacity-20"></div>
                    <div class="relative aspect-video bg-[#111] border border-white/10 p-2 clip-card">
                        <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=1000" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700">
                        {{-- Overlay UI --}}
                        <div class="absolute bottom-4 left-4 bg-black/80 backdrop-blur px-3 py-1 border-l-2 border-blue-500">
                            <span class="text-[10px] font-mono text-blue-400">EST. 2020 // JAKARTA_HQ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: THE FORGE PROTOCOL (PROCESS REPLACEMENT) --}}
        <div class="py-24 bg-[#080808] relative">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div class="max-w-[1440px] mx-auto px-4 relative z-10">
                
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic tracking-tighter">The Forge Protocol</h2>
                    <p class="text-gray-500 mt-4 max-w-xl mx-auto">Standar operasional kami lebih ketat daripada militer. Setiap sistem melewati 4 tahap kritis sebelum menyentuh meja Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Step 1 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">01</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Component Selection</h3>
                            <p class="text-gray-500 text-sm">Kami hanya menggunakan komponen Tier-A. Tidak ada PSU generik atau motherboard murah yang membatasi performa.</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">02</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">precision_manufacturing</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Precision Assembly</h3>
                            <p class="text-gray-500 text-sm">Manajemen kabel yang obsesif. Alur udara yang dikalkulasi. Setiap baut dikencangkan dengan torsi yang tepat.</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">03</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">bug_report</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">The Gauntlet</h3>
                            <p class="text-gray-500 text-sm">Stress test 24 jam. Cinebench, 3DMark, Furmark. Kami menyiksa PC Anda untuk memastikan ia tidak akan pernah gagal saat Anda bermain.</p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">04</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">rocket_launch</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Armored Shipping</h3>
                            <p class="text-gray-500 text-sm">Dikemas dengan Instapak foam yang mengikuti bentuk komponen. Aman dari guncangan kurir hingga sampai di meja Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: COMPONENT ALLIANCE (Partners) --}}
        <div class="py-20 px-4 border-t border-white/5">
            <div class="max-w-5xl mx-auto text-center">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-[0.3em] mb-10">Powered By Industry Leaders</p>
                <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    {{-- Logo Placeholder (Text Based for simplicity & speed) --}}
                    <span class="text-2xl font-black text-white hover:text-green-500 transition-colors">NVIDIA</span>
                    <span class="text-2xl font-black text-white hover:text-blue-500 transition-colors">INTEL</span>
                    <span class="text-2xl font-black text-white hover:text-red-600 transition-colors">AMD</span>
                    <span class="text-2xl font-black text-white hover:text-gray-300 transition-colors">ASUS ROG</span>
                    <span class="text-2xl font-black text-white hover:text-yellow-500 transition-colors">CORSAIR</span>
                    <span class="text-2xl font-black text-white hover:text-orange-500 transition-colors">GIGABYTE</span>
                </div>
            </div>
        </div>

        {{-- SECTION 6: CTA --}}
        <div class="relative py-32 text-center overflow-hidden">
            <div class="absolute inset-0 bg-blue-600/5"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
            
            <div class="relative z-10 px-4 max-w-4xl mx-auto">
                <h2 class="text-5xl md:text-7xl font-black text-white uppercase italic mb-8 tracking-tighter">Ready to Ascend?</h2>
                <p class="text-gray-300 max-w-xl mx-auto mb-10 text-lg md:text-xl font-light">
                    Jangan biarkan hardware menahan skill-mu. Rakit mesin impianmu sekarang dan rasakan perbedaannya.
                </p>
                <a href="{{ route('products.index') }}" class="group relative inline-flex items-center gap-3 px-10 py-5 bg-white text-black font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all clip-card shadow-[0_0_30px_rgba(255,255,255,0.2)] hover:shadow-[0_0_50px_rgba(37,99,235,0.5)] overflow-hidden">
                    <span class="relative z-10">Start Configuration</span>
                    <span class="material-symbols-outlined relative z-10 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    <div class="absolute inset-0 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 ease-out"></div>
                </a>
            </div>
        </div>

    </div>
@endsection