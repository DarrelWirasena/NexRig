{{-- 1. PEMBUNGKUS UTAMA: Menggunakan 'fixed' agar terkunci ke layar, bukan ke konten --}}
<div x-data="{ mobileMenuOpen: false, activeMegaMenu: null }" class="fixed top-0 left-0 right-0 z-[100] border-b border-white/10 bg-[#050505]/95 backdrop-blur-md transition-all duration-300">
    
    {{-- HEADER UTAMA --}}
    <div class="px-4 lg:px-10 py-3">
        <header class="flex items-center justify-between max-w-[1440px] mx-auto w-full relative">
            
            {{-- LOGO & MOBILE HAMBURGER --}}
            <div class="flex items-center gap-8">
                <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>
                
                <a class="flex items-center hover:opacity-80 transition-opacity" href="{{ route('home') }}">
                    <img src="{{ asset('images/nexrig.png') }}" alt="NexRig Logo" class="h-14 w-auto object-contain"> 
                </a>

                {{-- 2. NAVIGATION DESKTOP --}}
                <nav class="hidden lg:flex items-center gap-8">
                    @foreach($navbarCategories as $category)
                        <div class="group static">
                            <button class="flex items-center gap-1 py-4 text-gray-400 group-hover:text-white text-sm font-bold uppercase tracking-wide transition-colors outline-none border-b-2 border-transparent group-hover:border-blue-600">
                                {{ $category->name }}
                            </button>
                            
                            <div class="absolute left-0 top-full w-full bg-[#080808] border-t border-b border-white/10 shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 overflow-hidden z-50">
                                <div class="max-w-[1440px] mx-auto p-8">
                                    <div class="grid grid-cols-4 gap-8">
                                        @foreach($category->series as $series)
                                            <div class="flex flex-col items-center text-center group/item">
                                                @php
                                                    $firstProduct = $series->products->first();
                                                    $imgUrl = $firstProduct && $firstProduct->images->first() 
                                                        ? $firstProduct->images->first()->src 
                                                        : 'https://placehold.co/200x200/101010/FFF?text=' . urlencode($series->name);
                                                @endphp
                                                <div class="relative w-32 h-32 mb-4 flex items-center justify-center">
                                                    <div class="absolute inset-0 bg-blue-600/20 blur-xl rounded-full opacity-0 group-hover/item:opacity-100 transition-opacity duration-500"></div>
                                                    <img src="{{ $imgUrl }}" class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover/item:scale-105">
                                                </div>
                                                <h3 class="text-white font-black uppercase tracking-widest mb-4 border-b border-blue-600/50 pb-1">{{ $series->name }}</h3>
                                                <div class="flex flex-col gap-2 w-full max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                                                    @foreach($series->products as $product)
                                                        <a href="{{ route('products.show', $product->slug) }}" class="text-gray-400 hover:text-white text-sm hover:translate-x-1 transition-all block py-1 font-bold italic uppercase">{{ $product->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <a class="text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors py-4 border-b-2 border-transparent hover:border-white" href="{{ route('about') }}">About Us</a>
                    <a class="text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors py-4 border-b-2 border-transparent hover:border-white" href="{{ route('support') }}">Support</a>
                </nav>
            </div>

            {{-- 3. RIGHT ACTIONS --}}
            <div class="flex flex-1 justify-end gap-4 items-center">
                <button onclick="openSearch()" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors group">
                    <span class="material-symbols-outlined text-[24px] group-hover:text-blue-500 transition-colors">search</span>
                </button>

                <button onclick="toggleMiniCart()" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-white/5 hover:bg-blue-600 text-white transition-colors group">
                    <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-[10px] font-bold flex items-center justify-center border border-black">{{ count(session('cart')) }}</span>
                    @endif
                </button>

                @auth
                    <div class="relative group h-10 flex items-center z-50">
                        <button class="flex items-center gap-3 pl-4 border-l border-white/10 outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold border border-white/20">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div class="absolute top-full right-0 mt-2 w-56 bg-[#0a0a0a] border border-white/10 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 p-2">
                            <div class="px-4 py-3 border-b border-white/10 mb-2">
                                <p class="text-sm text-white font-bold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.app') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors"><span class="material-symbols-outlined text-lg">person</span> My Profile</a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors"><span class="material-symbols-outlined text-lg">history</span> Order History</a>
                            <a href="{{ route('support.history') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors"><span class="material-symbols-outlined text-lg">history</span> Support History</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded transition-colors mt-1"><span class="material-symbols-outlined text-lg">logout</span> Log Out</button></form>
                        </div>
                    </div>
                @else
                    <div class="flex gap-3 pl-4 border-l border-white/10">
                        <a href="{{ route('login') }}" class="hidden sm:flex items-center text-white hover:text-blue-500 font-bold text-sm uppercase tracking-wide transition-colors">Log In</a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center rounded px-5 py-2 bg-white text-black hover:bg-blue-600 hover:text-white text-sm font-bold uppercase tracking-wide transition-all clip-button">Sign Up</a>
                    </div>
                @endauth
            </div>
        </header>
    </div>
{{-- 4. MOBILE DRAWER --}}
<template x-teleport="body">
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[110] lg:hidden" style="display: none;">
        {{-- Backdrop --}}
        <div @click="mobileMenuOpen = false" class="absolute inset-0 bg-black/90 backdrop-blur-md transition-opacity"></div>
        
        {{-- Panel Drawer --}}
        <div class="absolute left-0 top-0 bottom-0 w-[85%] max-w-sm bg-[#080808] border-r border-white/10 flex flex-col shadow-2xl"
                x-show="mobileMenuOpen"
                x-transition:enter="transition transform duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition transform duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">
            
            {{-- Header Drawer --}}
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-[#0a0a0a]">
                <img src="{{ asset('images/nexrig.png') }}" class="h-14 w-auto">
                <button @click="mobileMenuOpen = false" class="text-white">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>

            {{-- Content Navigation - TAMBAHKAN CLASS scrollbar-custome DI SINI --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-sidebar-scroll">
                @foreach($navbarCategories as $category)
                    <div x-data="{ categoryOpen: false }">
                        {{-- Button Kategori Utama --}}
                        <button @click="categoryOpen = !categoryOpen" class="flex justify-between items-center w-full text-white font-black uppercase italic tracking-tighter text-xl border-b border-white/5 pb-3">
                            {{ $category->name }}
                            <span class="material-symbols-outlined transition-transform duration-300" :class="categoryOpen ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        {{-- Container Series --}}
                        <div x-show="categoryOpen" x-collapse class="pt-4 space-y-4 pl-2">
                            @foreach($category->series as $series)
                                <div x-data="{ seriesOpen: false }" class="border border-white/5 rounded-xl bg-white/5 overflow-hidden">
                                    {{-- Header Series --}}
                                    <button @click="seriesOpen = !seriesOpen" class="w-full flex items-center p-3 gap-4 text-left">
                                        @php
                                            $firstProduct = $series->products->first();
                                            $imgUrl = $firstProduct && $firstProduct->images->first() 
                                                ? $firstProduct->images->first()->src 
                                                : 'https://placehold.co/200x200/101010/FFF?text=' . urlencode($series->name);
                                        @endphp
                                        <div class="w-12 h-12 bg-[#111] rounded-lg flex items-center justify-center p-1 border border-white/10 shrink-0">
                                            <img src="{{ $imgUrl }}" class="w-full h-full object-contain drop-shadow-md">
                                        </div>
                                        <div class="grow">
                                            <p class="text-blue-500 text-[10px] font-black uppercase tracking-widest italic leading-none mb-1">{{ $series->name }}</p>
                                            <p class="text-white text-[8px] font-bold uppercase opacity-50">View Models</p>
                                        </div>
                                        <span class="material-symbols-outlined text-gray-500 transition-transform duration-300" :class="seriesOpen ? 'rotate-180' : ''">expand_more</span>
                                    </button>

                                    {{-- List Produk --}}
                                    <div x-show="seriesOpen" x-collapse class="bg-[#050505] border-t border-white/5">
                                        <div class="flex flex-col">
                                            @foreach($series->products as $product)
                                                <a href="{{ route('products.show', $product->slug) }}" class="px-6 py-3 text-gray-400 text-[11px] font-bold uppercase border-b border-white/5 last:border-0 active:bg-blue-600 active:text-white transition-colors">
                                                    {{ $product->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('about') }}" class="block text-white font-black uppercase italic tracking-tighter text-xl border-b border-white/5 pb-3">About Us</a>
                <a href="{{ route('support') }}" class="block text-white font-black uppercase italic tracking-tighter text-xl border-b border-white/5 pb-3">Support</a>

                {{-- [TAMBAHKAN DI SINI] SECTION AKUN / LOGIN --}}
                <div class="pt-8 space-y-4">
                    <p class="text-gray-600 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">/// Account Systems</p>
                    
                    @auth
                        {{-- Jika User Sudah Login --}}
                        <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-black text-white italic">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-white text-xs font-bold uppercase leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500 text-[9px] mt-1">Status: Active Operator</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.app') }}" class="block w-full py-3 text-center border border-white/10 text-white text-xs font-black uppercase italic tracking-widest hover:bg-white hover:text-black transition-all">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-3 text-xs font-black uppercase italic tracking-widest text-red-500 bg-red-500/10 border border-red-500/20">Logout</button>
                        </form>
                    @else
                        {{-- Jika User Belum Login --}}
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}" class="py-4 bg-white text-black text-center text-xs font-black uppercase italic tracking-widest clip-button">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="py-4 border border-white/20 text-white text-center text-xs font-black uppercase italic tracking-widest hover:bg-white/5 transition-all">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</template>
</div>

{{-- 5. SEARCH OVERLAY & SCRIPT (SAMA PERSIS SEPERTI MILIK ANDA) --}}
<div id="searchOverlay" class="fixed inset-0 z-[100] bg-[#050505]/90 backdrop-blur-md flex items-start justify-center pt-32 opacity-0 invisible transition-all duration-300">
    <button onclick="closeSearch()" class="absolute top-8 right-8 text-gray-400 hover:text-white transition-colors z-[102]">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    <div class="w-full max-w-3xl px-4 z-[101] transform transition-all duration-300 scale-95 translate-y-0" id="searchContainer">
        <form action="{{ route('products.index') }}" method="GET" class="relative group w-full">
            <div class="absolute left-6 top-0 h-full flex items-center justify-center pointer-events-none text-gray-500 group-focus-within:text-blue-500 transition-colors">
                <span class="material-symbols-outlined text-2xl">search</span>
            </div>
            <input type="text" name="search" id="searchInput" placeholder="Search products..." 
                   class="w-full bg-[#1a1a1a] border border-white/10 text-white text-lg py-5 pl-16 pr-8 rounded-full focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-[0_0_30px_rgba(0,0,0,0.5)] transition-all placeholder-gray-500"
                   autocomplete="off">
        </form>
        <div class="mt-4 text-center text-sm text-gray-500 font-mono">
            Press <span class="text-gray-300 font-bold">ENTER</span> to search
        </div>
    </div>
</div>

<script>
    const searchOverlay = document.getElementById('searchOverlay');
    const searchContainer = document.getElementById('searchContainer');
    const searchInput = document.getElementById('searchInput');

    function openSearch() {
        searchOverlay.classList.remove('invisible', 'opacity-0');
        searchOverlay.classList.add('visible', 'opacity-100');
        searchContainer.classList.remove('scale-95');
        searchContainer.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
        setTimeout(() => { searchInput.focus(); }, 100);
    }

    function closeSearch() {
        searchOverlay.classList.remove('visible', 'opacity-100');
        searchOverlay.classList.add('invisible', 'opacity-0');
        searchContainer.classList.remove('scale-100');
        searchContainer.classList.add('scale-95');
        document.body.style.overflow = '';
        searchInput.value = '';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeSearch();
    });

    searchOverlay.addEventListener('click', function(e) {
        if (e.target === searchOverlay) closeSearch();
    });
</script>