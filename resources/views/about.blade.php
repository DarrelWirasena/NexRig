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
            animation: scanline 10s linear infinite;
        }
        @keyframes scanline {
            0% { bottom: 100%; }
            100% { bottom: -100%; }
        }
    </style>

    <div class="bg-[#050505] min-h-screen text-white overflow-hidden">

        {{-- SECTION 1: HERO MANIFESTO --}}
        <div class="relative py-32 px-4 flex items-center justify-center overflow-hidden">
            {{-- Background Elements --}}
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2070')] bg-cover bg-center opacity-20 grayscale mix-blend-luminosity"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#050505] via-transparent to-[#050505]"></div>
                <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                <div class="scanline"></div>
            </div>

            <div class="relative z-10 text-center max-w-4xl mx-auto">
                <span class="inline-block py-1 px-3 rounded border border-primary/30 bg-primary/10 text-primary text-xs font-bold tracking-[0.3em] uppercase mb-6 animate-pulse">
                    System Manifesto // v.1.0
                </span>
                <h1 class="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-6">
                    We Don't Just Build PCs. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-400 to-gray-600">We Forge Weapons.</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                    NexRig lahir dari rasa frustrasi. Frustrasi terhadap kabel yang berantakan, bloatware, dan komponen murah. 
                    Kami hadir untuk mendefinisikan ulang standar performa gaming.
                </p>
            </div>
        </div>

        {{-- SECTION 2: THE PHILOSOPHY (Grid Layout) --}}
        <div class="max-w-[1440px] mx-auto px-4 md:px-10 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- Card 1 --}}
                <div class="bg-[#0a0a0a] p-8 border border-white/10 hover:border-primary/50 transition-colors group relative overflow-hidden clip-card">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <span class="material-symbols-outlined text-9xl">cable</span>
                    </div>
                    <h3 class="text-2xl font-bold uppercase mb-4 text-white group-hover:text-primary transition-colors">Obsessive Wiring</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Kami tidak menyembunyikan kabel; kami menatanya. Setiap jalur kabel dirancang untuk aliran udara maksimal dan estetika visual. Bagian belakang PC kami sama indahnya dengan bagian depan.
                    </p>
                </div>

                {{-- Card 2 --}}
                <div class="bg-[#0a0a0a] p-8 border border-white/10 hover:border-primary/50 transition-colors group relative overflow-hidden clip-card">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <span class="material-symbols-outlined text-9xl">speed</span>
                    </div>
                    <h3 class="text-2xl font-bold uppercase mb-4 text-white group-hover:text-primary transition-colors">Zero Bloatware</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Windows murni. Driver terbaru. Tanpa antivirus trial atau software sampah yang memperlambat boot time. Nyalakan, install game, mainkan.
                    </p>
                </div>

                {{-- Card 3 --}}
                <div class="bg-[#0a0a0a] p-8 border border-white/10 hover:border-primary/50 transition-colors group relative overflow-hidden clip-card">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <span class="material-symbols-outlined text-9xl">verified_user</span>
                    </div>
                    <h3 class="text-2xl font-bold uppercase mb-4 text-white group-hover:text-primary transition-colors">Torture Tested</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Setiap rig melewati "The Gauntlet": Stress test 24 jam penuh pada CPU, GPU, dan RAM sebelum dikirim. Jika tidak lulus, tidak kami kirim.
                    </p>
                </div>
            </div>
        </div>

        {{-- SECTION 3: STATS STRIP --}}
        <div class="border-y border-white/10 bg-white/5 backdrop-blur-sm py-12">
            <div class="max-w-[1440px] mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2">500+</span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Systems Deployed</span>
                </div>
                <div>
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2">99.9%</span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Customer Satisfaction</span>
                </div>
                <div>
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2">365</span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Days Support</span>
                </div>
                <div>
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2">0%</span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Compromise</span>
                </div>
            </div>
        </div>

        {{-- SECTION 4: THE ARCHITECTS (Team) --}}
        <div class="py-24 px-4">
            <div class="max-w-[1440px] mx-auto">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="text-4xl font-black text-white uppercase italic">The Architects</h2>
                        <div class="h-1 w-24 bg-primary mt-2"></div>
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-gray-500 font-mono text-xs">PERSONNEL_FILE // CLASSIFIED</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Team Member 1 --}}
                    <div class="group relative">
                        <div class="aspect-[3/4] overflow-hidden bg-gray-900 clip-card grayscale group-hover:grayscale-0 transition-all duration-500">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-white uppercase">Alex "Core" Mercer</h3>
                            <p class="text-primary text-xs font-bold tracking-widest uppercase mb-2">Lead Technician</p>
                            <p class="text-gray-400 text-sm">Overclocking specialist. Holds the record for highest stable clock on 14900KS in the region.</p>
                        </div>
                    </div>

                    {{-- Team Member 2 --}}
                    <div class="group relative">
                        <div class="aspect-[3/4] overflow-hidden bg-gray-900 clip-card grayscale group-hover:grayscale-0 transition-all duration-500">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-white uppercase">Sarah "Valkyrie" Chen</h3>
                            <p class="text-primary text-xs font-bold tracking-widest uppercase mb-2">System Architect</p>
                            <p class="text-gray-400 text-sm">Cable management artist. Ensures airflow dynamics are optimal for every custom loop.</p>
                        </div>
                    </div>

                    {{-- Team Member 3 --}}
                    <div class="group relative">
                        <div class="aspect-[3/4] overflow-hidden bg-gray-900 clip-card grayscale group-hover:grayscale-0 transition-all duration-500">
                            <img src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-white uppercase">David "Glitch" Ross</h3>
                            <p class="text-primary text-xs font-bold tracking-widest uppercase mb-2">Logistics & QA</p>
                            <p class="text-gray-400 text-sm">The final gatekeeper. Nothing leaves the warehouse without his stamp of approval.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: CTA --}}
        <div class="relative py-24 text-center overflow-hidden">
            <div class="absolute inset-0 bg-primary/10"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
            
            <div class="relative z-10 px-4">
                <h2 class="text-4xl md:text-6xl font-black text-white uppercase italic mb-6">Ready to Ascend?</h2>
                <p class="text-gray-300 max-w-xl mx-auto mb-8 text-lg">
                    Join the elite. Build your dream machine with components that refuse to compromise.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-black font-bold uppercase tracking-widest hover:bg-primary hover:text-white transition-all clip-card shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                    Start Your Build <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>

    </div>
@endsection