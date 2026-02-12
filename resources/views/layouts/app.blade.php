<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'NexRig - High Performance Gaming PCs' }}</title>
    
    {{-- 1. FONTS & ICONS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@1,900&family=Space+Grotesk:wght@300;400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    {{-- 2. ASSETS (Vite & Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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

    {{-- 3. GLOBAL CSS (Animations & Typography) --}}
    <style>
        /* Gaming Font for CTA/Hero */
        .font-gaming { 
            font-family: 'Inter', sans-serif; 
            font-style: italic; 
            font-weight: 900; 
            letter-spacing: -0.05em;
            text-transform: uppercase;
        }

        /* Scroll Reveal Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-reveal { 
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; 
        }
        .scroll-trigger { opacity: 0; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #101322; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #232948; border-radius: 10px; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden min-h-screen flex flex-col">
    
 {{-- Tampilkan Navbar HANYA JIKA bukan halaman login atau register --}}
@if(!Route::is('login') && !Route::is('register'))
    @include('components.navbar') {{-- Sesuaikan dengan nama file navbar Anda --}}
@endif

    {{-- MAIN CONTENT --}}
    <main class="flex-grow w-full relative">
        @yield('content')
    </main>

   {{-- Tampilkan Footer HANYA JIKA bukan halaman login atau register --}}
@if(!Route::is('login') && !Route::is('register'))
    @include('components.footer') {{-- Sesuaikan dengan nama file footer Anda --}}
@endif

    {{-- SIDEBARS & OVERLAYS --}}
    <x-mini-cart />

    {{-- 
        =========================================================
        TOAST NOTIFICATION (Success & Error Support)
        =========================================================
    --}}
    @if(session('success') || session('error'))
        @php 
            $isSuccess = session('success');
            $message = $isSuccess ?: session('error');
            $themeColor = $isSuccess ? 'blue-600' : 'red-600';
            $icon = $isSuccess ? 'check' : 'priority_high';
        @endphp

        <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] flex items-center gap-4 bg-[#0a0a0a] border border-{{ $themeColor }}/50 text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(0,0,0,0.5)] transform transition-all duration-500 translate-y-0 opacity-100">
            <div class="flex items-center justify-center w-8 h-8 bg-{{ $themeColor }}/20 rounded-full text-{{ $themeColor }}">
                <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
            </div>
            <div>
                <h4 class="font-bold text-sm text-{{ $themeColor }} uppercase tracking-wider">{{ $isSuccess ? 'Success' : 'Error' }}</h4>
                <p class="text-gray-300 text-xs mt-0.5">{{ $message }}</p>
            </div>
            <button onclick="closeToast()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
            <div class="absolute bottom-0 left-0 h-[2px] bg-{{ $themeColor }} transition-all duration-[3000ms] ease-linear w-full" id="toast-progress"></div>
        </div>
    @endif

    {{-- 
        =========================================================
        GLOBAL SCRIPTS
        =========================================================
    --}}
    <script>
        // 1. Toast Logic
        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const progress = document.getElementById('toast-progress');
            if (progress) {
                setTimeout(() => progress.style.width = '0%', 100);
                setTimeout(closeToast, 3000);
            }

            // 2. Scroll Reveal Animation Logic (Intersection Observer)
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-reveal');
                        entry.target.classList.remove('opacity-0');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-trigger').forEach((el) => observer.observe(el));
        });

        // 3. Mini Cart Logic
        function toggleMiniCart() {
            const cart = document.getElementById('miniCart');
            const overlay = document.getElementById('miniCartOverlay');
            if (!cart || !overlay) return; 

            if (cart.classList.contains('translate-x-full')) {
                cart.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                cart.classList.add('translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        // 4. AJAX Cart Operations
        function updateMiniCartUI(data) {
            const itemsContainer = document.getElementById('miniCartItems');
            const subtotalEl = document.getElementById('miniCartSubtotal');
            if(itemsContainer) itemsContainer.innerHTML = data.cartHtml;
            if(subtotalEl) subtotalEl.innerText = data.subtotal;
        }

        function addToCartAjax(e, form) {
            e.preventDefault(); 
            const formData = new FormData(form);
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">progress_activity</span>';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    updateMiniCartUI(data);
                    toggleMiniCart();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(err => alert("Terjadi kesalahan sistem."))
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        function removeCartItem(id) {
            const itemElement = document.getElementById(`cart-item-${id}`);
            if(itemElement) {
                itemElement.style.opacity = '0.3'; 
                itemElement.style.pointerEvents = 'none';
            }

            fetch(`/cart/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) updateMiniCartUI(data);
            })
            .catch(err => {
                if(itemElement) itemElement.style.opacity = '1';
                alert('Gagal menghapus item.');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>