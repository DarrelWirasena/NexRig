@extends('layouts.app')

@section('content')
    {{-- Header Statis: Akan hilang saat scroll ke bawah --}}
    <div class="mb-6 px-4 md:px-10">
        <h1 class="text-white text-3xl md:text-4xl font-bold italic tracking-tighter uppercase">The Rigs</h1>
        <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">
            {{ $products->total() }} Premium Builds Available
        </p>
    </div>

    {{-- STICKY AREA --}}
    <div x-data="{ openFilter: false, openSort: false }" class="w-full">
        
        {{-- Bar Filter yang akan nempel di bawah Navbar --}}
        {{-- top-[64px] disesuaikan dengan tinggi navbarmu --}}
        <div class="sticky top-[64px] z-40 bg-[#101322]/95 backdrop-blur-md border-b border-white/10 py-3 px-4 md:px-10 mb-8 transition-all duration-300">
            <div class="max-w-[1440px] mx-auto flex flex-col gap-4">
                
                {{-- Row 1: Search, Sort, Filter --}}
                <div class="flex items-center h-10 bg-white/5 border border-white/10 rounded-xl overflow-hidden shadow-inner">
                    <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex items-center h-full px-3">
                        <span class="material-symbols-outlined text-gray-500 text-xl mr-2">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search models..." 
                               class="w-full bg-transparent border-none p-0 text-white text-sm focus:ring-0 outline-none placeholder-gray-600">
                    </form>

                    <div class="w-[1px] h-5 bg-white/10"></div>

                    {{-- Sort --}}
                    <button @click="openSort = !openSort" class="px-4 flex items-center justify-center h-full text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[22px]">swap_vert</span>
                    </button>

                    <div class="w-[1px] h-5 bg-white/10"></div>

                    {{-- Filter --}}
                    <button @click="openFilter = true" class="px-4 flex items-center justify-center h-full text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[22px]">filter_list</span>
                    </button>
                </div>

                {{-- Row 2: Side-scroll Chips --}}
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar scroll-smooth">
                    @php $chips = ['RTX 4090', 'RTX 4080', 'Intel i9', 'Ryzen 9', 'White Build', 'Mini ITX']; @endphp
                    @foreach($chips as $chip)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['spec' => $chip])) }}" 
                           class="whitespace-nowrap px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border transition-all
                           {{ request('spec') == $chip ? 'bg-primary border-primary text-white' : 'bg-white/5 border-white/5 text-gray-500 hover:border-white/20 hover:text-gray-300' }}">
                            {{ $chip }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Sort Dropdown (Absolute inside Sticky) --}}
            <div x-show="openSort" @click.away="openSort = false" x-cloak
                 class="absolute right-10 mt-2 w-48 bg-[#0a0a0a] border border-white/10 rounded-xl shadow-2xl py-2 z-50">
                @foreach(['newest' => 'Newest Arrivals', 'price_asc' => 'Lowest Price', 'price_desc' => 'Highest Price'] as $val => $label)
                    <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => $val])) }}" 
                       class="block px-4 py-2 text-[11px] font-bold uppercase {{ request('sort') == $val ? 'text-primary' : 'text-gray-400 hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 2. BOTTOM SHEET FILTER (Fixed terhadap Window) --}}
        <div x-show="openFilter" class="fixed inset-0 z-[110]" x-cloak>
            <div x-show="openFilter" x-transition.opacity @click="openFilter = false" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
            
            <div x-show="openFilter" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="translate-y-full" 
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-300 transform"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="absolute bottom-0 inset-x-0 bg-[#101322] rounded-t-[2.5rem] border-t border-white/10 shadow-[0_-20px_50px_rgba(0,0,0,0.5)] flex flex-col max-h-[85vh]">
                
                <div class="w-12 h-1 bg-white/10 rounded-full mx-auto mt-4 mb-6 shrink-0"></div>

                <div class="px-8 pb-32 overflow-y-auto custom-scrollbar">
                    <h2 class="text-white font-bold text-2xl mb-8 italic uppercase tracking-tighter">Refine Builds</h2>
                    
                    <section class="mb-10">
                        <h3 class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mb-4">By Category</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('products.index', request()->except('category')) }}" 
                               class="p-4 rounded-2xl border text-center text-xs font-bold uppercase transition-all {{ !request('category') ? 'border-primary bg-primary/10 text-white' : 'border-white/5 text-gray-500' }}">
                                All Rigs
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $cat->slug])) }}" 
                                   class="p-4 rounded-2xl border text-center text-xs font-bold uppercase transition-all {{ request('category') == $cat->slug ? 'border-primary bg-primary/10 text-white' : 'border-white/5 text-gray-500' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Action Buttons --}}
                <div class="absolute bottom-0 inset-x-0 p-6 bg-gradient-to-t from-[#101322] via-[#101322] to-transparent">
                    <div class="flex gap-4">
                        <a href="{{ route('products.index') }}" class="flex-1 py-4 bg-white/5 text-white text-center rounded-2xl text-[11px] font-black uppercase tracking-widest border border-white/10">Reset</a>
                        <button @click="openFilter = false" class="flex-1 py-4 bg-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-primary/20">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 3. PRODUCT GRID --}}
        <div class="px-4 md:px-10 pb-20">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
                @forelse($products as $product)
                    <x-product-card 
                        :name="$product->name"
                        :price="$product->price"
                        :description="$product->short_description"
                        :image="$product->images->where('is_primary', true)->first()->src ?? 'https://via.placeholder.com/600'"
                        badge="{{ $product->tier ?? 'Custom' }}"
                        :specs="$product->components->take(3)->pluck('name')->toArray()"
                    />
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