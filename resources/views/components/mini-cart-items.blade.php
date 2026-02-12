@if(count($cart) > 0)
    <div class="space-y-4">
        @foreach($cart as $id => $details)
            <div id="cart-item-{{ $id }}" class="relative flex gap-4 p-3 rounded-xl bg-[#0a0a0a] border border-white/10 group hover:border-primary/50 transition-colors">
                
                {{-- Gambar Produk --}}
                <div class="w-20 h-20 bg-white/5 rounded-lg overflow-hidden shrink-0 border border-white/5">
                    <img src="{{ $details['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                
                {{-- Info Produk --}}
                <div class="flex-1 pr-6">
                    <h4 class="text-white font-bold text-sm line-clamp-2 leading-tight group-hover:text-primary transition-colors">
                        {{ $details['name'] }}
                    </h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">{{ $details['category'] ?? 'Component' }}</p>
                    
                    <div class="flex justify-between items-end mt-3">
                        <span class="text-primary font-bold text-sm">
                            Rp {{ number_format($details['price'], 0, ',', '.') }}
                        </span>
                        <span class="text-gray-500 text-[10px] font-mono bg-white/5 px-2 py-1 rounded border border-white/5">
                            Qty: {{ $details['quantity'] }}
                        </span>
                    </div>
                </div>

                {{-- TOMBOL HAPUS (Ajax) --}}
                <button onclick="removeCartItem('{{ $id }}')" 
                        class="absolute top-3 right-3 text-gray-600 hover:text-red-500 transition-colors p-1 hover:bg-red-500/10 rounded">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            </div>
        @endforeach
    </div>
@else
    {{-- TAMPILAN EMPTY STATE (Center Absolut) --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center py-12 px-8 opacity-75">
        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 relative border border-white/5">
            <span class="material-symbols-outlined text-5xl text-gray-600">shopping_cart_off</span>
            <div class="absolute top-6 right-6 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
        </div>
        
        <h4 class="text-xl font-bold text-white mb-2 italic uppercase">Your Rig is Empty</h4>
        <p class="text-gray-500 text-sm mb-8 max-w-[250px] leading-relaxed">
            Looks like you haven't added any premium gear to your build yet.
        </p>

        <button onclick="toggleMiniCart()" class="px-8 py-3 bg-white/5 hover:bg-primary hover:text-white text-gray-300 font-bold rounded-lg transition-all border border-white/10 text-[10px] tracking-widest uppercase flex items-center gap-2">
            Choose your Rig
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
    </div>
@endif