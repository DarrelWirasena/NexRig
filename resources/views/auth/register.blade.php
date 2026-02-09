<x-layouts.app>
    {{-- Menggunakan style yang sama dengan Login agar konsisten --}}
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

    <div class="relative min-h-screen flex items-center justify-center bg-[#050505] overflow-hidden py-20 px-4">
        
        {{-- Background Effects --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            {{-- Warna ungu sedikit untuk membedakan dengan login --}}
            <div class="absolute bottom-0 right-1/2 translate-x-1/2 w-[800px] h-[500px] bg-purple-600/20 blur-[120px] rounded-full mix-blend-screen"></div>
        </div>

        {{-- REGISTER CARD --}}
        <div class="relative z-10 w-full max-w-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded border border-purple-500/30 bg-purple-500/10 mb-4">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-purple-400 text-[10px] font-bold tracking-[0.2em] uppercase">New Operator</span>
                </div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">
                    Join The <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600">Elite</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2">Create your profile to start configuring your rig.</p>
            </div>

            {{-- FORM CONTAINER --}}
            <div class="bg-[#0a0a0a] border border-white/10 p-8 relative clip-corner-sm backdrop-blur-xl">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent opacity-50"></div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Name Input --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Codename / Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">badge</span>
                            <input type="text" name="name" class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="Your Name" value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email Input --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">alternate_email</span>
                            <input type="email" name="email" class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="you@example.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">lock</span>
                            <input type="password" name="password" class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="Min. 8 characters" required>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined">lock_reset</span>
                            <input type="password" name="password_confirmation" class="w-full pl-12 pr-4 py-3 input-tech rounded-sm" 
                                   placeholder="Re-enter password" required>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-4 bg-purple-600 hover:bg-white hover:text-black text-white font-bold uppercase tracking-[0.15em] transition-all duration-300 clip-corner-sm shadow-[0_0_20px_rgba(147,51,234,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] flex justify-center items-center gap-2 group mt-4">
                        Initialize Registration
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">rocket_launch</span>
                    </button>
                </form>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-gray-500 text-sm">
                        Already have access? 
                        <a href="{{ route('login') }}" class="text-white hover:text-purple-400 font-bold transition-colors uppercase tracking-wide ml-1">
                            Login Here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>