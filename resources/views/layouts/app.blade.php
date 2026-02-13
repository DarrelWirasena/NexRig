<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'NexRig - High Performance Gaming PCs' }}</title>
    
    {{-- 1. FONTS & ICONS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@1,900&family=Space+Grotesk:wght@300;400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    {{-- 3. RUNTIME CONFIG (Wajib ada di head untuk Tailwind CDN/Plugins) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1337ec",
                        "background-dark": "#101322",
                        "border-dark": "#232948",
                    },
                    fontFamily: { "display": ["Space Grotesk", "sans-serif"] }
                },
            },
        }
    </script>
    
    @stack('styles')
</head>

<body class="bg-[#f6f6f8] dark:bg-[#101322] text-slate-900 dark:text-white font-display overflow-x-hidden min-h-screen pt-20 flex flex-col">
    
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
    {{-- Logika JS-nya sudah ditangani Alpine, Style-nya sudah di app.css --}}
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

    {{-- 3. Toast Notification --}}
    {{-- Kita asumsikan logic JS toast sudah ada di app.js --}}
    @if(session('success') || session('error'))
        @php 
            $isSuccess = session('success');
            $message = $isSuccess ?: session('error');
            $themeColor = $isSuccess ? 'blue-600' : 'red-600';
            $icon = $isSuccess ? 'check' : 'priority_high';
        @endphp

        <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] flex items-center gap-4 bg-[#0a0a0a] border border-{{ $themeColor }}/50 text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-500 translate-y-0 opacity-100">
            <div class="flex items-center justify-center w-8 h-8 bg-{{ $themeColor }}/20 rounded-full text-{{ $themeColor }}">
                <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
            </div>
            <div>
                <h4 class="font-bold text-sm text-{{ $themeColor }} uppercase tracking-wider">{{ $isSuccess ? 'Success' : 'Error' }}</h4>
                <p class="text-gray-300 text-xs mt-0.5">{{ $message }}</p>
            </div>
            <button onclick="closeToast()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
            <div class="absolute bottom-0 left-0 h-[2px] bg-{{ $themeColor }} transition-all duration-[3000ms] ease-linear w-full" id="toast-progress"></div>
        </div>
    @endif
    
    {{-- 2. ASSETS (Vite) --}}
    {{-- Memanggil app.css dan app.js yang sudah kita bersihkan --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack Scripts (Untuk script halaman spesifik) --}}
    @stack('scripts')
</body>
</html>