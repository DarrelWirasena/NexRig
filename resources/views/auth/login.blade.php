@extends('layouts.app')

@section('content')
<style>
    .clip-corner-nex { 
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%); 
    }
    
    .scanline {
        width: 100%;
        height: 1px;
        background: rgba(37, 99, 235, 0.2);
        position: absolute;
        animation: scan 3s linear infinite;
        z-index: 10;
        pointer-events: none;
    }

    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }

    .input-tech {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: white;
        transition: all 0.3s ease;
    }

    .input-tech:focus {
        background: rgba(37, 99, 235, 0.05);
        border-color: #2563eb;
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.15);
        outline: none;
    }

    /* Memastikan layar tidak bisa scroll */
    body, html {
        overflow: hidden;
    }
</style>

{{-- FIXED BACKGROUND --}}
<div class="fixed inset-0 z-0 bg-[#050505]">
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-blue-600/10 blur-[100px] rounded-full"></div>
</div>

{{-- TOMBOL KEMBALI (Dikecilkan) --}}
<a href="/" class="fixed top-6 left-6 z-50 flex items-center gap-2 text-slate-500 hover:text-white transition-all group">
    <span class="material-symbols-outlined text-[16px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
    <span class="text-[9px] font-black uppercase tracking-[0.2em] italic">Home</span>
</a>

{{-- MAIN CONTENT --}}
<div class="relative h-screen w-full flex items-center justify-center p-4">
    
    <div class="relative z-20 w-full max-w-[360px] flex flex-col items-center">
        
        {{-- HEADER SECTION (Dibuat Lebih Rapat) --}}
        <div class="text-center mb-6 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-blue-500/20 bg-blue-500/5 mb-4">
                <span class="w-1 h-1 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-blue-500 text-[8px] font-black uppercase tracking-[0.3em]">Auth_Required</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Identify <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-200 to-cyan-100"
                      style="-webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.5));">
                    Yourself
                </span>
            </h1>
        </div>

        {{-- LOGIN CARD (Padding dikurangi dari 12 ke 8) --}}
        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-6 sm:p-8 clip-corner-nex shadow-2xl overflow-hidden">
            <div class="scanline"></div>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-4 relative z-30">
                @csrf

              {{-- Input Email --}}
<div class="space-y-1.5">
    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Email Command</label>
    <div class="relative flex items-center group">
        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-blue-500 transition-colors pointer-events-none">
            alternate_email
        </span>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full pl-12 pr-4 py-3 input-tech text-xs rounded-none relative z-20 @error('email') border-red-500/60 @enderror" 
               placeholder="user@nexrig.net">
    </div>
    @error('email')
        <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Input Password --}}
<div class="space-y-1.5">
    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Access Code</label>
    <div class="relative flex items-center group">
        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-blue-500 transition-colors pointer-events-none">
            terminal
        </span>
        <input type="password" name="password" required 
               class="w-full pl-12 pr-4 py-3 input-tech text-xs rounded-none tracking-widest relative z-20 @error('password') border-red-500/60 @enderror" 
               placeholder="••••••••">
    </div>
    @error('password')
        <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
    @enderror
</div>
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-4 bg-blue-600 hover:bg-white text-white hover:text-black font-black uppercase italic tracking-[0.2em] text-[9px] transition-all duration-500 clip-corner-nex shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 group">
                        <span>Initialize Session</span>
                        <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">bolt</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-white/5 text-center relative z-30">
                <p class="text-slate-600 text-[9px] font-bold uppercase tracking-[0.1em]">
                    New Pilot? 
                    <a href="{{ route('register') }}" class="text-white hover:text-blue-500 transition-colors ml-1 border-b border-white/20 pb-0.5">Deploy New Identity</a>
                </p>
            </div>
        </div>

        {{-- FOOTER MINIMALIS --}}
        <div class="w-full mt-5 flex justify-between items-center px-4 text-[8px] font-mono text-slate-700 uppercase tracking-[0.1em] opacity-50">
            <span>Secure_Conn</span>
            <span>TLS_v1.3</span>
        </div>
    </div>
</div>
@endsection