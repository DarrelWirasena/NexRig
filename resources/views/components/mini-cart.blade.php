@php
    $cartItems = [];
    $subtotal = 0;

    if(auth()->check()) {
        // A. JIKA LOGIN: Ambil dari Database
        // Kita load relasi product dan images agar hemat query
        $dbCart = \App\Models\CartItem::with(['product.images', 'product.series.category'])
                    ->where('user_id', auth()->id())
                    ->get();

        foreach($dbCart as $item) {
            $cartItems[] = (object) [
                'row_id' => $item->id, // ID untuk hapus (Cart ID)
                'name' => $item->product->name,
                'price' => $item->product->price,
                // Pakai Accessor 'src' yang sudah kita buat sebelumnya
                'image' => $item->product->images->first()->src ?? 'https://placehold.co/100',
                'quantity' => $item->quantity,
                'category' => $item->product->series->category->name ?? 'Component'
            ];
            $subtotal += $item->product->price * $item->quantity;
        }

    } else {
        // B. JIKA GUEST: Ambil dari Session
        $sessionCart = session('cart', []);
        
        foreach($sessionCart as $productId => $details) {
            $cartItems[] = (object) [
                'row_id' => $productId, // ID untuk hapus (Product ID)
                'name' => $details['name'],
                'price' => $details['price'],
                'image' => $details['image'],
                'quantity' => $details['quantity'],
                'category' => $details['category'] ?? 'Component'
            ];
            $subtotal += $details['price'] * $details['quantity'];
        }
    }
@endphp

{{-- 1. OVERLAY --}}
<div id="miniCartOverlay" onclick="toggleMiniCart()" 
     class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[99] hidden transition-opacity duration-300 opacity-0">
</div>

{{-- 2. SIDEBAR --}}
<div id="miniCart" 
     class="fixed inset-y-0 right-0 w-full md:w-[450px] bg-[#050014] border-l border-white/10 z-[100] transform translate-x-full transition-transform duration-300 shadow-[-10px_0_30px_rgba(19,55,236,0.2)] flex flex-col">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-6 border-b border-white/10 bg-[#0a0a0a] shrink-0">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">shopping_cart</span>
            My Rig Cart
        </h3>
        <button onclick="toggleMiniCart()" class="text-gray-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
    </div>

    {{-- BODY (LIST ITEM) --}}
    {{-- Kita tambahkan 'relative' dan 'flex-1' agar empty state bisa centering --}}
    <div id="miniCartItems" class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">
        {{-- Kirim variable $cartItems yang sudah distandarisasi --}}
        @include('components.mini-cart-items', ['items' => $cartItems])
    </div>

    {{-- FOOTER (ACTIONS) --}}
    <div class="p-6 border-t border-white/10 bg-[#0a0a0a] shrink-0">
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-400 text-sm uppercase font-bold tracking-widest">Subtotal</span>
            <span id="miniCartSubtotal" class="text-xl font-black text-white">
                Rp {{ number_format($subtotal, 0, ',', '.') }}
            </span>
        </div>
        
        <p class="text-[10px] text-gray-500 mb-6 text-right font-mono tracking-tighter uppercase opacity-50">
            Shipping & taxes calculated at checkout
        </p>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('cart.index') }}" class="px-4 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold rounded-lg text-center transition-all text-[11px] tracking-widest uppercase">
                View Cart
            </a>
            
           {{-- Kita buat satu anchor tunggal, statusnya kita kontrol via JS --}}
            <a href="{{ route('checkout.index') }}" 
                id="miniCartCheckoutBtn" 
                class="px-4 py-3 font-bold rounded-lg text-center transition-all text-[11px] tracking-widest uppercase flex items-center justify-center gap-2 
                {{ count($cartItems) > 0 ? 'bg-primary text-white shadow-[0_0_15px_rgba(59,130,246,0.4)]' : 'bg-white/10 text-gray-600 cursor-not-allowed pointer-events-none' }}">   
                <span id="checkoutBtnText">{{ count($cartItems) > 0 ? 'Checkout' : 'Empty' }}</span>
                <span id="checkoutBtnIcon" class="material-symbols-outlined text-sm">
                    {{ count($cartItems) > 0 ? 'arrow_forward' : 'lock' }}
                </span>
            </a>
        </div>
    </div>
</div>