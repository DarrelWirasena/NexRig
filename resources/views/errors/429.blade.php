<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 Too Many Requests - NexRig</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    <style> body { font-family: 'Space Grotesk', sans-serif; } </style>
</head>
<body class="bg-[#050014] text-white h-screen flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-600/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 text-center px-4 max-w-2xl">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-purple-500/10 border border-purple-500/20 mb-8 animate-pulse">
            <span class="material-symbols-outlined text-6xl text-purple-500">front_hand</span>
        </div>
        
        <h1 class="text-7xl md:text-9xl font-black italic tracking-tighter mb-2 text-transparent bg-clip-text bg-gradient-to-br from-purple-400 to-purple-900">
            429
        </h1>
        <h2 class="text-xl md:text-2xl font-bold uppercase tracking-[0.2em] text-purple-400 mb-6">
            Rate Limit Exceeded
        </h2>
        
        <p class="text-gray-400 mb-10 leading-relaxed">
            Sistem pertahanan otomatis kami mendeteksi terlalu banyak aktivitas dari jaringanmu dalam waktu singkat. Aksesmu dibatasi sementara untuk mencegah tindakan eksploitasi. Silakan tunggu beberapa menit sebelum mencoba lagi.
        </p>

        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-black uppercase tracking-widest text-sm rounded-xl transition-all hover:border-white/30">
            <span class="material-symbols-outlined text-lg">refresh</span>
            Coba Lagi Nanti
        </a>
    </div>
</body>
</html>