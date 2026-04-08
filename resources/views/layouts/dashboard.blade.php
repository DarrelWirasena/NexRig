<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- LOGIKA: Jika ada variabel $title, pakai itu. Jika tidak, pakai default 'NexRig' --}}
    <title>{{ $title ?? config('app.name', 'NexRig') }}</title>
    <link rel="icon" type="image/png"
        href="https://res.cloudinary.com/dwu1fbd69/image/upload/v1773198090/logonexrig_tryrac.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dwu1fbd69/image/upload/v1773198090/logonexrig_tryrac.png">
    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
        }

        .clip-card {
            clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px);
        }

        /* NexRig Cyberpunk SweetAlert Style */
        .nexrig-swal-popup {
            background: #0a0a0a !important;
            border: 1px solid rgba(37, 99, 235, 0.3) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 1.5rem !important;
        }

        @media (max-width: 640px) {
            .nexrig-swal-popup {
                width: 90% !important;
                padding: 1.25rem !important;
            }

            .swal2-actions {
                flex-direction: column-reverse !important;
                gap: 12px;
                width: 100% !important;
            }

            .swal2-actions button {
                width: 100% !important;
                margin: 0 !important;
            }
        }

        .input-tech {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-tech:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
        }

        .nav-item.active {
            background-color: #2563eb;
            color: white;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.4);
        }

        main::-webkit-scrollbar {
            width: 8px;
        }

        main::-webkit-scrollbar-track {
            background: #050014;
        }

        main::-webkit-scrollbar-thumb {
            background: #1f1f1f;
            border-radius: 4px;
        }

        .no-bounce {
            overscroll-behavior: none;
        }


        /* 🔥 STYLE KHUSUS TOAST NEXRIG (SEPERTI DI GAMBAR) 🔥 */
        .nexrig-toast {
            background: #0a0a0a !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-left: 4px solid #2563eb !important;
            /* Aksen biru di kiri */
            color: white !important;
            border-radius: 12px !important;
            padding: 1rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        /* Memaksa Toast berada paling depan */
        .swal2-container {
            z-index: 99999 !important;
        }

        /* 🚀 MENGGESER TOAST KE KIRI SAAT MINI CART BISA TERBUKA (DESKTOP) 🚀 */
        @media (min-width: 768px) {

            .swal2-container.swal2-bottom-end,
            .swal2-container.swal2-bottom-right {
                /* 450px (Lebar Mini Cart) + 24px (Jarak Spasi) */
                right: 474px !important;
                bottom: 24px !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-[#050014] text-white">

    {{-- 🔥 TOP LOADING BAR (NEXRIG STYLE) 🔥 --}}
    <div id="top-loading-bar"
        class="fixed top-0 left-0 h-[3px] bg-primary z-[999999] transition-all ease-out w-0 shadow-[0_0_15px_rgba(37,99,235,1)] opacity-0 pointer-events-none">
    </div>

    <div class="h-screen flex overflow-hidden relative no-bounce">
        {{-- Background Glow --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full">
            </div>
            <div
                class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-900/10 blur-[120px] rounded-full">
            </div>
        </div>

        <div id="sidebarOverlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/80 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity no-bounce"></div>

        <x-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-0 md:p-6 lg:p-12 w-full relative z-10 no-bounce scroll-smooth">
            {{-- Mobile Menu (Dipindahkan keluar dari content area agar fix di atas jika perlu) --}}
            <div
                class="lg:hidden m-4 mb-2 flex items-center justify-between bg-[#0a0a0a] border border-white/10 p-4 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">dashboard</span>
                    <span class="font-bold text-sm uppercase tracking-widest">Menu</span>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:text-blue-500 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            @yield('content')
        </main>
    </div>

    {{-- 🔥 TAMBAHAN WAJIB: KOMPONEN MINI CART & TOAST BRIDGE 🔥 --}}
    <x-mini-cart />

    <div id="flash-messages" data-success="{{ session('success') }}" data-error="{{ session('error') }}"
        data-validation="{{ $errors->any() ? $errors->first() : '' }}" class="hidden">
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // --- GLOBAL DELETE ---
        function confirmDelete(id) {
            Swal.fire({
                title: 'HAPUS ALAMAT?',
                text: "Data ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#1f2937',
                confirmButtonText: 'YA, HAPUS!',
                cancelButtonText: 'BATAL',
                background: '#0a0a0a',
                color: '#fff',
                width: window.innerWidth < 640 ? '90%' : '30rem',
                customClass: {
                    popup: 'nexrig-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            })
        }

        // --- 🔥 KONFIGURASI TOAST SWEETALERT2 🔥 ---
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'nexrig-toast'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Membuat fungsi global window.showToast agar bisa dipanggil dari AJAX manapun
        window.showToast = function(message, type = 'success') {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // --- FLASH MESSAGES DARI CONTROLLER (JIKA ADA) ---
        const msgSuccess = "{{ session('success') }}";
        const msgError = "{{ session('error') }}";

        if (msgSuccess) {
            window.showToast(msgSuccess, 'success');
        }

        if (msgError) {
            window.showToast(msgError, 'error');
        }

        document.addEventListener('DOMContentLoaded', function() {

            const topBar = document.getElementById('top-loading-bar');
            let loaderInterval;

            // 1. Fungsi Animasi Garis Mulai
            function startTopBar() {
                if (!topBar) return;
                clearInterval(loaderInterval);

                topBar.classList.remove('opacity-0', 'duration-500');
                topBar.classList.add('opacity-100', 'duration-300');
                topBar.style.width = '15%';

                let progress = 15;
                loaderInterval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress > 85) progress = 85;
                    topBar.style.width = progress + '%';
                }, 300);
            }

            // 2. Fungsi Animasi Garis Selesai
            function finishTopBar() {
                if (!topBar) return;
                clearInterval(loaderInterval);

                topBar.style.width = '100%';

                setTimeout(() => {
                    topBar.classList.remove('duration-300');
                    topBar.classList.add('duration-500', 'opacity-0');

                    setTimeout(() => {
                        topBar.style.width = '0%';
                    }, 500);
                }, 200);
            }

            // --- EVENT LISTENERS ---

            // A. Sembunyikan bar saat halaman selesai load
            window.addEventListener('load', finishTopBar);

            // B. Munculkan bar saat link diklik
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    const target = this.getAttribute('target');

                    // Filter bypass link tertentu
                    if (!href || href.startsWith('#') || target === '_blank' || href.startsWith(
                            'javascript:')) return;
                    if (this.classList.contains('no-global-loader')) return;
                    if (e.ctrlKey || e.metaKey) return;

                    startTopBar();
                });
            });

            // C. Fallback Safari/Bfcache (Tombol Back)
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) finishTopBar();
            });

            // D. Form Submit Protector dengan Pengecualian
            document.addEventListener('submit', function(event) {
                const form = event.target;

                // Bypass form khusus AJAX (Add to Cart / Checkout)
                if (form.classList.contains('no-global-loader')) return;

                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    const currentWidth = submitBtn.offsetWidth;
                    submitBtn.style.width = currentWidth + 'px';
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                            <span>Processing...</span>
                        </div>
                    `;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    // Trigger loader atas
                    startTopBar();
                }
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
