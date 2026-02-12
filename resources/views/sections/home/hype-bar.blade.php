<div>
   {{-- SECTION 2: INFINITE RUNNING TEXT (Hype Bar) --}}
    @php
        $hypes = [
            "High FPS Guarantee",
            "RTX 50-Series Ready",
            "Liquid Cooled",
            "24/7 Stress Tested",
            "Lifetime Support",
            "Zero Bloatware",
            "Professional Cable Management"
        ];
    @endphp

    <div class="bg-primary text-white py-4 overflow-hidden border-y border-white/10 relative z-20 group">
        {{-- Wrapper Utama --}}
        <div class="flex">
            
            {{-- SET 1 (Original) --}}
            {{-- Perhatikan class 'min-w-full' dan gap di sini --}}
            <div class="animate-infinite-scroll flex items-center gap-16 px-8"> 
                @foreach($hypes as $text)
                    <span class="font-black italic uppercase tracking-widest text-lg opacity-90 whitespace-nowrap">
                        {{ $text }}
                    </span>
                    <span class="text-black text-xl">•</span>
                @endforeach
            </div>

            {{-- SET 2 (Duplicate) --}}
            <div class="animate-infinite-scroll flex items-center gap-16 px-8" aria-hidden="true">
                @foreach($hypes as $text)
                    <span class="font-black italic uppercase tracking-widest text-lg opacity-90 whitespace-nowrap">
                        {{ $text }}
                    </span>
                    <span class="text-black text-xl">•</span>
                @endforeach
            </div>

        </div>
        
        {{-- Fade Effect --}}
        <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-primary to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-primary to-transparent z-10 pointer-events-none"></div>
    </div>
</div>
