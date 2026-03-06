@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY --}}
<div id="checkout-loading" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center">
    <div class="relative w-24 h-24 mb-4">
        <div class="absolute inset-0 border-4 border-white/10 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-primary rounded-full border-t-transparent animate-spin"></div>
        <span
            class="material-symbols-outlined absolute left-1/2 top-1/2 flex h-11 w-11 -translate-x-1/2 -translate-y-1/2 items-center justify-center text-primary animate-pulse"
            style="font-size: 44px; line-height: 44px; font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 48;"
            aria-hidden="true"
        >rocket_launch</span>
    </div>
    <h3 class="text-white font-black text-2xl uppercase italic tracking-widest mb-1">Deploying Order</h3>
    <p class="text-slate-400 font-mono text-xs uppercase tracking-[0.3em]">Securing payment channel...</p>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        {{-- STEPPER --}}
        <div class="flex items-center justify-center gap-4 mb-12 select-none">
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 opacity-50">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">1</span>
                <span class="font-bold text-sm hidden sm:block">Inventory</span>
            </div>
            <div class="w-12 h-[1px] bg-slate-800"></div>
            <div class="flex items-center gap-2 text-primary">
                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(19,55,236,0.5)]">2</span>
                <span class="font-bold text-sm uppercase tracking-wider">Deployment</span>
            </div>
            <div class="w-12 h-[1px] bg-slate-800"></div>
            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-700">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">3</span>
                <span class="font-bold text-sm hidden sm:block">Payment</span>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- MAIN GRID                                                     --}}
        {{-- KIRI (col-span-8) | KANAN (col-span-4) — sejajar horizontal --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">

            {{-- ========================= --}}
            {{-- KOLOM KIRI               --}}
            {{-- ========================= --}}
            <div class="w-full lg:w-2/3 space-y-8">

                {{-- A. SHIPPING SECTION --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">pin_drop</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Drop Zone Location</h2>
                    </div>

                    @if($address)
                        <input type="hidden" name="recipient_name" value="{{ $address->recipient_name }}">
                        <input type="hidden" name="phone" value="{{ $address->phone }}">
                        <input type="hidden" name="full_address" value="{{ $address->full_address }}">
                        <input type="hidden" name="city" value="{{ $address->city }}">
                        <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">

                        <div class="relative bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5 rounded-xl p-6 transition-all hover:border-primary/50">
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
                                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed max-w-md">{{ $address->full_address }}</p>
                                    <div class="flex items-center gap-4 mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <span>{{ $address->city }}</span>
                                        <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                                        <span>{{ $address->postal_code }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-300 dark:border-white/20 bg-slate-50/50 dark:bg-white/5 p-6 sm:p-8">
                            <div class="mb-6 flex items-start gap-4 p-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-500">
                                <span class="material-symbols-outlined text-2xl animate-pulse">warning</span>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest mb-1">Location Data Missing</h4>
                                    <p class="text-[11px] opacity-80 leading-relaxed">System requires valid delivery coordinates to initialize deployment logic.</p>
                                </div>
                            </div>
                            @if($errors->any())
                            <div class="flex items-start gap-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 mb-6">
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
                {{-- END SHIPPING --}}

                {{-- B. PAYMENT SECTION --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Payment Protocol</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="bank_transfer" checked class="peer sr-only">
                            <div class="h-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111422] peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                <span class="material-symbols-outlined text-2xl text-slate-400 peer-checked:text-primary">account_balance</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-300 peer-checked:text-primary">Virtual Account</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-tighter">BCA, Mandiri, BNI, BRI</span>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="qris" class="peer sr-only">
                            <div class="h-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111422] peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                <span class="material-symbols-outlined text-2xl text-slate-400 peer-checked:text-primary">qr_code_scanner</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-300 peer-checked:text-primary">QRIS / E-Wallet</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-tighter">Gopay, OVO, Dana, LinkAja</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                {{-- END PAYMENT --}}

            </div>
            {{-- END KOLOM KIRI --}}

            {{-- ========================= --}}
            {{-- KOLOM KANAN              --}}
            {{-- ========================= --}}
            <div class="w-full lg:w-1/3 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl">

                    <h2 class="text-lg font-black mb-6 text-slate-900 dark:text-white uppercase italic tracking-widest flex items-center gap-2">
                        <span class="w-1 h-5 bg-primary block"></span> Hardware Manifest
                    </h2>

                    {{-- Item List --}}
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-sidebar-scroll mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex gap-4 p-3 rounded-lg bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5">
                            <div class="w-14 h-14 rounded overflow-hidden flex-shrink-0 bg-white dark:bg-black border border-slate-200 dark:border-white/10">
                                <img src="{{ $item->image }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-between py-0.5 flex-1">
                                <p class="font-bold text-[11px] text-slate-900 dark:text-white line-clamp-1 uppercase tracking-wide">{{ $item->name }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-[10px] text-slate-500 font-mono bg-white dark:bg-black px-1.5 py-0.5 rounded border border-slate-200 dark:border-white/10">x{{ $item->quantity }}</span>
                                    <p class="text-primary font-bold text-xs font-mono">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Calculation --}}
                    <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-white/10 mb-8 font-mono text-xs">
                        <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                            <span class="uppercase tracking-widest font-bold">Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                            <span class="uppercase tracking-widest font-bold">Tax (11%)</span>
                            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-white/5 mt-2">
                            <span class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest font-display">Grand Total</span>
                            <span class="text-xl font-black text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-black py-4 rounded-xl shadow-[0_0_20px_rgba(19,55,236,0.4)] hover:shadow-[0_0_30px_rgba(19,55,236,0.6)] transition-all flex items-center justify-center gap-2 group uppercase italic tracking-[0.2em] text-sm relative overflow-hidden">
                        <span class="relative z-10">Deploy Order</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform relative z-10">rocket_launch</span>
                    </button>

                    <div class="mt-6 text-center">
                        <div class="flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-widest opacity-70">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            <span>Secure SSL Encrypted</span>
                        </div>
                    </div>

                </div>
            </div>
            {{-- END KOLOM KANAN --}}

        </div>
        {{-- END MAIN GRID --}}

    </form>
</div>

<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) return;

        const btn = this.querySelector('button[type="submit"]');
        const loadingOverlay = document.getElementById('checkout-loading');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    });
</script>
@endsection
