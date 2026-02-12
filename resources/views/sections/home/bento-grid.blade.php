<div>
    {{-- SECTION 3: BENTO GRID CATEGORIES --}}
    <div id="featured" class="scroll-trigger opacity-0 bg-[#050505] py-24 px-4 sm:px-10">
        <div class="max-w-[1440px] mx-auto">
            {{-- Header Section (Tetap) --}}
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
                {{-- 
                    GRID SYSTEM (4 Kolom Konsisten):
                    - grid-cols-4: Memaksa 4 kolom terkecil sekalipun di HP.
                    - md:grid-rows-2: Baris kaku hanya di desktop.
                    - h-auto: Agar tidak tumpang tindih di mobile.
                --}}
                <div class="grid grid-cols-4 md:grid-rows-2 gap-2 md:gap-4 h-auto md:h-[600px]">
                    
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
