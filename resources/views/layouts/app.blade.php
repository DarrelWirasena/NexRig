<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'NexRig - High Performance Gaming PCs' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1337ec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101322",
                        "surface-dark": "#191e33",
                        "border-dark": "#232948",
                        "text-secondary": "#929bc9"
                    },
                    fontFamily: { "display": ["Space Grotesk", "sans-serif"] }
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden min-h-screen flex flex-col">
    
    {{-- Pastikan file resources/views/components/navbar.blade.php ada --}}
    <x-navbar />

    <main class="flex-grow flex flex-col items-center w-full">
  
        
        {{-- INI KUNCI PERUBAHANNYA: --}}
        @yield('content')
        
    </main>
    {{-- 
        =========================================================
        TOAST NOTIFICATION (POP UP OTOMATIS)
        =========================================================
    --}}
    @if(session('success'))
        <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] flex items-center gap-4 bg-[#0a0a0a] border border-blue-600/50 text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(37,99,235,0.2)] transform transition-all duration-500 translate-y-0 opacity-100">
            
            {{-- Icon Check --}}
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600/20 rounded-full text-blue-500">
                <span class="material-symbols-outlined text-xl">check</span>
            </div>

            {{-- Text Message --}}
            <div>
                <h4 class="font-bold text-sm text-blue-500 uppercase tracking-wider">Success</h4>
                <p class="text-gray-300 text-xs mt-0.5">{{ session('success') }}</p>
            </div>

            {{-- Close Button (Manual) --}}
            <button onclick="closeToast()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>

            {{-- Progress Bar (Animasi Durasi) --}}
            <div class="absolute bottom-0 left-0 h-[2px] bg-blue-600 transition-all duration-[3000ms] ease-linear w-full" id="toast-progress"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast-notification');
                const progress = document.getElementById('toast-progress');

                if (toast) {
                    // 1. Mulai animasi progress bar mengecil sampai 0
                    setTimeout(() => {
                        progress.style.width = '0%';
                    }, 100);

                    // 2. Set timer untuk menghilangkan toast setelah 3 detik
                    setTimeout(() => {
                        closeToast();
                    }, 3000);
                }
            });

            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    // Efek menghilang (turun ke bawah & transparan)
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    
                    // Hapus dari DOM setelah animasi selesai
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }
            }
        </script>
    @endif


    {{-- Pastikan file resources/views/components/footer.blade.php ada --}}
    <x-footer /> 
</body>
</html>