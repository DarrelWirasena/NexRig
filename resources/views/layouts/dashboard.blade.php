<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NexRig') }}</title>

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- GLOBAL DASHBOARD STYLES --}}
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
        
        .clip-card { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
        
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

        /* Sidebar Styles */
        .nav-item.active {
            background-color: #2563eb;
            color: white;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.4);
        }
        .nav-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        
        /* Scrollbar */
        main::-webkit-scrollbar { width: 8px; }
        main::-webkit-scrollbar-track { background: #050014; }
        main::-webkit-scrollbar-thumb { background: #1f1f1f; border-radius: 4px; }
        main::-webkit-scrollbar-thumb:hover { background: #333; }
        
        .no-bounce { overscroll-behavior: none; }
    </style>
</head>
<body class="font-sans antialiased bg-[#050014] text-white">

    <div class="h-screen flex overflow-hidden relative no-bounce">
        
        {{-- Background Elements --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-900/10 blur-[120px] rounded-full"></div>
        </div>

        {{-- Overlay Mobile --}}
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity no-bounce"></div>

        {{-- PANGGIL COMPONENT SIDEBAR --}}
        <x-sidebar />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 h-full overflow-y-auto p-4 md:p-6 lg:p-12 w-full relative z-10 no-bounce scroll-smooth">
            
            {{-- Mobile Toggle Button --}}
            <div class="lg:hidden mb-6 flex items-center justify-between bg-[#0a0a0a] border border-white/10 p-4 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">dashboard</span>
                    <span class="font-bold text-sm uppercase">Menu</span>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:text-blue-500 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            {{-- Slot Konten Halaman --}}
            @yield('content')

        </main>
    </div>

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
</body>
</html>