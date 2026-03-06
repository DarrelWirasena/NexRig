@extends('layouts.app')

@section('content')
<style>
    .clip-corner-nex {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%);
    }

    .scanline {
        width: 100%;
        height: 1px;
        background: rgba(6, 182, 212, 0.3);
        /* Cyan Scanline */
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
        text-align: center;
        font-size: 1.5rem;
        letter-spacing: 0.5em;
    }

    .input-tech:focus {
        background: rgba(6, 182, 212, 0.05);
        border-color: #06b6d4;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
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
    {{-- Glow Cyan untuk Verification --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-cyan-600/10 blur-[100px] rounded-full"></div>
</div>

{{-- MAIN CONTENT --}}
<div class="relative h-screen w-full flex items-center justify-center p-4">

    <div class="relative z-20 w-full max-w-[360px] flex flex-col items-center">

        {{-- HEADER SECTION --}}
        <div class="text-center mb-6 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-cyan-500/30 bg-cyan-500/10 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-cyan-400 text-[8px] font-black uppercase tracking-[0.3em]">Verification_Pending</span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Verify <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-cyan-200 to-teal-200"
                    style="-webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.5));">
                    Identity
                </span>
            </h1>
            <p class="text-slate-400 text-[9px] uppercase tracking-widest mt-4">6-Digit code sent to your terminal</p>
        </div>

        {{-- OTP CARD --}}
        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-6 sm:p-8 clip-corner-nex shadow-2xl overflow-hidden">
            <div class="scanline"></div>

            <form method="POST" action="{{ route('otp.process') }}" class="space-y-4 relative z-30">
                @csrf

                {{-- Input OTP --}}
                <div class="space-y-2">
                    <input type="text" name="otp" required autofocus maxlength="6" autocomplete="off"
                        class="w-full py-4 input-tech font-bold rounded-none relative z-20 @error('otp') border-red-500/60 @enderror"
                        placeholder="------">

                    @error('otp')
                    <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider text-center mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 bg-cyan-600 hover:bg-white text-white hover:text-black font-black uppercase italic tracking-[0.2em] text-[9px] transition-all duration-500 clip-corner-nex shadow-lg shadow-cyan-900/20 flex items-center justify-center gap-2 group">
                        <span>Authenticate</span>
                        <span class="material-symbols-outlined text-[16px] group-hover:scale-110 transition-transform">fingerprint</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- FOOTER INFO --}}
        <div class="w-full mt-5 flex justify-between items-center px-4 text-[8px] font-mono text-slate-700 uppercase tracking-[0.1em] opacity-50">
            <span>Status: Awaiting Input</span>
            <span>Timeout: 05:00</span>
        </div>
    </div>
</div>
@endsection