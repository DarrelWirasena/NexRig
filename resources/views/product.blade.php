<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NexRig - High-Performance Gaming PCs</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1337ec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111422",
                        "surface-dark": "#232948",
                        "text-secondary": "#929bc9",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col transition-colors duration-200">
<!-- Header -->
<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-[#232948] bg-white/90 dark:bg-[#111422]/90 backdrop-blur-md px-4 md:px-10 py-3">
<div class="flex items-center gap-8">
<div class="flex items-center gap-3 text-gray-900 dark:text-white">
<div class="flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[32px] font-bold">sports_esports</span>
</div>
<h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">NexRig</h2>
</div>
<nav class="hidden lg:flex items-center gap-9">
<a class="text-gray-600 dark:text-white text-sm font-medium hover:text-primary transition-colors" href="#">Desktops</a>
<a class="text-gray-600 dark:text-white text-sm font-medium hover:text-primary transition-colors" href="#">Laptops</a>
<a class="text-gray-600 dark:text-white text-sm font-medium hover:text-primary transition-colors" href="#">Gear</a>
<a class="text-gray-600 dark:text-white text-sm font-medium hover:text-primary transition-colors" href="#">Support</a>
<a class="text-gray-600 dark:text-white text-sm font-medium hover:text-primary transition-colors" href="#">Sale</a>
</nav>
</div>
<div class="flex flex-1 justify-end gap-4 md:gap-8 items-center">
<!-- Search Bar -->
<label class="hidden md:flex flex-col min-w-40 !h-10 max-w-64 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-gray-100 dark:bg-[#232948] overflow-hidden group focus-within:ring-2 ring-primary/50 transition-all">
<div class="text-gray-500 dark:text-[#929bc9] flex items-center justify-center pl-4">
<span class="material-symbols-outlined text-[20px]">search</span>
</div>
<input class="flex w-full min-w-0 flex-1 resize-none bg-transparent border-none text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-[#929bc9] px-3 focus:outline-none text-sm font-normal leading-normal" placeholder="Search PCs..." value=""/>
</div>
</label>
<div class="flex gap-2 items-center">
<button class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary hover:bg-blue-700 transition-colors text-white text-sm font-bold tracking-[0.015em]">
<span class="truncate">Sign In</span>
</button>
<button class="flex items-center justify-center rounded-lg size-10 bg-gray-100 dark:bg-[#232948] text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-[#323b67] transition-colors">
<span class="material-symbols-outlined text-[20px]">shopping_cart</span>
</button>
<!-- Mobile Menu Toggle -->
<button class="lg:hidden flex items-center justify-center rounded-lg size-10 bg-gray-100 dark:bg-[#232948] text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-[#323b67] transition-colors">
<span class="material-symbols-outlined text-[20px]">menu</span>
</button>
</div>
</div>
</header>
<!-- Main Content -->
<main class="flex-1 flex flex-col w-full max-w-[1440px] mx-auto px-4 md:px-10 py-6">
<!-- Breadcrumbs & Title -->
<div class="mb-8">
<div class="flex flex-wrap gap-2 items-center mb-4 text-sm">
<a class="text-gray-500 dark:text-[#929bc9] font-medium hover:text-primary" href="#">Home</a>
<span class="text-gray-500 dark:text-[#929bc9] font-medium">/</span>
<a class="text-gray-500 dark:text-[#929bc9] font-medium hover:text-primary" href="#">Desktops</a>
<span class="text-gray-500 dark:text-[#929bc9] font-medium">/</span>
<span class="text-gray-900 dark:text-white font-medium">Gaming PCs</span>
</div>
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<h1 class="text-gray-900 dark:text-white text-3xl md:text-4xl font-bold leading-tight">High-Performance Gaming PCs</h1>
<p class="text-gray-500 dark:text-[#929bc9] text-base font-normal">Showing 24 premium builds</p>
</div>
</div>
<div class="flex flex-col lg:flex-row gap-8">
<!-- Sidebar Filters -->
<aside class="w-full lg:w-64 shrink-0 flex flex-col gap-8">
<!-- Categories -->
<div class="bg-white dark:bg-[#111422] rounded-xl lg:border border-gray-200 dark:border-[#232948] lg:p-4">
<div class="flex flex-col gap-2 mb-6">
<h3 class="text-gray-900 dark:text-white text-lg font-bold">Categories</h3>
<p class="text-gray-500 dark:text-[#929bc9] text-sm">Find your perfect rig</p>
</div>
<div class="flex flex-col gap-1">
<button class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 dark:bg-[#232948] text-primary dark:text-white font-medium group transition-all">
<span class="material-symbols-outlined text-[20px]">computer</span>
<span class="text-sm">All Models</span>
</button>
<button class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-[#232948] text-gray-600 dark:text-[#929bc9] hover:text-gray-900 dark:hover:text-white font-medium group transition-all">
<span class="material-symbols-outlined text-[20px]">speed</span>
<span class="text-sm">Performance Series</span>
</button>
<button class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-[#232948] text-gray-600 dark:text-[#929bc9] hover:text-gray-900 dark:hover:text-white font-medium group transition-all">
<span class="material-symbols-outlined text-[20px]">palette</span>
<span class="text-sm">Creator Series</span>
</button>
<button class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-[#232948] text-gray-600 dark:text-[#929bc9] hover:text-gray-900 dark:hover:text-white font-medium group transition-all">
<span class="material-symbols-outlined text-[20px]">desktop_windows</span>
<span class="text-sm">Compact Builds</span>
</button>
</div>
</div>
<!-- Price Filter -->
<div class="bg-white dark:bg-[#111422] rounded-xl lg:border border-gray-200 dark:border-[#232948] lg:p-4">
<div class="flex justify-between items-center mb-6">
<h3 class="text-gray-900 dark:text-white text-lg font-bold">Price Range</h3>
</div>
<div class="px-2 pb-2">
<div class="relative h-1.5 w-full bg-gray-200 dark:bg-[#323b67] rounded-full mb-8">
<div class="absolute left-[20%] right-[15%] top-0 bottom-0 bg-primary rounded-full"></div>
<!-- Left Handle -->
<div class="absolute left-[20%] top-1/2 -translate-y-1/2 -translate-x-1/2 size-5 bg-white border-2 border-primary rounded-full cursor-pointer shadow-md flex flex-col items-center">
<span class="absolute -top-8 text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap">$1,499</span>
</div>
<!-- Right Handle -->
<div class="absolute right-[15%] top-1/2 -translate-y-1/2 translate-x-1/2 size-5 bg-white border-2 border-primary rounded-full cursor-pointer shadow-md flex flex-col items-center">
<span class="absolute -top-8 text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap">$4,500</span>
</div>
</div>
<div class="flex justify-between text-xs text-gray-500 dark:text-[#929bc9] font-medium">
<span>Min: $999</span>
<span>Max: $10k+</span>
</div>
</div>
</div>
<!-- More Filters -->
<div class="bg-white dark:bg-[#111422] rounded-xl lg:border border-gray-200 dark:border-[#232948] lg:p-4">
<div class="flex justify-between items-center mb-4">
<h3 class="text-gray-900 dark:text-white text-lg font-bold">Specs</h3>
</div>
<div class="flex flex-col gap-3">
<label class="flex items-center gap-3 cursor-pointer group">
<div class="size-5 rounded border border-gray-300 dark:border-gray-600 bg-transparent flex items-center justify-center group-hover:border-primary transition-colors">
<!-- Checked state would have a checkmark here -->
</div>
<span class="text-sm text-gray-600 dark:text-[#929bc9] group-hover:text-gray-900 dark:group-hover:text-white">NVIDIA RTX 4090</span>
</label>
<label class="flex items-center gap-3 cursor-pointer group">
<div class="size-5 rounded bg-primary flex items-center justify-center">
<span class="material-symbols-outlined text-white text-[16px]">check</span>
</div>
<span class="text-sm text-gray-900 dark:text-white font-medium">NVIDIA RTX 4080</span>
</label>
<label class="flex items-center gap-3 cursor-pointer group">
<div class="size-5 rounded border border-gray-300 dark:border-gray-600 bg-transparent flex items-center justify-center group-hover:border-primary transition-colors"></div>
<span class="text-sm text-gray-600 dark:text-[#929bc9] group-hover:text-gray-900 dark:group-hover:text-white">AMD Ryzen 9</span>
</label>
<label class="flex items-center gap-3 cursor-pointer group">
<div class="size-5 rounded border border-gray-300 dark:border-gray-600 bg-transparent flex items-center justify-center group-hover:border-primary transition-colors"></div>
<span class="text-sm text-gray-600 dark:text-[#929bc9] group-hover:text-gray-900 dark:group-hover:text-white">Intel Core i9</span>
</label>
</div>
</div>
</aside>
<!-- Product Grid -->
<div class="flex-1">
<!-- Toolbar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
<div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 w-full sm:w-auto">
<span class="px-3 py-1 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-blue-300 text-xs font-bold border border-primary/20 flex items-center gap-1 whitespace-nowrap">
                            RTX 4080 <button class="hover:text-white"><span class="material-symbols-outlined text-[14px]">close</span></button>
</span>
<button class="text-xs font-bold text-gray-500 dark:text-[#929bc9] hover:text-primary dark:hover:text-white transition-colors whitespace-nowrap">Clear all</button>
</div>
<div class="flex items-center gap-3 ml-auto">
<span class="text-sm text-gray-500 dark:text-[#929bc9]">Sort by:</span>
<div class="relative group">
<button class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white bg-transparent">
                                Featured <span class="material-symbols-outlined text-[18px]">expand_more</span>
</button>
</div>
</div>
</div>
<!-- Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
<!-- Card 1 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="High-end gaming PC tower with RGB lighting in dark room" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAaAUwS5E_cWByMLjBfBY_5fHrcevUEJB-VgzWc_aCB6sDnOZcfcVfFpVpsPTjyOta3XKo2TLtO1rw5_cB_jxa_n_z4vVyXmrLVB59S-5uxI8kOu70eWMCavp3sGrXX5ieD5063OEb45IQrRL4Qv4eIHEBIRnHxvNH0WSx6fg95UPqDBoVdnLLpwAq-5CGl7t8NuK978oAt0y0evKQCjUROqeqGn6HkSvX_xEwa05hvMe-n_Bu6m7we8QuhKlCHX-mlWzxdMoY7bQqU"/>
<div class="absolute top-3 left-3 bg-primary text-white text-xs font-bold px-2.5 py-1 rounded">
                                Best Seller
                            </div>
<button class="absolute top-3 right-3 p-2 rounded-full bg-black/50 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary">
<span class="material-symbols-outlined text-[20px]">favorite</span>
</button>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Horizon Elite</h3>
<div class="flex items-center gap-1 text-yellow-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px] fill-current">star</span> 4.9
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">The ultimate 4K gaming experience with premium cooling.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">i9-13900K</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RTX 4090</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">64GB DDR5</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$3,499</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
<!-- Card 2 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="White gaming PC build with blue LED fans" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBPIA590ckS-TlIs5KqAZkTjty67VAZlnX3m8-1zds80wxBBP0t0oOZ0-sKHOgCw1AOhVNOS5ryZyT4jdk7qJVLxDD-KhbAf2TrU0qWnemWvwey31nguY-0eN-0ntoYLZSHC1XPhoNgu6R2zVYMg8ocSRRIK0PdTJJzyM97r7x7bn1qqhMsFdz3RD_xphmL06J6rGgzI06c-VgVdpAoy_BrMkxIZKMN_0A781eluwwxifgqQE6bjLzp96dcV86MyiJxaW6nsekhbJG"/>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Apex Pro</h3>
<div class="flex items-center gap-1 text-yellow-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px] fill-current">star</span> 4.7
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">Balanced performance for streaming and high-fps gaming.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">i7-13700K</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RTX 4080</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">32GB DDR5</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$2,499</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
<!-- Card 3 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="Sleek minimalist black PC case on desk" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHzsC7S12NMyYT2CP9IwfiAfLhlmbzkEN4y4CM_bLgxtODsXXP7BD9rtP9wRCLpb9oSlmgT8UjCXkrINrRhE6nx6Ytt5Q-y7m2O2hHwqT_dYOMx6RY9M3irWRMVZiDVIY6jMsYedXiX-AzXq3X4yLYuDbQrDLQM08CJ0KNy9Z9sTGDtmn3A6aBh0PxY5-ZZJu3q_EHRhJ-Sxjlw6cJynwgK6zllmUMczufV8SHTluobbTETxn7UAAdYZtUyqy_mINAW0T3ZDFCftaU"/>
<div class="absolute top-3 left-3 bg-teal-500 text-white text-xs font-bold px-2.5 py-1 rounded">
                                New Arrival
                            </div>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Stealth X</h3>
<div class="flex items-center gap-1 text-yellow-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px] fill-current">star</span> 4.8
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">Silent operation in a compact, minimalist chassis.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">Ryzen 9 7900X</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RX 7900 XTX</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">32GB DDR5</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$2,199</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
<!-- Card 4 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="Interior of a gaming PC showing glowing components and tubes" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOX7EXJljAJE5CqUq2xj49IF7AfhzURcnfOYU1SKSDn9L8kpzidImdfkeQPq1eRZZCId2hUgg2bKQfp4Iqb4ti4BgLgPbPq6PQHgDaIykVx1tPtef9zlMaqx2rOt9FxIgbzOZP6uSM2jQgJWshhpR64vTrn8TwTMPETUKeuZvv_xXXikALaZss3Lh5m53r7sjrXTWWkK1_wBlguh3vaUkuk1WApLaARAMIgL6kHZb7cYzPgleukZTw_hrjCBJFZ3dKq8TuSu3ytkFe"/>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Vortex liquid</h3>
<div class="flex items-center gap-1 text-gray-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px]">star</span> --
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">Custom liquid cooling loops for maximum overclocking.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">i9-14900KS</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RTX 4090</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">128GB DDR5</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$5,299</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
<!-- Card 5 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="Esports tournament stage with gaming computers" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCg-5RPN47hnvtQNIGkpTR8MOfTcKRl1A-fBdlqNWrX4ai0S-sFJydkqf3nYdOPek55JXs7XCwl6TchrrveHt_OXvifDE5SKimuSaR8UUDoHzid__t9Wq8mpRlvTBdJ13mAa-GiSBTNSfeqFt3ph1Et5g8COFsrHHVo6HPM4MIA76C-GEf_Aiu429SD_y6t6PTkSugBfmztEMiB3sB8Y6JvzKWaC-AySrVsRwujvn003bJtnNvu4_5EXcvjIyxuY_MyqbMtQp7RO81j"/>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Vector Comp</h3>
<div class="flex items-center gap-1 text-yellow-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px] fill-current">star</span> 5.0
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">Built specifically for competitive esports titles.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">Ryzen 7 7800X3D</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RTX 4070 Ti</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">32GB DDR5</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$1,899</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
<!-- Card 6 -->
<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary dark:hover:border-primary hover:shadow-xl dark:hover:shadow-blue-900/20 transition-all duration-300 h-full">
<div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
<img alt="Gaming PC" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="Abstract purple and blue lighting reflection on computer glass case" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZ9tdVwogCfIgjre6vDZ7GnbFSIEWxAjDpGDiL6DGwOEJ6035jIUwDzwiMUa01UJpbd0tofaNaAZeXZAd_pDC83ThikP1iajsQzKjaNGyvo41I4JO-1rErQj8QSrt4qQNFyU2mZP6CzgvmH9Y-JtI7RMKhJ4_jkX0IMjwfTTWvft9R4uu9Q6IqFqXXmioRP1qi-oTyRcODdsDpp7rWJxc-gCNhHUkHW5ppS_ZM9pIZhH4TuNal990Dv3Vg_8RF0jsXRTK5OaK1_pLy"/>
<div class="absolute top-3 left-3 bg-gray-900 text-white text-xs font-bold px-2.5 py-1 rounded border border-white/20">
                                Entry Level
                            </div>
</div>
<div class="p-5 flex flex-col flex-1">
<div class="flex justify-between items-start mb-2">
<h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors">Core One</h3>
<div class="flex items-center gap-1 text-yellow-400 text-xs font-bold">
<span class="material-symbols-outlined text-[14px] fill-current">star</span> 4.5
                                </div>
</div>
<p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">Perfect for 1080p gaming and everyday tasks.</p>
<div class="flex flex-wrap gap-2 mb-6 mt-auto">
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">i5-13400F</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">RTX 4060</span>
<span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">16GB DDR4</span>
</div>
<div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
<div class="flex flex-col">
<span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
<span class="text-gray-900 dark:text-white text-lg font-bold">$1,099</span>
</div>
<button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                                    Buy Now
                                </button>
</div>
</div>
</div>
</div>
<!-- Pagination -->
<div class="flex justify-center mt-12">
<div class="flex items-center gap-2">
<button class="size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-[#232948] text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-[#232948] transition-colors">
<span class="material-symbols-outlined text-[20px]">chevron_left</span>
</button>
<button class="size-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold">1</button>
<button class="size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-[#232948] text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-[#232948] transition-colors">2</button>
<button class="size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-[#232948] text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-[#232948] transition-colors">3</button>
<span class="px-2 text-gray-500 dark:text-[#929bc9]">...</span>
<button class="size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-[#232948] text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-[#232948] transition-colors">
<span class="material-symbols-outlined text-[20px]">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
<!-- Simple Footer for Context -->
<footer class="mt-auto border-t border-gray-200 dark:border-[#232948] bg-white dark:bg-[#111422] py-8">
<div class="max-w-[1440px] mx-auto px-4 md:px-10 flex flex-col md:flex-row items-center justify-between gap-4">
<p class="text-gray-500 dark:text-[#929bc9] text-sm">© 2024 NexRig Systems. All rights reserved.</p>
<div class="flex gap-6">
<a class="text-gray-500 dark:text-[#929bc9] hover:text-primary dark:hover:text-white transition-colors" href="#">Privacy</a>
<a class="text-gray-500 dark:text-[#929bc9] hover:text-primary dark:hover:text-white transition-colors" href="#">Terms</a>
<a class="text-gray-500 dark:text-[#929bc9] hover:text-primary dark:hover:text-white transition-colors" href="#">Warranty</a>
</div>
</div>
</footer>
</body></html>