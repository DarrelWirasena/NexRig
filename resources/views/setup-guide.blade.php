@extends('layouts.app')

@section('content')
    <style>
        /* Typography Content */
        .guide-content h2 { 
            color: white; font-weight: 800; text-transform: uppercase; font-style: italic; 
            letter-spacing: -0.02em; margin-top: 0; margin-bottom: 1.5rem; font-size: 2rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;
        }
        .guide-content h3 { 
            color: #60a5fa; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; 
            margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .guide-content p { margin-bottom: 1.5rem; line-height: 1.8; color: #d1d5db; font-size: 1rem; text-align: justify; }
        .guide-content ul, .guide-content ol { margin-bottom: 2rem; padding-left: 1.5rem; color: #d1d5db; line-height: 1.8; }
        
        /* Step Number Styling */
        .step-number {
            font-family: 'Figtree', sans-serif; font-weight: 900; font-size: 4rem; line-height: 1;
            color: rgba(255,255,255,0.05); position: absolute; top: -10px; right: 10px; z-index: 0; pointer-events: none;
        }

        /* Navigasi Samping */
        .nav-link.active {
            color: #3b82f6; border-left-color: #3b82f6;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
        }

        /* Diagram Styles */
        .tech-diagram {
            background: #080808; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem;
            padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center;
            position: relative; overflow: hidden; height: 100%;
        }
      .diagram-grid {
            background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            
            /* INI PERBAIKANNYA: Gunakan properti CSS asli */
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.2;
        }
        
        /* Print Styles */
        @media print {
            body { background: white; color: black; }
            .no-print { display: none !important; }
            .guide-content { width: 100% !important; grid-column: span 12 !important; }
            h1, h2, h3 { color: black !important; }
            p, li { color: #333 !important; }
        }
    </style>

    <div class="bg-[#050014] min-h-screen text-gray-300 font-sans selection:bg-blue-600 selection:text-white">

        {{-- HEADER SECTION --}}
        <div class="relative py-24 px-6 md:px-12 border-b border-white/5 bg-[#080808] overflow-hidden no-print">
            <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
            {{-- Glow --}}
            <div class="absolute top-0 left-0 w-[800px] h-[800px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 max-w-[1600px] mx-auto">
                <div class="flex flex-col lg:flex-row items-end justify-between gap-8">
                    <div>
                        <span class="inline-block py-1 px-3 rounded border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-bold tracking-[0.2em] uppercase mb-6">
                            Technical Documentation
                        </span>
                        <h1 class="text-5xl md:text-7xl font-black uppercase italic tracking-tighter text-white mb-4 leading-none">
                            System <span class="text-blue-600">Initialization</span>
                        </h1>
                        <p class="text-gray-400 max-w-2xl text-lg">
                            Panduan langkah demi langkah untuk melakukan setup, unboxing, dan konfigurasi awal unit NexRig Anda.
                        </p>
                    </div>

                    {{-- Functional Print/Download Button --}}
                    <button onclick="window.print()" class="px-6 py-4 bg-[#0a0a0a] border border-white/10 hover:border-blue-600 hover:text-white text-gray-400 font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-3 group">
                        <span class="material-symbols-outlined group-hover:animate-bounce">print</span> Save as PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="max-w-[1600px] mx-auto px-6 md:px-12 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                {{-- LEFT SIDEBAR (STICKY NAV) --}}
                <div class="lg:col-span-3 hidden lg:block no-print">
                    <div class="sticky top-24">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 px-4">Installation Steps</p>
                        <nav class="space-y-1 border-l border-white/10">
                            <a href="#unboxing" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                1. Unboxing & Inspection
                            </a>
                            <a href="#internal" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                2. Internal Protection
                            </a>
                            <a href="#cabling" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                3. Cable Connections
                            </a>
                            <a href="#firstboot" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                4. First Boot & Setup
                            </a>
                            <a href="#drivers" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                5. Drivers & Optimization
                            </a>
                        </nav>
                        
                        <div class="mt-8 pt-8 border-t border-white/10 px-4">
                            <a href="{{ route('support') }}" class="flex items-center gap-2 text-blue-500 hover:text-blue-400 text-sm font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-lg">arrow_back</span> Back to Support
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT CONTENT --}}
                <div class="lg:col-span-9 guide-content">

                    {{-- 1. UNBOXING --}}
                    <section id="unboxing" class="mb-20 scroll-mt-28">
                        <h2>1. Unboxing & Inspection</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl relative overflow-hidden group hover:border-blue-600 transition-colors">
                                <div class="step-number">01</div>
                                <div class="relative z-10">
                                    <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center text-blue-500 mb-4">
                                        <span class="material-symbols-outlined text-2xl">package_2</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Cek Fisik Luar</h4>
                                    <p class="text-sm text-gray-400 !mb-0">Periksa kardus packing kayu. Jika ada penyok parah, segera foto sebelum membuka. Kerusakan packing luar adalah indikasi awal.</p>
                                </div>
                            </div>
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl relative overflow-hidden group hover:border-blue-600 transition-colors">
                                <div class="step-number">02</div>
                                <div class="relative z-10">
                                    <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center text-blue-500 mb-4">
                                        <span class="material-symbols-outlined text-2xl">videocam</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Video Unboxing (Wajib)</h4>
                                    <p class="text-sm text-gray-400 !mb-0">Rekam video tanpa putus (no cut) saat membuka paket. Ini syarat mutlak untuk klaim asuransi jika ada komponen pecah.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 2. INTERNAL PROTECTION --}}
                    <section id="internal" class="mb-20 scroll-mt-28">
                        <h2>2. Remove Internal Protection</h2>
                        
                        <div class="bg-yellow-500/10 border border-yellow-500/50 p-6 rounded-xl mb-8 flex gap-4 items-start">
                            <span class="material-symbols-outlined text-yellow-500 text-4xl shrink-0">warning</span>
                            <div>
                                <h4 class="text-yellow-500 font-bold uppercase tracking-wider mb-1 text-lg">CRITICAL WARNING</h4>
                                <p class="text-gray-200 !mb-0 text-sm">
                                    JANGAN nyalakan kabel power sebelum melepas "Instapak" (Busa Pengeras) di dalam casing! Menyalakan PC dengan busa di dalamnya dapat mematahkan baling-baling kipas atau membakar komponen.
                                </p>
                            </div>
                        </div>

                        <ul class="space-y-4">
                            <li class="flex items-start gap-4">
                                <div class="mt-1 w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shrink-0">1</div>
                                <div>
                                    <strong class="block text-white">Buka Panel Kaca Samping</strong>
                                    Lepaskan 2-4 baut di bagian belakang atau samping. Simpan kaca di tempat aman (kasur/sofa) agar tidak pecah.
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="mt-1 w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shrink-0">2</div>
                                <div>
                                    <strong class="block text-white">Tarik Instapak Perlahan</strong>
                                    Tarik busa yang mengembang di sekitar VGA dan CPU Cooler. Pastikan tidak ada sisa styrofoam yang tertinggal.
                                </div>
                            </li>
                        </ul>
                    </section>

                    {{-- 3. CABLING (WITH SVG DIAGRAMS) --}}
                    <section id="cabling" class="mb-20 scroll-mt-28">
                        <h2>3. Cable Connections</h2>
                        <p>Kesalahan paling umum adalah salah colok kabel monitor. Perhatikan ilustrasi di bawah:</p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                            
                            {{-- CORRECT WAY (SVG Diagram) --}}
                            <div class="border border-green-500/30 bg-green-500/5 rounded-xl overflow-hidden">
                                <div class="p-4 border-b border-green-500/20 bg-green-500/10 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                                    <span class="text-green-500 font-bold uppercase text-sm">BENAR: Colok ke GPU (Bawah)</span>
                                </div>
                                <div class="p-8 flex justify-center bg-[#0a0a0a] h-64 relative">
                                    {{-- SVG Illustration of PC Back --}}
                                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="20" y="10" width="160" height="180" rx="4" stroke="#333" stroke-width="2"/>
                                        
                                        <rect x="40" y="30" width="40" height="80" rx="2" stroke="#444" stroke-width="2" stroke-dasharray="4 4"/>
                                        <rect x="45" y="40" width="10" height="20" fill="#222"/> <circle cx="60" cy="90" r="5" fill="#222"/> <rect x="20" y="130" width="160" height="40" fill="#1a1a1a" stroke="#22c55e" stroke-width="2"/>
                                        <rect x="40" y="140" width="25" height="10" rx="1" fill="#222" stroke="#22c55e" stroke-width="1"/>
                                        <rect x="75" y="140" width="25" height="10" rx="1" fill="#222" stroke="#22c55e" stroke-width="1"/>
                                        <rect x="110" y="140" width="25" height="10" rx="1" fill="#222" stroke="#22c55e" stroke-width="1"/>
                                        
                                        <path d="M52 180 L52 155" stroke="#22c55e" stroke-width="4"/>
                                        <circle cx="52" cy="185" r="5" fill="#22c55e"/>
                                        <text x="70" y="185" fill="#22c55e" font-family="sans-serif" font-size="10" font-weight="bold">HDMI / DP</text>
                                    </svg>
                                </div>
                            </div>

                            {{-- WRONG WAY (SVG Diagram) --}}
                            <div class="border border-red-500/30 bg-red-500/5 rounded-xl overflow-hidden">
                                <div class="p-4 border-b border-red-500/20 bg-red-500/10 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-500">cancel</span>
                                    <span class="text-red-500 font-bold uppercase text-sm">SALAH: Colok ke Motherboard (Atas)</span>
                                </div>
                                <div class="p-8 flex justify-center bg-[#0a0a0a] h-64 relative">
                                    {{-- SVG Illustration --}}
                                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="20" y="10" width="160" height="180" rx="4" stroke="#333" stroke-width="2"/>
                                        
                                        <rect x="40" y="30" width="40" height="80" rx="2" stroke="#ef4444" stroke-width="2"/>
                                        <rect x="45" y="40" width="10" height="20" fill="#222"/>
                                        <rect x="45" y="70" width="10" height="10" fill="#222" stroke="#ef4444" stroke-width="1"/> <rect x="20" y="130" width="160" height="40" fill="#111" stroke="#333" stroke-width="2"/>
                                        <rect x="40" y="140" width="25" height="10" rx="1" fill="#222"/>
                                        
                                        <path d="M30 75 L10 75 L10 180" stroke="#ef4444" stroke-width="4" stroke-dasharray="4 4"/>
                                        <text x="65" y="75" fill="#ef4444" font-family="sans-serif" font-size="10" font-weight="bold">NO SIGNAL!</text>
                                    </svg>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- 4. FIRST BOOT --}}
                    <section id="firstboot" class="mb-20 scroll-mt-28">
                        <h2>4. First Boot Sequence</h2>
                        <div class="space-y-4">
                            <div class="flex gap-6 items-start">
                                <span class="material-symbols-outlined text-blue-500 text-3xl shrink-0">power_settings_new</span>
                                <div>
                                    <h4 class="text-white font-bold text-lg mb-1">1. Nyalakan PSU & Tombol Power</h4>
                                    <p class="text-gray-400 text-sm">Pastikan saklar PSU (belakang) di posisi "I". Lalu tekan tombol power casing.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 items-start">
                                <span class="material-symbols-outlined text-blue-500 text-3xl shrink-0">hourglass_top</span>
                                <div>
                                    <h4 class="text-white font-bold text-lg mb-1">2. Memory Training (Layar Hitam)</h4>
                                    <p class="text-gray-400 text-sm">Untuk PC DDR5, booting pertama bisa memakan waktu <strong>1-3 menit</strong> dengan layar hitam untuk kalibrasi RAM. Kipas akan berputar kencang lalu pelan. <strong>JANGAN MATIKAN PAKSA!</strong> Tunggu hingga logo Windows muncul.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 5. DRIVERS (Functional Links) --}}
                    <section id="drivers" class="mb-20 scroll-mt-28">
                        <h2>5. Drivers & Updates</h2>
                        <p>Klik tombol di bawah untuk mendownload driver terbaru sesuai komponen PC Anda:</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NVIDIA --}}
                            <a href="https://www.nvidia.com/en-us/geforce/drivers/" target="_blank" class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl hover:border-[#76b900] transition-all group flex items-center justify-between">
                                <div>
                                    <h4 class="text-white font-bold mb-1 group-hover:text-[#76b900] transition-colors">NVIDIA GeForce</h4>
                                    <p class="text-xs text-gray-400">Download GeForce Experience</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-500 group-hover:text-[#76b900]">download</span>
                            </a>

                            {{-- AMD --}}
                            <a href="https://www.amd.com/en/support" target="_blank" class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl hover:border-[#ed1c24] transition-all group flex items-center justify-between">
                                <div>
                                    <h4 class="text-white font-bold mb-1 group-hover:text-[#ed1c24] transition-colors">AMD Radeon</h4>
                                    <p class="text-xs text-gray-400">Download Adrenalin Software</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-500 group-hover:text-[#ed1c24]">download</span>
                            </a>
                        </div>
                    </section>

                    {{-- CONTACT CTA --}}
                    <div class="mt-12 p-8 bg-blue-900/10 border border-blue-600/30 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6 no-print">
                        <div>
                            <h3 class="!mt-0 !mb-2 text-white !text-xl">Masih Bingung?</h3>
                            <p class="!mb-0 text-gray-400 text-sm">Jika PC tidak menyala setelah mengikuti panduan, hubungi teknisi kami.</p>
                        </div>
                        <a href="{{ route('support') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg shadow-blue-600/20">
                            Contact Support
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Navigasi Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                document.querySelectorAll('section').forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection