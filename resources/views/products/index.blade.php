@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col w-full max-w-[1440px] mx-auto px-4 md:px-10 py-6">
        
        {{-- Header & Breadcrumbs --}}
        <div class="mb-8">
            <h1 class="text-white text-3xl md:text-4xl font-bold">High-Performance Gaming PCs</h1>
            <p class="text-gray-400">
                Showing {{ $products->total() }} premium builds
                @if(request('category')) in <span class="text-primary font-bold">{{ str_replace('-', ' ', request('category')) }}</span> @endif
                @if(request('search')) matching "<span class="text-primary font-bold">{{ request('search') }}</span>" @endif
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- SIDEBAR FILTER --}}
            <aside class="w-full lg:w-64 shrink-0 space-y-8">
                
                {{-- 1. SEARCH BAR (Penting untuk filter spesifik) --}}
                <x-filter-section title="Search Rigs">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        {{-- Keep category if selected --}}
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Ex: RTX 4090..." 
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-500 hover:text-white">
                            <span class="material-symbols-outlined text-lg">search</span>
                        </button>
                    </form>
                </x-filter-section>

                {{-- 2. CATEGORIES (Dinamis dari Database) --}}
                <x-filter-section title="Categories" subtitle="By Chassis Type">
                    {{-- Tombol ALL MODELS --}}
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('category') ? 'bg-primary text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">grid_view</span> 
                        All Models
                    </a>

                    {{-- Loop Categories --}}
                    @foreach($categories as $cat)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $cat->slug, 'page' => null])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == $cat->slug ? 'bg-primary text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[20px]">
                                    {{-- Icon logic sederhana --}}
                                    {{ $cat->name == 'Laptops' ? 'laptop_chromebook' : 'desktop_windows' }}
                                </span>
                                {{ $cat->name }}
                            </div>
                            
                            {{-- Opsional: Badge jumlah series (kalau mau) --}}
                            {{-- <span class="text-xs opacity-50">{{ $cat->series->count() }}</span> --}}
                        </a>
                    @endforeach
                </x-filter-section>

              {{-- 3. QUICK SPECS FILTER (FIXED: Menjaga Kategori Tetap Ada) --}}
                <x-filter-section title="Quick Filter" subtitle="Popular Specs">
                    <div class="flex flex-col gap-2">
                        @php
                            $popularSpecs = ['RTX 4090', 'RTX 4080', 'Intel i9', 'Ryzen 9'];
                        @endphp

                        @foreach($popularSpecs as $spec)
                            {{-- 
                                LOGIC PERBAIKAN: 
                                1. request()->query(): Ambil semua parameter URL saat ini (misal: category=gaming-pc).
                                2. array_merge: Gabungkan dengan parameter baru ('search' => $spec).
                                3. 'page' => null: Reset pagination ke halaman 1 setiap kali filter berubah.
                            --}}
                            <a href="{{ route('products.index', array_merge(request()->query(), ['search' => $spec, 'page' => null])) }}" 
                               class="flex items-center gap-3 cursor-pointer group">
                                
                                {{-- Checkbox Style --}}
                                <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors
                                    {{ request('search') == $spec ? 'border-primary bg-primary' : 'border-gray-500 group-hover:border-white' }}">
                                    @if(request('search') == $spec)
                                        <span class="material-symbols-outlined text-white text-[10px] font-bold">check</span>
                                    @endif
                                </div>

                                {{-- Text Style --}}
                                <span class="text-sm transition-colors
                                    {{ request('search') == $spec ? 'text-white font-bold' : 'text-gray-400 group-hover:text-white' }}">
                                    {{ $spec }}
                                </span>
                            </a>
                        @endforeach
                        
                        {{-- Tombol Reset Filter Search (Hanya muncul jika sedang mencari) --}}
                        @if(request('search'))
                            <a href="{{ route('products.index', array_merge(request()->except('search'), ['page' => null])) }}" 
                               class="text-xs text-red-400 hover:text-red-300 mt-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">close</span> Clear Search
                            </a>
                        @endif
                    </div>
                </x-filter-section>
            </aside>

            {{-- PRODUCT GRID --}}
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}" class="block group">
                            <x-product-card 
                                :name="$product->name"
                                :price="$product->price"
                                :description="$product->short_description"
                                :image="$product->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/600'"
                                badge="{{ $product->tier ?? 'Custom' }}"
                                rating="5.0"
                                :specs="$product->components->take(3)->pluck('name')->toArray()"
                            />
                        </a>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">search_off</span>
                            <h3 class="text-xl font-bold text-white mb-2">No rigs found</h3>
                            <p class="text-gray-500 mb-6">We couldn't find any builds matching your filters.</p>
                            <a href="{{ route('products.index') }}" class="px-6 py-2 bg-white/10 hover:bg-primary text-white rounded transition-colors uppercase font-bold text-sm">
                                Clear Filters
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $products->links() }} 
                </div>
            </div>
        </div>
    </main>
@endsection