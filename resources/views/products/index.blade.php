@extends('layouts.app')

@section('content')
    {{-- Header Statis: Akan hilang saat scroll ke bawah --}}
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
        checkScroll() {
            this.isSticky = window.scrollY > 85;
        }
     }"
     x-init="window.addEventListener('scroll', () => checkScroll())"
     class="w-full relative">

    {{-- KUNCI PERBAIKAN: Kontainer dengan tinggi tetap agar tidak lompat --}}
    {{-- h-[120px] disesuaikan dengan tinggi total elemen filter bar kamu --}}
    <div class="h-[120px] md:h-[130px] w-full relative">
        
        {{-- Elemen Bar --}}
        <div :class="isSticky ? 'fixed top-[75px] left-4 right-4 md:left-10 md:right-10 rounded-2xl shadow-2xl animate-soft-landing' : 'relative w-full'"
             class="z-40 bg-[#101322]/95 backdrop-blur-md border border-white/10 py-3 px-4 transition-all duration-500 ease-in-out">
            
            <div class="max-w-[1440px] mx-auto flex flex-col gap-4">
                {{-- Row 1: Search, Sort, Filter --}}
                <div class="flex items-center h-10 bg-white/5 border border-white/10 rounded-xl overflow-hidden">
                    <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex items-center h-full px-3">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <span class="material-symbols-outlined text-gray-500 text-xl mr-2">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search model..." 
                               class="w-full bg-transparent border-none p-0 text-white text-sm focus:ring-0 outline-none placeholder-gray-500">
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

                {{-- Row 2: Chips --}}
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar scroll-smooth">
                    @php $chips = ['RTX 4090', 'RTX 4080', 'Intel i9', 'Ryzen 9', 'White Build', 'Mini ITX']; @endphp
                    @foreach($chips as $chip)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['spec' => $chip])) }}" 
                           class="whitespace-nowrap px-4 py-1.5 rounded-lg text-[10px] font-black uppercase border transition-all
                           {{ request('spec') == $chip ? 'bg-primary border-primary text-white' : 'bg-white/5 border-white/5 text-gray-500 hover:text-white' }}">
                            {{ $chip }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Dropdown Sort --}}
            <div x-show="openSort" @click.away="openSort = false" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute right-0 mt-2 w-48 bg-[#0a0a0a] border border-white/10 rounded-xl shadow-2xl py-2 z-50">
                 @foreach(['newest' => 'Newest Arrivals', 'price_asc' => 'Lowest Price', 'price_desc' => 'Highest Price'] as $val => $label)
                    <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => $val])) }}" 
                       class="block px-4 py-2 text-xs font-bold {{ request('sort') == $val ? 'text-primary' : 'text-gray-400' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{-- END STICKY NAVIGATION BAR WRAPPER --}}


{{-- 3. PRODUCT GRID --}}
        <div class="px-4 md:px-10 pb-20">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
                @forelse($products as $product)
                   <a href="{{ route('products.show', $product->slug) }}" class="block group h-full">
                        <x-product-card 
                            :name="$product->name"
                            :price="$product->price"
                            :description="$product->short_description"
                            :image="$product->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/600'"
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