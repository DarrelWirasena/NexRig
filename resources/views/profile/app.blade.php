@extends('layouts.dashboard')

@section('content')
    {{-- Custom Styles --}}
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
            border-color: #a6e22e; /* Neon Green */
            box-shadow: 0 0 10px rgba(166, 226, 46, 0.2);
            outline: none;
        }
        .nav-item.active {
            background-color: #a6e22e;
            color: black;
            font-weight: 800;
            box-shadow: 0 0 15px rgba(166, 226, 46, 0.3);
        }
        .nav-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Custom Scrollbar */
        main::-webkit-scrollbar { width: 8px; }
        main::-webkit-scrollbar-track { background: #050014; }
        main::-webkit-scrollbar-thumb { background: #1f1f1f; border-radius: 4px; }
        main::-webkit-scrollbar-thumb:hover { background: #333; }

        /* TEKNIK ANTI-STRETCH / ANTI-BOUNCE */
        .no-bounce {
            overscroll-behavior: none; /* Standar baru */
            overscroll-behavior-y: none; /* Khusus vertikal */
        }
    </style>

    {{-- 
        WRAPPER UTAMA:
        1. overflow-hidden: Mencegah scroll body.
        2. no-bounce: Mencegah efek tarik/memantul pada keseluruhan halaman.
    --}}
    <div class="h-screen bg-[#050014] text-white flex overflow-hidden no-bounce relative">
        
        {{-- Background Elements --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-[#a6e22e]/5 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-900/10 blur-[120px] rounded-full"></div>
        </div>

        {{-- OVERLAY MOBILE --}}
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity no-bounce"></div>

        {{-- SIDEBAR (FIXED/STATIC) --}}
        <aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-40 w-72 bg-[#0a0a0a] border-r border-white/10 flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:h-full lg:z-20 shadow-2xl lg:shadow-none no-bounce">
            
            {{-- Tombol Close (Mobile) --}}
            <div class="flex justify-end p-4 lg:hidden">
                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>

            <nav class="flex-1 p-6 space-y-2 overflow-y-auto no-bounce">
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest px-4 mb-4">Account Navigation</p>
                
                <a href="{{ route('profile.app') }}" class="nav-item active flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all uppercase tracking-wide">
                    <span class="material-symbols-outlined">person</span> Profile Settings
                </a>
                <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
                    <span class="material-symbols-outlined">location_on</span> Address Book
                </a>
                <a href="{{ route('orders.index') }}" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
                    <span class="material-symbols-outlined">receipt_long</span> My Orders
                </a>
            </nav>

            <div class="p-6 border-t border-white/10 bg-[#080808] mt-auto">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#a6e22e] to-green-800 flex items-center justify-center text-black font-black text-sm border border-white/20">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-[#a6e22e] uppercase tracking-wider">Elite Member</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-0 py-2 text-sm font-bold text-red-500 hover:text-red-400 transition-colors uppercase tracking-wide">
                        <span class="material-symbols-outlined rotate-180">logout</span> Log Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- 
             MAIN CONTENT:
             1. no-bounce: Mencegah scroll konten memantul saat mentok atas/bawah.
        --}}
        <main class="flex-1 h-full overflow-y-auto p-4 md:p-6 lg:p-12 w-full relative z-10 no-bounce">
            
            {{-- Mobile Toggle --}}
            <div class="lg:hidden mb-6 flex items-center justify-between bg-[#0a0a0a] border border-white/10 p-4 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#a6e22e]">manage_accounts</span>
                    <span class="font-bold text-sm uppercase">Menu Dashboard</span>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:text-[#a6e22e] transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            <div class="max-w-4xl mx-auto pb-20">
                
                {{-- Header --}}
                <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                            Edit <span class="text-[#a6e22e]">Profile</span>
                        </h1>
                        <p class="text-gray-400 text-sm mt-2">Manage your account credentials and security settings.</p>
                    </div>
                </div>

                {{-- Alert --}}
                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-[#a6e22e] rounded-lg flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Content --}}
                <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-10 relative overflow-hidden">
                    <form action="{{ route('profile.update') }}" method="POST" class="relative z-10">
                        @csrf
                        @method('PATCH')

                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                <span class="w-1 h-5 bg-[#a6e22e] rounded-full"></span> Identity Information
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
                                    <label class="block text-xs font-bold text-[#a6e22e] uppercase tracking-wider mb-2">Display Name</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-lg">badge</span>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-[#a6e22e]">
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

                        <div class="flex justify-end pt-4 border-t border-white/5">
                            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#a6e22e] hover:bg-[#8bc026] text-black font-bold uppercase tracking-widest transition-all rounded-lg shadow-[0_0_15px_rgba(166,226,46,0.4)] flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined">save</span> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
@endsection