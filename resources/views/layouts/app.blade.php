<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'NexRig - High Performance Gaming PCs' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Ini adalah kunci utama: Sembunyikan apapun yang punya atribut x-cloak */
        [x-cloak] { 
            display: none !important; 
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1337ec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101322",
                        "surface-dark": "#191e33",
                        "border-dark": "#232948",
                        "text-secondary": "#929bc9"
                    },
                    fontFamily: { "display": ["Space Grotesk", "sans-serif"] }
                },
            },
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden min-h-screen pt-20 flex flex-col">
    
    {{-- Pastikan file resources/views/components/navbar.blade.php ada --}}
    <x-navbar />

  {{-- UBAH MENJADI SEPERTI INI --}}
<main class="flex-grow w-full relative">
        @yield('content')
    </main>

    {{-- 
        =========================================================
        TOAST NOTIFICATION (POP UP OTOMATIS)
        =========================================================
    --}}
    @if(session('success'))
        <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] flex items-center gap-4 bg-[#0a0a0a] border border-blue-600/50 text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(37,99,235,0.2)] transform transition-all duration-500 translate-y-0 opacity-100">
            
            {{-- Icon Check --}}
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600/20 rounded-full text-blue-500">
                <span class="material-symbols-outlined text-xl">check</span>
            </div>

            {{-- Text Message --}}
            <div>
                <h4 class="font-bold text-sm text-blue-500 uppercase tracking-wider">Success</h4>
                <p class="text-gray-300 text-xs mt-0.5">{{ session('success') }}</p>
            </div>

            {{-- Close Button (Manual) --}}
            <button onclick="closeToast()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>

            {{-- Progress Bar (Animasi Durasi) --}}
            <div class="absolute bottom-0 left-0 h-[2px] bg-blue-600 transition-all duration-[3000ms] ease-linear w-full" id="toast-progress"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast-notification');
                const progress = document.getElementById('toast-progress');

                if (toast) {
                    // 1. Mulai animasi progress bar mengecil sampai 0
                    setTimeout(() => {
                        progress.style.width = '0%';
                    }, 100);

                    // 2. Set timer untuk menghilangkan toast setelah 3 detik
                    setTimeout(() => {
                        closeToast();
                    }, 3000);
                }
            });

            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    // Efek menghilang (turun ke bawah & transparan)
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    
                    // Hapus dari DOM setelah animasi selesai
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }
            }
        </script>
    @endif


    {{-- Pastikan file resources/views/components/footer.blade.php ada --}}
    <x-footer /> 

    {{-- Panggil Mini Cart --}}
    <x-mini-cart />

    {{-- Script untuk logika Mini Cart --}}
    @stack('scripts')

    {{-- VIDEO OVERLAY COMPONENT --}}
    <div x-data="{ open: false, videoUrl: '' }" 
        x-on:open-video.window="open = true; videoUrl = $event.detail.url"
        x-on:keydown.escape.window="open = false; videoUrl = ''"
        x-show="open" 
        class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-10"
        x-cloak>
        
        {{-- Backdrop dengan Blur --}}
        <div x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false; videoUrl = ''"
            class="absolute inset-0 bg-black/90 backdrop-blur-xl">
        </div>

        {{-- Container Video --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-90 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-5xl aspect-video bg-black rounded-2xl overflow-hidden border border-white/10 shadow-[0_0_50px_rgba(19,55,236,0.3)]">
            
            {{-- Close Button --}}
            <button @click="open = false; videoUrl = ''" 
                    class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-black/50 text-white border border-white/10 hover:bg-primary transition-colors group">
                <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">close</span>
            </button>

            {{-- Loading Spinner (Sambil nunggu YouTube load) --}}
            <div class="absolute inset-0 flex items-center justify-center -z-10">
                <span class="material-symbols-outlined text-primary text-5xl animate-spin">progress_activity</span>
            </div>

            {{-- Iframe --}}
            <template x-if="open">
                <iframe class="w-full h-full" 
                        :src="videoUrl" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                </iframe>
            </template>
        </div>

        {{-- Garis Dekorasi ala NexRig --}}
        <div class="absolute bottom-10 left-10 hidden md:block opacity-20">
            <p class="text-primary font-mono text-xs tracking-[0.5em] uppercase">Visual Feed Established // 4K Resolution</p>
        </div>
    </div>

    {{-- SCRIPT GLOBAL (WAJIB ADA DI SINI) --}}
    <script>
        // 1. Fungsi Buka/Tutup Sidebar (Ini yang tadi hilang!)
        function toggleMiniCart() {
            const cart = document.getElementById('miniCart');
            const overlay = document.getElementById('miniCartOverlay');
            
            // Cek apakah elemen ada (untuk menghindari error di halaman tanpa cart)
            if (!cart || !overlay) return; 

            if (cart.classList.contains('translate-x-full')) {
                // Buka Sidebar
                cart.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                // Tutup Sidebar
                cart.classList.add('translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        // 2. Fungsi Add to Cart AJAX
        function addToCartAjax(e, form) {
            e.preventDefault(); 

            const formData = new FormData(form);
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">progress_activity</span> Adding...';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Header wajib 1
                    'Accept': 'application/json',         // [BARU] Header wajib 2 (Agar controller tau kita butuh JSON)
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                // Jika redirect (302) terjadi, fetch biasanya mengikutinya secara transparan.
                // Kita harus cek apakah url berubah atau statusnya oke.
                return response.json(); 
            })
            .then(data => {
                if(data.success) {
                    const itemsContainer = document.getElementById('miniCartItems');
                    const subtotalEl = document.getElementById('miniCartSubtotal');

                    if(itemsContainer) itemsContainer.innerHTML = data.cartHtml;
                    if(subtotalEl) subtotalEl.innerText = data.subtotal;

                    toggleMiniCart();
                } else {
                    // Jika json dikirim tapi success: false
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Pesan error lebih spesifik
                console.log("Kemungkinan controller me-redirect halaman, bukan mengirim JSON.");
                alert("Terjadi kesalahan. Cek Console (F12) untuk detail.");
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // 3. Fungsi Hapus Item dari Mini Cart
        function removeCartItem(id) {
        const itemElement = document.getElementById(`cart-item-${id}`);

        if(itemElement) {
            itemElement.style.opacity = '0.3'; 
            itemElement.style.pointerEvents = 'none';
        }

        const url = `/cart/${id}`; 

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to remove');
            return response.json();
        })
        .then(data => {
            if(data.success) {
                const itemsContainer = document.getElementById('miniCartItems');
                const subtotalEl = document.getElementById('miniCartSubtotal');
                
                // 1. Update List Item (Bisa jadi Empty State)
                if(itemsContainer) itemsContainer.innerHTML = data.cartHtml;
                
                // 2. Update Subtotal
                if(subtotalEl) subtotalEl.innerText = data.subtotal;

                // 3. KONTEKS BARU: Update Badge Navbar (Jika ada)
                // Cari elemen badge di navbar (asumsi ID 'cart-count' atau cari class-nya)
                const cartBadges = document.querySelectorAll('.absolute.-top-1.-right-1'); // Selector badge merah di navbar
                cartBadges.forEach(badge => {
                    if (data.cartCount > 0) {
                        badge.innerText = data.cartCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden'); // Sembunyikan jika 0
                    }
                });

                // 4. KONTEKS BARU: Update Tombol Checkout Footer
                if(data.cartCount === 0) {
                    const footerArea = document.querySelector('#miniCart .p-6.border-t'); // Area footer sidebar
                    if(footerArea) {
                        // Kita bisa ganti tombolnya jadi disabled state
                        const checkoutBtn = footerArea.querySelector('a, button');
                        if(checkoutBtn) {
                            // Ganti tag <a> ke <button disabled> secara dinamis atau manipulasi class
                            const disabledBtn = document.createElement('button');
                            disabledBtn.disabled = true;
                            disabledBtn.className = "block w-full py-4 bg-white/10 text-gray-500 text-center font-bold uppercase tracking-widest cursor-not-allowed clip-button";
                            disabledBtn.innerText = 'Checkout Now';
                            checkoutBtn.parentNode.replaceChild(disabledBtn, checkoutBtn);
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(itemElement) {
                itemElement.style.opacity = '1';
                itemElement.style.pointerEvents = 'auto';
            }
            alert('Gagal menghapus item.');
        });
    }
    </script>
</body>
</html>