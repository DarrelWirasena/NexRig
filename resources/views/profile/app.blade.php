@extends('layouts.dashboard')

@section('content')
    <style>
        .clip-card { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
        .input-tech {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-tech:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #2563eb;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
            outline: none;
        }
        /* Style Sidebar dipindah ke komponen sidebar, atau biarkan di global css */
        .nav-item.active {
            background-color: #2563eb; color: white; font-weight: 700; box-shadow: 0 0 15px rgba(37, 99, 235, 0.4);
        }
        .nav-item:hover:not(.active) { background-color: rgba(255, 255, 255, 0.05); color: white; }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        
        main::-webkit-scrollbar { width: 8px; }
        main::-webkit-scrollbar-track { background: #050014; }
        main::-webkit-scrollbar-thumb { background: #1f1f1f; border-radius: 4px; }
        main::-webkit-scrollbar-thumb:hover { background: #333; }
        .no-bounce { overscroll-behavior: none; overscroll-behavior-y: none; }
    </style>

    <div class="h-screen bg-[#050014] text-white flex overflow-hidden relative no-bounce">
        
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-900/10 blur-[120px] rounded-full"></div>
        </div>

        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity no-bounce"></div>



        <main class="flex-1 h-full overflow-y-auto p-4 md:p-6 lg:p-12 w-full relative z-10 no-bounce scroll-smooth">
            <div class="lg:hidden mb-6 flex items-center justify-between bg-[#0a0a0a] border border-white/10 p-4 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">manage_accounts</span>
                    <span class="font-bold text-sm uppercase">Menu Dashboard</span>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:text-blue-500 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

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

                <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-10 relative overflow-hidden">
                    <form action="{{ route('profile.update') }}" method="POST" class="relative z-10">
                        @csrf
                        @method('PATCH')

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                <span class="w-1 h-5 bg-blue-600 rounded-full"></span> Identity Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address (Locked)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined text-lg">lock</span>
                                        <input type="email" value="{{ $user->email }}" readonly class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm bg-white/5 cursor-not-allowed">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Display Name</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-lg">badge</span>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                <hr class="border-white/5 mb-10">

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                                <span class="w-1 h-5 bg-red-500 rounded-full"></span> Security Protocol
                            </h3>
                            <p class="text-gray-500 text-xs mb-6">Leave fields blank if you don't want to change your password.</p>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Current Password</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 material-symbols-outlined text-lg">key</span>
                                        <input type="password" name="current_password" class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">New Password</label>
                                        <input type="password" name="new_password" class="w-full px-4 py-3 input-tech rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 input-tech rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end pt-4 border-t border-white/5">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest transition-all rounded-lg shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection