@extends('layouts.app')

@section('content')
<style>
    /* 1. Tech Corner & Scanline */
    .clip-corner-nex {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%);
    }

    .scanline {
        width: 100%;
        height: 1px;
        background: rgba(168, 85, 247, 0.2);
        /* Purple Scanline */
        position: absolute;
        animation: scan 3s linear infinite;
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

    /* 2. Glow & Input Styling */
    .input-tech {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: white;
        transition: all 0.3s ease;
    }

    .input-tech:focus {
        background: rgba(168, 85, 247, 0.05);
        border-color: #a855f7;
        box-shadow: 0 0 15px rgba(168, 85, 247, 0.15);
        outline: none;
    }

    /* Memastikan layar tidak bisa scroll di monitor standar */
    body,
    html {
        overflow: hidden;
    }
</style>

{{-- FIXED BACKGROUND --}}
<div class="fixed inset-0 z-0 bg-[#050505]">
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    {{-- Glow Ungu untuk Register --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-purple-600/10 blur-[100px] rounded-full"></div>
</div>

{{-- TOMBOL KEMBALI MINIMALIS --}}
<a href="/" class="fixed top-4 left-4 z-50 flex items-center gap-2 text-slate-500 hover:text-white transition-all group sm:top-6 sm:left-6">
    <span class="material-symbols-outlined text-[16px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
    <span class="text-[9px] font-black uppercase tracking-[0.2em] italic">Home</span>
</a>

{{-- MAIN CONTENT WRAPPER --}}
<div class="relative h-screen w-full flex items-center justify-center p-4">

    <div class="relative z-20 w-full max-w-[380px] flex flex-col items-center">

        {{-- HEADER SECTION --}}
        <div class="text-center mb-5 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-purple-500/20 bg-purple-500/5 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                <span class="text-purple-400 text-[8px] font-black uppercase tracking-[0.3em]">New_Operative_Protocol</span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Join The <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-purple-300 to-pink-200"
                    style="-webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 10px rgba(168, 85, 247, 0.5));">
                    Elite Fleet
                </span>
            </h1>
        </div>

        {{-- REGISTER CARD (Sangat Compact) --}}
        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-6 sm:px-8 sm:py-7 clip-corner-nex shadow-2xl overflow-hidden">
            <div class="scanline"></div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3 relative z-30">
                @csrf
                @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif
                {{-- Name Input --}}
                <div class="space-y-1">
                    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Codename</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-purple-500 transition-colors pointer-events-none">badge</span>
                        <input type="text" name="name" value="{{ old('name') }}" autofocus
                            class="w-full pl-12 pr-4 py-2.5 input-tech text-xs rounded-none {{ $errors->has('name') ? '!border-red-500' : '' }}"
                            placeholder="OPERATIVE NAME">
                    </div>
                    @error('name')
                    <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Input --}}
                <div class="space-y-1">
                    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Email Terminal</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px] group-focus-within:text-purple-500 transition-colors pointer-events-none">alternate_email</span>
                        <input type="text" name="email" value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-2.5 input-tech text-xs rounded-none {{ $errors->has('email') ? '!border-red-500' : '' }}"
                            placeholder="you@nexrig.net">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input --}}
                {{-- 🔥 INLINE SMART PASSWORD SYSTEM (Alpine.js) 🔥 --}}
                <div x-data="{ 
                    password: '',
                    confirmPassword: '',
                    showPass: false,
                    showConfirm: false,
                    
                    get hasMinLength() { return this.password.length >= 8; },
                    get hasUpperCase() { return /[A-Z]/.test(this.password); },
                    get hasLowerCase() { return /[a-z]/.test(this.password); },
                    get hasNumber() { return /[0-9]/.test(this.password); },
                    get isAllValid() { return this.hasMinLength && this.hasUpperCase && this.hasLowerCase && this.hasNumber; },
                    get isTyping() { return this.password.length > 0; },
                    
                    get isConfirmTyping() { return this.confirmPassword.length > 0; },
                    get passwordsMatch() { return this.isConfirmTyping && this.password === this.confirmPassword; }
                }" class="space-y-4">

                    {{-- 1. INPUT: MAIN PASSWORD --}}
                    <div class="space-y-1">
                        <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Access Code</label>
                        <div class="relative flex items-center group">
                            {{-- Ikon Kiri --}}
                            <span class="absolute left-4 z-30 material-symbols-outlined text-[18px] transition-colors pointer-events-none"
                                  :class="isAllValid ? 'text-green-500' : (isTyping ? 'text-red-500' : 'text-slate-600')">
                                  terminal
                            </span>
                            
                            {{-- Input Field --}}
                            <input :type="showPass ? 'text' : 'password'" name="password" x-model="password"
                                class="w-full pl-12 pr-10 py-2.5 input-tech text-xs rounded-none tracking-widest transition-all duration-300"
                                :class="isAllValid ? 'border-green-500/50 focus:border-green-500 focus:shadow-[0_0_15px_rgba(34,197,94,0.2)]' : (isTyping ? 'border-red-500/50 focus:border-red-500' : '')"
                                placeholder="••••••••">
                                
                            {{-- Tombol Mata --}}
                            <button type="button" @click="showPass = !showPass" tabindex="-1"
                                    class="absolute right-3 z-30 text-slate-600 hover:text-white focus:outline-none transition-colors duration-300">
                                <span class="material-symbols-outlined text-[13px]" x-text="showPass ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                        
                        {{-- 🔥 INDIKATOR PASSWORD INLINE (MINIMALIS) 🔥 --}}
                        <div class="mt-1.5 flex flex-wrap gap-x-3 gap-y-1 px-1">
                            {{-- Syarat 1 --}}
                            <span class="flex items-center gap-0.5 text-[7px] font-black uppercase tracking-[0.1em] transition-colors" 
                                  :class="hasMinLength ? 'text-green-500' : (isTyping ? 'text-red-500' : 'text-slate-600')">
                                <span class="material-symbols-outlined text-[10px]" x-text="hasMinLength ? 'check' : (isTyping ? 'close' : 'remove')"></span> 8+ Chars
                            </span>
                            {{-- Syarat 2 --}}
                            <span class="flex items-center gap-0.5 text-[7px] font-black uppercase tracking-[0.1em] transition-colors" 
                                  :class="hasUpperCase ? 'text-green-500' : (isTyping ? 'text-red-500' : 'text-slate-600')">
                                <span class="material-symbols-outlined text-[10px]" x-text="hasUpperCase ? 'check' : (isTyping ? 'close' : 'remove')"></span> 1 Upper
                            </span>
                            {{-- Syarat 3 --}}
                            <span class="flex items-center gap-0.5 text-[7px] font-black uppercase tracking-[0.1em] transition-colors" 
                                  :class="hasLowerCase ? 'text-green-500' : (isTyping ? 'text-red-500' : 'text-slate-600')">
                                <span class="material-symbols-outlined text-[10px]" x-text="hasLowerCase ? 'check' : (isTyping ? 'close' : 'remove')"></span> 1 Lower
                            </span>
                            {{-- Syarat 4 --}}
                            <span class="flex items-center gap-0.5 text-[7px] font-black uppercase tracking-[0.1em] transition-colors" 
                                  :class="hasNumber ? 'text-green-500' : (isTyping ? 'text-red-500' : 'text-slate-600')">
                                <span class="material-symbols-outlined text-[10px]" x-text="hasNumber ? 'check' : (isTyping ? 'close' : 'remove')"></span> 1 Number
                            </span>
                        </div>
                    </div>

                    {{-- 2. INPUT: CONFIRM PASSWORD --}}
                    <div class="space-y-1">
                        <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Confirm Code</label>
                        <div class="relative flex items-center group">
                            {{-- Ikon Kiri --}}
                            <span class="absolute left-4 z-30 material-symbols-outlined text-[18px] transition-colors pointer-events-none"
                                  :class="passwordsMatch ? 'text-green-500' : (isConfirmTyping ? 'text-red-500' : 'text-slate-600')">
                                lock_reset
                            </span>
                            
                            {{-- Input Field --}}
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="confirmPassword"
                                class="w-full pl-12 pr-10 py-2.5 input-tech text-xs rounded-none tracking-widest transition-all duration-300"
                                :class="passwordsMatch ? 'border-green-500/50 focus:border-green-500 focus:shadow-[0_0_15px_rgba(34,197,94,0.2)]' : (isConfirmTyping ? 'border-red-500/50 focus:border-red-500' : '')"
                                placeholder="••••••••">
                                
                            {{-- Tombol Mata --}}
                            <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                                    class="absolute right-3 z-30 text-slate-600 hover:text-white focus:outline-none transition-colors duration-300">
                                <span class="material-symbols-outlined text-[13px]" x-text="showConfirm ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                        
                        {{-- Peringatan Mismatch --}}
                        <div x-show="isConfirmTyping && !passwordsMatch" style="display: none;"
                             class="text-red-500 text-[7px] font-black uppercase tracking-[0.1em] ml-1 mt-1 flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[10px]">close</span> Codes do not match
                        </div>
                    </div>

                </div>

                <div class="pt-3">
                    <button type="submit"
                        class="w-full py-4 bg-purple-600 hover:bg-white text-white hover:text-black font-black uppercase italic tracking-[0.2em] text-[9px] transition-all duration-500 clip-corner-nex shadow-lg shadow-purple-900/20 flex items-center justify-center gap-2 group">
                        <span>Deploy operative</span>
                        <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">rocket_launch</span>
                    </button>
                </div>
            </form>

            <div class="mt-5 pt-5 border-t border-white/5 text-center relative z-30">
                <p class="text-slate-600 text-[9px] font-bold uppercase tracking-[0.1em]">
                    Already have access?
                    <a href="{{ route('login') }}" class="text-white hover:text-purple-500 transition-colors ml-1 border-b border-white/20 pb-0.5">Access Mainframe</a>
                </p>
            </div>
        </div>

        {{-- FOOTER INFO --}}
        <div class="w-full mt-4 flex justify-between items-center px-4 text-[8px] font-mono text-slate-700 uppercase tracking-[0.1em] opacity-50">
            <span>Reg_ID: NX-09</span>
            <span>Security: Active</span>
        </div>
    </div>
</div>
@endsection