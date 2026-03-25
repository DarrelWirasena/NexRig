<div>
    {{-- SECTION 5 ALTERNATIF: BATTLESTATION GALLERY --}}
    {{-- Tambahkan Alpine x-data untuk skeleton loading --}}
    <section class="scroll-trigger opacity-0 bg-[#080808] py-24 border-t border-white/5"
             x-data="{ isLoaded: false }" 
             x-init="window.addEventListener('load', () => { setTimeout(() => isLoaded = true, 500) })">
             
        <div class="max-w-[1440px] mx-auto px-4">
            
            {{-- Header (Dibuat tetap statis agar langsung terlihat) --}}
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic">Deployed <span class="text-primary">&</span> Operational</h2>
                <p class="text-gray-500 mt-4 uppercase tracking-[0.2em] text-xs font-bold">NexRig setup around the world</p>
            </div>

            {{-- ========================================== --}}
            {{-- 1. SKELETON UI (Tampil saat gambar loading) --}}
            {{-- ========================================== --}}
            <div x-show="!isLoaded" class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-pulse w-full">
                @for($i = 0; $i < 4; $i++)
                <div class="aspect-square bg-white/5 border border-white/10 rounded-xl relative overflow-hidden">
                    {{-- Aksen kilatan tipis di dalam skeleton --}}
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-transparent"></div>
                </div>
                @endfor
            </div>

            {{-- ========================================== --}}
            {{-- 2. KONTEN ASLI (Tampil setelah loading) --}}
            {{-- ========================================== --}}
            <div x-show="isLoaded" x-cloak x-transition.opacity.duration.700ms class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                {{-- Foto 1 --}}
                <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                    <img src="https://images.unsplash.com/photo-1547082299-de196ea013d6?q=80&w=600" loading="lazy" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #021</span>
                    </div>
                </div>
                
                {{-- Foto 2 --}}
                <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                    <img src="https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b?q=80&w=600" loading="lazy" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #044</span>
                    </div>
                </div>
                
                {{-- Foto 3 --}}
                <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                    <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=600" loading="lazy" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #089</span>
                    </div>
                </div>
                
                {{-- Foto 4 --}}
                <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden group relative">
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=600" loading="lazy" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold bg-primary px-2 py-1 uppercase italic">User Setup #102</span>
                    </div>
                </div>
                
            </div>
            
        </div>
    </section>
</div>