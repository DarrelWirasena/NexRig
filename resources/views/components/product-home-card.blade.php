@props(['product'])

<div class="group relative bg-[#121212] border border-white/5 hover:border-primary/50 transition-all duration-300 flex flex-col clip-corner">
    <div class="relative aspect-[4/5] overflow-hidden bg-black/50">
        <div class="absolute top-4 left-4 w-2 h-2 border-t border-l border-white/30 z-10"></div>
        <div class="absolute top-4 right-4 w-2 h-2 border-t border-r border-white/30 z-10"></div>
        
        <img src="{{ $product->images->where('is_primary', true)->first()->image_url ?? 'https://via.placeholder.com/500x600' }}" 
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 group-hover:contrast-110"
             alt="{{ $product->name }}">
        
        <div class="absolute inset-0 bg-primary/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
            <a href="{{ route('products.show', $product->slug) }}" class="px-6 py-3 border border-white text-white font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-colors">
                View Specs
            </a>
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col border-t border-white/5 bg-[#121212] group-hover:bg-[#1a1a1a] transition-colors">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-white font-bold text-lg uppercase italic truncate">{{ $product->name }}</h3>
        </div>
        
        {{-- Jika ingin dinamis ambil category name --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <span class="text-[10px] uppercase font-bold px-2 py-1 bg-white/5 text-gray-400 rounded border border-white/5">
                {{ $product->category->name ?? 'Gaming PC' }}
            </span>
        </div>

        <div class="mt-auto flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs text-gray-500 uppercase font-bold">Price</span>
                <span class="text-primary font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
           <form action="{{ route('cart.add', $product->id) }}" method="POST">
    @csrf
    {{-- Input hidden quantity (default 1) --}}
    <input type="hidden" name="quantity" value="1">

    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/5 hover:bg-blue-600 text-white transition-colors group" title="Add to Cart">
        <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
    </button>
</form>
        </div>
    </div>
</div>