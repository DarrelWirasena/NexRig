@extends('layouts.app')

@section('content')
<style>
    .clip-corner-nex {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%);
    }

    .scanline-amber {
        width: 100%;
        height: 1px;
        background: rgba(245, 158, 11, 0.3);
        position: absolute;
        animation: scan 2.5s linear infinite;
        z-index: 10;
        pointer-events: none;
    }

    @keyframes scan {
        0% {
            top: 0;
        }

        100% {
            top: 100%;
        }
    }

    .input-tech {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: white;
        transition: all 0.3s ease;
    }

    .input-tech:focus {
        background: rgba(245, 158, 11, 0.05);
        border-color: #f59e0b;
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.15);
        outline: none;
    }

    body,
    html {
        overflow: hidden;
    }
</style>

{{-- FIXED BACKGROUND --}}
<div class="fixed inset-0 z-0 bg-[#050505]">
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    {{-- Glow Amber untuk Override --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-amber-600/10 blur-[100px] rounded-full"></div>
</div>

{{-- TOMBOL KEMBALI / BATAL --}}
<a href="{{ route('login') }}" class="fixed top-4 left-4 z-50 flex items-center gap-2 text-slate-500 hover:text-white transition-all group sm:top-6 sm:left-6">
    <span class="material-symbols-outlined text-[16px] group-hover:-translate-x-1 transition-transform">close</span>
    <span class="text-[9px] font-black uppercase tracking-[0.2em] italic">Cancel Override</span>
</a>

{{-- MAIN CONTENT --}}
<div class="relative h-screen w-full flex items-center justify-center p-4">
    <div class="relative z-20 w-full max-w-[360px] flex flex-col items-center">

        {{-- HEADER SECTION --}}
        <div class="text-center mb-6 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-amber-500/20 bg-amber-500/5 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-amber-400 text-[8px] font-black uppercase tracking-[0.3em]">Identity_Verified</span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Override <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-amber-200 to-yellow-200"
                    style="-webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.5));">
                    Protocol
                </span>
            </h1>
            <p class="text-slate-400 text-[9px] uppercase tracking-widest mt-4">Establish new access code</p>
        </div>

        {{-- FORM CARD --}}
        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-6 sm:p-8 clip-corner-nex shadow-2xl overflow-hidden">
            <div class="scanline-amber"></div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4 relative z-30">
                @csrf

                {{-- Input New Password --}}
                <div class="space-y-1.5">
                    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">New Access Code</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-amber-500 transition-colors">key</span>
                        <input type="password" name="password" required autofocus
                            class="w-full pl-12 pr-4 py-3 input-tech text-xs rounded-none tracking-widest" placeholder="••••••••">
                    </div>
                    @error('password') <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Input Confirm Password --}}
                <div class="space-y-1.5">
                    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Confirm New Code</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-amber-500 transition-colors">lock_reset</span>
                        <input type="password" name="password_confirmation" required
                            class="w-full pl-12 pr-4 py-3 input-tech text-xs rounded-none tracking-widest" placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-amber-600 hover:bg-white text-white hover:text-black font-black uppercase tracking-[0.2em] text-[9px] transition-all duration-500 clip-corner-nex shadow-lg flex justify-center items-center gap-2 group">
                        <span>Initialize Override</span>
                        <span class="material-symbols-outlined text-[14px] group-hover:rotate-180 transition-transform duration-500">sync</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- FOOTER INFO --}}
        <div class="w-full mt-5 flex justify-between items-center px-4 text-[8px] font-mono text-slate-700 uppercase tracking-[0.1em] opacity-50">
            <span>Conn: Secure</span>
            <span>Auth: Granted</span>
        </div>
    </div>
</div>
@endsection