@if(count($cart) > 0)
    @foreach($cart as $id => $details)
        <div id="cart-item-{{ $id }}" class="relative flex gap-4 p-3 rounded-xl bg-[#0a0a0a] border border-white/10 group hover:border-primary/50 transition-colors">
            
            {{-- Gambar Produk --}}
            <div class="w-20 h-20 bg-white/5 rounded-lg overflow-hidden shrink-0 border border-white/5">
                <img src="{{ $details['image'] }}" class="w-full h-full object-cover">
            </div>
            
            {{-- Info Produk --}}
            <div class="flex-1 pr-6"> {{-- pr-6 memberi ruang agar tidak nabrak tombol hapus --}}
                <h4 class="text-white font-bold text-sm line-clamp-2 leading-tight group-hover:text-primary transition-colors">
                    {{ $details['name'] }}
                </h4>
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
            {{-- Kita pakai onclick untuk memanggil fungsi JS --}}
            <button onclick="removeCartItem('{{ $id }}')" 
                    class="absolute top-3 right-3 text-gray-600 hover:text-red-500 transition-colors p-1 hover:bg-red-500/10 rounded">
                <span class="material-symbols-outlined text-lg">delete</span>
            </button>
        </div>
    @endforeach

@else
    {{-- TAMPILAN EMPTY STATE (KERANJANG KOSONG) --}}
    <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4 opacity-75">
        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 relative">
            <span class="material-symbols-outlined text-5xl text-gray-600">shopping_cart</span>
            {{-- Hiasan dot merah --}}
            <div class="absolute top-6 right-6 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
        </div>
        
        <h4 class="text-xl font-bold text-white mb-2">Your Rig is Empty</h4>
        <p class="text-gray-500 text-sm mb-8 max-w-[200px]">
            Looks like you haven't added any gear yet.
        </p>

        <button onclick="toggleMiniCart()" class="px-6 py-2 bg-white/10 hover:bg-primary hover:text-white text-gray-300 font-bold rounded-lg transition-all border border-white/10 text-sm flex items-center gap-2">
            Start Building
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
    </div>
@endif