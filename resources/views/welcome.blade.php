<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NexRig - High Performance Gaming PCs</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
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
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #101322; 
        }
        ::-webkit-scrollbar-thumb {
            background: #232948; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1337ec; 
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden min-h-screen flex flex-col">
<!-- Top Navigation -->
<div class="border-b border-solid border-gray-200 dark:border-border-dark px-4 lg:px-10 py-3 sticky top-0 z-50 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm">
<header class="flex items-center justify-between whitespace-nowrap max-w-[1440px] mx-auto w-full">
<div class="flex items-center gap-4 lg:gap-8">
<a class="flex items-center gap-3 text-slate-900 dark:text-white hover:opacity-80 transition-opacity" href="#">
<div class="size-8 text-primary">
<svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
<path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">NexRig</h2>
</a>
<div class="hidden lg:flex items-center gap-6 xl:gap-9">
<a class="text-slate-600 dark:text-gray-300 hover:text-primary text-sm font-medium leading-normal transition-colors" href="#">Gaming PCs</a>
<a class="text-slate-600 dark:text-gray-300 hover:text-primary text-sm font-medium leading-normal transition-colors" href="#">Workstations</a>
<a class="text-slate-600 dark:text-gray-300 hover:text-primary text-sm font-medium leading-normal transition-colors" href="#">Laptops</a>
<a class="text-slate-600 dark:text-gray-300 hover:text-primary text-sm font-medium leading-normal transition-colors" href="#">Support</a>
</div>
</div>
<div class="flex flex-1 justify-end gap-4 lg:gap-8 items-center">
<label class="hidden md:flex flex-col min-w-40 !h-10 max-w-64">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full group focus-within:ring-2 ring-primary/50 transition-all">
<div class="text-slate-400 dark:text-text-secondary flex border-none bg-gray-100 dark:bg-border-dark items-center justify-center pl-4 rounded-l-lg border-r-0">
<span class="material-symbols-outlined text-[20px]">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg rounded-l-none border-none bg-gray-100 dark:bg-border-dark text-slate-900 dark:text-white focus:outline-0 focus:ring-0 h-full placeholder:text-slate-400 dark:placeholder:text-text-secondary px-4 pl-2 text-sm font-normal leading-normal" placeholder="Search rigs..." value=""/>
</div>
</label>
<div class="flex gap-2">
<button class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary hover:bg-blue-700 transition-colors text-white text-sm font-bold leading-normal tracking-[0.015em]">
<span class="truncate">Log In</span>
</button>
<button class="flex min-w-[40px] sm:min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-3 sm:px-4 bg-gray-200 dark:bg-border-dark hover:bg-gray-300 dark:hover:bg-slate-700 transition-colors text-slate-900 dark:text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
<span class="material-symbols-outlined text-[20px]">shopping_cart</span>
<span class="truncate hidden sm:inline">Cart</span>
</button>
</div>
</div>
</header>
</div>
<!-- Main Content Layout -->
<div class="flex-grow flex flex-col items-center w-full">
<div class="w-full max-w-[1440px] px-4 md:px-10 lg:px-40 pb-20">
<!-- Hero Section -->
<div class="@container py-6 md:py-8">
<div class="relative flex min-h-[500px] flex-col gap-6 items-center justify-center p-8 md:p-12 rounded-2xl overflow-hidden group shadow-2xl shadow-primary/10">
<!-- Hero Background Image -->
<div class="absolute inset-0 z-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" data-alt="High-end gaming PC setup with RGB lighting on a desk in a dark room" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBK5zOvxinJKiXjxkrBNJmzVuAjSfCL6eqSpOu2IBjJzaiiglPPMYrGtD0cY8CYsLesuBndxVPs1UEEtBnRKyTAojPw591SrO7icfOiNpadTWGeeYQ_YHL87mwwnCLyqu08goGLk5j8BZ7-qgt2fMaLxXtLzfYyAv9EwnfOv_YErLiDdAKVW2W2TOnUzBhwn9SNSDKJrbAG0p4YPeu_3bLRlizsdn0-RLiFoIr1AL4KziPrMYlPwXxV_B420bEq76_xHqENetFbUJZw");'>
</div>
<!-- Gradient Overlay -->
<div class="absolute inset-0 z-0 bg-gradient-to-t from-background-dark via-background-dark/80 to-transparent"></div>
<div class="absolute inset-0 z-0 bg-gradient-to-r from-background-dark/50 to-transparent"></div>
<!-- Hero Content -->
<div class="relative z-10 flex flex-col gap-4 text-center max-w-[800px]">
<span class="inline-block mx-auto py-1 px-3 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold uppercase tracking-wider backdrop-blur-md">New Arrivals</span>
<h1 class="text-white text-5xl md:text-6xl lg:text-7xl font-black leading-tight tracking-[-0.033em] drop-shadow-lg">
                            Build Your Legacy.
                        </h1>
<h2 class="text-gray-200 text-lg md:text-xl font-normal leading-relaxed max-w-[600px] mx-auto drop-shadow-md">
                            Experience next-level gaming with our custom-tuned rigs, engineered for maximum FPS and stunning visuals.
                        </h2>
<div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center">
<button class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 bg-primary hover:bg-blue-600 text-white text-base font-bold transition-all transform hover:scale-105 shadow-lg shadow-blue-900/50">
                                Shop Now
                            </button>
<button class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 text-white text-base font-bold transition-all">
                                View Gallery
                            </button>
</div>
</div>
</div>
</div>
<!-- Categories Section -->
<div class="flex flex-col gap-6 py-8">
<div class="flex items-end justify-between px-2">
<h2 class="text-slate-900 dark:text-white text-3xl font-bold leading-tight tracking-[-0.015em]">Choose Your Power Level</h2>
<a class="text-primary font-bold hover:underline hidden sm:block" href="#">View all categories -&gt;</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Card 1 -->
<div class="group flex flex-col gap-4 p-4 rounded-xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark hover:border-primary/50 transition-all hover:shadow-xl hover:shadow-primary/5 cursor-pointer">
<div class="w-full aspect-video bg-center bg-cover rounded-lg overflow-hidden relative">
<div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
<div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="Entry level gaming PC tower with clean white aesthetic" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDXWJCv_H__JRO9QTiKbY5rVcAl8VVkFCWdvNsYT-_8_auNzxbqVQ0jBdRJCB-j6GAZrD9x4-EWtbz9TS4j9SurAgF2K9NrwcsztyNM0um7j9aHhpxFRWX2cNp_kO2CZJVu_RCxFmrTjkuEOsJf4MXf7dI7ghihs9eIRpw3FiDMqKCSQloTPverErtAdkiVakLDQGnxr6ON4k_g4ISlJgqyzls49aPlEObB29_Oe5_t04QtRivQ-tHM1hEelH-jxu-DTvLY-D6w6q17");'>
</div>
</div>
<div>
<div class="flex justify-between items-center mb-1">
<p class="text-slate-900 dark:text-white text-xl font-bold">Entry-Level</p>
<span class="text-primary font-bold">$999</span>
</div>
<p class="text-slate-500 dark:text-text-secondary text-sm">Great for 1080p gaming &amp; Esports titles.</p>
</div>
</div>
<!-- Card 2 -->
<div class="group flex flex-col gap-4 p-4 rounded-xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark hover:border-primary/50 transition-all hover:shadow-xl hover:shadow-primary/5 cursor-pointer relative overflow-hidden">
<div class="absolute top-0 right-0 bg-primary text-white text-xs font-bold px-3 py-1 rounded-bl-lg z-10">BEST SELLER</div>
<div class="w-full aspect-video bg-center bg-cover rounded-lg overflow-hidden relative">
<div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
<div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="Professional gaming setup with dual monitors and ambient lighting" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAx94t4CBtIPbLmUZwjdc5qZ5CEoVykDrBcWREYxc_3D9QuZJUInEh-gN6VTJEWewu-MMfNFid-Z6ovzzCJuFvKME6nxP3IurwGpOsfLEdxDham4CBLn9BPY4MriT1riB-IUIp9K1vuE8Tfa3XajZZgqFJ5kdh1R9E0uHBr-BcG3Gj6MsZ-ImGM8sTcnbPNjFMChyS7UJRDduxqgRTSqUmUDfNIwsnAzSAJTNe4OeX5EyhuUb_-ISiDCMSqw39msbDJxhEwcRjJ3RZw");'>
</div>
</div>
<div>
<div class="flex justify-between items-center mb-1">
<p class="text-slate-900 dark:text-white text-xl font-bold">Professional</p>
<span class="text-primary font-bold">$1,899</span>
</div>
<p class="text-slate-500 dark:text-text-secondary text-sm">1440p High Refresh Rate gaming &amp; streaming.</p>
</div>
</div>
<!-- Card 3 -->
<div class="group flex flex-col gap-4 p-4 rounded-xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark hover:border-primary/50 transition-all hover:shadow-xl hover:shadow-primary/5 cursor-pointer">
<div class="w-full aspect-video bg-center bg-cover rounded-lg overflow-hidden relative">
<div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
<div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="Extreme high-end gaming PC with custom liquid cooling and heavy RGB" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAA9aBT9_5I86Ehi022LjZdYGsGDMoKhdU6o61zzjn_3N4z7X1FJkq9jqbXyMzBhSistZywmUSRT9U3ZE-3BiWAr_C_sGq07XjHk-X-MMMg4Nxp-6_Mk7GkXOxRLZtSFl2f4OIsell5LVzXVF9GkdUbr16wn4-ayxTKlm0A4c3i0IyhLwtJjTMxRaCWY_-L69JC3Gwkgs8SpYLUxbivrNnYxisgDk2DIE62XrIPuiG88hH6q18ipXPnocdLr2C-NHMVpDDioHNLzReR");'>
</div>
</div>
<div>
<div class="flex justify-between items-center mb-1">
<p class="text-slate-900 dark:text-white text-xl font-bold">Extreme</p>
<span class="text-primary font-bold">$3,499+</span>
</div>
<p class="text-slate-500 dark:text-text-secondary text-sm">4K Ultimate Performance &amp; VR Ready.</p>
</div>
</div>
</div>
</div>
<!-- Features Section -->
<div class="flex flex-col xl:flex-row gap-10 py-16 @container">
<!-- Text Content -->
<div class="flex flex-col gap-6 flex-1 xl:max-w-[400px]">
<div class="flex flex-col gap-4">
<h1 class="text-slate-900 dark:text-white tracking-tight text-4xl md:text-5xl font-bold leading-tight">
                            Why NexRig?
                        </h1>
<div class="h-1 w-20 bg-primary rounded-full"></div>
<p class="text-slate-600 dark:text-gray-300 text-lg font-normal leading-relaxed">
                            Built by gamers, for gamers. We ensure every rig is hand-assembled and stress-tested for maximum performance before it reaches your doorstep.
                        </p>
</div>
<button class="flex items-center justify-center rounded-lg h-12 px-6 bg-primary hover:bg-blue-600 text-white text-base font-bold w-fit transition-colors shadow-lg shadow-blue-900/20">
                        Learn More
                    </button>
</div>
<!-- Features Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-[2]">
<!-- Feature 1 -->
<div class="flex gap-4 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark p-6 flex-col hover:-translate-y-1 transition-transform duration-300">
<div class="text-primary mb-2">
<span class="material-symbols-outlined text-[32px]">sports_esports</span>
</div>
<div class="flex flex-col gap-2">
<h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">Plug &amp; Play</h2>
<p class="text-slate-500 dark:text-text-secondary text-sm leading-normal">Ready to game right out of the box. No bloatware, just pure performance.</p>
</div>
</div>
<!-- Feature 2 -->
<div class="flex gap-4 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark p-6 flex-col hover:-translate-y-1 transition-transform duration-300">
<div class="text-primary mb-2">
<span class="material-symbols-outlined text-[32px]">verified_user</span>
</div>
<div class="flex flex-col gap-2">
<h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">2-Year Warranty</h2>
<p class="text-slate-500 dark:text-text-secondary text-sm leading-normal">Comprehensive hardware and labor coverage for total peace of mind.</p>
</div>
</div>
<!-- Feature 3 -->
<div class="flex gap-4 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark p-6 flex-col hover:-translate-y-1 transition-transform duration-300">
<div class="text-primary mb-2">
<span class="material-symbols-outlined text-[32px]">memory</span>
</div>
<div class="flex flex-col gap-2">
<h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">Premium Parts</h2>
<p class="text-slate-500 dark:text-text-secondary text-sm leading-normal">We only use top-tier components from trusted brands like ASUS, Corsair, and NVIDIA.</p>
</div>
</div>
<!-- Feature 4 -->
<div class="flex gap-4 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark p-6 flex-col hover:-translate-y-1 transition-transform duration-300">
<div class="text-primary mb-2">
<span class="material-symbols-outlined text-[32px]">support_agent</span>
</div>
<div class="flex flex-col gap-2">
<h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight">Expert Support</h2>
<p class="text-slate-500 dark:text-text-secondary text-sm leading-normal">24/7 assistance from our US-based team of PC experts.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</body></html>