<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NexRig') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Custom Styles Stack --}}
    @stack('styles')

    <style>
        /* Custom Utilities */
        main::-webkit-scrollbar { width: 8px; }
        main::-webkit-scrollbar-track { background: #050014; }
        main::-webkit-scrollbar-thumb { background: #1f1f1f; border-radius: 4px; }
        main::-webkit-scrollbar-thumb:hover { background: #333; }
        
        .no-bounce { overscroll-behavior: none; overscroll-behavior-y: none; }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }

        /* Input Styles */
        .input-tech {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-tech:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #2563eb;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
            outline: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#050014] text-white selection:bg-blue-600 selection:text-white overflow-hidden">

    {{-- 
        LAYOUT UTAMA (SIDEBAR ONLY)
        h-screen: Tinggi 100% layar browser.
        flex: Membuat layout menyamping (Sidebar di Kiri, Konten di Kanan).
        overflow-hidden: Mencegah scrollbar ganda di body.
    --}}
    <div class="h-screen flex w-full relative no-bounce">

        {{-- Background Elements (Global) --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-900/10 blur-[120px] rounded-full"></div>
        </div>

        {{-- Overlay Mobile --}}
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity no-bounce"></div>

        {{-- 1. SIDEBAR (Component) --}}
        {{-- Pastikan file components/sidebar.blade.php sudah Anda buat sesuai langkah sebelumnya --}}
        <x-sidebar />

        {{-- 
            2. MAIN CONTENT AREA
            flex-1: Mengambil sisa lebar layar (setelah dikurangi lebar sidebar).
            h-full: Mengikuti tinggi layar.
            overflow-y-auto: Scrollbar HANYA aktif di area ini.
        --}}
        <main class="flex-1 h-full overflow-y-auto p-4 md:p-6 lg:p-12 relative z-10 no-bounce scroll-smooth w-full">
            
            {{-- Tombol Mobile Menu (Hanya muncul di Layar Kecil/HP) --}}
            <div class="lg:hidden mb-6 flex items-center justify-between bg-[#0a0a0a] border border-white/10 p-4 rounded-xl sticky top-0 z-50 backdrop-blur-md">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">manage_accounts</span>
                    <span class="font-bold text-sm uppercase">Dashboard</span>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:text-blue-500 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            {{-- ISI KONTEN (Profile, Orders, dll) --}}
            @yield('content')

        </main>

    </div>


    {{-- 
        =========================================================
        GLOBAL TOAST NOTIFICATION
        =========================================================
    --}}
    @if(session('success'))
        <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] flex items-center gap-4 bg-[#0a0a0a] border border-blue-600/50 text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(37,99,235,0.2)] transform transition-all duration-500 translate-y-0 opacity-100 backdrop-blur-md">
            
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600/20 rounded-full text-blue-500 shrink-0">
                <span class="material-symbols-outlined text-xl">check</span>
            </div>

            <div>
                <h4 class="font-bold text-sm text-blue-500 uppercase tracking-wider">Success</h4>
                <p class="text-gray-300 text-xs mt-0.5">{{ session('success') }}</p>
            </div>

            <button onclick="closeToast()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>

            <div class="absolute bottom-0 left-0 h-[2px] bg-blue-600 transition-all duration-[3000ms] ease-linear w-full rounded-b-xl" id="toast-progress"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast-notification');
                const progress = document.getElementById('toast-progress');

                if (toast) {
                    setTimeout(() => { progress.style.width = '0%'; }, 100);
                    setTimeout(() => { closeToast(); }, 3000);
                }
            });

            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    setTimeout(() => { toast.remove(); }, 500);
                }
            }
        </script>
    @endif

    {{-- SCRIPT TOGGLE SIDEBAR --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>