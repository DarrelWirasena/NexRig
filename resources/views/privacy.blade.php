@extends('layouts.app')

@section('content')
    <style>
        /* Typography Content */
        .policy-content h2 { 
            color: white; font-weight: 800; text-transform: uppercase; font-style: italic; 
            letter-spacing: -0.02em; margin-top: 0; margin-bottom: 1.5rem; font-size: 2rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;
        }
        .policy-content h3 { 
            color: #60a5fa; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; 
            margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .policy-content p { margin-bottom: 1.5rem; line-height: 1.8; color: #d1d5db; font-size: 1rem; text-align: justify; }
        .policy-content ul, .policy-content ol { margin-bottom: 2rem; padding-left: 1.5rem; color: #d1d5db; line-height: 1.8; }
        .policy-content ul { list-style-type: disc; }
        .policy-content li { margin-bottom: 0.5rem; }
        .policy-content strong { color: white; font-weight: 700; }

        /* Background Pattern */

        /* Navigasi Samping */
        .nav-link.active {
            color: #3b82f6; border-left-color: #3b82f6;
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
                            Data Protection
                        </span>
                        <h1 class="text-5xl md:text-7xl font-black uppercase italic tracking-tighter text-white mb-4 leading-none">
                            Privacy <span class="text-blue-600">Policy</span>
                        </h1>
                        <p class="text-gray-400 max-w-2xl text-lg">
                            Komitmen kami untuk melindungi data pribadi dan privasi digital Anda saat berbelanja di NexRig.
                        </p>
                    </div>

                    {{-- Quick Stats Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px] flex items-center gap-4">
                            <span class="material-symbols-outlined text-3xl text-green-500">lock</span>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Security</p>
                                <p class="text-white font-bold text-lg">SSL Encrypted</p>
                            </div>
                        </div>
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px] flex items-center gap-4">
                            <span class="material-symbols-outlined text-3xl text-blue-500">visibility_off</span>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Data Sharing</p>
                                <p class="text-white font-bold text-lg">No 3rd Party</p>
                            </div>
                        </div>
                        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl min-w-[200px] flex items-center gap-4">
                            <span class="material-symbols-outlined text-3xl text-purple-500">cookie</span>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Cookies</p>
                                <p class="text-white font-bold text-lg">Managed</p>
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
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 px-4">Contents</p>
                        <nav class="space-y-1 border-l border-white/10">
                            <a href="#collection" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                1. Pengumpulan Data
                            </a>
                            <a href="#usage" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                2. Penggunaan Data
                            </a>
                            <a href="#sharing" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                3. Pembagian Data
                            </a>
                            <a href="#security" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                4. Keamanan & Cookies
                            </a>
                            <a href="#rights" class="nav-link block px-4 py-3 text-sm text-gray-400 hover:text-white border-l-2 border-transparent transition-all">
                                5. Hak Pengguna
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
                            NexRig menghargai privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.
                        </p>
                    </div>

                    {{-- 1. DATA COLLECTION --}}
                    <section id="collection" class="mb-16 scroll-mt-28">
                        <h2>1. Informasi yang Kami Kumpulkan</h2>
                        <p>Kami mengumpulkan informasi untuk memproses pesanan dan memberikan pengalaman terbaik. Data yang kami kumpulkan meliputi:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white/5 p-6 rounded border border-white/10">
                                <h4 class="text-white font-bold mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">person</span> Identitas Pribadi
                                </h4>
                                <p class="text-sm !mb-0 text-gray-400">Nama lengkap, alamat email, nomor telepon (WhatsApp), dan tanggal lahir (opsional).</p>
                            </div>
                            <div class="bg-white/5 p-6 rounded border border-white/10">
                                <h4 class="text-white font-bold mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">local_shipping</span> Data Pengiriman
                                </h4>
                                <p class="text-sm !mb-0 text-gray-400">Alamat pengiriman lengkap, kode pos, dan catatan khusus untuk kurir.</p>
                            </div>
                            <div class="bg-white/5 p-6 rounded border border-white/10">
                                <h4 class="text-white font-bold mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">credit_card</span> Informasi Pembayaran
                                </h4>
                                <p class="text-sm !mb-0 text-gray-400">Bukti transfer bank atau status transaksi dari Payment Gateway. <strong>Kami tidak menyimpan nomor Kartu Kredit Anda.</strong></p>
                            </div>
                            <div class="bg-white/5 p-6 rounded border border-white/10">
                                <h4 class="text-white font-bold mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">devices</span> Data Teknis
                                </h4>
                                <p class="text-sm !mb-0 text-gray-400">Alamat IP, jenis browser, dan data perangkat untuk keperluan keamanan dan analitik.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 2. DATA USAGE --}}
                    <section id="usage" class="mb-16 scroll-mt-28">
                        <h2>2. Bagaimana Kami Menggunakan Data</h2>
                        <p>Data Anda digunakan semata-mata untuk operasional layanan NexRig:</p>
                        <ul class="space-y-2">
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-blue-500 mt-1">check_circle</span>
                                <div><strong>Pemrosesan Pesanan:</strong> Memverifikasi pembayaran, merakit PC sesuai spesifikasi, dan mengatur pengiriman.</div>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-blue-500 mt-1">check_circle</span>
                                <div><strong>Layanan Pelanggan:</strong> Menghubungi Anda via WhatsApp/Email untuk update status perakitan atau merespon klaim garansi.</div>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-blue-500 mt-1">check_circle</span>
                                <div><strong>Keamanan:</strong> Mendeteksi dan mencegah penipuan atau akses tidak sah.</div>
                            </li>
                        </ul>
                    </section>

                    {{-- 3. DATA SHARING --}}
                    <section id="sharing" class="mb-16 scroll-mt-28">
                        <h2>3. Pembagian Data ke Pihak Ketiga</h2>
                        <p>NexRig <strong>TIDAK AKAN PERNAH</strong> menjual data pribadi Anda kepada pihak ketiga untuk tujuan pemasaran. Kami hanya membagikan data kepada mitra terpercaya untuk penyelesaian pesanan:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-500 mb-3">local_shipping</span>
                                <h4 class="text-white font-bold mb-2">Logistik</h4>
                                <p class="text-xs text-gray-400">JNE, J&T, Sicepat (Hanya Nama, Alamat, No. Telp).</p>
                            </div>
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-500 mb-3">payments</span>
                                <h4 class="text-white font-bold mb-2">Pembayaran</h4>
                                <p class="text-xs text-gray-400">Midtrans / Bank (Untuk verifikasi transaksi).</p>
                            </div>
                            <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-500 mb-3">gavel</span>
                                <h4 class="text-white font-bold mb-2">Hukum</h4>
                                <p class="text-xs text-gray-400">Pihak berwajib (Jika diwajibkan oleh undang-undang).</p>
                            </div>
                        </div>
                    </section>

                    {{-- 4. SECURITY & COOKIES --}}
                    <section id="security" class="mb-16 scroll-mt-28">
                        <h2>4. Keamanan & Cookies</h2>
                        <h3>Keamanan Data</h3>
                        <p>Kami menerapkan langkah-langkah keamanan teknis seperti enkripsi SSL (Secure Socket Layer) untuk melindungi transmisi data Anda. Akses ke data pribadi di database kami dibatasi hanya untuk karyawan yang memerlukannya (prinsip <em>need-to-know</em>).</p>
                        
                        <h3>Kebijakan Cookies</h3>
                        <p>NexRig menggunakan cookies untuk meningkatkan pengalaman belanja:</p>
                        <ul>
                            <li><strong>Session Cookies:</strong> Mengingat item dalam keranjang belanja Anda saat Anda menjelajahi situs.</li>
                            <li><strong>Analytics Cookies:</strong> Membantu kami memahami halaman mana yang paling populer (data anonim).</li>
                        </ul>
                        <p>Anda dapat menonaktifkan cookies melalui pengaturan browser Anda, namun beberapa fitur situs (seperti keranjang belanja) mungkin tidak berfungsi dengan baik.</p>
                    </section>

                    {{-- 5. USER RIGHTS --}}
                    <section id="rights" class="mb-16 scroll-mt-28">
                        <h2>5. Hak Pengguna</h2>
                        <p>Sebagai pengguna, Anda memiliki hak penuh atas data Anda:</p>
                        <div class="bg-blue-600/10 border border-blue-600/30 p-6 rounded-xl">
                            <ul class="!mb-0 !pl-0 !list-none space-y-3">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-blue-500">arrow_right_alt</span>
                                    <span>Hak untuk mengakses dan meminta salinan data pribadi Anda.</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-blue-500">arrow_right_alt</span>
                                    <span>Hak untuk memperbarui atau mengoreksi data yang salah.</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-blue-500">arrow_right_alt</span>
                                    <span>Hak untuk meminta penghapusan akun dan data pribadi (Right to be Forgotten).</span>
                                </li>
                            </ul>
                        </div>
                        <p class="mt-4 text-sm text-gray-400">Untuk mengajukan permintaan penghapusan data, silakan hubungi <a href="mailto:support@nexrig.com" class="text-blue-500 hover:underline">support@nexrig.com</a>.</p>
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