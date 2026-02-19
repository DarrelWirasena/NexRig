<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-40 w-72 bg-[#0a0a0a] border-r border-white/10 flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:h-full lg:z-20 shadow-2xl lg:shadow-none no-bounce">
            
    {{-- BAGIAN HEADER SIDEBAR (Gabungan Logo & Close Button) --}}
    <div class="px-6 pt-6 pb-4 border-b border-white/5">
        
        {{-- Flex Container: Logo di Kiri, Tombol Close di Kanan --}}
        <div class="flex items-center justify-between mb-6">
            
            {{-- 1. LOGO --}}
            <a class="flex items-center hover:opacity-80 transition-opacity" href="{{ route('home') }}">
                {{-- Saya sesuaikan tinggi logo (h-10) agar pas di mobile, h-14 di desktop --}}
                <img src="{{ asset('images/nexrig.png') }}" alt="NexRig Logo" class="h-14 md:h-14 w-auto object-contain"> 
            </a>

            {{-- 2. TOMBOL CLOSE (Hanya Muncul di Mobile / lg:hidden) --}}
            <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white lg:hidden bg-white/5 p-2 rounded-lg border border-white/10 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

        </div>

        {{-- 3. BACK LINK --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 text-gray-400 hover:text-blue-500 transition-colors group text-[10px] font-bold uppercase tracking-widest">
            <span class="material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Back to Home
        </a>
    </div>

    {{-- BAGIAN 2: NAVIGASI --}}
    <nav class="flex-1 p-6 space-y-2 overflow-y-auto no-bounce custom-sidebar-scroll">
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest px-4 mb-4">Account Navigation</p>
        
        <a href="{{ route('profile.app') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">person</span> Profile Settings
        </a>
        <a href="{{ route('address.index') }}" class="nav-item {{ request()->routeIs('address.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">location_on</span> Address Book
        </a>
        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">receipt_long</span> My Orders
        </a>

        {{-- MENU BARU: SUPPORT HISTORY --}}
        <a href="{{ route('support.history') }}" class="nav-item {{ request()->routeIs('support.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 transition-all uppercase tracking-wide">
            <span class="material-symbols-outlined">history</span> Support History
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