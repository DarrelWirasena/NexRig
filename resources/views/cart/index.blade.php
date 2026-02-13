@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">
    {{-- Stepper tetap sama --}}
    
    @if(session('cart') && count(session('cart')) > 0)
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- PRODUCT LIST --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-baseline justify-between mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900 dark:text-white font-display uppercase italic">Your Rig Setup</h1>
                    <p class="text-slate-500 dark:text-[#929bc9] font-mono">{{ count(session('cart')) }} Item(s)</p>
                </div>

                @foreach(session('cart') as $id => $details)
                {{-- Tambahkan ID unik per baris item --}}
                <div id="cart-row-{{ $id }}" class="group relative bg-white dark:bg-[#0a0a0a] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-white/10 transition-all hover:border-primary/50 shadow-lg shadow-black/5">
                    <div class="flex flex-col sm:flex-row gap-6">
                        
                        <div class="relative w-full sm:w-40 aspect-square rounded-lg overflow-hidden bg-slate-100 dark:bg-[#111422] border border-white/5 shrink-0">
                            <img src="{{ $details['image'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2 gap-4">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ $details['name'] }}</h3>
                                    <p class="text-lg font-bold text-primary whitespace-nowrap">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2 text-xs text-slate-500 dark:text-[#929bc9] mb-4">
                                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-white/5 px-2 py-1 rounded">
                                        <span class="material-symbols-outlined text-[14px]">verified</span><span>Official Build</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/10">
                                
                                {{-- BUTTON QUANTITY (Tanpa Form, Pake OnClick) --}}
                                <div class="flex items-center bg-slate-100 dark:bg-[#111422] rounded-lg p-1 border border-white/5">
                                    <button type="button" onclick="updateMainCartItem('{{ $id }}', -1)" 
                                            class="w-8 h-8 flex items-center justify-center hover:bg-white/10 hover:text-red-500 rounded transition-colors {{ $details['quantity'] <= 1 ? 'opacity-30 pointer-events-none' : '' }}">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                    
                                    {{-- ID UNIK untuk Angka --}}
                                    <span id="qty-display-{{ $id }}" class="w-10 text-center font-bold text-sm text-slate-900 dark:text-white font-mono">
                                        {{ $details['quantity'] }}
                                    </span>
                                    
                                    <button type="button" onclick="updateMainCartItem('{{ $id }}', 1)" 
                                            class="w-8 h-8 flex items-center justify-center hover:bg-white/10 hover:text-primary rounded transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>

                                {{-- BUTTON REMOVE (Tanpa Form) --}}
                                <button onclick="removeMainCartItem('{{ $id }}')" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1 group/del">
                                    <span class="material-symbols-outlined text-base group-hover/del:animate-bounce">delete</span>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- SUMMARY SIDEBAR --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-[#0a0a0a] p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl">
                        <h2 class="text-xl font-black italic uppercase mb-6 text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary block"></span> Order Summary
                        </h2>
                        
                        @php
                            $tax = $total * 0.11; 
                            $grandTotal = $total + $tax;
                        @endphp

                        <div class="space-y-4 mb-8 font-mono text-sm">
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Subtotal</span>
                                {{-- ID UNTUK SUBTOTAL --}}
                                <span id="summary-subtotal" class="text-slate-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Shipping</span>
                                <span class="text-green-500">Free via JNE Trucking</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Tax (11%)</span>
                                {{-- ID UNTUK TAX --}}
                                <span id="summary-tax" class="text-slate-900 dark:text-white">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-white/10 mb-8">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-bold text-slate-900 dark:text-white uppercase">Total</span>
                                {{-- ID UNTUK GRAND TOTAL --}}
                                <span id="summary-grand-total" class="text-2xl font-black text-primary italic">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="w-full bg-primary hover:bg-blue-600 text-white font-black italic uppercase py-4 rounded-lg shadow-[0_0_20px_rgba(19,55,236,0.5)] transition-all flex items-center justify-center gap-2">
                            Secure Checkout <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    @else
        {{-- EMPTY STATE (Jika Cart Kosong) --}}
        <div class="text-center py-24">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 dark:bg-[#161b30] mb-6">
                <span class="material-symbols-outlined text-4xl text-slate-400">shopping_cart_off</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Your cart is empty</h2>
            <p class="text-slate-500 dark:text-[#929bc9] mb-8">Looks like you haven't made your choice yet.</p>
            <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                Start Building Now
            </a>
        </div>
    @endif

</div>

{{-- JAVASCRIPT UNTUK UPDATE QUANTITY --}}
<script>
    function updateQty(btn, change) {
        // Cari input quantity terdekat dari tombol yang diklik
        let form = btn.closest('form');
        let input = form.querySelector('input[name="quantity"]');
        let currentVal = parseInt(input.value);
        
        // Hitung nilai baru
        let newVal = currentVal + change;
        
        // Minimal 1
        if(newVal < 1) return;

        // Set nilai input
        input.value = newVal;

        // Submit form otomatis
        form.submit();
    }
</script>

@endsection