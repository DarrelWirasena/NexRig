@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-12">
    
    {{-- FORM WRAPPER UTAMA (Membungkus Input & Sidebar) --}}
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        {{-- 1. HEADER & STEPPER --}}
        <div class="flex items-center justify-center gap-4 mb-12">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span class="font-bold text-sm">Cart</span>
            </div>
            <div class="w-12 h-[1px] bg-primary"></div>
            
            <div class="flex items-center gap-2 text-primary">
                <span class="font-bold text-sm">02</span>
                <span class="font-medium text-sm">Shipping & Payment</span>
            </div>
            
            <div class="w-12 h-[1px] bg-slate-200 dark:bg-[#232948]"></div>
            <div class="flex items-center gap-2 text-slate-400">
                <span class="font-bold text-sm">03</span>
                <span class="font-medium text-sm">Done</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- KOLOM KIRI: FORM INPUT --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- SHIPPING INFORMATION --}}
                <div class="bg-white dark:bg-[#161b30] p-8 rounded-xl border border-slate-200 dark:border-[#232948]">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        Shipping Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">Full Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="Enter your full name" required>
                        </div>

                        {{-- Email --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">Email Address</label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="alex@example.com" required>
                        </div>

                        {{-- Phone --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">Phone Number</label>
                            <input type="text" name="phone" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="0812..." required>
                        </div>

                        {{-- Alamat --}}
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">Shipping Address</label>
                            <input type="text" name="address" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="Jalan Sudirman No. 123, Apartemen..." required>
                        </div>

                        {{-- Kota --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">City</label>
                            <input type="text" name="city" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="Jakarta Selatan" required>
                        </div>

                        {{-- Kode Pos --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#929bc9]">Postal Code</label>
                            <input type="text" name="postal_code" 
                                   class="bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948] rounded-lg h-12 px-4 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder-slate-400" 
                                   placeholder="12xxx" required>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT METHOD (Visual Only) --}}
                <div class="bg-white dark:bg-[#161b30] p-8 rounded-xl border border-slate-200 dark:border-[#232948]">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        Payment Method
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        {{-- Opsi 1: Transfer (Aktif) --}}
                        <div class="relative cursor-pointer flex flex-col items-center justify-center gap-3 p-6 rounded-lg border-2 border-primary bg-primary/5 transition-all">
                            <span class="material-symbols-outlined text-3xl text-primary">account_balance</span>
                            <span class="font-bold text-sm text-slate-900 dark:text-white">Bank Transfer</span>
                            <div class="absolute top-2 right-2 w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[10px]">check</span>
                            </div>
                        </div>
                        
                        {{-- Opsi 2: CC (Disabled) --}}
                        <div class="cursor-not-allowed opacity-50 flex flex-col items-center justify-center gap-3 p-6 rounded-lg border border-slate-200 dark:border-[#232948] bg-slate-50 dark:bg-[#111422]">
                            <span class="material-symbols-outlined text-3xl text-slate-400">credit_card</span>
                            <span class="font-bold text-sm text-slate-500">Credit Card</span>
                        </div>

                        {{-- Opsi 3: QRIS (Disabled) --}}
                        <div class="cursor-not-allowed opacity-50 flex flex-col items-center justify-center gap-3 p-6 rounded-lg border border-slate-200 dark:border-[#232948] bg-slate-50 dark:bg-[#111422]">
                            <span class="material-symbols-outlined text-3xl text-slate-400">qr_code_2</span>
                            <span class="font-bold text-sm text-slate-500">QRIS</span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-[#111422] rounded-lg border border-slate-200 dark:border-[#232948] flex items-start gap-3">
                        <span class="material-symbols-outlined text-slate-400 mt-1">info</span>
                        <div class="text-sm text-slate-500 dark:text-[#929bc9]">
                            <p class="font-bold mb-1">Manual Bank Transfer</p>
                            <p>Payment instructions will be sent to your email after you place the order. Your build process starts once payment is confirmed.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: SUMMARY SIDEBAR (Sticky) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-[#161b30] p-8 rounded-xl border border-slate-200 dark:border-[#232948]">
                        <h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white">Order Summary</h2>
                        
                        {{-- ITEM LIST --}}
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 mb-6 custom-scrollbar">
                            @if(session('cart'))
                                @foreach(session('cart') as $id => $details)
                                    <div class="flex gap-4 p-3 rounded-lg bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-[#232948]">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 dark:bg-gray-800">
                                            <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col justify-between py-1 flex-1">
                                            <div>
                                                <p class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $details['name'] }}</p>
                                                <p class="text-xs text-slate-500 dark:text-[#929bc9]">Qty: {{ $details['quantity'] }}</p>
                                            </div>
                                            <p class="text-primary font-bold text-sm">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- CALCULATIONS --}}
                        @php
                            $total = 0;
                            if(session('cart')) {
                                foreach(session('cart') as $details) {
                                    $total += $details['price'] * $details['quantity'];
                                }
                            }
                            $tax = $total * 0.11; 
                            $grandTotal = $total + $tax;
                        @endphp

                        <div class="space-y-3 pt-4 border-t border-slate-200 dark:border-[#232948]">
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Subtotal</span>
                                <span class="text-slate-900 dark:text-white font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Shipping</span>
                                <span class="text-green-500 font-medium">Free (Promo)</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-[#929bc9]">
                                <span>Tax (11%)</span>
                                <span class="text-slate-900 dark:text-white font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-baseline pt-6 border-t border-slate-200 dark:border-[#232948] mb-8">
                            <span class="text-lg font-bold text-slate-900 dark:text-white">Total</span>
                            <span class="text-2xl font-bold text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        {{-- TOMBOL SUBMIT ORDER --}}
                        <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-primary/50 transition-all flex items-center justify-center gap-2 group">
                            <span>Place Order</span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>

                        <div class="flex flex-col gap-3 mt-6">
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-[#929bc9]">
                                <span class="material-symbols-outlined text-sm text-primary">verified_user</span>
                                <span>256-bit SSL Secure Checkout</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-[#929bc9]">
                                <span class="material-symbols-outlined text-sm text-primary">package_2</span>
                                <span>Estimated Build Time: 3-5 Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection