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
        0% { top: 0; }
        100% { top: 100%; }
    }
    
    /* Animasi untuk timer yang mau habis */
    .text-danger-pulse {
        color: #ef4444 !important;
        animation: pulse-danger 1s infinite;
    }
    @keyframes pulse-danger {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
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
            <h2 class="text-white font-bold text-lg mb-1">Order #{{ $order->id }}</h2>
            <p class="text-slate-400 text-xs mb-4 font-mono">Total: Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>

            {{-- 🔥 TAMBAHAN: KOTAK COUNTDOWN TIMER 🔥 --}}
            <div class="mb-6 p-4 bg-black/50 border border-white/5 rounded-lg flex flex-col items-center justify-center">
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Batas Waktu Pembayaran</span>
                <div id="countdown-timer" class="text-2xl font-mono font-black text-white tracking-widest">
                    --:--
                </div>
            </div>

            {{-- TOMBOL PEMICU MIDTRANS --}}
            <button id="pay-button" class="w-full py-4 bg-blue-600 hover:bg-white text-white hover:text-black font-black uppercase tracking-[0.2em] text-[10px] transition-all duration-500 clip-corner-nex shadow-lg flex justify-center items-center gap-2 group">
                <span id="pay-btn-text">Open Gateway</span>
                <span id="pay-btn-icon" class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">lock_open</span>
            </button>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // 🔥 LOGIKA COUNTDOWN DINAMIS BERDASARKAN PAYMENT TYPE 🔥
    
    const createdAtStr = "{{ $order->created_at->format('Y-m-d H:i:s') }}";
    const createdAt = new Date(createdAtStr.replace(' ', 'T') + 'Z'); 
    
    // 1. Deteksi Jenis Pembayaran dari Database
    // (Jika baru pertama kali load dan belum bayar, payment_type mungkin masih kosong, kita beri default 24 Jam)
    const paymentType = "{{ strtolower($order->payment_type ?? '') }}";
    
    // Anggap metode cepat (15 menit) hanya untuk QRIS dan E-Wallet
    const isFastPayment = paymentType.includes('qris') || paymentType.includes('gopay') || paymentType.includes('shopeepay');
    
    // Jika QRIS/E-Wallet = 15 Menit. Jika VA/Transfer/Kosong = 24 Jam (1440 menit)
    const durationInMinutes = isFastPayment ? 15 : (24 * 60); 
    
    // 2. Hitung Waktu Kedaluwarsa
    const expiryTime = new Date(createdAt.getTime() + durationInMinutes * 60000);

    const timerDisplay = document.getElementById('countdown-timer');
    const payBtn = document.getElementById('pay-button');
    const payBtnText = document.getElementById('pay-btn-text');
    const payBtnIcon = document.getElementById('pay-btn-icon');

    // 3. Fungsi yang dijalankan setiap 1 detik
    const timerInterval = setInterval(function() {
        const now = new Date();
        const distance = expiryTime - now;

        // Jika waktu sudah habis
        if (distance < 0) {
            clearInterval(timerInterval);
            timerDisplay.innerHTML = "EXPIRED";
            timerDisplay.classList.add('text-red-500');
            
            // Matikan tombol
            payBtn.disabled = true;
            payBtn.classList.remove('bg-blue-600', 'hover:bg-white', 'text-white', 'hover:text-black');
            payBtn.classList.add('bg-red-500/20', 'text-red-500/50', 'cursor-not-allowed', 'border', 'border-red-500/20');
            payBtnText.innerText = "Order Cancelled";
            payBtnIcon.innerText = "block";
            
            // Reload setelah 3 detik agar sistem backend membatalkan pesanan
            setTimeout(() => {
                window.location.reload();
            }, 3000); 
            
            return;
        }

        // 🔥 PERBAIKAN: Hitung Jam, Menit, dan Detik 🔥
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Format angka (tambahkan 0 di depan jika di bawah 10)
        const h = hours < 10 ? "0" + hours : hours;
        const m = minutes < 10 ? "0" + minutes : minutes;
        const s = seconds < 10 ? "0" + seconds : seconds;

        // Jika waktu lebih dari 1 jam (VA), tampilkan HH:MM:SS. Jika kurang (QRIS), tampilkan MM:SS.
        if (hours > 0) {
            timerDisplay.innerHTML = h + ":" + m + ":" + s;
        } else {
            timerDisplay.innerHTML = m + ":" + s;
        }

        // Jika sisa waktu kurang dari 3 menit (180.000 ms), ubah warna jadi merah kedip-kedip
        if (distance < 180000) {
            timerDisplay.classList.add('text-danger-pulse');
        }
    }, 1000);


    // ── Logika Midtrans Snap ──
    function triggerSnap() {
        if (new Date() >= expiryTime) return;

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('checkout.success', $order->id) }}";
            },
            onPending: function(result) {
                // Biarkan user stay di halaman ini jika mereka menutup pop-up tapi order masih pending
                // Atau bisa di-reload agar timer update dengan payment_type yang baru dipilih
                window.location.reload(); 
            },
            onError: function(result) {
                alert("Payment failed!");
            },
            onClose: function() {
                // Sangat Penting: Reload halaman saat popup ditutup!
                // Ini berguna agar jika user memilih QRIS, saat popup ditutup timer langsung berubah jadi 15 menit.
                window.location.reload();
            }
        });
    }

    // Langsung panggil Snap saat halaman selesai loading (jika belum expired)
    window.onload = function() {
        if (new Date() < expiryTime) {
            triggerSnap();
        }
    };

    payBtn.onclick = function() {
        triggerSnap();
    };
</script>
@endsection