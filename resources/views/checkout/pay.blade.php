@extends('layouts.app')

@section('content')
<style>
    .clip-corner-nex {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%);
    }

    .scanline-blue {
        width: 100%;
        height: 1px;
        background: rgba(59, 130, 246, 0.3);
        position: absolute;
        animation: scan 2s linear infinite;
        z-index: 10;
        pointer-events: none;
    }

    @keyframes scan {
        0% {
            top: 0;
        }

        100% {
            top: 100%;
        }
    }
</style>

<div class="fixed inset-0 z-0 bg-[#050505]">
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-[400px] bg-blue-600/10 blur-[100px] rounded-full"></div>
</div>

<div class="relative h-screen w-full flex items-center justify-center p-4">
    <div class="relative z-20 w-full max-w-[400px] flex flex-col items-center">

        <div class="text-center mb-6 w-full relative z-50">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-blue-500/30 bg-blue-500/10 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="text-blue-400 text-[8px] font-black uppercase tracking-[0.3em]">Payment_Gateway_Active</span>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic leading-[0.85]">
                Secure <span class="text-blue-400">Channel</span>
            </h1>
        </div>

        <div class="w-full relative bg-[#0a0a0a]/90 backdrop-blur-md border border-white/10 p-8 clip-corner-nex shadow-2xl overflow-hidden text-center">
            <div class="scanline-blue"></div>

            <span class="material-symbols-outlined text-5xl text-blue-500 mb-4 animate-pulse">qr_code_scanner</span>
            <h2 class="text-white font-bold text-lg mb-2">Order #{{ $order->id }}</h2>
            <p class="text-slate-400 text-xs mb-6 font-mono">Total: Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>

            {{-- TOMBOL PEMICU MIDTRANS --}}
            <button id="pay-button" class="w-full py-4 bg-blue-600 hover:bg-white text-white hover:text-black font-black uppercase tracking-[0.2em] text-[10px] transition-all duration-500 clip-corner-nex shadow-lg flex justify-center items-center gap-2 group">
                <span>Open Gateway</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">lock_open</span>
            </button>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // Langsung panggil Snap saat halaman selesai loading
    window.onload = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('checkout.success', $order->id) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('orders.index') }}";
            },
            onError: function(result) {
                alert("Payment failed!");
            },
            onClose: function() {
                // Jika ditutup, beri pilihan untuk buka lagi manual lewat tombol
                console.log('User closed the popup');
            }
        });
    };

    // Tetap sediakan fungsi tombol jika user tidak sengaja menutup popup
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snapToken }}');
    };
</script>
@endsection