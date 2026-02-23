@extends('layouts.app')

@section('content')
    <style>
        /* Typography untuk Dokumen Legal */
        .legal-content h2 { 
            color: white; 
            font-weight: 800; 
            text-transform: uppercase; 
            font-style: italic; 
            letter-spacing: -0.02em; 
            margin-top: 0; 
            margin-bottom: 1.5rem; 
            font-size: 2rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 1rem;
        }
        .legal-content h3 { 
            color: #60a5fa; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            margin-top: 2.5rem; 
            margin-bottom: 1rem; 
            font-size: 1.25rem; 
        }
        .legal-content p { 
            margin-bottom: 1.5rem; 
            line-height: 1.8; 
            color: #d1d5db; 
            font-size: 1rem;
            text-align: justify;
        }
        .legal-content ul, .legal-content ol { 
            margin-bottom: 2rem; 
            padding-left: 1.5rem; 
            color: #d1d5db; 
            line-height: 1.8;
        }
        .legal-content ul { list-style-type: disc; }
        .legal-content ol { list-style-type: decimal; }
        .legal-content li { margin-bottom: 0.75rem; }
        .legal-content strong { color: white; font-weight: 700; }
        
        /* Background Pattern */

        /* Navigasi Samping */
        .nav-link.active {
            color: #3b82f6; /* Blue-500 */
            border-left-color: #3b82f6;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
        }
    </style>

    <div class="bg-[#050014] min-h-screen text-gray-300 font-sans selection:bg-blue-600 selection:text-white">

        {{-- HEADER SECTION (FULL WIDTH) --}}
        <div class="relative py-24 px-6 md:px-12 border-b border-white/5 bg-[#080808] overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
            {{-- Glow --}}
            <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 max-w-[1600px] mx-auto">
                <div class="flex flex-col lg:flex-row items-end justify-between gap-8">
                    <div>
                        <span class="inline-block py-1 px-3 rounded border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-bold tracking-[0.2em] uppercase mb-6">
                            Legal Document
                        </span>
                        <h1 class="text-5xl md:text-7xl font-black uppercase italic tracking-tighter text-white mb-4 leading-none">
                            Limited <span class="text-blue-600">Warranty</span>
                        </h1>
                        <p class="text-gray-400 max-w-2xl text-lg">
                            Ketentuan layanan purna jual untuk Rakitan PC dan Laptop NexRig.
                        </p>
                    </div>

                    {{-- Quick Stats Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
                        {{-- Card 1: PC --}}
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl flex items-center gap-4 min-w-[200px]">
                            <div class="w-12 h-12 bg-blue-600/20 rounded-full flex items-center justify-center text-blue-500">
                                <span class="material-symbols-outlined text-2xl">computer</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Desktop PC</p>
                                <p class="text-white font-bold text-lg">2 Tahun</p>
                            </div>
                        </div>
                        
                        {{-- Card 2: Laptop --}}
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl flex items-center gap-4 min-w-[200px]">
                            <div class="w-12 h-12 bg-blue-600/20 rounded-full flex items-center justify-center text-blue-500">
                                <span class="material-symbols-outlined text-2xl">laptop_chromebook</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Laptops</p>
                                <p class="text-white font-bold text-lg">1 Tahun</p>
                            </div>
                        </div>

                        {{-- Card 3: Refurbished --}}
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl flex items-center gap-4 min-w-[200px]">
                            <div class="w-12 h-12 bg-blue-600/20 rounded-full flex items-center justify-center text-blue-500">
                                <span class="material-symbols-outlined text-2xl">autorenew</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Refurbished</p>
                                <p class="text-white font-bold text-lg">365 Hari</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN LAYOUT (SIDEBAR + CONTENT) --}}
        <div class="max-w-[1600px] mx-auto px-6 md:px-12 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                {{-- LEFT SIDEBAR (STICKY NAVIGATION) --}}
                <div class="lg:col-span-3 hidden lg:block">
                    <div class="sticky top-24">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 px-4">Table of Contents</p>
                        <nav class="space-y-1 border-l border-white/10">
                            <a href="#overview" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                1. Ikhtisar Garansi
                            </a>
                            <a href="#terms" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                2. Syarat & Ketentuan
                            </a>
                            <a href="#exclusions" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                3. Pengecualian
                            </a>
                            <a href="#dataloss" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                4. Kehilangan Data
                            </a>
                            <a href="#claim" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                5. Cara Klaim
                            </a>
                        </nav>
                          <div class="mt-8 pt-8 border-t border-white/10 px-4">
                            <a href="{{ route('support') }}" class="flex items-center gap-2 text-blue-500 hover:text-blue-400 text-sm font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-lg">arrow_back</span> Back to Support
                            </a>
                        </div>
                        
                    </div>
                </div>

                {{-- RIGHT CONTENT (FULL WIDTH TEXT) --}}
                <div class="lg:col-span-9 legal-content">
                    
                    {{-- Intro Box --}}
                    <div class="bg-[#0a0a0a] border-l-4 border-blue-600 p-8 rounded-r-xl mb-12">
                        <p class="!mb-0 text-lg text-white font-medium">
                            Pembelian sistem komputer (PC Rakitan / Laptop) dari NexRig menandakan pengakuan dan persetujuan Anda terhadap syarat dan ketentuan Garansi Terbatas ini.
                        </p>
                    </div>

                    {{-- 1. OVERVIEW --}}
                    <section id="overview" class="mb-16 scroll-mt-28">
                        <h2>1. Ikhtisar (Overview)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="!mt-0">PC Desktop</h3>
                                <p>Semua PC Rakitan NexRig <strong>BARU</strong> dilengkapi dengan garansi perangkat keras dan jasa selama <strong>2 tahun</strong> untuk pembeli asli, dimulai dari tanggal pembelian.</p>
                            </div>
                            <div>
                                <h3 class="!mt-0">Laptop</h3>
                                <p>Semua Laptop NexRig dilengkapi dengan garansi perangkat keras dan jasa selama <strong>1 tahun</strong> (atau sesuai ketentuan spesifik model) untuk pembeli asli.</p>
                            </div>
                        </div>
                        <p>"Komputer Inti" didefinisikan sebagai semua perangkat keras di dalam casing komputer, namun <strong>tidak termasuk</strong> suku cadang pesanan khusus (*special order parts*) yang diminta secara spesifik oleh pembeli asli. Suku cadang tersebut hanya tunduk pada garansi dari produsen aslinya.</p>
                        <ul>
                            <li>Sistem bersertifikat <em>refurbished</em> dilindungi garansi selama <strong>365 hari</strong>.</li>
                            <li>NexRig berhak atas kebijakannya sendiri untuk memperbaiki atau mengganti sistem Anda dengan model yang setara.</li>
                            <li>Garansi ini <strong>tidak dapat dipindahtangankan</strong> (non-transferable).</li>
                        </ul>
                    </section>

                    {{-- 2. WARRANTY TERMS --}}
                    <section id="terms" class="mb-16 scroll-mt-28">
                        <h2>2. Syarat & Ketentuan Garansi</h2>
                        <p>Jika sistem NexRig Anda tidak beroperasi sesuai dengan spesifikasi teknis yang dipublikasikan, produk akan diganti atau diperbaiki atas biaya NexRig (kecuali biaya pengiriman awal). NexRig dapat menyediakan produk baru atau rekondisi jika penggantian unit baru tidak memungkinkan.</p>
                        
                        <h3>Prosedur Pengembalian</h3>
                        <p>Untuk menggunakan hak garansi, Anda harus mengembalikan perangkat keras ke tempat pembelian asli atau lokasi yang ditunjuk NexRig dengan menyertakan bukti pembelian asli. Anda mungkin diharuskan membayar biaya pengiriman, penanganan, serta tarif yang berlaku.</p>
                        <p>Setiap sistem pengganti akan dijamin selama sisa masa garansi asli atau <strong>30 hari</strong>, mana yang lebih lama.</p>

                        <h3>Garansi Tidak Berlaku Jika:</h3>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 !pl-0 !list-none">
                            <li class="bg-white/5 p-4 rounded border border-white/10 flex gap-3 items-start">
                                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                                <div>Sistem tidak lagi tercakup oleh garansi NexRig atau hukum konsumen yang berlaku.</div>
                            </li>
                            <li class="bg-white/5 p-4 rounded border border-white/10 flex gap-3 items-start">
                                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                                <div>Masalah disebabkan oleh kerusakan fisik (jatuh, terkena air) atau modifikasi tidak sah.</div>
                            </li>
                            <li class="bg-white/5 p-4 rounded border border-white/10 flex gap-3 items-start">
                                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                                <div>Masalah disebabkan oleh perangkat lunak pihak ketiga (virus, malware, software bajakan).</div>
                            </li>
                            <li class="bg-white/5 p-4 rounded border border-white/10 flex gap-3 items-start">
                                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                                <div>Segel garansi rusak atau nomor seri tidak teridentifikasi.</div>
                            </li>
                        </ul>
                    </section>

                    {{-- 3. EXCLUSIONS --}}
                    <section id="exclusions" class="mb-16 scroll-mt-28">
                        <h2>3. Pengecualian</h2>
                        <p>Garansi ini tidak mencakup masalah atau kerusakan yang disebabkan oleh, namun tidak terbatas pada:</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-[#0a0a0a] p-6 rounded border border-white/10">
                                <strong class="block text-blue-400 mb-2 text-lg">Wear & Tear</strong>
                                <p class="!text-sm !mb-0">Keausan normal akibat penggunaan wajar sehari-hari (contoh: debu, goresan halus pada casing).</p>
                            </div>
                            <div class="bg-[#0a0a0a] p-6 rounded border border-white/10">
                                <strong class="block text-blue-400 mb-2 text-lg">Human Error</strong>
                                <p class="!text-sm !mb-0">Overclocking berlebihan, modifikasi fisik casing, instalasi komponen tambahan yang salah, atau perbaikan mandiri.</p>
                            </div>
                            <div class="bg-[#0a0a0a] p-6 rounded border border-white/10">
                                <strong class="block text-blue-400 mb-2 text-lg">Force Majeure</strong>
                                <p class="!text-sm !mb-0">Kecelakaan, penyalahgunaan, korsleting listrik rumah, petir, banjir, atau bencana alam lainnya.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 4. DATA LOSS WARNING --}}
                    <section id="dataloss" class="mb-16 scroll-mt-28">
                        <div class="bg-red-500/5 border border-red-500/30 p-8 rounded-xl relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 text-red-500/10">
                                <span class="material-symbols-outlined text-[200px]">warning</span>
                            </div>
                            <div class="relative z-10">
                                <h2 class="!text-red-500 !mt-0 !border-none !text-3xl flex items-center gap-3">
                                    <span class="material-symbols-outlined text-4xl">warning</span> Data Loss Warning
                                </h2>
                                <p class="!text-gray-200 text-lg">
                                    Sebagai bagian dari proses penggantian atau perbaikan, semua perangkat lunak dan data pada sistem Anda akan diatur ulang ke <strong>PENGATURAN PABRIK (Factory Reset)</strong>.
                                </p>
                                <p class="!mb-0 !text-gray-400">
                                    NexRig <strong>tidak bertanggung jawab</strong> atas hilangnya data, keuntungan, atau pendapatan. Anda bertanggung jawab penuh untuk melakukan cadangan (*backup*) data dan menghapus informasi rahasia sebelum mengirimkan unit kepada kami.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- 5. HOW TO CLAIM --}}
                    <section id="claim" class="mb-16 scroll-mt-28">
                        <h2>4. Cara Mengajukan Klaim</h2>
                        <p>Semua permintaan garansi ditangani secara terpusat melalui sistem tiket layanan pelanggan kami.</p>
                        
                        <div class="bg-[#0a0a0a] rounded-xl p-8 border border-white/10">
                            <ol class="space-y-6 !mb-0">
                                <li class="pl-4">
                                    <strong class="block text-white text-lg mb-1">1. Buat Tiket Support</strong>
                                    Ajukan permintaan melalui halaman <strong><a href="{{ route('support') }}" class="text-blue-400 hover:underline">Support Center</a></strong>. Sertakan bukti pembelian dan foto serial number.
                                </li>
                                <li class="pl-4">
                                    <strong class="block text-white text-lg mb-1">2. Validasi & RMA</strong>
                                    Tunggu tim kami memvalidasi klaim Anda. Jika disetujui, Anda akan mendapatkan nomor <strong>RMA (Return Merchandise Authorization)</strong>.
                                </li>
                                <li class="pl-4">
                                    <strong class="block text-white text-lg mb-1">3. Pengiriman Unit</strong>
                                    Kemas produk dengan aman (wajib packing kayu & asuransi). Tulis nomor RMA di luar paket dengan jelas.
                                </li>
                                <li class="pl-4">
                                    <strong class="block text-white text-lg mb-1">4. Perbaikan & Pengembalian</strong>
                                    Unit akan diperbaiki dalam 3-7 hari kerja dan dikirim kembali ke alamat Anda.
                                </li>
                            </ol>
                        </div>
                    </section>

                    {{-- Footer Print --}}
                    <div class="pt-8 border-t border-white/10 flex justify-between items-center">
                      
                        <button onclick="window.print()" class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-white transition-colors bg-white/5 px-4 py-2 rounded">
                            <span class="material-symbols-outlined">print</span> Print Policy
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Navigasi Aktif saat Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollY >= (sectionTop - 150)) {
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