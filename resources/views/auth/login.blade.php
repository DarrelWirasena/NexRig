@extends('layouts.app')

@section('content')
    {{-- Custom CSS untuk halaman ini (bisa dipindah ke CSS global jika mau) --}}
    <style>
        .clip-corner-sm { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%); }
        .input-tech {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-tech:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
            outline: none;
        }
    </style>

    {{-- BACKGROUND WRAPPER --}}
    <div class="relative min-h-screen flex items-center justify-center bg-[#050505] overflow-hidden py-20 px-4">
        
        {{-- Background Effects (Sama seperti Home) --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-primary/20 blur-[120px] rounded-full mix-blend-screen"></div>
        </div>

        {{-- LOGIN CARD --}}
        <div class="relative z-10 w-full max-w-md">
            
            {{-- Header Title --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded border border-primary/30 bg-primary/10 mb-4">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-primary text-[10px] font-bold tracking-[0.2em] uppercase">System Access</span>
                </div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">
                    Identify <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-cyan-400">Yourself</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2">Enter your credentials to access the NexRig mainframe.</p>
            </div>

            {{-- ALERT MESSAGES (Success/Error) --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 text-green-400 text-sm rounded flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 text-red-400 text-sm rounded flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">error</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- FORM CONTAINER --}}
            <div class="bg-[#0a0a0a] border border-white/10 p-8 relative clip-corner-sm backdrop-blur-xl">
                {{-- Decorative Line --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary to-transparent opacity-50"></div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Command</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">alternate_email</span>
                            <input type="email" name="email" id="email" 
                                   class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="pilot@nexrig.com" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Access Code</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">lock</span>
                            <input type="password" name="password" id="password" 
                                   class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="••••••••" required>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    {{-- (Opsional: Kalau mau nambah fitur remember token nanti) --}}
                    {{-- <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded bg-white/10 border-white/20 text-primary focus:ring-primary">
                        <label for="remember_me" class="ml-2 text-sm text-gray-400">Keep session active</label>
                    </div> --}}

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-4 bg-primary hover:bg-white hover:text-black text-white font-bold uppercase tracking-[0.15em] transition-all duration-300 clip-corner-sm shadow-[0_0_20px_rgba(59,130,246,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] flex justify-center items-center gap-2 group">
                        Initialize Session
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">login</span>
                    </button>
                </form>

                {{-- Footer Links --}}
                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-gray-500 text-sm">
                        New Recruit? 
                        <a href="{{ route('register') }}" class="text-white hover:text-primary font-bold transition-colors uppercase tracking-wide ml-1">
                            Deploy Here
                        </a>
                    </p>
                </div>
            </div>
            
            {{-- Decorative Text Bottom --}}
            <div class="flex justify-between text-[10px] text-gray-600 mt-4 uppercase font-mono px-2">
                <span>NexRig OS v2.0</span>
                <span>Secure Connection // TLS 1.3</span>
            </div>
        </div>
    </div>
@endsection