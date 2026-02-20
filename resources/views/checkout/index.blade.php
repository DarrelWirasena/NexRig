@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY (Tetap Sama) --}}
<div id="checkout-loading" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center">
    <div class="relative w-24 h-24 mb-4">
        <div class="absolute inset-0 border-4 border-white/10 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-primary rounded-full border-t-transparent animate-spin"></div>
        <span class="material-symbols-outlined absolute inset-0 flex items-center justify-center text-primary text-3xl animate-pulse">rocket_launch</span>
    </div>
    <h3 class="text-white font-black text-2xl uppercase italic tracking-widest mb-1">Deploying Order</h3>
    <p class="text-slate-400 font-mono text-xs uppercase tracking-[0.3em]">Securing payment channel...</p>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">
    
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        {{-- 1. STEPPER (Tetap Sama) --}}
        <div class="flex items-center justify-center gap-4 mb-12 select-none">
            {{-- Step 1 --}}
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 opacity-50">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">1</span>
                <span class="font-bold text-sm hidden sm:block">Inventory</span>
            </div>
            <div class="w-12 h-[1px] bg-slate-800"></div>
            
            {{-- Step 2 --}}
            <div class="flex items-center gap-2 text-primary">
                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(19,55,236,0.5)]">2</span>
                <span class="font-bold text-sm uppercase tracking-wider">Deployment</span>
            </div>
            
            <div class="w-12 h-[1px] bg-slate-800"></div>
            
            {{-- Step 3 --}}
            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-700">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">3</span>
                <span class="font-bold text-sm hidden sm:block">Payment</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- KOLOM KIRI (Tetap Sama) --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- A. SHIPPING SECTION (Tetap Sama) --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group">
                     {{-- ... (Isi Shipping Section tidak berubah karena pakai $address) ... --}}
                     <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">pin_drop</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">
                            Drop Zone Location
                        </h2>
                    </div>

                    @if($address)
                        {{-- VIEW: ADDRESS CARD --}}
                        <div class="relative bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5 rounded-xl p-6 transition-all hover:border-primary/50 group/card">
                            
                            <a href="{{ route('address.index', ['origin' => 'checkout']) }}" 
                               class="absolute top-4 right-4 text-[10px] font-bold text-slate-500 hover:text-white bg-white dark:bg-white/5 hover:bg-primary border border-slate-200 dark:border-white/10 px-3 py-1.5 rounded-lg transition-all uppercase tracking-widest flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit_location</span> Change
                            </a>

                            <div class="flex items-start gap-4">
                                <div class="mt-1 p-3 bg-white dark:bg-[#050505] rounded-lg border border-slate-200 dark:border-white/10 text-primary shadow-sm shrink-0">
                                    <span class="material-symbols-outlined">satellite_alt</span>
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="font-bold text-slate-900 dark:text-white text-lg">{{ $address->recipient_name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-primary/10 text-primary border border-primary/20">{{ $address->label }}</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-mono text-xs mb-2 tracking-tight">{{ $address->phone }}</p>
                                    
                                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed max-w-md">
                                        {{ $address->full_address }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <span>{{ $address->city }}</span>
                                        <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                                        <span>{{ $address->postal_code }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    @else
                        {{-- VIEW: NEW ADDRESS FORM --}}
                        <div class="rounded-xl border border-dashed border-slate-300 dark:border-white/20 bg-slate-50/50 dark:bg-white/5 p-6 sm:p-8">
                            <div class="mb-6 flex items-start gap-4 p-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-500">
                                <span class="material-symbols-outlined text-2xl animate-pulse">warning</span>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest mb-1">Location Data Missing</h4>
                                    <p class="text-[11px] opacity-80 leading-relaxed">System requires valid delivery coordinates to initialize deployment logic.</p>
                                </div>
                            </div>
                            @if($errors->any())
                                <div class="flex items-start gap-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
                                    <span class="material-symbols-outlined text-2xl">error</span>
                                    <div>
                                        <h4 class="text-xs font-black uppercase tracking-widest mb-1">Validation Error</h4>
                                        <p class="text-[11px] opacity-80 leading-relaxed">{{ $errors->first() }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="space-y-4">
                                <x-address-form-fields />
                            </div>
                            <input type="hidden" name="is_default" value="1">
                        </div>
                    @endif
                </div>

                {{-- B. PAYMENT SECTION (Tetap Sama) --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl">
                    {{-- ... (Isi Payment Section tidak berubah) ... --}}
                     <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">
                            Payment Protocol
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="bank_transfer" checked class="peer sr-only">
                            <div class="h-full flex flex-col items-center justify-center gap-4 p-6 rounded-xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111422] peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:shadow-[0_0_20px_rgba(19,55,236,0.15)] transition-all">
                                <span class="material-symbols-outlined text-4xl text-slate-400 peer-checked:text-primary transition-colors">account_balance</span>
                                <span class="font-bold text-xs uppercase tracking-widest text-slate-500 dark:text-slate-300 peer-checked:text-primary">Bank Transfer</span>
                                <div class="absolute -top-3 -right-3 w-6 h-6 bg-primary rounded-full items-center justify-center shadow-lg border-2 border-[#0a0a0a] hidden peer-checked:flex animate-reveal">
                                    <span class="material-symbols-outlined text-white text-[14px] font-bold">check</span>
                                </div>
                            </div>
                        </label>
                        
                        <div class="opacity-40 grayscale flex flex-col items-center justify-center gap-4 p-6 rounded-xl border border-dashed border-slate-300 dark:border-white/10 bg-transparent cursor-not-allowed">
                            <span class="material-symbols-outlined text-4xl text-slate-500">credit_card</span>
                            <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500">Credit Card</span>
                        </div>

                        <div class="opacity-40 grayscale flex flex-col items-center justify-center gap-4 p-6 rounded-xl border border-dashed border-slate-300 dark:border-white/10 bg-transparent cursor-not-allowed">
                            <span class="material-symbols-outlined text-4xl text-slate-500">qr_code_scanner</span>
                            <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500">QRIS</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center gap-2 text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-white/5 p-3 rounded-lg border border-slate-200 dark:border-white/5">
                        <span class="material-symbols-outlined text-base">info</span>
                        <p>Manual verification required. Deployment initiates after payment confirmation.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: ORDER SUMMARY (YANG DIPERBAIKI) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
                        
                        <h2 class="text-lg font-black mb-6 text-slate-900 dark:text-white uppercase italic tracking-widest flex items-center gap-2">
                            <span class="w-1 h-5 bg-primary block"></span> Hardware Manifest
                        </h2>
                        
                        {{-- Item List (Scrollable) --}}
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-sidebar-scroll mb-6">
                            {{-- [FIX] Ubah session('cart') menjadi $cartItems --}}
                            @foreach($cartItems as $item)
                                <div class="flex gap-4 p-3 rounded-lg bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5">
                                    {{-- Image --}}
                                    <div class="w-14 h-14 rounded overflow-hidden flex-shrink-0 bg-white dark:bg-black border border-slate-200 dark:border-white/10">
                                        {{-- [FIX] Akses Object: $item->image --}}
                                        <img src="{{ $item->image }}" class="w-full h-full object-cover">
                                    </div>
                                    
                                    {{-- Info --}}
                                    <div class="flex flex-col justify-between py-0.5 flex-1">
                                        {{-- [FIX] Akses Object: $item->name --}}
                                        <p class="font-bold text-[11px] text-slate-900 dark:text-white line-clamp-1 uppercase tracking-wide">{{ $item->name }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            {{-- [FIX] Akses Object: $item->quantity --}}
                                            <span class="text-[10px] text-slate-500 font-mono bg-white dark:bg-black px-1.5 py-0.5 rounded border border-slate-200 dark:border-white/10">x{{ $item->quantity }}</span>
                                            {{-- [FIX] Akses Object: $item->price --}}
                                            <p class="text-primary font-bold text-xs font-mono">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Calculation --}}
                        {{-- [FIX] Hapus logic PHP perhitungan, gunakan variabel dari Controller --}}
                        
                        <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-white/10 mb-8 font-mono text-xs">
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span class="uppercase tracking-widest font-bold">Subtotal</span>
                                {{-- [FIX] Gunakan $subtotal dari Controller --}}
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span class="uppercase tracking-widest font-bold">Tax (11%)</span>
                                {{-- [FIX] Gunakan $tax dari Controller --}}
                                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-white/5 mt-2">
                                <span class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest font-display">Grand Total</span>
                                {{-- [FIX] Gunakan $grandTotal dari Controller --}}
                                <span class="text-xl font-black text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-black py-4 rounded-xl shadow-[0_0_20px_rgba(19,55,236,0.4)] hover:shadow-[0_0_30px_rgba(19,55,236,0.6)] transition-all flex items-center justify-center gap-2 group uppercase italic tracking-[0.2em] text-sm relative overflow-hidden">
                            <span class="relative z-10">Deploy Order</span>
                            <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform relative z-10">rocket_launch</span>
                        </button>

                        {{-- Security --}}
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-widest opacity-70">
                                <span class="material-symbols-outlined text-sm">lock</span> 
                                <span>Secure SSL Encrypted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT KHUSUS UNTUK FORM --}}
<script>
    // UX: Prevent double submit & Show Loading
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Cek validasi HTML5 standar dulu
        if(!this.checkValidity()) return;

        const btn = this.querySelector('button[type="submit"]');
        const loadingOverlay = document.getElementById('checkout-loading');

        // Disable button
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Show Full Screen Loading
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    });
</script>
@endsection