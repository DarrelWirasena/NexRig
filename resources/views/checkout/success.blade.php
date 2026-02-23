@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#050014] flex items-center justify-center py-20 px-4 relative overflow-hidden">
    
    {{-- Background Glow Decor --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-2xl w-full relative z-10">
        
        {{-- SUCCESS CARD --}}
        <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 md:p-12 text-center shadow-2xl relative overflow-hidden">
            
            {{-- Top Decor Line --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-600 to-transparent"></div>

            {{-- 1. ANIMATED ICON --}}
            <div class="mb-8 inline-flex items-center justify-center">
                <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center border border-green-500/20 relative">
                    <div class="absolute inset-0 bg-green-500/20 rounded-full animate-ping"></div>
                    <span class="material-symbols-outlined text-5xl text-green-500">check_circle</span>
                </div>
            </div>

            {{-- 2. HEADLINE --}}
            <h1 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tighter mb-4">
                Order <span class="text-blue-600">Secured!</span>
            </h1>
            <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto">
                Thank you for choosing NexRig. Your custom build configuration has been received and is awaiting payment confirmation.
            </p>

            {{-- 3. ORDER DETAILS BOX --}}
            <div class="bg-[#111422] rounded-xl border border-white/10 p-6 mb-8 text-left">
                <div class="flex justify-between items-center border-b border-white/10 pb-4 mb-4">
                    <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Order ID</span>
                    <span class="text-white font-mono font-bold text-lg">#{{ $order->id }}</span>
                </div>
                
                <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-400 text-sm">Total Amount</span>
                    <span class="text-white font-bold text-xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <p class="text-right text-xs text-blue-400">*Includes Tax & Shipping</p>
            </div>

            {{-- 4. PAYMENT INSTRUCTIONS (Hanya tampil jika pending/transfer) --}}
            @if($order->status == 'pending')
                <div class="bg-blue-600/10 border border-blue-600/30 rounded-xl p-6 mb-8 relative group">
                    <p class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-3">Please Transfer To:</p>
                    
                    <div class="flex items-center justify-between bg-[#050014] border border-blue-600/20 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-white">account_balance</span>
                            <div class="text-left">
                                <p class="text-xs text-gray-500 uppercase font-bold">BCA (NexRig Corp)</p>
                                <p class="text-white font-mono text-lg font-bold tracking-wide" id="rekNumber">{{config('shop.bank_account_number')}}</p>
                            </div>
                        </div>
                        <button onclick="copyToClipboard()" class="text-gray-400 hover:text-white transition-colors p-2" title="Copy Number">
                            <span class="material-symbols-outlined text-lg">content_copy</span>
                        </button>
                    </div>

                    <p class="text-gray-500 text-xs mt-4">
                        Your order will be processed automatically once the payment is verified (approx. 10-15 mins).
                    </p>
                </div>
            @endif

            {{-- 5. ACTION BUTTONS --}}
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('orders.show', $order->id) }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-lg transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>
                    Track Order
                </a>
                <a href="{{ route('home') }}" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-lg border border-white/10 transition-all flex items-center justify-center gap-2">
                    Back to Home
                </a>
            </div>

        </div>
        
        {{-- Footer Note --}}
        <p class="text-center text-gray-600 text-xs mt-8">
            A confirmation email has been sent to <span class="text-gray-400">{{ auth()->user()->email }}</span>
        </p>

    </div>
</div>

{{-- Script Sederhana untuk Copy Rekening --}}
<script>
    function copyToClipboard() {
        const rek = document.getElementById('rekNumber').innerText;
        navigator.clipboard.writeText(rek);
        window.showToast('Account number copied!');
    }
</script>

@endsection