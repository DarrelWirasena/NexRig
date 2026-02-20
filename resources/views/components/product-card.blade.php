@props(['name', 'price', 'description', 'image', 'rating' => null, 'badge' => null, 'specs' => []])

{{-- DEBUG --}}
<!-- IMAGE URL: {{ $image }} -->

<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary transition-all duration-300 h-full shadow-sm hover:shadow-xl hover:shadow-primary/10">
    {{-- IMAGE SECTION --}}
    <div class="relative aspect-square md:aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden shrink-0">
        <img src="{{ $image }}" alt="{{ $name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
        
        @if($badge)
            <div class="absolute top-2 left-2 md:top-3 md:left-3 bg-primary text-white text-[10px] md:text-xs font-bold px-2 py-0.5 md:px-2.5 md:py-1 rounded backdrop-blur-sm shadow-md z-10">
                {{ $badge }}
            </div>
        @endif
    </div>

    {{-- CONTENT SECTION --}}
    <div class="p-3 md:p-5 flex flex-col flex-1">
        
        {{-- HEADER --}}
        <div class="mb-1">
            <h3 class="text-gray-900 dark:text-white text-sm md:text-lg font-bold group-hover:text-primary transition-colors line-clamp-1 leading-tight">
                {{ $name }}
            </h3>
        </div>
        
        {{-- DESCRIPTION --}}
        {{-- Mobile: Tampil 1 baris, text-xs (sangat kecil) --}}
        {{-- Desktop: Tampil 2 baris, text-sm --}}
        <p class="text-gray-500 dark:text-[#929bc9] text-[10px] md:text-sm mb-2 line-clamp-1 md:line-clamp-2 leading-tight">
            {{ $description }}
        </p>

        {{-- SPECS (The Core Logic) --}}
        <div class="flex flex-wrap gap-1.5 md:gap-2 mb-3 md:mb-6 mt-auto">
            @foreach($specs as $index => $spec)
                {{-- LOGIC: 
                     - Mobile: Hanya tampilkan index 0 & 1 (2 item pertama).
                     - Desktop: Tampilkan semua.
                --}}
                <span class="{{ $index > 1 ? 'hidden md:inline-block' : 'inline-block' }} 
                             px-1.5 py-0.5 md:px-2 md:py-1 rounded 
                             bg-gray-100 dark:bg-[#232948] 
                             text-gray-700 dark:text-gray-300 
                             text-[10px] md:text-xs font-medium 
                             border border-gray-200 dark:border-white/5 whitespace-nowrap">
                    {{ $spec }}
                </span>
            @endforeach
            
            {{-- Indikator "+More" di Mobile jika spek lebih dari 2 --}}
            @if(count($specs) > 2)
                <span class="md:hidden text-[10px] text-gray-400 self-center">+{{ count($specs) - 2 }}</span>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="pt-2 md:pt-4 border-t border-gray-200 dark:border-[#232948] flex items-center justify-between gap-2">
            
            <div class="flex flex-col">
                <span class="hidden md:block text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
                {{-- Harga di mobile sedikit diperkecil --}}
                <span class="text-gray-900 dark:text-white text-sm md:text-lg font-bold">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </span>
            </div>

            {{-- Button --}}
            <button class="px-3 py-1.5 md:px-4 md:py-2 bg-primary hover:bg-blue-600 text-white text-[10px] md:text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30 whitespace-nowrap">
                Buy
            </button>
        </div>
    </div>
</div>