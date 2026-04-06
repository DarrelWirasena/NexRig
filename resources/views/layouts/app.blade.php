<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dwu1fbd69/image/upload/v1773198090/logonexrig_tryrac.png">
    <link rel="shortcut icon"         href="https://res.cloudinary.com/dwu1fbd69/image/upload/v1773198090/logonexrig_tryrac.png">

    {{-- 2. ASSETS (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(request()->is('/'))
        {{ config('app.name', 'NexRig') }} - {{ $title ?? 'The Ultimate Gaming Experience' }}
        @else
        {{ $title ?? ucwords(str_replace(['-', '.'], ' ', Route::currentRouteName())) }} - {{ config('app.name', 'NexRig') }}
        @endif
    </title>

    {{-- 1. FONTS & ICONS --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    @stack('styles')
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-clip min-h-screen flex flex-col
    {{ Route::is('login') || Route::is('register') ? '' : 'pt-20' }}">

    {{-- NAVIGATION --}}
    @if(!Route::is('login') && !Route::is('register'))
    @include('components.navbar')
    @endif

    {{-- MAIN CONTENT --}}
    <main class="flex-grow w-full relative">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @if(!Route::is('login') && !Route::is('register'))
    @include('components.footer')
    @endif

    {{--
        =========================================================
        GLOBAL COMPONENTS (Overlays)
        =========================================================
    --}}

    {{-- 1. Mini Cart Sidebar --}}
    <x-mini-cart />

    {{-- 2. Video Overlay (Alpine.js Component) --}}
    <div x-data="{ open: false, videoUrl: '' }"
        x-on:open-video.window="open = true; videoUrl = $event.detail.url"
        x-on:keydown.escape.window="open = false; videoUrl = ''"
        x-show="open" x-cloak
        class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-10">

        {{-- Backdrop --}}
        <div x-show="open" x-transition @click="open = false; videoUrl = ''" class="absolute inset-0 bg-black/90 backdrop-blur-xl"></div>

        {{-- Video Container --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-500 transform" x-transition:enter-start="opacity-0 scale-90"
            class="relative w-full max-w-5xl aspect-video bg-black rounded-2xl overflow-hidden border border-white/10 shadow-2xl">

            <button @click="open = false; videoUrl = ''" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/50 text-white border border-white/10 hover:bg-primary transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined">close</span>
            </button>

            {{-- Loading Spinner --}}
            <div class="absolute inset-0 flex items-center justify-center -z-10">
                <span class="material-symbols-outlined text-primary text-5xl animate-spin">progress_activity</span>
            </div>

            <template x-if="open">
                <iframe class="w-full h-full" :src="videoUrl" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </template>
        </div>
    </div>

    {{-- 3. Flash Message Data (Hidden Bridge to JS) --}}
    <div id="flash-messages"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        data-validation="{{ $errors->any() ? $errors->first() : '' }}"
        class="hidden">
    </div>

    {{--
    =========================================================
    CHATBOT (NexRig Assistant)
    =========================================================
    --}}

    @if(!Route::is('login') && !Route::is('register'))
    {{-- Floating Button Chat --}}
    <button id="chat-toggle-btn"
        onclick="toggleChat()"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-primary rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(19,55,236,0.3)] hover:scale-110 transition-all duration-300 transform">
        <span class="material-symbols-outlined text-white">chat</span>
    </button>
    @endif

    {{-- Chat Window --}}
    <div id="chat-window"
        class="chat-closed chat-hide fixed bottom-24 right-6 z-50 w-80 border border-white/10 rounded-2xl shadow-2xl flex flex-col bg-[#121212] transition-all duration-300 transform"
        style="display:none; height: 420px"
        aria-hidden="true">

        {{-- Header --}}
        <div class="p-4 border-b border-white/10 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <p class="text-white font-bold text-sm">NexRig Assistant</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Tombol clear --}}
                <button onclick="clearChatHistory()"
                    class="text-gray-500 hover:text-red-400 transition-colors"
                    title="Hapus riwayat">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>
                <button onclick="toggleChat()" class="text-gray-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
            {{-- Pesan awal --}}
            <div class="flex justify-start">
                <div class="bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%]">
                    Halo! Saya <strong>NexRig</strong>, asisten virtual toko ini. Ada yang bisa saya bantu? 👋
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="p-3 border-t border-white/10 flex gap-2 shrink-0">
            <input id="chat-input"
                type="text"
                placeholder="Ketik pesan..."
                class="flex-1 bg-white/5 text-white text-sm rounded-xl px-3 py-2 outline-none border border-white/10 focus:border-primary transition-colors">
            <button onclick="sendMessage()"
                id="send-btn"
                class="bg-primary px-3 py-2 rounded-xl hover:bg-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-white text-sm">send</span>
            </button>
        </div>
    </div>

    {{-- marked.js untuk render Markdown --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    {{-- Enter to send --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('chat-input')?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    window.sendMessage();
                }
            });
        });
    </script>

    {{-- 
    =========================================================
    BACK TO TOP BUTTON
    =========================================================
    --}}
    <button id="backToTopBtn" onclick="scrollToTop()" 
        class="fixed bottom-6 right-6 z-40 w-14 h-10 bg-white/5 backdrop-blur-md border border-white/10 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transform translate-y-4 transition-all duration-300 hover:bg-white/20 hover:scale-110">
        <span class="material-symbols-outlined text-xl">keyboard_arrow_up</span>
    </button>

    {{-- 
    =========================================================
    GLOBAL SCRIPTS
    =========================================================
    --}}
    <script>
        // 1. Script Menu Footer Mobile
        function toggleFooterMenu(menuId, iconId) {
            if (window.innerWidth >= 768) return;
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // 2. Logika Dynamic Scroll: Back to Top & Chatbot Position
        const backToTopBtn = document.getElementById("backToTopBtn");
        const chatToggleBtn = document.getElementById("chat-toggle-btn");
        const chatWindow = document.getElementById("chat-window");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) { 
                // Munculkan tombol Back to Top
                if (backToTopBtn) {
                    backToTopBtn.classList.remove("opacity-0", "invisible", "translate-y-4");
                    backToTopBtn.classList.add("opacity-100", "visible", "translate-y-0");
                }
                
                // Dorong Tombol Chat & Jendela Chat ke atas menghindari tabrakan
                if (chatToggleBtn) chatToggleBtn.classList.add("-translate-y-16");
                if (chatWindow) chatWindow.classList.add("-translate-y-16");
                
            } else {
                // Sembunyikan tombol Back to Top
                if (backToTopBtn) {
                    backToTopBtn.classList.remove("opacity-100", "visible", "translate-y-0");
                    backToTopBtn.classList.add("opacity-0", "invisible", "translate-y-4");
                }
                
                // Kembalikan Tombol Chat & Jendela Chat ke posisi default
                if (chatToggleBtn) chatToggleBtn.classList.remove("-translate-y-16");
                if (chatWindow) chatWindow.classList.remove("-translate-y-16");
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
    </script>

    {{-- Stack Scripts (Untuk script halaman spesifik) --}}
    @stack('scripts')

    {{-- ========================================================
     COOKIE & TERMS CONSENT SYSTEM (BANNER + MODAL)
     ======================================================== --}}
<div x-data="{ 
        showBanner: false,
        showPreferences: false,
        // State untuk menyimpan pilihan checkbox
        prefs: {
            transaction: true,
            shipping: true,
            cancellation: true,
            reviews: true
        },
        init() {
            if (!localStorage.getItem('nexrig_consent')) {
                setTimeout(() => this.showBanner = true, 1500);
            }
        },
        acceptAll() {
            this.prefs = { transaction: true, shipping: true, cancellation: true, reviews: true };
            localStorage.setItem('nexrig_consent', 'all');
            this.showBanner = false;
            this.showPreferences = false;
        },
        acceptSelected() {
            // Menyimpan spesifik opsi mana saja yang dipilih user
            localStorage.setItem('nexrig_consent_prefs', JSON.stringify(this.prefs));
            localStorage.setItem('nexrig_consent', 'selected');
            this.showBanner = false;
            this.showPreferences = false;
        },
        rejectAll() {
            this.prefs = { transaction: false, shipping: false, cancellation: false, reviews: false };
            localStorage.setItem('nexrig_consent', 'rejected');
            this.showBanner = false;
        }
    }" 
    class="no-print">

    {{-- 1. BOTTOM BANNER --}}
    <div x-show="showBanner"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         style="display: none;"
         class="fixed bottom-0 left-0 right-0 z-[9990] bg-[#12121a] border-t border-[#2a2a35] shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
        
        <div class="max-w-[1600px] mx-auto px-4 md:px-8 py-4 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-300 text-center lg:text-left">
                This website uses cookies and local storage to ensure you get the best experience on our website. 
                <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white underline underline-offset-2 transition-colors">Privacy Policy</a>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 shrink-0 w-full lg:w-auto">
                <button @click="showPreferences = true" 
                    class="px-6 py-2.5 border border-gray-400 hover:border-white bg-[#1a1a24] hover:bg-[#252532] text-gray-200 hover:text-white text-xs font-black uppercase tracking-wider transition-colors">
                    Preferences
                </button>
                <button @click="rejectAll()" 
                    class="px-6 py-2.5 bg-primary hover:bg-blue-500text-white text-xs font-black uppercase tracking-wider transition-colors">
                    Reject
                </button>
                <button @click="acceptAll()" 
                    class="px-8 py-2.5 bg-primary hover:bg-blue-500 text-white text-xs font-black uppercase tracking-wider transition-colors shadow-[0_0_15px_rgba(155,81,224,0.4)]">
                    Accept
                </button>
            </div>
        </div>
    </div>

    {{-- 2. PREFERENCES MODAL (Pop-up Detail) --}}
    <div x-show="showPreferences"
         style="display: none;"
         class="fixed inset-0 z-[9999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 lg:p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.outside="showPreferences = false"
             x-show="showPreferences"
             x-transition:enter="transition ease-out duration-400 delay-100"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="w-full max-w-4xl bg-[#0e0e14] border border-[#2a2a35] shadow-[0_20px_60px_rgba(0,0,0,0.8)] flex flex-col max-h-[90vh]">
            
            {{-- Header Modal --}}
            <div class="flex justify-between items-center p-6 border-b border-[#2a2a35] shrink-0">
                <h1 class="text-lg md:text-xl font-bold text-white tracking-wide">Choose Type of Cookies & Terms You Accept</h1>
                <button @click="showPreferences = false" class="w-8 h-8 border border-blue-500/50 hover:bg-blue-500/20 text-white flex items-center justify-center transition-colors group">
                    <span class="material-symbols-outlined text-lg group-hover:rotate-90 transition-transform">close</span>
                </button>
            </div>

            {{-- Content Scroll Area --}}
            <div class="p-6 overflow-y-auto space-y-8 flex-1" style="scrollbar-width: thin; scrollbar-color: #3f3f4e #1a1a24;">
                
                {{-- Point 1: Strictly Required (TIDAK BISA DI-KLIK/DIMATIKAN) --}}
                <div class="opacity-80">
                    <div class="flex items-center gap-3 mb-2 cursor-not-allowed">
                        <span class="material-symbols-outlined text-[#4ade80] text-[20px] font-bold">check</span>
                        <h2 class="text-white font-bold text-sm tracking-wide">Strictly Required Cookies & Local Storage</h2>
                        <span class="ml-auto text-[10px] text-gray-500 font-bold uppercase tracking-widest">Always Active</span>
                    </div>
                    <p class="text-[13px] text-gray-400 leading-relaxed pl-8">
                        Penyimpanan lokal diperlukan agar situs dapat berjalan, seperti menyimpan data Keranjang Belanja (Cart) Anda saat belum login dan mengelola sesi autentikasi. Data ini tidak dapat dinonaktifkan.
                    </p>
                </div>

                {{-- Point 2: Transaksi (INTERAKTIF) --}}
                <div>
                    <button type="button" @click="prefs.transaction = !prefs.transaction" class="flex items-center gap-3 mb-2 cursor-pointer outline-none group w-full text-left">
                        <span class="material-symbols-outlined text-[20px] font-bold transition-colors"
                              :class="prefs.transaction ? 'text-[#4ade80]' : 'text-gray-600 group-hover:text-gray-400'">check</span>
                        <h2 class="font-bold text-sm tracking-wide transition-colors"
                            :class="prefs.transaction ? 'text-white' : 'text-gray-500'">Transaksi & Verifikasi Pembayaran</h2>
                    </button>
                    <p class="text-[13px] leading-relaxed pl-8 transition-opacity duration-300"
                       :class="prefs.transaction ? 'text-gray-400 opacity-100' : 'text-gray-600 opacity-50'">
                        Kami menggunakan Midtrans sebagai gerbang pembayaran. Pesanan akan dibatalkan otomatis jika pembayaran tidak diselesaikan dalam 1x24 jam. NexRig berhak membatalkan pesanan secara sepihak jika terjadi kesalahan sistem pada harga.
                    </p>
                </div>

                {{-- Point 3: Pengiriman (INTERAKTIF) --}}
                <div>
                    <button type="button" @click="prefs.shipping = !prefs.shipping" class="flex items-center gap-3 mb-2 cursor-pointer outline-none group w-full text-left">
                        <span class="material-symbols-outlined text-[20px] font-bold transition-colors"
                              :class="prefs.shipping ? 'text-[#4ade80]' : 'text-gray-600 group-hover:text-gray-400'">check</span>
                        <h2 class="font-bold text-sm tracking-wide transition-colors"
                            :class="prefs.shipping ? 'text-white' : 'text-gray-500'">Akurasi Alamat & Tracking Pengiriman</h2>
                    </button>
                    <p class="text-[13px] leading-relaxed pl-8 transition-opacity duration-300"
                       :class="prefs.shipping ? 'text-gray-400 opacity-100' : 'text-gray-600 opacity-50'">
                        Fitur pelacakan rute Distribution Center (DC) adalah simulasi visual. Pengguna wajib memeriksa kembali kebenaran titik koordinat alamat pengiriman. NexRig tidak bertanggung jawab atas paket yang nyasar akibat kesalahan input pengguna.
                    </p>
                </div>

                {{-- Point 4: Pembatalan (INTERAKTIF) --}}
                <div>
                    <button type="button" @click="prefs.cancellation = !prefs.cancellation" class="flex items-center gap-3 mb-2 cursor-pointer outline-none group w-full text-left">
                        <span class="material-symbols-outlined text-[20px] font-bold transition-colors"
                              :class="prefs.cancellation ? 'text-[#4ade80]' : 'text-gray-600 group-hover:text-gray-400'">check</span>
                        <h2 class="font-bold text-sm tracking-wide transition-colors"
                            :class="prefs.cancellation ? 'text-white' : 'text-gray-500'">Kebijakan Pembatalan Pesanan</h2>
                    </button>
                    <p class="text-[13px] leading-relaxed pl-8 transition-opacity duration-300"
                       :class="prefs.cancellation ? 'text-gray-400 opacity-100' : 'text-gray-600 opacity-50'">
                        Pembatalan pesanan secara mandiri hanya diizinkan jika status pesanan masih "Menunggu Pembayaran" atau "Dikemas". Pesanan tidak dapat dibatalkan dengan alasan apa pun jika status telah berubah menjadi "Dikirim".
                    </p>
                </div>

                {{-- Point 5: Ulasan (INTERAKTIF) --}}
                <div>
                    <button type="button" @click="prefs.reviews = !prefs.reviews" class="flex items-center gap-3 mb-2 cursor-pointer outline-none group w-full text-left">
                        <span class="material-symbols-outlined text-[20px] font-bold transition-colors"
                              :class="prefs.reviews ? 'text-[#4ade80]' : 'text-gray-600 group-hover:text-gray-400'">check</span>
                        <h2 class="font-bold text-sm tracking-wide transition-colors"
                            :class="prefs.reviews ? 'text-white' : 'text-gray-500'">Konten & Ulasan Pengguna</h2>
                    </button>
                    <p class="text-[13px] leading-relaxed pl-8 transition-opacity duration-300"
                       :class="prefs.reviews ? 'text-gray-400 opacity-100' : 'text-gray-600 opacity-50'">
                        Fitur ulasan hanya dapat diakses setelah pesanan selesai. NexRig berhak menghapus ulasan yang mengandung SARA, spam, atau kata kasar tanpa pemberitahuan. Anda memberikan izin kepada kami untuk menggunakan foto ulasan sebagai materi promosi.
                    </p>
                </div>

            </div>

            {{-- Footer Modal / Action Buttons --}}
            <div class="p-6 border-t border-[#2a2a35] shrink-0 bg-[#12121a]">
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                    {{-- Tombol Accept Selected (Menjalankan fungsi acceptSelected() --}}
                    <button @click="acceptSelected()" 
                        class="flex-1 px-6 py-4 bg-primary hover:bg-blue-500 text-white text-sm font-black uppercase tracking-wider transition-colors text-center">
                        Accept Selected
                    </button>
                    {{-- Tombol Accept All (Menjalankan fungsi acceptAll() dan mencentang semua kembali --}}
                    <button @click="acceptAll()" 
                        class="flex-1 px-8 py-4 bg-primary hover:bg-blue-500 text-white text-sm font-black uppercase tracking-wider transition-colors text-center shadow-[0_0_15px_rgba(139,92,246,0.4)]">
                        Accept All
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</body>

</html>