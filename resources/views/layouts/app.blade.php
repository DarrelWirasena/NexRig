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
        
        {{-- Flash Message (Login/Cart Success) --}}
        @if(session('success'))
            <div class="w-full bg-green-500/10 border-b border-green-500/20 text-green-400 px-4 py-3 text-center backdrop-blur-md">
                <p class="font-bold tracking-wide text-sm uppercase">/// SYSTEM NOTIFICATION: {{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="w-full bg-red-500/10 border-b border-red-500/20 text-red-400 px-4 py-3 text-center backdrop-blur-md">
                <p class="font-bold tracking-wide text-sm uppercase">/// SYSTEM ERROR: {{ session('error') }}</p>
            </div>
        @endif
        
        {{-- INI KUNCI PERUBAHANNYA: --}}
        @yield('content')
        
    </main>

    {{-- Pastikan file resources/views/components/footer.blade.php ada --}}
    <x-footer /> 
</body>
</html>