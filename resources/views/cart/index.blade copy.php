@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">
    
    {{-- 1. STEPPER (Inventory Active) --}}
    <div class="flex items-center justify-center gap-4 mb-12 select-none">
        
        {{-- Step 1: Inventory (ACTIVE) --}}
        <div class="flex items-center gap-2 text-primary">
            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(19,55,236,0.5)]">1</span>
            <span class="font-bold text-sm uppercase tracking-wider">Inventory</span>
        </div>
        
        <div class="w-12 h-[1px] bg-slate-300 dark:bg-slate-700"></div>
        
        {{-- Step 2: Deployment (Inactive) --}}
        <div class="flex items-center gap-2 text-slate-400 dark:text-slate-600 opacity-70">
            <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">2</span>
            <span class="font-bold text-sm hidden sm:block">Deployment</span>
        </div>
        
        <div class="w-12 h-[1px] bg-slate-300 dark:bg-slate-700"></div>
        
        {{-- Step 3: Payment (Inactive) --}}
        <div class="flex items-center gap-2 text-slate-400 dark:text-slate-600 opacity-70">
            <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">3</span>
            <span class="font-bold text-sm hidden sm:block">Payment</span>
        </div>
    </div>


    @if(count($cart) > 0)
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- PRODUCT LIST --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-baseline justify-between mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900 dark:text-white font-display uppercase italic">Your Rig Setup</h1>
                    <p class="text-slate-500 dark:text-[#929bc9] font-mono">{{ count($cart) }} Item(s)</p>
                </div>

                @foreach($cart as $item)
                <div id="cart-row-{{ $item->row_id }}" class="group relative bg-white dark:bg-[#0a0a0a] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-white/10 transition-all hover:border-primary/50 shadow-lg shadow-black/5">
                    <div class="flex flex-col sm:flex-row gap-6">
                        
                        <div class="relative w-full sm:w-40 aspect-square rounded-lg overflow-hidden bg-slate-100 dark:bg-[#111422] border border-white/5 shrink-0">
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
                                
                                {{-- BUTTON QUANTITY --}}
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

                                {{-- BUTTON REMOVE --}}
                                <button onclick="openDeleteModal('{{ $item->row_id }}')" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1 group/del">
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
                    <div class="bg-white dark:bg-[#0a0a0a] p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl flex flex-col h-full">
                        <h2 class="text-xl font-black italic uppercase mb-6 text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary block"></span> Order Summary
                        </h2>
                        
                        @php
                            // $total sudah dikirim dari Controller
                            $tax = $total * 0.11; 
                            $grandTotal = $total + $tax;
                        @endphp

                        <div class="space-y-4 mb-8 font-mono text-sm">
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Subtotal</span>
                                <span id="summary-subtotal" class="text-slate-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Shipping</span>
                                <span class="text-green-500">Free via JNE Trucking</span>
                            </div>
                            <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                                <span>Tax (11%)</span>
                                <span id="summary-tax" class="text-slate-900 dark:text-white">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-white/10 mb-8">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-bold text-slate-900 dark:text-white uppercase">Total</span>
                                <span id="summary-grand-total" class="text-2xl font-black text-primary italic">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS GROUP --}}
                        <div class="mt-auto flex flex-col">
                            
                            {{-- 1. Primary Action: Checkout --}}
                            <a href="{{ route('checkout.index') }}" class="w-full bg-primary hover:bg-blue-600 text-white font-black italic uppercase py-4 rounded-lg shadow-[0_0_20px_rgba(19,55,236,0.5)] transition-all flex items-center justify-center gap-2 hover:translate-y-[-2px] relative z-20">
                                Secure Checkout <span class="material-symbols-outlined">arrow_forward</span>
                            </a>

                            {{-- 2. Secondary Action: Continue Shopping --}}
                            <a href="{{ route('products.index') }}" 
                            class="block w-full text-center text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white underline underline-offset-4 transition-colors capitalize mt-4">
                                Continue Shopping
                            </a>

                        </div>

                        {{-- Security Badge --}}
                        <div class="mt-6 text-center border-t border-slate-100 dark:border-white/5 pt-4">
                            <div class="flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-widest opacity-70">
                                <span class="material-symbols-outlined text-sm">lock</span> 
                                <span>Secure SSL Encrypted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @else
        {{-- EMPTY STATE --}}
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

{{-- CUSTOM DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" class="fixed inset-0 z-[150] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop (Gelap) --}}
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="deleteModalBackdrop"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-[#0a0a0a] border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="deleteModalPanel">
                
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-500/10 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-red-500">warning</span>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-white uppercase italic" id="modal-title">Remove Item?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Are you sure you want to remove this build from your setup? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="bg-white/5 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" id="confirmDeleteBtn" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors items-center gap-2">
                        <span class="material-symbols-outlined text-sm">delete</span> Remove
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white/5 px-3 py-2 text-sm font-bold text-gray-300 shadow-sm ring-1 ring-inset ring-white/10 hover:bg-white/10 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT KHUSUS HALAMAN CART --}}
<script>
    // Variable global untuk menyimpan ID item yang akan dihapus
    let itemIdToDelete = null;

    // 1. FUNGSI BUKA MODAL
    // Fungsi ini menggantikan window.removeMainCartItem yang lama
    window.openDeleteModal = function(id) {
        itemIdToDelete = id; // Simpan ID
        
        const modal = document.getElementById('deleteModal');
        const backdrop = document.getElementById('deleteModalBackdrop');
        const panel = document.getElementById('deleteModalPanel');

        // Tampilkan Modal
        modal.classList.remove('hidden');
        
        // Animasi Masuk (Fade In & Scale Up)
        // Kita pakai setTimeout dikit biar transisi CSS jalan
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    // 2. FUNGSI TUTUP MODAL
    window.closeDeleteModal = function() {
        itemIdToDelete = null;
        
        const modal = document.getElementById('deleteModal');
        const backdrop = document.getElementById('deleteModalBackdrop');
        const panel = document.getElementById('deleteModalPanel');

        // Animasi Keluar
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        // Sembunyikan div setelah animasi selesai (300ms)
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // 3. EVENT LISTENER TOMBOL CONFIRM
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (itemIdToDelete) {
            // Panggil fungsi AJAX penghapusan yang ada di app.js
            // Kita buat fungsi baru di app.js bernama 'executeRemoveCartItem'
            window.executeRemoveCartItem(itemIdToDelete);
            
            // Tutup modal
            closeDeleteModal();
        }
    });
</script>

@endsection