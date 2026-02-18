@props(['product'])

<div class="group relative bg-[#121212] border border-white/5 hover:border-primary/50 transition-all duration-500 flex flex-col clip-corner hover:shadow-[0_0_30px_rgba(19,55,236,0.15)] h-full">
    
    {{-- 1. LINK TRANSPARAN (KHUSUS MOBILE) --}}
    {{-- Di mobile, seluruh kartu bisa diklik. Di desktop, link ini dimatikan (hidden md:block) --}}
    <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-10 md:hidden">
        <span class="sr-only">View {{ $product->name }}</span>
    </a>

    {{-- IMAGE CONTAINER --}}
    <div class="relative aspect-square md:aspect-[4/5] overflow-hidden bg-black/50">
        {{-- Decorative Corners (Tetap ada) --}}
        <div class="hidden md:block absolute top-4 left-4 w-2 h-2 border-t border-l border-white/30 z-20 transition-all group-hover:w-16 group-hover:h-16 group-hover:border-primary/50"></div>
        <div class="hidden md:block absolute top-4 right-4 w-2 h-2 border-t border-r border-white/30 z-20 transition-all group-hover:w-16 group-hover:h-16 group-hover:border-primary/50"></div>
        
        <img src="{{ $product->images->where('is_primary', true)->first()->src ?? 'https://via.placeholder.com/500x600' }}" 
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:contrast-110"
             alt="{{ $product->name }}">
        
        {{-- OVERLAY HOVER --}}
        {{-- 
            z-30 agar tombol berada di atas link transparan. 
            pointer-events-none + group-hover:pointer-events-auto 
            memastikan tombol tidak menghalangi link utama sebelum muncul.
        --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 p-4 z-30 pointer-events-none group-hover:pointer-events-auto">
            
            {{-- Tombol VIEW SPECS --}}
            <a href="{{ route('products.show', $product->slug) }}" 
               class="w-full max-w-[200px] py-3 border border-white/30 text-white font-bold uppercase tracking-widest text-[10px] md:text-xs hover:border-white hover:bg-white hover:text-black transition-all text-center">
                View Specs
            </a>

            {{-- Tombol ADD TO CART (AJAX) --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST" onsubmit="addToCartAjax(event, this)" class="w-full max-w-[200px]">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" 
                        class="w-full py-3 bg-primary text-white font-bold uppercase tracking-widest text-[10px] md:text-xs hover:bg-blue-600 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-primary/50">
                    <span>Add to Cart</span>
                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                </button>
            </form>
        </div>
    </div>

    {{-- INFO SECTION --}}
    <div class="p-4 md:p-5 flex-1 flex flex-col border-t border-white/5 bg-[#121212] group-hover:bg-[#161b30] transition-colors duration-300">
        <div class="flex justify-between items-start mb-1 md:mb-2">
            <h3 class="text-white font-bold text-base md:text-lg uppercase italic truncate group-hover:text-primary transition-colors">
                {{ $product->name }}
            </h3>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-3 md:mb-6">
            <span class="text-[9px] md:text-[10px] uppercase font-bold px-2 py-1 bg-white/5 text-gray-400 rounded border border-white/5">
                {{ $product->series->category->name ?? 'Gaming PC' }}
            </span>
        </div>

        <div class="mt-auto pt-3 md:pt-4 border-t border-white/5 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[9px] md:text-[10px] text-gray-500 uppercase font-bold tracking-wider">Starting From</span>
                <span class="text-white font-bold text-base md:text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>

            {{-- INDIKASI CLICKABLE (Hanya muncul di Mobile) --}}
            <div class="md:hidden text-primary">
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </div>
        </div>
    </div>
</div>