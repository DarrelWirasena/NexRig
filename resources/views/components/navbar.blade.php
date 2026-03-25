{{-- 1. PEMBUNGKUS UTAMA: LIQUID GLASS EFFECT --}}
<div x-data="{ mobileMenuOpen: false }"
    class="fixed top-0 left-0 right-0 z-[100] border-b border-white/10 bg-[#050505]/40 backdrop-blur-xl backdrop-saturate-150 shadow-[0_8px_32px_rgba(0,0,0,0.3)] transition-all duration-300">

    {{-- HEADER UTAMA --}}
    <div class="px-4 lg:px-10 py-3">
        <header class="flex items-center justify-between max-w-[1440px] mx-auto w-full relative">

            {{-- BAGIAN KIRI: LOGO + NAV berdampingan --}}
            <div class="flex items-center gap-3">

                {{-- Hamburger — mobile only --}}
                <button @click="mobileMenuOpen = true"
                    class="lg:hidden flex items-center justify-center w-9 h-9 text-gray-400 hover:text-white hover:bg-white/10 rounded-full transition-colors">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>

                {{-- Logo --}}
                <a class="flex items-center hover:opacity-80 transition-opacity shrink-0" href="{{ route('home') }}">
                    <img src="{{ asset('images/nexrig.png') }}" alt="NexRig Logo" class="h-10 lg:h-14 w-auto object-contain">
                </a>

                {{-- NAVIGATION DESKTOP --}}
                <nav class="hidden lg:flex items-center gap-1 ml-4">
                @foreach ($navbarCategories as $category)
                    <div class="relative group">
                        <button
                            class="flex items-center px-4 py-4 text-gray-400 group-hover:text-white text-sm font-bold uppercase tracking-wide transition-colors border-b-2 border-transparent group-hover:border-blue-500 whitespace-nowrap">
                            {{ $category->name }}
                        </button>

                        <div class="fixed left-0 right-0 px-4 lg:px-10
                                    opacity-0 invisible pointer-events-none
                                    group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto
                                    transition-all duration-500 ease-out
                                    -translate-x-8 group-hover:translate-x-0
                                    z-50"
                             style="top: 80px;">
                            <div class="bg-[#0a0a0a]/90 backdrop-blur-3xl border border-white/10 rounded-2xl shadow-[0_40px_80px_rgba(0,0,0,0.9)] overflow-hidden p-6 max-w-[1440px] mx-auto">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                    @foreach ($category->series as $series)
                                        <div class="flex flex-col items-center text-center group/item bg-white/[0.02] hover:bg-white/[0.06] border border-white/5 hover:border-white/10 rounded-xl p-4 transition-all duration-300 h-full cursor-default">
                                            @php
                                                $imgUrl = $series->banner_image
                                                    ? $series->banner_image
                                                    : 'https://placehold.co/200x200/101010/FFF?text=' . urlencode($series->name);
                                            @endphp
                                            <div class="relative w-20 h-20 mb-4 flex items-center justify-center shrink-0">
                                                <div class="absolute inset-0 bg-blue-600/20 blur-xl rounded-full opacity-0 group-hover/item:opacity-100 transition-opacity duration-500"></div>
                                                <img src="{{ $imgUrl }}" alt="{{ $series->name }}"
                                                    class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover/item:scale-110 group-hover/item:-translate-y-1">
                                            </div>
                                            <h3 class="text-white font-black uppercase tracking-widest text-xs mb-3 border-b border-white/10 w-full pb-2.5">
                                                {{ $series->name }}
                                            </h3>
                                            <div class="flex flex-col w-full flex-grow max-h-[200px] overflow-y-auto custom-scrollbar gap-0.5">
                                                @foreach ($series->products as $product)
                                                    <a href="{{ route('products.show', $product->slug) }}"
                                                        class="text-gray-400 hover:text-white hover:bg-white/5 rounded-lg px-2.5 py-2 text-[11px] transition-all block font-bold italic uppercase w-full">
                                                        {{ $product->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <a class="px-4 py-4 text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors border-b-2 border-transparent hover:border-white whitespace-nowrap"
                    href="{{ route('about') }}">About Us</a>
                <a class="px-4 py-4 text-gray-400 hover:text-white text-sm font-bold uppercase tracking-wide transition-colors border-b-2 border-transparent hover:border-white whitespace-nowrap"
                    href="{{ route('support') }}">Support</a>
                </nav>
            </div>

            {{-- BAGIAN KANAN: RIGHT ACTIONS --}}
            <div class="flex items-center gap-1 shrink-0">

                {{-- Search --}}
                <button onclick="openSearch()"
                    class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors group">
                    <span class="material-symbols-outlined text-[22px] group-hover:text-blue-500 transition-colors">search</span>
                </button>

                {{-- Wishlist — desktop only --}}
                @auth
                <a href="{{ route('wishlist.index') }}"
                   class="hidden lg:flex relative items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors group">
                    <span class="material-symbols-outlined text-[22px] group-hover:text-red-400 transition-colors"
                          style="font-variation-settings: 'FILL' 0">favorite</span>
                    @php
                        $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                    @endphp
                    @if($wishlistCount > 0)
                    <div class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold leading-none">
                        {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                    </div>
                    @endif
                </a>
                @endauth

                {{-- Cart --}}
                <button onclick="toggleMiniCart()"
                    class="relative flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-gray-400 hover:text-white transition-colors group">
                    <span class="material-symbols-outlined text-[22px] group-hover:text-blue-500 transition-colors">shopping_cart</span>
                    @php
                        $badgeCount = 0;
                        if (auth()->check()) {
                            $badgeCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                        } else {
                            $cartSession = session()->get('cart', []);
                            $badgeCount = array_reduce($cartSession, fn($carry, $item) => $carry + $item['quantity'], 0);
                        }
                    @endphp
                    <div id="cart-count"
                        class="{{ $badgeCount > 0 ? 'flex' : 'hidden' }} absolute -top-1 -right-1 bg-red-600 text-white text-[9px] w-4 h-4 rounded-full items-center justify-center font-bold leading-none">
                        {{ $badgeCount }}
                    </div>
                </button>

                {{-- User avatar — desktop only --}}
                @auth
                <div class="hidden lg:flex relative group h-10 items-center z-50 ml-1 pl-3 border-l border-white/10">
                    <button class="flex items-center gap-2.5 outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 flex items-center justify-center shrink-0 hover:bg-white/20 transition-colors">
                            <span class="material-symbols-outlined text-[22px] text-white"
                                  style="font-variation-settings: 'FILL' 1">account_circle</span>
                        </div>
                    </button>

                    {{-- DROPDOWN USER --}}
                    <div class="absolute top-full right-0 mt-3 w-52
                                bg-[#0a0a0a]/90 backdrop-blur-xl border border-white/10
                                rounded-xl shadow-[0_8px_32px_rgba(0,0,0,0.6)]
                                opacity-0 invisible pointer-events-none
                                group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto
                                transition-all duration-200
                                translate-y-2 group-hover:translate-y-0
                                z-50 p-1.5">
                        <div class="px-3 py-2.5 border-b border-white/10 mb-1">
                            <p class="text-sm text-white font-bold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.app') }}"
                            class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[18px]">person</span> My Profile
                        </a>
                        <a href="{{ route('orders.index') }}"
                            class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span> Order History
                        </a>
                        <a href="{{ route('support.history') }}"
                            class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[18px]">support_agent</span> Support History
                        </a>
                        <div class="border-t border-white/10 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">logout</span> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                {{-- Login/Register — desktop only --}}
                <div class="hidden lg:flex items-center gap-2 ml-1 pl-3 border-l border-white/10">
                    <a href="{{ route('login') }}"
                        class="items-center text-white hover:text-blue-400 font-bold text-sm uppercase tracking-wide transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center justify-center rounded px-5 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-black shadow-[0_4px_15px_rgba(255,255,255,0.1)] text-sm font-bold uppercase tracking-wide transition-all clip-button">
                        Sign Up
                    </a>
                </div>
                @endauth

            </div>

        </header>
    </div>

    {{-- MOBILE DRAWER --}}
    <template x-teleport="body">
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-[110] lg:hidden" style="display: none;">
            <div @click="mobileMenuOpen = false"
                class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="absolute left-0 top-0 bottom-0 w-[85%] max-w-sm bg-[#080808]/80 backdrop-blur-2xl border-r border-white/10 flex flex-col shadow-[20px_0_50px_rgba(0,0,0,0.5)]"
                x-show="mobileMenuOpen"
                x-transition:enter="transition transform duration-300 ease-out"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition transform duration-300 ease-in"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">

                <div class="px-5 py-4 border-b border-white/10 flex justify-between items-center">
                    <img src="{{ asset('images/nexrig.png') }}" alt="NexRig Logo" class="h-12 w-auto">
                    <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white hover:rotate-90 transition-all duration-300 w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/10">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-3 custom-sidebar-scroll">
                    @foreach ($navbarCategories as $category)
                        <div x-data="{ categoryOpen: false }">
                            <button @click="categoryOpen = !categoryOpen"
                                class="flex justify-between items-center w-full text-white font-black uppercase tracking-wider text-sm py-3 border-b border-white/10">
                                {{ $category->name }}
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                    :class="categoryOpen ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="categoryOpen" x-collapse class="pt-3 space-y-2 pl-1">
                                @foreach ($category->series as $series)
                                    <div x-data="{ seriesOpen: false }"
                                        class="border border-white/10 rounded-xl bg-white/[0.03] overflow-hidden">
                                        <button @click="seriesOpen = !seriesOpen"
                                            class="w-full flex items-center p-3 gap-3 text-left hover:bg-white/5 transition-colors">
                                            @php
                                                $imgUrl = $series->banner_image
                                                    ? $series->banner_image
                                                    : 'https://placehold.co/200x200/101010/FFF?text=' . urlencode($series->name);
                                            @endphp
                                            <div class="w-10 h-10 bg-black/40 rounded-lg flex items-center justify-center p-1 border border-white/10 shrink-0">
                                                <img src="{{ $imgUrl }}" alt="{{ $series->name }}" class="w-full h-full object-contain">
                                            </div>
                                            <div class="grow min-w-0">
                                                <p class="text-white text-xs font-bold uppercase tracking-wider truncate">{{ $series->name }}</p>
                                                <p class="text-gray-500 text-[10px] mt-0.5">View Models</p>
                                            </div>
                                            <span class="material-symbols-outlined text-gray-500 text-xl transition-transform duration-300 shrink-0"
                                                :class="seriesOpen ? 'rotate-180' : ''">expand_more</span>
                                        </button>
                                        <div x-show="seriesOpen" x-collapse class="border-t border-white/10 bg-black/20">
                                            @foreach ($series->products as $product)
                                                <a href="{{ route('products.show', $product->slug) }}"
                                                    class="block px-5 py-2.5 text-gray-400 text-xs font-semibold uppercase tracking-wide border-b border-white/5 last:border-0 hover:bg-white/5 hover:text-white active:bg-blue-600/20 active:text-blue-300 transition-colors">
                                                    {{ $product->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('about') }}"
                        class="flex items-center justify-between text-white font-black uppercase tracking-wider text-sm py-3 border-b border-white/10 hover:text-blue-400 transition-colors">
                        About Us
                        <span class="material-symbols-outlined text-gray-600 text-lg">chevron_right</span>
                    </a>
                    <a href="{{ route('support') }}"
                        class="flex items-center justify-between text-white font-black uppercase tracking-wider text-sm py-3 border-b border-white/10 hover:text-blue-400 transition-colors">
                        Support
                        <span class="material-symbols-outlined text-gray-600 text-lg">chevron_right</span>
                    </a>

                    {{-- SECTION AKUN --}}
                    <div class="pt-6 space-y-3">
                        <p class="text-gray-600 text-[10px] font-bold uppercase tracking-[0.25em]">Account</p>

                        @auth
                            <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/10">
                                <div class="w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[24px] text-white"
                                          style="font-variation-settings: 'FILL' 1">account_circle</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-gray-500 text-xs truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.app') }}"
                                class="block w-full py-3 text-center rounded-xl border border-white/20 text-white text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-all">
                                My Dashboard
                            </a>

                            {{-- ── WISHLIST (mobile) ── --}}
                            <a href="{{ route('wishlist.index') }}"
                               class="flex items-center justify-between w-full py-3 px-4 rounded-xl
                                      border border-white/10 text-gray-300 text-xs font-bold uppercase
                                      tracking-widest hover:bg-white/10 hover:text-white transition-all">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-red-400"
                                          style="font-variation-settings: 'FILL' 1">favorite</span>
                                    My Wishlist
                                </span>
                                @if(isset($wishlistCount) && $wishlistCount > 0)
                                <span class="bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-500/30">
                                    {{ $wishlistCount }}
                                </span>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl text-xs font-bold uppercase tracking-widest text-red-400 bg-red-500/10 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}"
                                    class="py-3.5 bg-white text-black text-center rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-100 transition-all">
                                    Log In
                                </a>
                                <a href="{{ route('register') }}"
                                    class="py-3.5 border border-white/20 text-white rounded-xl text-center text-xs font-bold uppercase tracking-wider hover:bg-white/10 transition-all">
                                    Sign Up
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- SEARCH OVERLAY --}}
<div id="searchOverlay"
    class="fixed inset-0 z-[200] bg-black/60 backdrop-blur-2xl flex items-start justify-center pt-28 opacity-0 invisible transition-all duration-300">
    <button onclick="closeSearch()"
        class="absolute top-6 right-6 text-gray-400 hover:text-white hover:rotate-90 transition-all duration-300 w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10">
        <span class="material-symbols-outlined text-3xl">close</span>
    </button>
    <div class="w-full max-w-2xl px-5 transform transition-all duration-400 scale-95 translate-y-3" id="searchContainer">
        <form action="{{ route('products.index') }}" method="GET" class="relative group w-full">
            <div class="absolute left-5 top-0 h-full flex items-center pointer-events-none text-gray-500 group-focus-within:text-blue-400 transition-colors">
                <span class="material-symbols-outlined text-2xl">search</span>
            </div>
            <input type="text" name="search" id="searchInput"
                placeholder="Search for rigs, components, or series..."
                class="w-full bg-white/[0.06] backdrop-blur-md border border-white/15 focus:border-blue-500 text-white text-lg py-5 pl-16 pr-6 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 shadow-[0_20px_60px_rgba(0,0,0,0.5)] transition-all placeholder-gray-600"
                autocomplete="off">
        </form>
        <div class="mt-4 text-center text-xs text-gray-600 flex items-center justify-center gap-2">
            Press <kbd class="bg-white/10 px-2 py-0.5 rounded text-gray-400 font-mono text-xs border border-white/20">ENTER</kbd> to search &nbsp;·&nbsp;
            <kbd class="bg-white/10 px-2 py-0.5 rounded text-gray-400 font-mono text-xs border border-white/20">ESC</kbd> to close
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
        searchContainer.classList.remove('scale-95', 'translate-y-3');
        searchContainer.classList.add('scale-100', 'translate-y-0');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 100);
    }

    function closeSearch() {
        searchOverlay.classList.remove('visible', 'opacity-100');
        searchOverlay.classList.add('invisible', 'opacity-0');
        searchContainer.classList.remove('scale-100', 'translate-y-0');
        searchContainer.classList.add('scale-95', 'translate-y-3');
        document.body.style.overflow = '';
        searchInput.value = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSearch(); });
    searchOverlay.addEventListener('click', e => { if (e.target === searchOverlay) closeSearch(); });
</script>