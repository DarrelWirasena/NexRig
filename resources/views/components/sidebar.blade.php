<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-40 w-72 bg-[#0a0a0a] border-r border-white/10 flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:h-full lg:z-20 shadow-2xl lg:shadow-none no-bounce">
            
    {{-- Tombol Close (Mobile) --}}
    <div class="flex justify-end p-4 lg:hidden">
        <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white">
            <span class="material-symbols-outlined text-3xl">close</span>
        </button>
    </div>

    {{-- BAGIAN 1: LOGO & BACK --}}
    <div class="px-6 pt-8 pb-4 border-b border-white/5">
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('images/nexrig.png') }}" alt="NexRig Logo" class="h-8 w-auto object-contain">
            <span class="text-lg font-black italic tracking-tighter text-white">NEX<span class="text-blue-600">RIG</span></span>
        </div>

        <a href="{{ route('home') }}" class="flex items-center gap-3 text-gray-400 hover:text-blue-500 transition-colors group text-xs font-bold uppercase tracking-widest">
            <span class="material-symbols-outlined text-base group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Back to Home
        </a>
    </div>

    {{-- BAGIAN 2: NAVIGASI --}}
    <nav class="flex-1 p-6 space-y-2 overflow-y-auto no-bounce">
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest px-4 mb-4">Account Navigation</p>
        
        <a href="{{ route('profile.app') }}" class="nav-item {{ request()->routeIs('profile.app') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">person</span> Profile Settings
        </a>
        <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">location_on</span> Address Book
        </a>
        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">receipt_long</span> My Orders
        </a>
    </nav>

    {{-- BAGIAN 3: USER PROFILE --}}
    <div class="p-6 border-t border-white/10 bg-[#080808] mt-auto">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-purple-800 flex items-center justify-center text-white font-black text-sm border border-white/20">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-blue-500 uppercase tracking-wider">Elite Member</p>
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