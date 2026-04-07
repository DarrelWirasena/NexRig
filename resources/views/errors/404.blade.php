<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found - NexRig</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    <style> body { font-family: 'Space Grotesk', sans-serif; } </style>
</head>
<body class="bg-[#050014] text-white h-screen flex items-center justify-center relative overflow-hidden selection:bg-primary selection:text-white">
    
    {{-- Background Effects --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20 pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-2xl">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-white/5 border border-white/10 mb-8 shadow-[0_0_30px_rgba(59,130,246,0.15)]">
            <span class="material-symbols-outlined text-6xl text-blue-500">satellite_alt</span>
        </div>
        
        <h1 class="text-7xl md:text-9xl font-black italic tracking-tighter mb-2 text-transparent bg-clip-text bg-gradient-to-br from-white to-gray-500">
            404
        </h1>
        <h2 class="text-xl md:text-2xl font-bold uppercase tracking-[0.2em] text-blue-400 mb-6">
            Signal Lost
        </h2>
        
        <p class="text-gray-400 mb-10 leading-relaxed">
            Sektor yang kamu cari tidak ditemukan di dalam koordinat server kami. Mungkin halamannya sudah dihapus atau kamu salah memasukkan URL.
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-primary hover:bg-blue-600 text-white font-black uppercase tracking-widest text-sm rounded-xl transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] hover:shadow-[0_0_30px_rgba(37,99,235,0.6)] hover:-translate-y-1">
            <span class="material-symbols-outlined text-lg">rocket_launch</span>
            Return to Base
        </a>
    </div>
</body>
</html>