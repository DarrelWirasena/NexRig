{{-- SECTION: CTA --}}
<div class="scroll-trigger opacity-0 relative w-full overflow-hidden py-24 md:py-32 px-4">

    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#050505] z-0"></div>
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
        <div class="w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px]"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 max-w-3xl mx-auto text-center">

        {{-- Label --}}
        <p class="text-blue-500 font-mono text-[10px] uppercase tracking-[0.4em] mb-8">
            — Ready to Upgrade —
        </p>

        {{-- Heading --}}
        <h2 class="text-5xl md:text-7xl font-black italic uppercase tracking-tighter mb-6
                   text-white">
            Built to <span class="text-primary">Dominate.</span>
        </h2>

        {{-- Description --}}
        <p class="text-gray-500 text-sm md:text-base mb-12 max-w-xl mx-auto leading-relaxed">
            Engineering meets performance. Every rig built by
            <span class="text-white font-semibold">NexRig</span>
            is precision-tuned for those who refuse to settle.
        </p>

        {{-- Buttons --}}
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">

            {{-- Primary --}}
            <a href="{{ route('products.index') }}"
               class="w-full sm:w-auto px-10 py-4
                      bg-primary hover:bg-blue-500
                      text-white font-bold text-xs uppercase tracking-widest
                      transition-all duration-200
                      hover:-translate-y-0.5
                      hover:shadow-[0_8px_30px_rgba(37,99,235,0.4)]
                      flex items-center justify-center gap-2">
                Shop Rigs
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>

            {{-- Secondary --}}
            <a href="{{ route('about') }}"
               class="w-full sm:w-auto px-10 py-4
                      border border-white/15 hover:border-white/40
                      text-white/60 hover:text-white
                      font-bold text-xs uppercase tracking-widest
                      transition-all duration-200
                      flex items-center justify-center">
                About Us
            </a>

        </div>
    </div>
</div>