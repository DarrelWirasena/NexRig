<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 System Failure - NexRig</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    <style> body { font-family: 'Space Grotesk', sans-serif; } </style>
</head>
<body class="bg-[#050014] text-white h-screen flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-600/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-2xl">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-amber-500/10 border border-amber-500/20 mb-8">
            <span class="material-symbols-outlined text-6xl text-amber-500">warning</span>
        </div>
        
        <h1 class="text-7xl md:text-9xl font-black italic tracking-tighter mb-2 text-transparent bg-clip-text bg-gradient-to-br from-amber-400 to-amber-900">
            500
        </h1>
        <h2 class="text-xl md:text-2xl font-bold uppercase tracking-[0.2em] text-amber-400 mb-6">
            System Failure
        </h2>
        
        <p class="text-gray-400 mb-10 leading-relaxed">
            Terjadi anomali fatal pada server inti (Core Server) kami. Para teknisi (Engineers) sedang bekerja keras untuk menstabilkan kembali sistem. Silakan coba beberapa saat lagi.
        </p>

        <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-8 py-4 bg-amber-500/20 border border-amber-500/30 hover:bg-amber-500 text-amber-500 hover:text-black font-black uppercase tracking-widest text-sm rounded-xl transition-all shadow-[0_0_20px_rgba(245,158,11,0.1)] hover:shadow-[0_0_30px_rgba(245,158,11,0.4)]">
            <span class="material-symbols-outlined text-lg">refresh</span>
            Reboot System
        </button>
    </div>
</body>
</html>