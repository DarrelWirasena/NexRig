@extends('layouts.app')

@section('content')

{{-- 1. HEADER & STEPPER --}}
<div class="max-w-7xl mx-auto py-12">
    <div class="flex items-center justify-center gap-4 mb-12">
        <div class="flex items-center gap-2 text-primary">
            <span class="font-bold text-sm">01</span>
            <span class="font-medium text-sm">Cart</span>
        </div>
        <div class="w-12 h-[1px] bg-slate-200 dark:bg-[#232948]"></div>
        <div class="flex items-center gap-2 text-slate-400">
            <span class="font-bold text-sm">02</span>
            <span class="font-medium text-sm">Shipping</span>
        </div>
        <div class="w-12 h-[1px] bg-slate-200 dark:bg-[#232948]"></div>
        <div class="flex items-center gap-2 text-slate-400">
            <span class="font-bold text-sm">03</span>
            <span class="font-medium text-sm">Payment</span>
        </div>
    </div>

    {{-- LOGIC: Cek Apakah Cart Kosong? --}}
    @if(session('cart') && count(session('cart')) > 0)
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- 2. PRODUCT LIST (Kiri) --}}
            <div class="lg:col-span-8">
                <div class="flex items-baseline justify-between mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">Your Rig Setup</h1>
                    <p class="text-slate-500 dark:text-[#929bc9]">{{ count(session('cart')) }} Build(s) in cart</p>
                </div>

                <div class="space-y-6">
                    @foreach(session('cart') as $id => $details)
                    <div class="group relative bg-white dark:bg-[#161b30] p-6 rounded-xl border border-slate-200 dark:border-[#232948] transition-all hover:border-primary/50">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="relative w-full md:w-48 aspect-[4/3] rounded-lg overflow-hidden bg-slate-100 dark:bg-[#111422]">
                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $details['name'] }}</h3>
                                        <p class="text-xl font-bold text-primary">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 text-sm text-slate-500 dark:text-[#929bc9] mb-6">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-xs">verified</span>
                                            <span>Official NexRig Build</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-xs">package_2</span>
                                            <span>Ready to Ship</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-[#232948]">
                                    
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-6 update-cart-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        
                                        <div class="flex items-center bg-slate-100 dark:bg-[#111422] rounded-lg p-1">
                                            <button type="button" onclick="updateQty(this, -1)" class="w-8 h-8 flex items-center justify-center hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-sm">remove</span>
                                            </button>
                                            
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" readonly class="w-10 bg-transparent text-center font-bold border-none p-0 focus:ring-0">
                                            
                                            <button type="button" onclick="updateQty(this, 1)" class="w-8 h-8 flex items-center justify-center hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-sm">add</span>
                                            </button>
                                        </div>
                                    </form>

                                    <a href="{{ route('cart.remove', $id) }}" class="text-sm font-medium text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                        Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="neon-divider opacity-20"></div>
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-dashed border-slate-300 dark:border-[#232948] bg-slate-50/50 dark:bg-transparent">
                        <span class="material-symbols-outlined text-primary">verified_user</span>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">NexGuard 2-Year Premium Warranty</p>
                            <p class="text-xs text-slate-500 dark:text-[#929bc9]">On-site repair and accidental damage coverage included.</p>
                        </div>
                        <span class="text-sm font-bold text-green-500">FREE</span>
                    </div>
                </div>
            </div>

            {{-- 3. SUMMARY SIDEBAR (Kanan) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-[#161b30] p-8 rounded-xl border border-slate-200 dark:border-[#232948]">
                        <h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white">Order Summary</h2>
                        
                        @php
                            $tax = $total * 0.11; // PPN 11%
                            $grandTotal = $total + $tax;
                        @endphp

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Subtotal</span>
                                <span class="text-slate-900 dark:text-white font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Shipping Estimate</span>
                                <span class="text-green-500 font-medium">Free</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Estimated Tax (11%)</span>
                                <span class="text-slate-900 dark:text-white font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-[#232948] mb-8">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-bold text-slate-900 dark:text-white">Total</span>
                                <span class="text-2xl font-bold text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- TOMBOL CHECKOUT (Login Check via Middleware/Controller) --}}
                        <a href="{{ route('checkout.index') }}" class="w-full bg-primary text-white font-bold py-4 rounded-lg glow-button transition-all flex items-center justify-center gap-2 mb-4 group hover:bg-blue-700">
                            <span>Secure Checkout</span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-[#929bc9]">
                                <span class="material-symbols-outlined text-sm">shield_lock</span>
                                <span>256-bit SSL Secure Encryption</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-[#929bc9]">
                                <span class="material-symbols-outlined text-sm">local_shipping</span>
                                <span>Estimated Delivery: 3-5 Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 flex flex-col gap-3">
                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors flex items-center justify-between">
                            <span>Continue Shopping</span>
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
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