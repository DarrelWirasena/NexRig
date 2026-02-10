@extends('layouts.dashboard')

@section('content')

    <div class="max-w-4xl mx-auto pb-20">
        
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                    Edit <span class="text-blue-600">Profile</span>
                </h1>
                <p class="text-gray-400 text-sm mt-2">Manage your account credentials and security settings.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-green-400 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-10 relative overflow-hidden transition-all hover:border-blue-600/30">
            
            <form action="{{ route('profile.update') }}" method="POST" class="relative z-10">
                @csrf
                @method('PATCH')

                {{-- SECTION 1: IDENTITY --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-5 bg-blue-600 rounded-full"></span> Identity Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Email (Read Only) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address (Locked)</label>
                            <div class="relative">
                                {{-- PERBAIKAN ICON: Pakai Flex Wrapper Absolute --}}
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-500 material-symbols-outlined text-lg">lock</span>
                                </div>
                                <input type="email" value="{{ $user->email }}" readonly 
                                       class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm bg-white/5 cursor-not-allowed text-gray-400">
                            </div>
                        </div>

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Display Name</label>
                            <div class="relative">
                                {{-- PERBAIKAN ICON --}}
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-400 material-symbols-outlined text-lg">badge</span>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-white/5 mb-10">

                {{-- SECTION 2: SECURITY --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                        <span class="w-1 h-5 bg-red-500 rounded-full"></span> Security Protocol
                    </h3>
                    <p class="text-gray-500 text-xs mb-6">Leave fields blank if you don't want to change your password.</p>
                    
                    <div class="space-y-6">
                        {{-- Current Password --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Current Password</label>
                            <div class="relative">
                                {{-- PERBAIKAN ICON --}}
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-500 material-symbols-outlined text-lg">key</span>
                                </div>
                                <input type="password" name="current_password" 
                                       class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm placeholder-gray-600">
                            </div>
                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- New Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">New Password</label>
                                <input type="password" name="new_password" 
                                       class="w-full px-4 py-3 input-tech rounded-lg text-sm placeholder-gray-600">
                                @error('new_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" 
                                       class="w-full px-4 py-3 input-tech rounded-lg text-sm placeholder-gray-600">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-white/5">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest transition-all rounded-lg shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection