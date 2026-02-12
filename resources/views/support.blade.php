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

        /* Input Form Styles */
        .input-dark {
            background-color: #0a0a0a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #2563eb;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.2);
            outline: none;
            background-color: #0f0f0f;
        }
    </style>

    <div class="bg-[#050014] min-h-screen text-white font-sans selection:bg-blue-600 selection:text-white">

        {{-- SECTION 1: HERO & SEARCH --}}
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

                {{-- Main Title --}}
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black uppercase italic tracking-tighter mb-12 leading-tight">
                    <span class="block text-white">How Can We</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-500 via-white to-blue-500 animate-shine pb-4">
                        Help You?
                    </span>
                </h1>

                {{-- SEARCH INPUT --}}
                <div class="relative max-w-3xl mx-auto group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-8 pointer-events-none z-20 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                        <span class="material-symbols-outlined text-3xl">search</span>
                    </div>
                    <input type="text" 
                           id="masterSearchInput" 
                           onkeyup="jalankanPencarian()" 
                           placeholder="Cari topik (misal: 'Garansi', 'Driver', 'Refund')..." 
                           autocomplete="off"
                           class="w-full pl-20 pr-8 py-6 bg-[#0a0a0a] border border-white/20 text-white rounded-full text-lg md:text-xl shadow-[0_20px_50px_-10px_rgba(0,0,0,0.8)] focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder-gray-600 font-medium relative z-10">
                </div>
            </div>
        </div>
        
        {{-- SECTION 2: TOPIC CARDS --}}
        <div class="w-full px-6 md:px-12 relative z-20 pb-24 -mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-[1600px] mx-auto">
                {{-- ITEM 1 --}}
                <a href="{{ route('orders.index') }}" class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" data-cari="order tracking status pengiriman ship delivery lacak resi">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">local_shipping</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Order Tracking</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Cek posisi paket dan status perakitan PC Anda.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Lacak Sekarang <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 2 --}}
                <a href="{{ route('warranty') }}" class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" data-cari="warranty claim garansi rusak service repair perbaikan mati">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">verified_user</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Warranty Claim</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Ajukan klaim perbaikan atau penggantian komponen.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Klaim Garansi <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 3 --}}
                <a href="{{ route('setup-guide') }}" class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" data-cari="setup guide panduan install driver manual tutorial cara pasang">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">menu_book</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Setup Guide</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Panduan unboxing, instalasi driver, dan setup awal.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Baca Panduan <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>

                {{-- ITEM 4 --}}
                <a href="{{ route('returns') }}" class="target-pencarian group bg-[#0a0a0a] p-8 border border-white/10 hover:border-blue-600 rounded-2xl transition-all duration-300 hover:-translate-y-2 shadow-xl hover:shadow-blue-900/20 h-full flex flex-col justify-between backdrop-blur-md" data-cari="return refund pengembalian dana batal cancel uang kembali">
                    <div>
                        <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl">currency_exchange</span>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-3 text-white italic">Returns</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Kebijakan pengembalian dana dan pembatalan.</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center text-blue-500 font-bold uppercase tracking-wider text-xs">
                        Lihat Kebijakan <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- SECTION 3: FAQ SECTION --}}
        <div class="py-24 px-6 bg-[#080808] border-t border-white/5">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-blue-500 font-bold text-xs uppercase tracking-[0.2em] mb-4 block">Knowledge Base</span>
                    <h2 class="text-3xl md:text-4xl font-black uppercase italic text-white tracking-tighter">Common Questions</h2>
                </div>

                <div class="space-y-4">
                    {{-- FAQ 1 --}}
                    <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" data-cari="rakit berapa lama waktu proses assembly build time hari">
                        <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-white/5 select-none">
                            <span class="font-bold text-lg">Berapa lama proses perakitan PC?</span>
                            <span class="material-symbols-outlined text-gray-400 text-xl group-open:text-blue-500">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-gray-400 text-sm border-t border-white/5 pt-4 leading-relaxed">
                            <p>Proses perakitan memakan waktu 3-7 hari kerja tergantung seri PC. Kami melakukan stress-test selama 24 jam untuk memastikan stabilitas sebelum dikirim.</p>
                        </div>
                    </details>
                    {{-- FAQ 2 --}}
                    <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" data-cari="garansi pengiriman asuransi packing kayu broken pecah aman">
                        <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-white/5 select-none">
                            <span class="font-bold text-lg">Bagaimana dengan garansi pengiriman?</span>
                            <span class="material-symbols-outlined text-gray-400 text-xl group-open:text-blue-500">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-gray-400 text-sm border-t border-white/5 pt-4 leading-relaxed">
                            <p>Kami menggunakan Packing Kayu dan Asuransi Penuh untuk setiap pengiriman. Jika terjadi kerusakan fisik saat barang diterima, kami akan mengganti unit baru 100%.</p>
                        </div>
                    </details>
                     {{-- FAQ 3 --}}
                     <details class="target-pencarian group bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden open:border-blue-600 transition-all duration-300" data-cari="upgrade ganti komponen vga ram">
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
                    <h3 class="text-white font-bold text-xl mb-2">Tidak ada hasil ditemukan</h3>
                    <p class="text-gray-500">Coba kata kunci lain.</p>
                </div>
            </div>
        </div>

        {{-- 
            SECTION 4: CONTACT US (ID="contact")
            PENTING: ID ini yang membuat link footer berfungsi!
        --}}
        <div id="contact" class="py-24 px-6 bg-[#050014] relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <span class="text-blue-500 font-bold text-xs uppercase tracking-[0.2em] mb-4 block">Hubungi Kami</span>
                    <h2 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter text-white">Contact Us</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    {{-- LEFT COLUMN: Contact Info --}}
                    <div class="space-y-6">
                        <div class="bg-[#0a0a0a] border border-white/10 p-8 rounded-2xl">
                            <h3 class="text-xl font-bold text-white mb-6 uppercase italic">Saluran Langsung</h3>
                            
                            {{-- Email --}}
                            <a href="mailto:support@nexrig.com" class="group flex items-center gap-4 p-4 rounded-xl hover:bg-white/5 transition-colors mb-4 border border-transparent hover:border-white/10">
                                <div class="w-12 h-12 bg-blue-600/10 rounded-lg flex items-center justify-center text-blue-500 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined">mail</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Email Support</p>
                                    <p class="text-white font-medium">nexrigsupp0rt@gmail.com</p>
                                </div>
                            </a>

                            {{-- WhatsApp --}}
                            <a href="https://wa.me/6289507094710" target="_blank" class="group flex items-center gap-4 p-4 rounded-xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/10">
                                <div class="w-12 h-12 bg-[#25D366]/10 rounded-lg flex items-center justify-center text-[#25D366] group-hover:bg-[#25D366] group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined">chat</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">WhatsApp Chat</p>
                                    <p class="text-white font-medium">+62 895-0709-4710</p>
                                </div>
                            </a>
                        </div>

                        {{-- Location Info with Maps Link --}}
                        <div class="bg-[#0a0a0a] border border-white/10 p-8 rounded-2xl">
                            <h3 class="text-xl font-bold text-white mb-6 uppercase italic">Headquarters</h3>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-white/5 rounded-lg flex items-center justify-center text-gray-400 mt-1">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <p class="text-white font-bold mb-1">NexRig Experience Center</p>
                                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                                        Jl. Kanal No. 5, Lamper Lor<br>
                                        Semarang Selatan, 50132<br>
                                        Jawa Tengah, Indonesia
                                    </p>
                                    {{-- Link Maps ke Koordinat --}}
                                    <a href="https://www.google.com/maps/search/?api=1&query=-7.000663,110.437499" target="_blank" class="text-blue-500 text-sm font-bold uppercase tracking-wider hover:text-white transition-colors flex items-center gap-1">
                                        Lihat di Peta <span class="material-symbols-outlined text-sm">open_in_new</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

      {{-- RIGHT COLUMN: Contact Form (Visible to All, Submit Protected) --}}
