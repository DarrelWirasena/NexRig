<div>
   {{-- SECTION 1: CINEMATIC HERO --}}
    <div x-data="{}" class="relative w-full min-h-screen flex flex-col justify-center items-center overflow-hidden bg-[#050505]">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1624705002806-5d72df8343f7?q=80&w=2500" 
                 class="w-full h-full object-cover opacity-60 mix-blend-luminosity scale-105 animate-pulse" style="animation-duration: 10s"
                 alt="Hero Background">
            <div class="absolute inset-0 bg-gradient-to-b from-[#050505]/30 via-transparent to-[#050505]"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto -mt-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/30 bg-primary/10 backdrop-blur-md mb-6 animate-bounce">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                <span class="text-primary text-xs font-bold tracking-widest uppercase">Next Gen Hardware Ready</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-black text-white tracking-tighter mb-4 leading-[0.9]">
                BEYOND <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-primary to-cyan-400 text-glow">REALITY</span>
            </h1>
            
            <p class="text-gray-400 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Precision-engineered gaming systems designed to dominate. 
                Experience <span class="text-white font-bold">4K Ray Tracing</span> like never before.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('products.index') }}" class="group relative px-8 py-4 bg-primary text-white font-bold text-lg tracking-widest uppercase clip-button transition-all hover:bg-white hover:text-black hover:shadow-[0_0_30px_rgba(19,55,236,0.6)]">
                    Choose Your Rig
                </a>
                {{-- Tambahkan x-data dan x-on di sekitar atau pada container hero jika belum ada --}}
                <a href="javascript:void(0)" 
                    @click="console.log('tombol dikil'); $dispatch('open-video', { url: 'https://www.youtube.com/embed/Gf4j-x-WfGM?autoplay=1' })"
                    class="flex items-center gap-2 text-white hover:text-primary transition-colors font-medium uppercase tracking-wider text-sm">
                        <span class="material-symbols-outlined">play_circle</span> Watch Showcase
                </a>
            </div>
        </div>
    </div>
</div>
