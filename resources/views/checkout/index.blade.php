@extends('layouts.app')

@section('content')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }
    @keyframes pulse-soft {
        0%, 100% { opacity: 1; shadow: 0 0 10px rgba(37, 99, 235, 0.2); }
        50% { opacity: 0.7; shadow: 0 0 20px rgba(37, 99, 235, 0.4); }
    }
    .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }
    .animate-pulse-slow { animation: pulse-soft 3s infinite; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }

    /* Neon Glow Effect */
    .glow-container:focus-within {
        border-color: rgba(37, 99, 235, 0.5) !important;
        box-shadow: 0 0 25px rgba(37, 99, 235, 0.1);
        transform: translateY(-2px);
    }
</style>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        {{-- 1. MISSION PROGRESS (STEPPER) --}}
        <div class="flex items-center justify-center gap-4 mb-12 select-none">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span class="font-bold text-xs uppercase tracking-widest">Inventory</span>
            </div>
            <div class="w-16 h-[1px] bg-primary/30"></div>
            <div class="flex items-center gap-2 text-primary">
                <div class="w-6 h-6 rounded-full border border-primary flex items-center justify-center text-[10px] font-bold">02</div>
                <span class="font-bold text-xs uppercase tracking-widest">Deployment</span>
            </div>
            <div class="w-16 h-[1px] bg-slate-800"></div>
            <div class="flex items-center gap-2 text-slate-500">
                <div class="w-6 h-6 rounded-full border border-slate-700 flex items-center justify-center text-[10px] font-bold">03</div>
                <span class="font-bold text-xs uppercase tracking-widest">Finished</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- KOLOM KIRI: DROP ZONE & PAYMENT --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- SHIPPING SECTION (DROP ZONE) --}}
                <div class="bg-white dark:bg-[#161b30] p-8 rounded-2xl border border-slate-200 dark:border-[#232948] relative overflow-hidden transition-all glow-container">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 rounded-full blur-3xl -mr-20 -mt-20"></div>

                    <div class="flex justify-between items-center mb-8 relative z-10">
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3 uppercase italic tracking-tighter">
                            <span class="material-symbols-outlined text-primary">distance</span>
                            Drop Zone Location
                        </h2>
                    </div>

                    @if($address)
                        {{-- VIEW: ADDRESS CARD --}}
                        <div class="relative border border-primary/30 bg-primary/5 rounded-xl p-6 group transition-all hover:border-primary/60 shadow-sm">
                            <a href="{{ route('address.index', ['origin' => 'checkout']) }}" class="absolute top-6 right-6 text-[10px] font-black text-primary hover:text-white border border-primary/20 px-3 py-1 rounded-full transition-all uppercase tracking-widest bg-primary/5 hover:bg-primary">
                                RE-ROUTE
                            </a>

                            <div class="flex items-start gap-5">
                                <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 text-primary shadow-inner">
                                    <span class="material-symbols-outlined">map</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-bold text-slate-900 dark:text-white text-lg">{{ $address->recipient_name }}</span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                                        <span class="text-slate-400 font-mono text-sm tracking-tighter">{{ $address->phone }}</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
                                        {{ $address->full_address }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-3">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $address->city }}</span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $address->postal_code }}</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-primary/10 text-primary border border-primary/20">{{ $address->label }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Inputs --}}
                        <input type="hidden" name="recipient_name" value="{{ $address->recipient_name }}">
                        <input type="hidden" name="phone" value="{{ $address->phone }}">
                        <input type="hidden" name="full_address" value="{{ $address->full_address }}">
                        <input type="hidden" name="city" value="{{ $address->city }}">
                        <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">

                    @else
                        {{-- VIEW: NEW ADDRESS FORM (ALAMAT KOSONG) --}}
                        <div class="relative rounded-2xl border {{ $errors->any() ? 'border-red-500 animate-shake' : 'border-blue-600/20' }} bg-[#0a0a0a] p-8 transition-all">
                            
                            {{-- Warning Banner --}}
                            <div class="mb-8 flex items-center gap-5 rounded-xl {{ $errors->any() ? 'bg-red-500/10 border-red-500/20' : 'bg-blue-600/5 border-blue-600/20' }} p-6 border">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $errors->any() ? 'bg-red-600 shadow-lg shadow-red-900/40' : 'bg-blue-600 shadow-lg shadow-blue-900/40' }}">
                                    <span class="material-symbols-outlined text-white text-2xl {{ $errors->any() ? 'animate-bounce' : 'animate-pulse' }}">
                                        {{ $errors->any() ? 'gpp_maybe' : 'sensors' }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white italic">
                                        {{ $errors->any() ? 'Deployment Blocked' : 'Unidentified Location' }}
                                    </h4>
                                    <p class="mt-1 text-[11px] leading-relaxed {{ $errors->any() ? 'text-red-400' : 'text-slate-500' }}">
                                        {{ $errors->any() ? 'Harap lengkapi koordinat pengiriman Anda di bawah ini.' : 'Sistem membutuhkan koordinat pengiriman untuk memulai perakitan hardware Anda.' }}
                                    </p>
                                </div>
                            </div>

                            <x-address-form-fields />
                            <input type="hidden" name="is_default" value="1">
                        </div>
                    @endif
                </div>

                {{-- PAYMENT SECTION --}}
                <div class="bg-white dark:bg-[#161b30] p-8 rounded-2xl border border-slate-200 dark:border-[#232948] glow-container transition-all">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3 uppercase italic tracking-tighter">
                        <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                        Payment Protocol
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="relative cursor-pointer flex flex-col items-center justify-center gap-4 p-8 rounded-xl border-2 border-primary bg-primary/5 group shadow-lg shadow-primary/5">
                            <span class="material-symbols-outlined text-4xl text-primary">account_balance</span>
                            <span class="font-bold text-xs uppercase tracking-widest text-white">Bank Transfer</span>
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full flex items-center justify-center shadow-lg shadow-primary/50 border-2 border-[#161b30]">
                                <span class="material-symbols-outlined text-white text-xs font-bold">check</span>
                            </div>
                        </div>
                        
                        {{-- Disabled Options --}}
                        @foreach(['credit_card' => 'Credit Card', 'qr_code_2' => 'QRIS'] as $icon => $label)
                        <div class="opacity-30 grayscale flex flex-col items-center justify-center gap-4 p-8 rounded-xl border border-slate-800 bg-slate-900/50 cursor-not-allowed">
                            <span class="material-symbols-outlined text-4xl text-slate-500">{{ $icon }}</span>
                            <span class="font-bold text-[10px] uppercase tracking-widest text-slate-600">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: HARDWARE CONFIG (SUMMARY) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-[#161b30] p-8 rounded-2xl border border-slate-200 dark:border-[#232948] shadow-2xl relative overflow-hidden">
                        
                        <h2 class="text-xl font-black mb-8 text-slate-900 dark:text-white uppercase italic tracking-widest border-b border-slate-800 pb-4">
                            Hardware Config
                        </h2>
                        
                        {{-- Item List --}}
                        <div class="space-y-4 max-h-[280px] overflow-y-auto pr-2 mb-8 custom-scrollbar">
                            @if(session('cart'))
                                @foreach(session('cart') as $id => $details)
                                    <div class="flex gap-4 p-3 rounded-xl bg-slate-900/50 border border-slate-800 group hover:border-primary/50 transition-all">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-slate-800 border border-slate-700">
                                            <img src="{{ $details['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="flex flex-col justify-between py-1 flex-1">
                                            <p class="font-bold text-[11px] text-white line-clamp-1 uppercase tracking-wider">{{ $details['name'] }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-[10px] text-slate-500 font-bold">QTY: {{ $details['quantity'] }}</span>
                                                <p class="text-primary font-bold text-xs font-mono">Rp{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- BUILD STATUS ESTIMATE --}}
                        <div class="p-4 mb-8 bg-primary/5 border border-primary/20 rounded-xl flex items-start gap-4 animate-pulse-slow">
                            <span class="material-symbols-outlined text-primary text-xl">engineering</span>
                            <div class="text-[10px]">
                                <p class="font-black text-primary uppercase tracking-widest mb-1">NEXRIG BUILD STATUS</p>
                                <p class="text-slate-400">Est. Assembly & Test: <span class="text-white font-bold">3-5 Days</span></p>
                            </div>
                        </div>

                        {{-- Calculation --}}
                        @php
                            $total = 0;
                            if(session('cart')) {
                                foreach(session('cart') as $details) { $total += $details['price'] * $details['quantity']; }
                            }
                            $tax = $total * 0.11; 
                            $grandTotal = $total + $tax;
                        @endphp

                        <div class="space-y-3 pt-6 border-t border-slate-800 mb-8">
                            <div class="flex justify-between text-[11px] uppercase tracking-widest text-slate-500 font-bold">
                                <span>Subtotal</span>
                                <span class="text-slate-300">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[11px] uppercase tracking-widest text-slate-500 font-bold">
                                <span>Tax (VAT 11%)</span>
                                <span class="text-slate-300">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-6 border-t border-slate-800 mt-2">
                                <span class="text-sm font-black text-white uppercase italic tracking-widest">Grand Total</span>
                                <span class="text-2xl font-black text-primary font-mono">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-black py-5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 group uppercase italic tracking-[0.2em] text-sm overflow-hidden relative">
                            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative z-10">Initialize Deployment</span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-2 transition-transform relative z-10">bolt</span>
                        </button>

                        {{-- TRUST BADGES --}}
                        <div class="mt-8 flex flex-wrap justify-center gap-5 opacity-40 hover:opacity-100 transition-opacity duration-700">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3" alt="Visa">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5" alt="Mastercard">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Logo_asli_BCA.png" class="h-3" alt="BCA">
                        </div>
                        <p class="text-center text-[8px] text-slate-600 mt-6 uppercase tracking-[0.3em] font-black">
                            <span class="material-symbols-outlined text-[10px] align-middle mr-1">verified_user</span>
                            Encrypted NexRig Secure Protocol
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // UX: Prevent double click on Place Order
    document.getElementById('checkout-form').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin material-symbols-outlined">sync</span> DEPLOYING...';
    });
</script>
@endsection