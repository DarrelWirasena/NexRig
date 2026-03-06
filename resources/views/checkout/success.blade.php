@extends('layouts.app')

@section('content')

{{-- Font Impor dari Invoice --}}
<!-- <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"> -->

<style>
    /* Tipografi Utama */
    /* .font-display { font-family: 'Playfair Display', Georgia, serif; }
    .font-body    { font-family: 'DM Sans', sans-serif; }
    .font-mono    { font-family: 'DM Mono', 'Courier New', monospace; } */

    /* Animasi Card */
    .fade-up {
        animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Efek Icon Glow (Hijau) */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.2), inset 0 0 20px rgba(34, 197, 94, 0.2); }
        50%      { box-shadow: 0 0 40px rgba(34, 197, 94, 0.5), inset 0 0 30px rgba(34, 197, 94, 0.4); }
    }
    .icon-glow-green {
        animation: pulse-glow 3s ease-in-out infinite;
    }

    /* Efek Kotak Total Harga (Mirip Invoice) */
    .premium-box {
        background: rgba(37,99,235,0.05);
        border: 1px solid rgba(37,99,235,0.3);
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }
    .premium-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    }
</style>

<div class="min-h-screen bg-[#050505] font-body flex items-center justify-center py-20 px-4 relative overflow-hidden">
    
    {{-- Background Ambient (Blue Glow + Watermark) --}}
    <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-20 z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-green-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 font-display text-[15vw] font-black text-green-500/5 whitespace-nowrap pointer-events-none z-0 select-none">
        VERIFIED
    </div>

    <div class="max-w-2xl w-full relative z-10 fade-up">
        
        {{-- SUCCESS CARD --}}
        <div class="bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-white/10 rounded-[20px] p-8 md:p-12 text-center shadow-[0_24px_80px_rgba(0,0,0,0.6),0_0_0_1px_rgba(37,99,235,0.12)] relative overflow-hidden">
            
            {{-- Top Decor Line (Hijau) --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent"></div>

            {{-- 1. ANIMATED ICON --}}
            <div class="relative w-24 h-24 mx-auto mb-8">
                <div class="absolute inset-0 bg-green-500/20 rounded-full blur-xl"></div>
                <div class="relative w-full h-full bg-[#050014] border border-green-500/50 rounded-full flex items-center justify-center icon-glow-green">
                    <span class="material-symbols-outlined text-green-400 text-5xl">check_circle</span>
                </div>
            </div>

            {{-- 2. HEADLINE --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-sm border border-green-500/30 bg-green-500/10 mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-green-400 text-[9px] font-mono font-bold uppercase tracking-[0.3em]">Payment_Verified</span>
            </div>

            <h1 class="font-display text-4xl md:text-5xl font-black text-slate-900 dark:text-white italic tracking-tighter mb-4">
                Deployment <span class="text-green-500 drop-shadow-[0_0_20px_rgba(34,197,94,0.4)]">Secured</span>.
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-10 max-w-md mx-auto leading-relaxed">
                Sistem telah memverifikasi pembayaranmu secara lunas. Perangkat keras pesananmu kini sedang dipersiapkan untuk dikirim.
            </p>

            {{-- 3. ORDER DETAILS BOX --}}
            <div class="premium-box p-6 mb-10 text-left flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div>
                    <span class="font-mono text-[9px] tracking-[0.3em] uppercase text-blue-400 block mb-1">Manifest ID</span>
                    <span class="font-display text-2xl font-bold text-white">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="w-full sm:w-px h-[1px] sm:h-12 bg-white/10"></div>

                <div class="text-left sm:text-right w-full sm:w-auto">
                    <span class="font-mono text-[9px] tracking-[0.3em] uppercase text-blue-400 block mb-1">Amount Paid</span>
                    <span class="font-display text-2xl sm:text-3xl font-black text-white drop-shadow-[0_0_20px_rgba(37,99,235,0.4)]">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                    <p class="text-[10px] text-green-400 font-mono mt-1 font-bold uppercase tracking-widest">LUNAS / PAID</p>
                </div>
            </div>

            {{-- 4. ACTION BUTTONS --}}
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('orders.show', $order->id) }}" class="w-full md:w-auto px-8 py-4 bg-primary hover:bg-blue-600 text-white font-black rounded-xl shadow-[0_0_20px_rgba(19,55,236,0.4)] hover:shadow-[0_0_30px_rgba(19,55,236,0.6)] transition-all flex items-center justify-center gap-2 uppercase italic tracking-[0.2em] text-sm group/track">
                    <span class="material-symbols-outlined text-lg group-hover/track:translate-x-1 transition-transform">rocket_launch</span>
                    Track Order
                </a>
                <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="w-full md:w-auto px-8 py-4 bg-slate-100 dark:bg-white/5 hover:dark:bg-white/10 text-slate-600 dark:text-white font-black rounded-xl border border-slate-200 dark:border-white/10 transition-all flex items-center justify-center gap-2 uppercase italic tracking-[0.2em] text-sm">
                    <span class="material-symbols-outlined text-lg">receipt_long</span>
                    View Invoice
                </a>
            </div>

        </div>
        
        {{-- Footer Note --}}
        <div class="text-center mt-8 flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-widest font-bold opacity-70">
            <span class="material-symbols-outlined text-[14px] text-green-500">mark_email_read</span> 
            <span>E-Receipt Sent To <span class="text-white">{{ auth()->user()->email }}</span></span>
        </div>

    </div>
</div>

@endsection