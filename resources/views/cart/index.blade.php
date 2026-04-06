@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">

    {{-- STEPPER (Tetap Sama) --}}
    <div class="flex items-center justify-center gap-4 mb-12 select-none">
        <div class="flex items-center gap-2 text-primary">
            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(19,55,236,0.5)]">1</span>
            <span class="font-bold text-sm uppercase tracking-wider">Inventory</span>
        </div>
        <div class="w-12 h-[1px] bg-slate-300 dark:bg-slate-700"></div>
        <div class="flex items-center gap-2 text-slate-400 dark:text-slate-600 opacity-70">
            <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">2</span>
            <span class="font-bold text-sm hidden sm:block">Deployment</span>
        </div>
        <div class="w-12 h-[1px] bg-slate-300 dark:bg-slate-700"></div>
        <div class="flex items-center gap-2 text-slate-400 dark:text-slate-600 opacity-70">
            <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">3</span>
            <span class="font-bold text-sm hidden sm:block">Payment</span>
        </div>
    </div>

    @if(count($cart) > 0)
    
    {{-- 🔥 BUNGKUS DENGAN FORM KE ARAH CHECKOUT 🔥 --}}
    <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            {{-- PRODUCT LIST --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-baseline justify-between mb-8">
                    <div class="flex items-center gap-4">
                        {{-- 🔥 CHECKBOX PILIH SEMUA 🔥 --}}
                        <input type="checkbox" id="check-all" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" checked>
                        <label for="check-all" class="text-sm font-bold text-slate-900 dark:text-white cursor-pointer select-none">Pilih Semua</label>
                    </div>
                    <p class="text-slate-500 dark:text-[#929bc9] font-mono"> <span id="cart-page-count">{{ count($cart) }}</span> Item(s)</p>
                </div>

                @foreach($cart as $item)
                <div id="cart-row-{{ $item->row_id }}" class="group relative bg-white dark:bg-[#0a0a0a] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-white/10 transition-all hover:border-primary/50 shadow-lg shadow-black/5 flex gap-4 items-center">
                    
                    {{-- 🔥 CHECKBOX MASING-MASING ITEM 🔥 --}}
                    <input type="checkbox" name="selected_items[]" value="{{ $item->row_id }}" 
                           data-price="{{ $item->price }}" 
                           data-qty="{{ $item->quantity }}"
                           class="item-checkbox w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer shrink-0" checked>

                    <div class="flex flex-col sm:flex-row gap-6 w-full">
                        <div class="relative w-full sm:w-32 aspect-square rounded-lg overflow-hidden bg-slate-100 dark:bg-[#111422] border border-white/5 shrink-0">
                            <img src="{{ $item->image }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2 gap-4">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ $item->name }}</h3>
                                    <p class="text-lg font-bold text-primary whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2 text-xs text-slate-500 dark:text-[#929bc9] mb-4">
                                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-white/5 px-2 py-1 rounded">
                                        <span class="material-symbols-outlined text-[14px]">verified</span><span>{{ $item->category }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/10">
                                <div class="flex items-center bg-slate-100 dark:bg-[#111422] rounded-lg p-1 border border-white/5">
                                    <button type="button" onclick="updateMainCartItem('{{ $item->row_id }}', -1)"
                                        class="w-8 h-8 flex items-center justify-center hover:bg-white/10 hover:text-red-500 rounded transition-colors {{ $item->quantity <= 1 ? 'opacity-30 pointer-events-none' : '' }}">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>

                                    <span id="qty-display-{{ $item->row_id }}" class="w-10 text-center font-bold text-sm text-slate-900 dark:text-white font-mono">
                                        {{ $item->quantity }}
                                    </span>

                                    <button type="button" onclick="updateMainCartItem('{{ $item->row_id }}', 1)"
                                        class="w-8 h-8 flex items-center justify-center hover:bg-white/10 hover:text-primary rounded transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>

                                <button type="button" onclick="openDeleteModal('{{ $item->row_id }}')" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1 group/del">
                                    <span class="material-symbols-outlined text-base group-hover/del:animate-bounce">delete</span> Remove
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
                    <div class="bg-white dark:bg-[#0a0a0a] p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl flex flex-col h-full">
                        <h2 class="text-xl font-black italic uppercase mb-6 text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary block"></span> Order Summary
                        </h2>

                        <div class="space-y-4 mb-8 font-mono text-sm">
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Subtotal</span>
                                <span id="summary-subtotal" class="text-slate-900 dark:text-white">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Shipping</span>
                                <span class="text-green-500">Free via JNE Trucking</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Tax (11%)</span>
                                <span id="summary-tax" class="text-slate-900 dark:text-white">Rp 0</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-white/10 mb-8">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-bold text-slate-900 dark:text-white uppercase">Total</span>
                                <span id="summary-grand-total" class="text-2xl font-black text-primary italic">Rp 0</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS GROUP --}}
                        <div class="mt-auto flex flex-col">
                            {{-- 🔥 TOMBOL SUBMIT CHECKOUT 🔥 --}}
                            <button type="submit" id="checkout-btn" class="w-full bg-primary hover:bg-blue-600 text-white font-black italic uppercase py-4 rounded-lg shadow-[0_0_20px_rgba(19,55,236,0.5)] transition-all flex items-center justify-center gap-2 hover:translate-y-[-2px] relative z-20 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:shadow-none">
                                Secure Checkout <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    @else
    {{-- EMPTY STATE (Sama seperti aslinya) --}}
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

{{-- Modal Delete Tetap Sama --}}
{{-- 🔥 SCRIPT KHUSUS PERHITUNGAN CHECKBOX 🔥 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAllBtn = document.getElementById('check-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkoutBtn = document.getElementById('checkout-btn');

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('Rp', 'Rp ').trim();
        };

        // Fungsi Hitung Total Harga Real-time
        const calculateTotals = () => {
            let subtotal = 0;
            let checkedCount = 0;

            itemCheckboxes.forEach(cb => {
                if(cb.checked) {
                    subtotal += (parseFloat(cb.dataset.price) * parseInt(cb.dataset.qty));
                    checkedCount++;
                }
            });

            let tax = subtotal * 0.11;
            let grandTotal = subtotal + tax;

            document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
            document.getElementById('summary-tax').innerText = formatRupiah(tax);
            document.getElementById('summary-grand-total').innerText = formatRupiah(grandTotal);

            // Nyalakan/Matikan Tombol Checkout
            if(checkedCount === 0) {
                checkoutBtn.disabled = true;
                if(checkAllBtn) checkAllBtn.checked = false;
            } else {
                checkoutBtn.disabled = false;
                if(checkAllBtn) checkAllBtn.checked = (checkedCount === itemCheckboxes.length);
            }
        };

        if(checkAllBtn) {
            checkAllBtn.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                calculateTotals();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', calculateTotals);
        });

        if(itemCheckboxes.length > 0) calculateTotals();
    });
</script>
@endsection