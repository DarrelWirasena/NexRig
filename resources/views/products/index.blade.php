@extends('layouts.app')

@section('content')
    {{-- Header Statis --}}
    <div class="mb-6 px-4 md:px-10">
        <h1 class="text-white text-3xl md:text-4xl font-bold italic tracking-tighter uppercase">The Rigs</h1>
        <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">
            {{ $products->total() }} Premium Builds Available
        </p>
    </div>

    {{-- STICKY NAVIGATION BAR WRAPPER --}}
    <div x-data="{ 
            openFilter: false, 
            openSort: false,
            isSticky: false,
            searchQuery: '{{ request('search') }}',
            checkScroll() {
                this.isSticky = window.scrollY > 85;
            },
            clearSearch() {
                this.searchQuery = '';
                this.$nextTick(() => this.$refs.searchForm.submit());
            }
         }"
         x-init="window.addEventListener('scroll', () => checkScroll())"
         class="w-full relative">

        {{-- Kontainer dengan tinggi tetap agar tidak lompat --}}
        <div class="h-[120px] md:h-[130px] w-full relative">
            
            {{-- Elemen Bar --}}
            <div :class="isSticky ? 'fixed top-[75px] left-4 right-4 md:left-10 md:right-10 rounded-2xl shadow-2xl' : 'relative w-full'"
                 class="z-40 bg-[#101322]/95 backdrop-blur-md border border-white/10 py-3 px-4 transition-all duration-500 ease-in-out">
                
                <div class="max-w-[1440px] mx-auto flex flex-col gap-4">
                    
                    {{-- Row 1: Search, Sort, Filter --}}
                    <div class="flex items-center h-10 bg-white/5 border border-white/10 rounded-xl overflow-hidden relative">
                        <form x-ref="searchForm" action="{{ route('products.index') }}" method="GET" class="flex-1 flex items-center h-full px-3 relative">
                            @foreach(request()->except(['search', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            
                            <span class="material-symbols-outlined text-gray-500 text-xl mr-2">search</span>
                            
                            {{-- Input Search dengan Alpine.js (x-model) --}}
                            <input x-ref="searchInput" x-model="searchQuery" type="text" name="search" placeholder="Search model..." 
                                   class="w-full bg-transparent border-none p-0 text-white text-sm focus:ring-0 outline-none placeholder-gray-500 pr-8">
                                                        
                            <button 
                                type="button" 
                                x-show="searchQuery.length > 0"
                                x-cloak
                                @click="clearSearch()" 
                                class="absolute right-2 inset-y-0 my-auto text-gray-500 hover:text-white transition-colors flex items-center justify-center z-10 h-fit">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </form>
                        
                        <div class="w-[1px] h-6 bg-white/10"></div>
                        <button @click="openSort = !openSort" class="px-3 flex items-center justify-center h-full text-gray-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">swap_vert</span>
                        </button>
                        <div class="w-[1px] h-6 bg-white/10"></div>
                        <button @click="openFilter = true" class="px-3 flex items-center justify-center h-full text-gray-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">filter_list</span>
                        </button>
                    </div>

                    {{-- Row 2: Chips (Trending / Quick Search) --}}
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar scroll-smooth">
                                            
                        @foreach($chips as $chip)
                            @php
                                // KITA UBAH: Gunakan 'search' bukan 'spec', agar terhubung ke controller
                                $isActive = request('search') == $chip;
                                
                                // Logika Toggle: Jika nyala, hapus 'search'. Jika mati, isi 'search' dengan $chip
                                $url = $isActive 
                                    ? route('products.index', request()->except(['search', 'page'])) 
                                    : route('products.index', array_merge(request()->except('page'), ['search' => $chip]));
                            @endphp
                            
                            <a href="{{ $url }}" 
                            class="whitespace-nowrap px-4 py-1.5 rounded-lg text-[10px] font-black uppercase border transition-all
                            {{ $isActive ? 'bg-primary border-primary text-white' : 'bg-white/5 border-white/5 text-gray-500 hover:text-white' }}">
                                {{ $chip }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Dropdown Sort (Dipindahkan ke dalam Bar agar posisinya absolut relatif ke Bar) --}}
                <div x-show="openSort" @click.away="openSort = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute right-4 mt-2 w-48 bg-[#0a0a0a] border border-white/10 rounded-xl shadow-2xl py-2 z-50">
                     @foreach(['newest' => 'Newest Arrivals', 'price_asc' => 'Lowest Price', 'price_desc' => 'Highest Price'] as $val => $label)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => $val])) }}" 
                           class="block px-4 py-2 text-xs font-bold hover:bg-white/5 {{ request('sort') == $val ? 'text-primary' : 'text-gray-400' }}">
                            {{ $label }}
                        </a>
                     @endforeach
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL FILTER SLIDE UP (DIPERBAIKI)           --}}
        {{-- ============================================ --}}
        <div x-show="openFilter" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
            
            {{-- Backdrop (Gelap) --}}
            <div x-show="openFilter" x-transition.opacity 
                 @click="openFilter = false"
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>
            
            {{-- Panel Modal (Slide Up di mobile, Fade/Scale di desktop) --}}
            <div x-show="openFilter" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                 x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                 class="relative bg-[#101322] w-full sm:w-[400px] sm:rounded-2xl rounded-t-2xl border border-white/10 shadow-2xl h-[80vh] sm:h-auto max-h-[90vh] flex flex-col">
                
                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between shrink-0">
                    <h3 class="text-white font-bold uppercase tracking-widest text-sm">Filter Catalog</h3>
                    <button @click="openFilter = false" class="text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Konten Modal (Bisa di-scroll) --}}
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                    
                    {{-- Kategori --}}
                    <div>
                        <h4 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-3">Categories</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $cat)
                                @php
                                    $isActive = request('category') == $cat->slug;
                                    // Jika aktif, buang 'category' dari URL. Jika tidak, timpa URL dengan category baru.
                                    // Parameter 'page' selalu dibuang agar kembali ke halaman 1.
                                    $url = $isActive 
                                        ? route('products.index', request()->except(['category', 'page'])) 
                                        : route('products.index', array_merge(request()->except('page'), ['category' => $cat->slug]));
                                @endphp
                                <a href="{{ $url }}" 
                                class="px-3 py-1.5 rounded border text-xs font-bold transition-colors
                                {{ $isActive ? 'bg-primary border-primary text-white' : 'border-white/10 text-gray-400 hover:border-white/30 hover:text-white' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Filter Harga --}}
                    <div>
                        <h4 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-3">Price Range</h4>
                        <div class="flex flex-wrap gap-2">
                            @php 
                                $prices = [
                                    'under-20' => 'Under 20jt', 
                                    '20-50'    => '20jt - 50jt', 
                                    'over-50'  => 'Over 50jt'
                                ]; 
                            @endphp
                            
                            @foreach($prices as $val => $label)
                                @php
                                    $isActive = request('price') == $val;
                                    // Logika yang sama persis seperti kategori
                                    $url = $isActive 
                                        ? route('products.index', request()->except(['price', 'page'])) 
                                        : route('products.index', array_merge(request()->except('page'), ['price' => $val]));
                                @endphp
                                <a href="{{ $url }}" 
                                class="px-3 py-1.5 rounded border text-xs font-bold transition-colors
                                {{ $isActive ? 'bg-primary border-primary text-white' : 'border-white/10 text-gray-400 hover:border-white/30 hover:text-white' }}">
                                {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Footer Modal --}}
                <div class="p-4 border-t border-white/10 shrink-0">
                    <a href="{{ route('products.index') }}" class="w-full block text-center py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-colors">
                        Clear All Filters
                    </a>
                </div>
            </div>
        </div>

    </div>
    {{-- END STICKY NAVIGATION BAR WRAPPER --}}


    {{-- 3. PRODUCT GRID --}}
    <div class="px-4 md:px-10 pb-20">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
            @forelse($products as $product)
                @php
                    $primaryImage = $product->images->where('is_primary', true)->first() 
                                    ?? $product->images->first();
                    $imageUrl = $primaryImage ? $primaryImage->full_url : 'https://via.placeholder.com/600';
                @endphp
                
                <a href="{{ route('products.show', $product->slug) }}" class="block group h-full">
                    <x-product-card 
                        :name="$product->name"
                        :price="$product->price"
                        :description="$product->short_description"
                        :image="$imageUrl"
                        badge="{{ $product->tier ?? 'Custom' }}"
                        :specs="$product->components->take(3)->pluck('name')->toArray()"
                    />
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-800 mb-4 italic">search_off</span>
                    <p class="text-gray-500 font-bold uppercase italic tracking-widest">No builds found in the database.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

@endsection