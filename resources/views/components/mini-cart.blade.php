<div id="miniCartOverlay" onclick="toggleMiniCart()" 
     class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[99] hidden transition-opacity duration-300 opacity-0">
</div>

<div id="miniCart" 
     class="fixed inset-y-0 right-0 w-full md:w-[450px] bg-[#050014] border-l border-white/10 z-[100] transform translate-x-full transition-transform duration-300 shadow-[-10px_0_30px_rgba(19,55,236,0.2)] flex flex-col">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-6 border-b border-white/10 bg-[#0a0a0a]">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">shopping_cart</span>
            My Rig Cart
        </h3>
        <button onclick="toggleMiniCart()" class="text-gray-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
    </div>

    {{-- BODY (LIST ITEM) --}}
    <div id="miniCartItems" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
        {{-- 
            KONTEN DI SINI AKAN DI-INJECT OLEH JAVASCRIPT 
            (Berdasarkan data session cart terbaru)
        --}}
        
        {{-- CONTOH ITEM (Template Statis untuk styling) --}}
        {{-- 
        <div class="flex gap-4 p-3 rounded-xl bg-[#0a0a0a] border border-white/10">
            <div class="w-20 h-20 bg-white/5 rounded-lg overflow-hidden shrink-0">
                <img src="..." class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <h4 class="text-white font-bold text-sm line-clamp-1">RTX 4090 Rog Strix</h4>
                <p class="text-gray-500 text-xs mb-2">Graphic Card</p>
                <div class="flex justify-between items-center">
                    <span class="text-primary font-bold text-sm">Rp 28.000.000</span>
                    <span class="text-gray-400 text-xs">x1</span>
                </div>
            </div>
        </div> 
        --}}
    </div>

    {{-- FOOTER (ACTIONS) --}}
    <div class="p-6 border-t border-white/10 bg-[#0a0a0a]">
        
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-400 text-sm uppercase font-bold tracking-widest">Subtotal</span>
            <span id="miniCartSubtotal" class="text-xl font-black text-white">Rp 0</span>
        </div>
        
        <p class="text-[10px] text-gray-500 mb-6 text-right">Shipping & taxes calculated at checkout</p>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('cart.index') }}" class="px-4 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold rounded-lg text-center transition-all text-sm">
                VIEW CART
            </a>
            <a href="{{ route('checkout.index') }}" class="px-4 py-3 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg text-center transition-all shadow-[0_0_15px_rgba(59,130,246,0.4)] text-sm flex items-center justify-center gap-2">
                CHECKOUT <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</div>