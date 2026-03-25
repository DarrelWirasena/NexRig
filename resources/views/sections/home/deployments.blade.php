<div>
    {{-- SECTION 4: LATEST DEPLOYMENTS --}}
    {{-- Tambahkan Alpine.js x-data untuk mendeteksi status loading --}}
    <div class="scroll-trigger opacity-0 w-full bg-[#080808] py-16 md:py-24 border-t border-white/5 relative"
         x-data="{ isLoaded: false }" 
         x-init="window.addEventListener('load', () => { setTimeout(() => isLoaded = true, 500) })">

        {{-- Decorative Lines (PC Only) --}}
        <div class="hidden md:block absolute top-0 left-10 w-px h-24 bg-gradient-to-b from-primary to-transparent"></div>
        <div class="hidden md:block absolute bottom-0 right-10 w-px h-24 bg-gradient-to-t from-primary to-transparent"></div>

        {{-- ========================================== --}}
        {{-- 1. SKELETON UI (Tampil saat loading) --}}
        {{-- ========================================== --}}
        <div x-show="!isLoaded" class="w-full relative animate-pulse">
            
            {{-- Header Skeleton --}}
            <div class="max-w-[1440px] mx-auto px-4 md:px-10 mb-8 md:mb-12">
                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-8 h-8 bg-white/10 rounded"></div>
                        <div class="w-48 md:w-64 h-8 md:h-10 bg-white/10 rounded"></div>
                    </div>
                    <div class="hidden md:block w-32 h-5 bg-white/10 rounded"></div>
                </div>
            </div>

            {{-- Slider Area Skeleton --}}
            <div class="flex overflow-hidden gap-4 pb-8 px-4 md:px-10 md:grid md:grid-cols-3 lg:grid-cols-4 max-w-[1440px] mx-auto">
                @for($i = 0; $i < 4; $i++)
                <div class="w-[80vw] sm:w-[350px] md:w-auto shrink-0 bg-white/5 border border-white/10 rounded-2xl h-[400px] md:h-[450px] flex flex-col overflow-hidden">
                    {{-- Image Skeleton --}}
                    <div class="w-full h-[55%] bg-white/10"></div>
                    
                    {{-- Content Skeleton --}}
                    <div class="p-5 flex flex-col flex-1 gap-3">
                        <div class="w-1/3 h-3 bg-white/10 rounded"></div>
                        <div class="w-3/4 h-6 bg-white/10 rounded mb-2"></div>
                        
                        <div class="w-full h-2 bg-white/5 rounded"></div>
                        <div class="w-5/6 h-2 bg-white/5 rounded"></div>
                        
                        <div class="mt-auto flex justify-between items-end pt-4 border-t border-white/5">
                            <div class="w-1/2 h-5 bg-white/10 rounded"></div>
                            <div class="w-10 h-10 bg-white/10 rounded-xl"></div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- 2. KONTEN ASLI (Tampil setelah loading selesai) --}}
        {{-- ========================================== --}}
        <div x-show="isLoaded" x-cloak x-transition.opacity.duration.700ms>
            
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
                <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-8 px-4 md:px-10 md:grid md:grid-cols-3 lg:grid-cols-4 custom-scrollbar max-w-[1440px] mx-auto md:overflow-visible">

                    @foreach($featured as $index => $product)
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
</div>