<div>
    {{-- SECTION 4: LATEST DEPLOYMENTS --}}
    {{-- Section terluar tanpa max-width agar hitamnya full kanan-kiri di PC --}}
    <div class="scroll-trigger opacity-0 w-full bg-[#080808] py-16 md:py-24 border-t border-white/5 relative">
        
        {{-- Decorative Lines (PC Only) --}}
        <div class="hidden md:block absolute top-0 left-10 w-px h-24 bg-gradient-to-b from-primary to-transparent"></div>
        <div class="hidden md:block absolute bottom-0 right-10 w-px h-24 bg-gradient-to-t from-primary to-transparent"></div>

        {{-- 1. HEADER: Terpisah agar alignment max-width-nya aman --}}
        <div class="max-w-[1440px] mx-auto px-4 md:px-10 mb-8 md:mb-12">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-primary font-bold text-xl animate-pulse">///</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-white uppercase tracking-wider">Latest Deployments</h2>
                </div>
                
                <a href="{{ route('products.index') }}" class="group hidden md:flex items-center gap-2 text-white hover:text-primary transition-colors font-bold uppercase text-sm tracking-wider">
                    View All Systems <span class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-2">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- 2. SLIDER AREA: Full width untuk scroll yang lancar --}}
        <div class="relative w-full">
            {{-- 
                Container Flex:
                - md:max-w-[1440px] md:mx-auto: Di PC tetap rapi di tengah.
                - px-4 md:px-10: Memberi jarak awal kartu agar sejajar judul.
                - overflow-x-auto: Mengaktifkan swipe.
            --}}
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-8 px-4 md:px-10 md:grid md:grid-cols-3 lg:grid-cols-4 custom-scrollbar max-w-[1440px] mx-auto md:overflow-visible">
                
                @foreach($featured as $index => $product)
                    {{-- 
                        Kartu:
                        - w-[80vw]: Jangan 85 agar kartu ke-2 lebih terlihat sebagai indikasi scroll.
                        - shrink-0: Mencegah kartu gepeng.
                    --}}
                    <div class="w-[80vw] sm:w-[350px] md:w-auto shrink-0 snap-start">
                        <x-product-home-card :product="$product" />
                    </div>
                @endforeach

                {{-- Spacer Akhir agar kartu terakhir tidak mepet kanan di HP --}}
                <div class="w-4 shrink-0 md:hidden"></div>
            </div>

            {{-- Indikator Fade Kanan (Mobile Only) --}}
            <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#080808] to-transparent pointer-events-none md:hidden z-20"></div>
        </div>

        {{-- Mobile View All Button --}}
        <div class="max-w-[1440px] mx-auto px-4 mt-4 md:hidden">
            <a href="{{ route('products.index') }}" class="w-full inline-block py-4 border border-white/20 text-white font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all clip-button text-center">
                View All Systems
            </a>
        </div>
    </div>
</div>
