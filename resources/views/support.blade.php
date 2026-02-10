@extends('layouts.app')

@section('content')
    {{-- Custom Styles --}}
    <style>
        /* CSS Dasar & Utilities */
        .clip-card { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        
        /* Animasi */
        @keyframes shine { to { background-position: 200% center; } }
        .animate-shine { background-size: 200% auto; animation: shine 3s linear infinite; }
        @keyframes fadeInUp { from { opacity: 0; transform: translate3d(0, 20px, 0); } to { opacity: 1; transform: translate3d(0, 0, 0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        
        /* Accordion Animation */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] summary ~ * { animation: sweep .3s ease-in-out; }
        @keyframes sweep { 0% {opacity: 0; transform: translateY(-10px)} 100% {opacity: 1; transform: translateY(0)} }

        /* SEARCH LOGIC CSS */
        .force-hide { display: none !important; }

        /* Hover Effects */
        .cta-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(37,99,235,0.2);
            border-color: rgba(37,99,235,0.5);
        }
    </style>

    <div class="bg-[#050014] min-h-screen text-white font-sans selection:bg-blue-600 selection:text-white">

        {{-- 
            SECTION 1: HERO & SEARCH 
            Perbaikan: Menggunakan padding atas-bawah yang lebih besar (py-32) 
            agar judul tidak tertabrak kartu.
        --}}
        <div class="relative w-full flex flex-col items-center justify-center text-center overflow-hidden border-b border-white/5 py-32 lg:py-40">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1000px] h-[1000px] bg-blue-600/10 blur-[150px] rounded-full pointer-events-none z-0"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-30 z-0"></div>

            <div class="relative z-10 max-w-[1400px] w-full px-6">
                
                {{-- Badge --}}
                <div class="mb-8 animate-fade-in-up">
                    <span class="inline-flex items-center gap-3 py-2 px-6 rounded-full border border-blue-500/30 bg-[#0a0a0a]/80 backdrop-blur-md text-blue-400 text-sm font-bold tracking-[0.2em] uppercase">
                        NexRig Support Center
                    </span>
                </div>

                {{-- 
                    Main Title 
                    Perbaikan: Ukuran font disesuaikan dan leading (jarak baris) diperbaiki
                    agar teks 'Help You' tidak terpotong.
                --}}
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black uppercase italic tracking-tighter mb-12 leading-tight">
                    <span class="block text-white">How Can We</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-500 via-white to-blue-500 animate-shine pb-4">
                        Help You?
                    </span>
                </h1>

                {{-- 
                    SEARCH INPUT 
                    Perbaikan: Ikon sekarang menggunakan Flexbox (items-center) 
                    agar PASTI berada di tengah vertikal, tidak menempel di atas.
                --}}
                <div class="relative max-w-3xl mx-auto group">
                    {{-- Wrapper Ikon: inset-y-0 + flex + items-center menjamin posisi tengah --}}
                    <div class="absolute inset-y-0 left-0 flex items-center pl-8 pointer-events-none z-20 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                        <span class="material-symbols-outlined text-3xl">search</span>
                    </div>
                    
                    {{-- Input Field --}}
                    <input type="text" 
                           id="masterSearchInput" 
                           onkeyup="jalankanPencarian()" 
                           placeholder="Search for 'Warranty', 'Drivers', 'Refunds'..." 
                           autocomplete="off"
                           class="w-full pl-20 pr-8 py-6 bg-[#0a0a0a] border border-white/20 text-white rounded-full text-lg md:text-xl shadow-[0_20px_50px_-10px_rgba(0,0,0,0.8)] focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder-gray-600 font-medium relative z-10">
                </div>
            </div>
        </div>
        
        {{-- 
            SECTION 2: TOPIC CARDS 
            Perbaikan: Margin negatif (-mt) dikurangi agar tidak terlalu menumpuk ke atas.
            Grid gap diperbesar agar lebih lega.
        --}}
        <div class="w-full px-6 md:px-12 relative z-20 pb-24 -mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-[1600px] mx-auto">
                
                {{-- ITEM 1 --}}
                <a href="{{ route('orders.index') }}" 
                   class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" 
                   data-cari="order tracking status pengiriman ship delivery lacak resi">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">local_shipping</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Order Tracking</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Cek posisi paket dan status perakitan PC Anda.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Track Now <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 2 --}}
                <a href="{{ route('warranty') }}" 
                   class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" 
                   data-cari="warranty claim garansi rusak service repair perbaikan mati">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">verified_user</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Warranty Claim</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Ajukan klaim perbaikan atau penggantian komponen.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                         View More<span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 3 --}}
                <a href="{{ route('setup-guide') }}" 
                   class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" 
                   data-cari="setup guide panduan install driver manual tutorial cara pasang">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">menu_book</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Setup Guide</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Panduan unboxing, instalasi driver, dan setup awal.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Read Guide <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 4 --}}
                <a href="{{ route('returns') }}" 
                   class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" 
                   data-cari="return refund pengembalian dana batal cancel uang kembali">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">currency_exchange</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Returns</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Kebijakan pengembalian dana dan pembatalan.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        View Policy <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

            </div>
        </div>

        {{-- FAQ SECTION --}}
        <div class="py-24 px-6 bg-[#080808] border-t border-white/5">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-blue-500 font-bold text-xs uppercase tracking-[0.2em] mb-4 block">Knowledge Base</span>
                    <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white tracking-tighter">Common Questions</h2>
                </div>

                <div class="space-y-4">
                    
                    {{-- FAQ 1 --}}
                    <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" 
                             data-cari="rakit berapa lama waktu proses assembly build time hari">
                        <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-white/5 select-none">
                            <span class="font-bold text-lg">Berapa lama proses perakitan PC?</span>
                            <span class="material-symbols-outlined text-gray-400 text-xl group-open:text-blue-500">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-gray-400 text-sm border-t border-white/5 pt-4 leading-relaxed">
                            <p>Proses perakitan memakan waktu 3-7 hari kerja tergantung seri PC. Kami melakukan stress-test selama 24 jam untuk memastikan stabilitas sebelum dikirim.</p>
                        </div>
                    </details>

                    {{-- FAQ 2 --}}
                    <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" 
                             data-cari="garansi pengiriman asuransi packing kayu broken pecah aman">
                        <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-white/5 select-none">
                            <span class="font-bold text-lg">Bagaimana dengan garansi pengiriman?</span>
                            <span class="material-symbols-outlined text-gray-400 text-xl group-open:text-blue-500">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-gray-400 text-sm border-t border-white/5 pt-4 leading-relaxed">
                            <p>Kami menggunakan Packing Kayu dan Asuransi Penuh untuk setiap pengiriman. Jika terjadi kerusakan fisik saat barang diterima, kami akan mengganti unit baru 100%.</p>
                        </div>
                    </details>

                     {{-- FAQ 3 --}}
                     <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" 
                        data-cari="upgrade ganti komponen vga ram">
                        <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-white/5 select-none">
                            <span class="font-bold text-lg">Bisa upgrade sendiri?</span>
                            <span class="material-symbols-outlined text-gray-400 text-xl group-open:text-blue-500">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-gray-400 text-sm border-t border-white/5 pt-4 leading-relaxed">
                            <p>Ya, Anda bisa upgrade komponen (RAM/SSD/VGA) di masa depan tanpa menghanguskan garansi komponen lain, selama tidak merusak fisik motherboard.</p>
                        </div>
                    </details>

                </div>
                
                {{-- PESAN JIKA TIDAK ADA HASIL --}}
                <div id="pesanKosong" class="force-hide text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-gray-700 mb-6">search_off</span>
                    <h3 class="text-white font-bold text-xl mb-2">No results found</h3>
                    <p class="text-gray-500">Coba kata kunci lain.</p>
                </div>
            </div>
        </div>

        {{-- MODERN CTA SECTION --}}
        <div class="relative py-24 px-6 bg-[#050014] overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            
            <div class="max-w-6xl mx-auto relative z-10 text-center">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-5xl font-black uppercase italic tracking-tighter mb-4">Still Need Help?</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    
                    {{-- CTA Card 1: Email --}}
                    <a href="mailto:support@nexrig.com" class="cta-card bg-[#0a0a0a] border border-white/10 p-8 rounded-2xl transition-all duration-300 group relative overflow-hidden text-left flex items-center gap-6">
                        <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-600/30">
                            <span class="material-symbols-outlined text-2xl">mail</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-1">Email Support</h3>
                            <p class="text-gray-400 text-sm mb-3">Untuk klaim garansi & teknis.</p>
                            <span class="text-blue-500 font-bold uppercase text-xs tracking-wider flex items-center">
                                Send Email <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                            </span>
                        </div>
                    </a>

                    {{-- CTA Card 2: WhatsApp --}}
                    <a href="https://wa.me/6281234567890" target="_blank" class="cta-card bg-[#0a0a0a] border border-white/10 p-8 rounded-2xl transition-all duration-300 group relative overflow-hidden text-left flex items-center gap-6">
                        <div class="w-14 h-14 bg-[#25D366] rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-green-500/30">
                            <span class="material-symbols-outlined text-2xl">chat</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-1">WhatsApp Chat</h3>
                            <p class="text-gray-400 text-sm mb-3">Respon cepat untuk pertanyaan umum.</p>
                            <span class="text-[#25D366] font-bold uppercase text-xs tracking-wider flex items-center">
                                Chat Now <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                            </span>
                        </div>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT JAVASCRIPT (SAMA SEPERTI SEBELUMNYA) --}}
    <script>
        function jalankanPencarian() {
            var input = document.getElementById("masterSearchInput");
            var filter = input.value.toUpperCase();
            var targets = document.getElementsByClassName("target-pencarian");
            var jumlahHasil = 0;

            for (var i = 0; i < targets.length; i++) {
                var item = targets[i];
                var textContent = item.textContent || item.innerText;
                var keywords = item.getAttribute("data-cari") || "";
                var gabunganTeks = textContent + " " + keywords;

                if (gabunganTeks.toUpperCase().indexOf(filter) > -1) {
                    item.classList.remove("force-hide");
                    jumlahHasil++;
                    if(item.tagName === "DETAILS" && filter !== "") {
                        item.setAttribute("open", "true");
                    }
                } else {
                    item.classList.add("force-hide");
                }
            }

            var pesanKosong = document.getElementById("pesanKosong");
            if (jumlahHasil === 0) {
                pesanKosong.classList.remove("force-hide");
            } else {
                pesanKosong.classList.add("force-hide");
            }
        }
    </script>
@endsection