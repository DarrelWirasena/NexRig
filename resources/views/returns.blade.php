@extends('layouts.app')

@section('content')
    <style>
        /* Typography Content */
        .policy-content h2 { 
            color: white; 
            font-weight: 800; 
            text-transform: uppercase; 
            font-style: italic; 
            letter-spacing: -0.02em; 
            margin-top: 0; 
            margin-bottom: 1.5rem; 
            font-size: 2rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            padding-bottom: 1rem;
        }
        .policy-content h3 { 
            color: #60a5fa; /* Blue-400 */
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            margin-top: 2.5rem; 
            margin-bottom: 1rem; 
            font-size: 1.25rem; 
        }
        .policy-content p { 
            margin-bottom: 1.5rem; 
            line-height: 1.8; 
            color: #d1d5db; 
            font-size: 1rem; 
            text-align: justify; 
        }
        .policy-content ul, .policy-content ol { 
            margin-bottom: 2rem; 
            padding-left: 1.5rem; 
            color: #d1d5db; 
            line-height: 1.8; 
        }
        .policy-content ul { list-style-type: disc; }
        .policy-content li { margin-bottom: 0.5rem; }
        .policy-content strong { color: white; font-weight: 700; }

        /* Background Pattern */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Navigasi Samping */
        .nav-link.active {
            color: #3b82f6; 
            border-left-color: #3b82f6;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
        }
        
        /* Print Styles */
        @media print {
            body { background: white; color: black; }
            .no-print { display: none !important; }
            .policy-content { width: 100% !important; grid-column: span 12 !important; }
            h1, h2, h3, strong { color: black !important; }
            p, li { color: #333 !important; }
        }
    </style>

    <div class="bg-[#050014] min-h-screen text-gray-300 font-sans selection:bg-blue-600 selection:text-white">

        {{-- HEADER SECTION --}}
        <div class="relative py-24 px-6 md:px-12 border-b border-white/5 bg-[#080808] overflow-hidden no-print">
            <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
            {{-- Glow --}}
            <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 max-w-[1600px] mx-auto">
                <div class="flex flex-col lg:flex-row items-end justify-between gap-8">
                    <div>
                        <span class="inline-block py-1 px-3 rounded border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-bold tracking-[0.2em] uppercase mb-6">
                            Sales Policy
                        </span>
                        <h1 class="text-5xl md:text-7xl font-black uppercase italic tracking-tighter text-white mb-4 leading-none">
                            Returns & <span class="text-blue-600">Refunds</span>
                        </h1>
                        <p class="text-gray-400 max-w-2xl text-lg">
                            Syarat dan ketentuan pengembalian produk, pembatalan pesanan, dan biaya restocking.
                        </p>
                    </div>

                    {{-- Quick Stats Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px]">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Return Window</p>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500">calendar_month</span>
                                <span class="text-white font-bold text-xl">14 Hari</span>
                            </div>
                        </div>
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px]">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Restocking Fee</p>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-yellow-500">percent</span>
                                <span class="text-white font-bold text-xl">15% Max</span>
                            </div>
                        </div>
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px]">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Processing Time</p>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500">schedule</span>
                                <span class="text-white font-bold text-xl">3-5 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="max-w-[1600px] mx-auto px-6 md:px-12 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                {{-- LEFT SIDEBAR (STICKY NAV) --}}
                <div class="lg:col-span-3 hidden lg:block no-print">
                    <div class="sticky top-24">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 px-4">Policy Sections</p>
                        <nav class="space-y-1 border-l border-white/10">
                            <a href="#overview" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                1. Ketentuan Umum
                            </a>
                            <a href="#fees" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                2. Biaya Restocking
                            </a>
                            <a href="#non-returnable" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                3. Barang Non-Refundable
                            </a>
                            <a href="#damages" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                4. Barang Rusak / DOA
                            </a>
                            <a href="#process" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                5. Proses Pengembalian
                            </a>
                        </nav>
                        
                        <div class="mt-8 pt-8 border-t border-white/10 px-4">
                            <a href="{{ route('support') }}" class="flex items-center gap-2 text-blue-500 hover:text-blue-400 text-sm font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-lg">arrow_back</span> Back to Support
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT CONTENT --}}
                <div class="lg:col-span-9 policy-content">

                    {{-- Intro --}}
                    <div class="bg-[#0a0a0a] border-l-4 border-blue-600 p-8 rounded-r-xl mb-12">
                        <p class="!mb-0 text-lg text-white font-medium">
                            Di NexRig, kami ingin Anda puas dengan pembelian Anda. Namun, sebagai penyedia PC rakitan (Custom PC), terdapat kebijakan khusus mengenai pengembalian barang karena sifat produk yang dipersonalisasi.
                        </p>
                    </div>

                    {{-- 1. OVERVIEW --}}
                    <section id="overview" class="mb-16 scroll-mt-28">
                        <h2>1. Ketentuan Umum</h2>
                        <p>Anda dapat mengajukan permintaan pengembalian produk dalam waktu <strong>14 hari kalender</strong> setelah tanggal penerimaan barang. Untuk memenuhi syarat pengembalian:</p>
                        <ul>
                            <li>Produk harus dalam kondisi fisik yang sama seperti saat diterima (tidak ada goresan, penyok, atau kerusakan fisik).</li>
                            <li>Semua aksesori, kemasan asli, buku manual, dan hadiah promosi (jika ada) harus dikembalikan lengkap.</li>
                            <li>Segel garansi pada komponen (jika ada) tidak boleh rusak.</li>
                        </ul>
                        <p>Pengembalian dana (refund) akan diproses ke metode pembayaran asli Anda setelah unit diterima dan diperiksa oleh tim teknis kami.</p>
                    </section>

                    {{-- 2. RESTOCKING FEES --}}
                    <section id="fees" class="mb-16 scroll-mt-28">
                        <h2>2. Biaya Restocking (Restocking Fee)</h2>
                        <p>PC Rakitan NexRig dibuat secara *custom* sesuai pesanan. Oleh karena itu, jika Anda mengembalikan PC yang berfungsi normal (bukan karena cacat produksi) hanya karena "berubah pikiran", biaya restocking akan dikenakan.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl">
                                <h4 class="text-white font-bold mb-2 uppercase">Kondisi Segel Utuh</h4>
                                <p class="text-3xl font-black text-blue-500 mb-2">No Fee</p>
                                <p class="text-xs text-gray-400 !mb-0">Jika kotak PC/Komponen belum pernah dibuka sama sekali (Seal Utuh).</p>
                            </div>
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl">
                                <h4 class="text-white font-bold mb-2 uppercase">Kondisi Terbuka (Open Box)</h4>
                                <p class="text-3xl font-black text-yellow-500 mb-2">15% Fee</p>
                                <p class="text-xs text-gray-400 !mb-0">Dipotong 15% dari harga beli untuk biaya pemeriksaan ulang, pembersihan, dan penurunan nilai barang menjadi "Refurbished".</p>
                            </div>
                        </div>
                        <p>Biaya pengiriman awal (shipping cost) tidak dapat dikembalikan. Biaya pengiriman balik ke gudang NexRig ditanggung oleh pembeli.</p>
                    </section>

                    {{-- 3. NON-RETURNABLE --}}
                    <section id="non-returnable" class="mb-16 scroll-mt-28">
                        <h2>3. Barang Non-Refundable</h2>
                        <p>Produk berikut bersifat final dan <strong>tidak dapat dikembalikan</strong> atau ditukar:</p>
                        
                        <div class="bg-red-500/5 border border-red-500/20 p-6 rounded-xl">
                            <ul class="!mb-0 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                                <li class="flex items-center gap-3 text-gray-300">
                                    <span class="material-symbols-outlined text-red-500">block</span>
                                    Software yang sudah diaktivasi (Windows Key, Office).
                                </li>
                                <li class="flex items-center gap-3 text-gray-300">
                                    <span class="material-symbols-outlined text-red-500">block</span>
                                    Gift Card / Voucher Digital.
                                </li>
                                <li class="flex items-center gap-3 text-gray-300">
                                    <span class="material-symbols-outlined text-red-500">block</span>
                                    PC dengan kustomisasi fisik (Engraving/Cat Khusus).
                                </li>
                                <li class="flex items-center gap-3 text-gray-300">
                                    <span class="material-symbols-outlined text-red-500">block</span>
                                    Produk yang rusak akibat kelalaian pengguna.
                                </li>
                            </ul>
                        </div>
                    </section>

                    {{-- 4. DAMAGES / DOA --}}
                    <section id="damages" class="mb-16 scroll-mt-28">
                        <h2>4. Barang Rusak / Dead on Arrival (DOA)</h2>
                        <p>Jika PC atau Laptop Anda tiba dalam keadaan rusak fisik akibat pengiriman atau mati total (DOA):</p>
                        <ol>
                            <li><strong>Lapor dalam 48 Jam:</strong> Anda wajib melaporkan kerusakan maksimal 2x24 jam setelah status resi "Diterima".</li>
                            <li><strong>Bukti Video:</strong> Sertakan video unboxing tanpa putus. Tanpa video unboxing, klaim kerusakan fisik akan ditolak.</li>
                            <li><strong>Penggantian Gratis:</strong> Untuk kasus DOA, NexRig akan menanggung 100% biaya pengiriman balik dan mengirimkan unit pengganti baru tanpa biaya restocking.</li>
                        </ol>
                    </section>

                    {{-- 5. RETURN PROCESS --}}
                    <section id="process" class="mb-16 scroll-mt-28">
                        <h2>5. Proses Pengembalian</h2>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">1</div>
                                <div>
                                    <h4 class="text-white font-bold mb-1">Hubungi Support</h4>
                                    <p class="text-gray-400 text-sm !mb-0">Buka halaman Support dan ajukan tiket "Return Request". Sertakan nomor pesanan.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">2</div>
                                <div>
                                    <h4 class="text-white font-bold mb-1">Dapatkan Label RMA</h4>
                                    <p class="text-gray-400 text-sm !mb-0">Jika disetujui, kami akan mengirimkan dokumen RMA. Cetak dan tempel di paket.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">3</div>
                                <div>
                                    <h4 class="text-white font-bold mb-1">Inspeksi & Refund</h4>
                                    <p class="text-gray-400 text-sm !mb-0">Setelah barang sampai di gudang, kami butuh 3-5 hari kerja untuk inspeksi. Jika lolos, dana akan dikembalikan dalam 7-14 hari kerja tergantung bank Anda.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Footer Print --}}
                    <div class="pt-8 border-t border-white/10 flex justify-between items-center no-print">
                      
                        <button onclick="window.print()" class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-white transition-colors bg-white/5 px-4 py-2 rounded">
                            <span class="material-symbols-outlined">print</span> Print Policy
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Navigasi Scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                document.querySelectorAll('section').forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection