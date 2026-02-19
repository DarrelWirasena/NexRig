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
{{-- GANTI overflow-x-hidden MENJADI overflow-x-clip --}}
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-clip min-h-screen pt-20 flex flex-col">
    
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
    {{-- 3. Flash Message Data (Hidden Bridge to JS) --}}
    <div id="flash-messages" 
         data-success="{{ session('success') }}" 
         data-error="{{ session('error') }}" 
         data-validation="{{ $errors->any() ? $errors->first() : '' }}"
         class="hidden">
    </div>
    
    {{-- 
    =========================================================
    CHATBOT (SAKA Assistant)
    Taruh sebelum @vite di bagian bawah app.blade.php
    =========================================================
    --}}

    {{-- Floating Button --}}
    <button id="chat-toggle-btn" 
            onclick="toggleChat()" 
            class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-primary rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-white">chat</span>
    </button>

    {{-- Chat Window --}}
    <div id="chat-window" 
        class="chat-closed chat-hide fixed bottom-24 right-6 z-50 w-80 border border-white/10 rounded-2xl shadow-2xl flex flex-col bg-[#121212]" 
        style="height: 420px">
        
        {{-- Header --}}
        <div class="p-4 border-b border-white/10 flex items-center justify-between shrink-0">
           <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <p class="text-white font-bold text-sm">SAKA Assistant</p>
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


    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    {{-- 2. ASSETS (Vite) --}}
    {{-- Memanggil app.css dan app.js yang sudah kita bersihkan --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack Scripts (Untuk script halaman spesifik) --}}
    @stack('scripts')
</script>
</body>
</html>