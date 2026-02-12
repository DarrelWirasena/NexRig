@props([
    'title' => 'READY TO ASCEND?',
    'description' => 'Join thousands of gamers who have upgraded their battle station with NexRig. Built by gamers, for gamers.',
    'buttonText' => 'DEPLOY NOW',
    'buttonLink' => route('products.index')
])

{{-- 
    Class 'scroll-trigger' dan 'opacity-0' adalah kunci untuk animasi 'In' dari bawah. 
    Pastikan script Intersection Observer sudah terpasang di layout Anda.
--}}
<div {{ $attributes->merge(['class' => 'scroll-trigger  bg-[#11141d] py-24 px-6 text-center relative overflow-hidden border-t border-white/5']) }}>
    
    {{-- Subtle Noise Texture Overlay untuk kesan premium --}}
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] pointer-events-none"></div>
    
    <div class="relative z-10 max-w-4xl mx-auto">
        {{-- Judul: Inter 900 Italic, Tracking Rapat (Agresif) --}}
        <h2 class="font-gaming text-5xl md:text-7xl text-white uppercase tracking-[-0.06em] mb-6 leading-none drop-shadow-lg">
            {{ $title }}
        </h2>
        
        {{-- Deskripsi: Clean & Minimalist --}}
        <p class="text-gray-400 text-sm md:text-lg mb-12 max-w-2xl mx-auto leading-relaxed opacity-90">
            {{ $description }}
        </p>
        
        <div class="flex justify-center">
            {{-- Tombol: Hitam pekat dengan Clip-Path Tajam (Style NexRig) --}}
            <a href="{{ $buttonLink }}" 
               class="inline-block px-12 py-4 bg-black text-white font-black text-sm tracking-[0.2em] uppercase transition-all hover:bg-blue-600 hover:scale-105 active:scale-95 shadow-[0_20px_50px_rgba(0,0,0,0.5)]"
               style="clip-path: polygon(0 0, 100% 0, 100% 70%, 88% 100%, 0 100%);">
                {{ $buttonText }}
            </a>
        </div>
    </div>
</div>