<div>
    {{-- SECTION 3: BENTO GRID CATEGORIES --}}
    <div id="featured" class="scroll-trigger opacity-0 bg-[#050505] py-24 px-4 sm:px-10"
         x-data="{ isLoaded: false }" 
         x-init="window.addEventListener('load', () => { setTimeout(() => isLoaded = true, 500) })">
         
        <div class="max-w-[1440px] mx-auto">
            
            {{-- Header Section (Tetap statis & langsung terlihat) --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-2">Curated Series</h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-primary to-transparent"></div>
                </div>
                <p class="text-gray-400 max-w-md text-left md:text-right">
                    From esports dominance to workstation powerhouses, choose the chassis that fits your ambition.
                </p>
            </div>

            {{-- Logika Keamanan Data --}}
            @if($featured->count() > 0)
            
                {{-- ========================================== --}}
                {{-- 1. SKELETON BENTO GRID (Tampil saat loading) --}}
                {{-- ========================================== --}}
                <div x-show="!isLoaded" class="grid grid-cols-4 md:grid-rows-2 gap-2 md:gap-4 h-auto md:h-[600px] w-full animate-pulse">
                    
                    {{-- Skeleton Flagship (Besar - Kiri) --}}
                    <div class="col-span-4 md:col-span-2 md:row-span-2 bg-white/5 border border-white/10 rounded-2xl h-[300px] md:h-full p-6 md:p-10 flex flex-col justify-end">
                        <div class="w-20 h-6 bg-white/10 rounded mb-4"></div>
                        <div class="w-3/4 h-10 bg-white/10 rounded mb-2"></div>
                        <div class="w-1/2 h-4 bg-white/5 rounded"></div>
                    </div>
                    
                    {{-- Skeleton Bestseller (Lebar - Kanan Atas) --}}
                    <div class="col-span-4 md:col-span-2 md:row-span-1 bg-white/5 border border-white/10 rounded-2xl h-[200px] md:h-full p-6 flex flex-col justify-end">
                        <div class="w-16 h-5 bg-white/10 rounded mb-3"></div>
                        <div class="w-2/3 h-8 bg-white/10 rounded mb-2"></div>
                        <div class="w-1/3 h-3 bg-white/5 rounded"></div>
                    </div>

                    {{-- Skeleton Standard (Kecil - Kanan Bawah Kiri) --}}
                    <div class="col-span-2 md:col-span-1 md:row-span-1 bg-white/5 border border-white/10 rounded-2xl h-[150px] md:h-full p-4 md:p-6 flex flex-col justify-end">
                        <div class="w-full h-6 bg-white/10 rounded mb-2"></div>
                        <div class="w-2/3 h-3 bg-white/5 rounded"></div>
                    </div>

                    {{-- Skeleton Custom (Kecil - Kanan Bawah Kanan) --}}
                    <div class="col-span-2 md:col-span-1 md:row-span-1 bg-white/5 border border-white/10 rounded-2xl h-[150px] md:h-full p-4 md:p-6 flex flex-col justify-end">
                        <div class="w-full h-6 bg-white/10 rounded mb-2"></div>
                        <div class="w-2/3 h-3 bg-white/5 rounded"></div>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- 2. KONTEN ASLI (Tampil setelah loading selesai) --}}
                {{-- ========================================== --}}
                <div x-show="isLoaded" x-cloak x-transition.opacity.duration.700ms class="grid grid-cols-4 md:grid-rows-2 gap-2 md:gap-4 h-auto md:h-[600px]">

                    {{-- ITEM 1: FLAGSHIP --}}
                    @if($featured->has(0))
                    <x-bento-card :product="$featured->get(0)" type="flagship" />
                    @endif

                    {{-- ITEM 2: BEST SELLER --}}
                    @if($featured->has(1))
                    <x-bento-card :product="$featured->get(1)" type="bestseller" />
                    @endif

                    {{-- ITEM 3: STANDARD --}}
                    @if($featured->has(2))
                    <x-bento-card :product="$featured->get(2)" type="standard" />
                    @endif

                    {{-- ITEM 4: CUSTOM (Selalu Muncul sebagai Placeholder) --}}
                    <x-bento-card type="custom" />
                    
                </div>
                
            @else
                {{-- Tampilan jika data kosong --}}
                <div class="text-center py-20 border border-dashed border-white/20 rounded-xl bg-white/5">
                    <span class="material-symbols-outlined text-4xl text-gray-600 mb-2">dns</span>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No featured systems deployed yet.</p>
                </div>
            @endif
            
        </div>
    </div>
</div>