<div class="bg-[#0a0a0a] border border-white/10 p-8 md:p-10 rounded-2xl relative">
    
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl flex items-center gap-4 animate-fade-in-up">
            <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center text-green-500 shrink-0">
                <span class="material-symbols-outlined">check</span>
            </div>
            <div>
                <h4 class="text-green-500 font-bold text-sm uppercase">Berhasil Terkirim!</h4>
                <p class="text-gray-300 text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
            <ul class="text-red-400 text-xs list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="text-xl font-bold text-white mb-6 uppercase italic">Kirim Pesan</h3>
    
    {{-- FORMULIR UTAMA --}}
    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
        @csrf 
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Input Nama --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" 
                       class="w-full px-4 py-3 rounded-lg input-dark bg-white/5 text-gray-400 border-transparent focus:border-transparent cursor-not-allowed" 
                       {{-- LOGIKA VALUE: Jika login ambil nama, jika tidak kosongkan --}}
                       value="{{ Auth::user()->name ?? '' }}" 
                       {{-- LOGIKA PLACEHOLDER: Beri petunjuk jika belum login --}}
                       placeholder="{{ Auth::check() ? '' : 'Login untuk mengisi otomatis' }}"
                       readonly>
            </div>

            {{-- Input Email --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" 
                       class="w-full px-4 py-3 rounded-lg input-dark bg-white/5 text-gray-400 border-transparent focus:border-transparent cursor-not-allowed" 
                       value="{{ Auth::user()->email ?? '' }}" 
                       placeholder="{{ Auth::check() ? '' : 'Login untuk mengisi otomatis' }}"
                       readonly>
            </div>
        </div>

        {{-- Dropdown Subject --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Perihal</label>
            <select name="subject" class="w-full px-4 py-3 rounded-lg input-dark cursor-pointer">
                <option value="general">Pertanyaan Umum</option>
                <option value="support">Dukungan Teknis</option>
                <option value="sales">Penjualan & Pesanan</option>
                <option value="partnership">Kerjasama / Partnership</option>
            </select>
        </div>

        {{-- Textarea Pesan --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pesan</label>
            <textarea name="message" rows="5" class="w-full px-4 py-3 rounded-lg input-dark resize-none" placeholder="Bagaimana kami dapat membantu Anda?" required></textarea>
        </div>

        {{-- LOGIKA TOMBOL: GANTI FUNGSI BERDASARKAN STATUS LOGIN --}}
        @auth
            {{-- JIKA SUDAH LOGIN: Tampilkan Tombol SUBMIT --}}
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg hover:shadow-blue-600/40 flex items-center justify-center gap-2 group">
                Kirim Pesan <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">send</span>
            </button>
        @else
            {{-- JIKA TAMU (BELUM LOGIN): Tampilkan Tombol LINK KE LOGIN --}}
            <a href="{{ route('login') }}" class="w-full py-4 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg flex items-center justify-center gap-2 group">
                <span class="material-symbols-outlined text-lg">lock</span>
                Login Untuk Mengirim
            </a>
        @endauth

    </form>
</div>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // Logika Pencarian (Yang sudah ada)
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

        // --- BARU: Logika Simulasi Kirim Pesan ---
        function kirimPesan() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnLoader = document.getElementById('btnLoader');
            const successMsg = document.getElementById('successMessage');

            // 1. Ubah tombol jadi Loading
            btnText.innerText = "Mengirim...";
            btnIcon.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            // 2. Simulasi delay 2 detik (pura-pura kirim ke server)
            setTimeout(() => {
                // 3. Tampilkan Pesan Sukses
                successMsg.classList.remove('hidden');
                // Trik kecil agar animasi CSS opacity berjalan mulus
                setTimeout(() => {
                    successMsg.classList.remove('opacity-0', 'scale-95');
                    successMsg.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
                }, 50);
                
                // Reset tombol (di balik layar)
                btnText.innerText = "Kirim Pesan";
                btnIcon.classList.remove('hidden');
                btnLoader.classList.add('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                
            }, 2000);
        }

        function resetForm() {
            const form = document.getElementById('contactForm');
            const successMsg = document.getElementById('successMessage');

            // Sembunyikan pesan sukses
            successMsg.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            successMsg.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            
            setTimeout(() => {
                successMsg.classList.add('hidden');
                form.reset(); // Kosongkan input
            }, 300);
        }
    </script>
@endsection