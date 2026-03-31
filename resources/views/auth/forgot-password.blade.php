@extends('layouts.app')

@section('content')
<style>
    .clip-corner-nex {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%);
    }

    .scanline-red {
        width: 100%;
        height: 1px;
        background: rgba(239, 68, 68, 0.3);
        position: absolute;
        animation: scan 2s linear infinite;
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
        background: rgba(239, 68, 68, 0.05);
        border-color: #ef4444;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
        outline: none;
    }

    body,
    html {
        overflow: hidden;
    }
</style>

<div class="fixed inset-0 z-0 bg-[#050505]">
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-red-600/10 blur-[100px] rounded-full"></div>
</div>

<a href="{{ route('login') }}" class="fixed top-4 left-4 z-50 flex items-center gap-2 text-slate-500 hover:text-white transition-all group">
    <span class="material-symbols-outlined text-[16px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
    <span class="text-[9px] font-black uppercase tracking-[0.2em] italic">Abort</span>
</a>

<div class="relative h-screen w-full flex items-center justify-center p-4">
    <div class="relative z-20 w-full max-w-[360px] flex flex-col items-center">

        <div class="text-center mb-6 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-red-500/20 bg-red-500/5 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span class="text-red-400 text-[8px] font-black uppercase tracking-[0.3em]">System_Recovery</span>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Lost <span class="text-red-400">Access?</span>
            </h1>
        </div>

        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-6 sm:p-8 clip-corner-nex shadow-2xl overflow-hidden">
            <div class="scanline-red"></div>

            {{-- KONDISI 1: JIKA OTP BELUM DIKIRIM (TAMPILKAN INPUT EMAIL) --}}
            @if(!session('otp_sent'))
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4 relative z-30">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] ml-1">Identify Target Email</label>
                    <div class="relative flex items-center group">
                        <span class="absolute left-4 z-30 text-slate-600 material-symbols-outlined text-[18px]">alternate_email</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-12 pr-4 py-3 input-tech text-xs rounded-none" placeholder="user@nexrig.net">
                    </div>
                    @error('email') <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-4 bg-red-600 hover:bg-white text-white hover:text-black font-black uppercase tracking-[0.2em] text-[9px] transition-all duration-500 clip-corner-nex shadow-lg mt-4">
                    Request Recovery Code
                </button>
            </form>

            {{-- KONDISI 2: JIKA OTP SUDAH DIKIRIM (TAMPILKAN INPUT OTP DENGAN AUTO-SUBMIT) --}}
            @else
            <form id="verify-form" method="POST" action="{{ route('password.verify') }}" class="space-y-4 relative z-30">
                @csrf
                <div class="space-y-1.5 text-center">
                    <label class="block text-[10px] font-black text-green-400 uppercase tracking-[0.2em] mb-3">Code Sent to Terminal</label>
                    <input type="text" id="otp-auto-submit" name="otp" required autofocus maxlength="6" autocomplete="off"
                        class="w-full py-4 input-tech font-bold rounded-none text-center tracking-[0.5em] text-lg @error('otp') border-red-500/60 @enderror"
                        placeholder="------">
                    @error('otp') <p class="text-red-500 text-[9px] font-bold uppercase tracking-wider mt-2">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="hidden">Verify</button>
            </form>

            {{-- FITUR RESEND OTP BARU --}}
            <div class="mt-6 text-center relative z-30 border-t border-white/5 pt-4">
                <form action="{{ route('otp.resend') }}" method="POST" id="resend-form">
                    @csrf
                    {{-- PENANDA BAHWA INI RESEND UNTUK RESET --}}
                    <input type="hidden" name="type" value="reset">
                    
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest mb-2">Signal Lost?</p>
                    <button type="submit" id="resend-btn" disabled
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-red-500 hover:text-white disabled:text-slate-700 transition-colors">
                        Resend Code <span id="timer">(60s)</span>
                    </button>
                </form>
            </div>

            {{-- Tombol Cancel jika OTP tidak masuk / ingin ganti email --}}
            <div class="mt-4 text-center relative z-30">
                <a href="{{ route('login') }}" class="text-[9px] text-slate-500 hover:text-red-400 uppercase tracking-widest font-bold transition-colors">Abort & Return to Login</a>
            </div>

            <script>
                // Script Auto Submit OTP
                document.getElementById('otp-auto-submit').addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        this.style.opacity = '0.5';
                        document.getElementById('verify-form').submit();
                    }
                });

                // Script Cooldown Resend OTP
                let timeLeft = 60; // Jeda 60 detik
                const timerElem = document.getElementById('timer');
                const resendBtn = document.getElementById('resend-btn');

                const countdown = setInterval(() => {
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        timerElem.textContent = "";
                        resendBtn.disabled = false;
                    } else {
                        timerElem.textContent = `(${timeLeft}s)`;
                    }
                }, 1000);
            </script>
            @endif
        </div>
    </div>
</div>
@endsection