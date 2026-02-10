<div class="border-b border-white/10 px-4 lg:px-10 py-3 sticky top-0 z-50 bg-[#050505]/95 backdrop-blur-md transition-all duration-300">
    <header class="flex items-center justify-between whitespace-nowrap max-w-[1440px] mx-auto w-full relative">
        
        {{-- 1. LOGO --}}
        <div class="flex items-center gap-8">
            <a class="flex items-center hover:opacity-80 transition-opacity" href="{{ route('home') }}">
                <img src="{{ asset('images/nexrig.png') }}" 
                     alt="NexRig Logo" 
                     class="h-14 w-auto object-contain"> 
            </a>

            {{-- 2. NAVIGATION (DYNAMIC CATEGORIES) --}}
            <nav class="hidden lg:flex items-center gap-8">
                
                {{-- LOOPING MAIN CATEGORIES --}}
                @foreach($navbarCategories as $category)
                    <div class="group static">
                        <button class="flex items-center gap-1 py-4 text-gray-400 group-hover:text-white text-sm font-bold uppercase tracking-wide transition-colors outline-none border-b-2 border-transparent group-hover:border-blue-600">
                            {{ $category->name }}
                        </button>
                        
                        {{-- MEGA MENU CONTENT --}}
                        <div class="absolute left-0 top-full w-full bg-[#080808] border-t border-b border-white/10 shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 overflow-hidden z-50">
                            <div class="max-w-[1440px] mx-auto p-8">
                                
                                {{-- GRID SERIES --}}
                                <div class="grid grid-cols-4 gap-8">
                                    
                                    {{-- LOOPING SERIES WITHIN CATEGORY --}}
                                    @foreach($category->series as $series)
                                        <div class="flex flex-col items-center text-center group/item">
                                            
                                            {{-- SERIES IMAGE (Taken from first product) --}}
                                            @php
                                                $firstProduct = $series->products->first();
                                                $imgUrl = $firstProduct && $firstProduct->images->first() 
                                                    ? $firstProduct->images->first()->image_url 
                                                    : 'https://placehold.co/200x200/101010/FFF?text=' . urlencode($series->name);
                                            @endphp

                                            <div class="relative w-32 h-32 mb-4 flex items-center justify-center">
                                                <div class="absolute inset-0 bg-blue-600/20 blur-xl rounded-full opacity-0 group-hover/item:opacity-100 transition-opacity duration-500"></div>
                                                <img src="{{ $imgUrl }}" class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover/item:scale-105">
                                            </div>
                                            
                                            {{-- SERIES NAME --}}
                                            <h3 class="text-white font-black uppercase tracking-widest mb-4 border-b border-blue-600/50 pb-1">
                                                {{ $series->name }}
                                            </h3>
                                            
                                            {{-- 
                                                LIST OF ALL PRODUCTS IN SERIES 
                                                Added 'max-h' and 'overflow-y-auto' so if there are many products, 
                                                the menu can be scrolled neatly (custom scrollbar optional).
                                            --}}
                                            <div class="flex flex-col gap-2 w-full max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                                                @forelse($series->products as $product)
                                                    {{-- LINK TO PRODUCT DETAIL --}}
                                                    <a href="{{ route('products.show', $product->slug) }}" class="text-gray-400 hover:text-white text-sm hover:translate-x-1 transition-all block py-1">
                                                        {{ $product->name }}
                                                    </a>
                                                @empty
                                                    <span class="text-xs text-gray-600">No products yet</span>
                                                @endforelse
                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                {{-- STATIC MENU --}}
                <a class="text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors py-4 border-b-2 border-transparent hover:border-white" href="{{ route('about') }}">About Us</a>
                <a class="text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors py-4 border-b-2 border-transparent hover:border-white" href="{{ route('support') }}">Support</a>
            </nav>
        </div>

        {{-- 3. RIGHT ACTIONS --}}
        <div class="flex flex-1 justify-end gap-4 items-center">
            
            {{-- SEARCH BUTTON --}}
            <button onclick="openSearch()" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors group">
                <span class="material-symbols-outlined text-[24px] group-hover:text-blue-500 transition-colors">search</span>
            </button>

            {{-- CART BUTTON --}}
            <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-white/5 hover:bg-blue-600 text-white transition-colors group">
                <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-[10px] font-bold flex items-center justify-center border border-black">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            {{-- AUTHENTICATION --}}
            @auth
                <div class="relative group h-10 flex items-center z-50">
                    <button class="flex items-center gap-3 pl-4 border-l border-white/10 outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-gray-400">Welcome,</p>
                            <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold border border-white/20">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>
                    <div class="absolute top-full right-0 mt-2 w-56 bg-[#0a0a0a] border border-white/10 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                        <div class="p-2">
                            <div class="px-4 py-3 border-b border-white/10 mb-2">
                                <p class="text-sm text-white font-bold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.app') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors">
                                <span class="material-symbols-outlined text-lg">person</span> My Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors">
                                <span class="material-symbols-outlined text-lg">history</span> Order History
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded transition-colors mt-1">
                                    <span class="material-symbols-outlined text-lg">logout</span> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex gap-3 pl-4 border-l border-white/10">
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center text-white hover:text-blue-500 font-bold text-sm uppercase tracking-wide transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center rounded px-5 py-2 bg-white text-black hover:bg-blue-600 hover:text-white text-sm font-bold uppercase tracking-wide transition-all clip-button">
                        Sign Up
                    </a>
                </div>
            @endauth
        </div>
    </header>
</div>

{{-- FULL SCREEN SEARCH OVERLAY --}}
<div id="searchOverlay" class="fixed inset-0 z-[100] bg-[#050505]/90 backdrop-blur-md flex items-start justify-center pt-32 opacity-0 invisible transition-all duration-300">
    <button onclick="closeSearch()" class="absolute top-8 right-8 text-gray-400 hover:text-white transition-colors z-[102]">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    <div class="w-full max-w-3xl px-4 z-[101] transform transition-all duration-300 scale-95 translate-y-0" id="searchContainer">
        <form action="{{ route('products.index') }}" method="GET" class="relative group w-full">
            <div class="absolute left-6 top-0 h-full flex items-center justify-center pointer-events-none text-gray-500 group-focus-within:text-blue-500 transition-colors">
                <span class="material-symbols-outlined text-2xl">search</span>
            </div>
            <input type="text" 
                   name="search" 
                   id="searchInput"
                   placeholder="Search products..." 
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

{{-- Optional Style for Dropdown Scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>