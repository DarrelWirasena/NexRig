<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NexRig - Performance Benchmarks</title>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind Config -->
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1337ec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101322",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        body {
            font-family: 'Noto Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col text-slate-900 dark:text-white transition-colors duration-200">
<!-- Main Container -->
<div class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8 lg:p-12">
<!-- Breadcrumb / Header Context -->
<header class="mb-8">
<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4">
<span class="hover:text-primary cursor-pointer">Products</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="hover:text-primary cursor-pointer">NexRig Pro</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-slate-900 dark:text-white font-medium">Benchmarks</span>
</div>
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-slate-200 dark:border-slate-800">
<div>
<h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-2 font-display">Performance Data</h1>
<p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl">
                        See how the NexRig Pro handles the most demanding titles and synthetic workloads.
                        Tested in controlled environments.
                    </p>
</div>
<div class="flex gap-3">
<button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 transition text-sm font-medium">
<span class="material-symbols-outlined text-[20px]">download</span>
                        Full Report
                    </button>
<button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white hover:bg-blue-700 transition text-sm font-medium shadow-lg shadow-primary/25">
                        Configure Rig
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
</button>
</div>
</div>
</header>
<!-- Navigation Tabs -->
<nav class="flex gap-8 mb-10 overflow-x-auto no-scrollbar">
<a class="pb-3 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium whitespace-nowrap" href="#">Overview</a>
<a class="pb-3 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium whitespace-nowrap" href="#">Specifications</a>
<a class="pb-3 border-b-2 border-primary text-primary font-bold whitespace-nowrap" href="#">Benchmarks</a>
<a class="pb-3 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium whitespace-nowrap" href="#">Gallery</a>
<a class="pb-3 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium whitespace-nowrap" href="#">Reviews</a>
</nav>
<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- Left Column: Synthetic Benchmarks -->
<div class="lg:col-span-5 flex flex-col gap-6">
<!-- 3DMark Card -->
<div class="bg-white dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-start justify-between mb-6">
<div>
<div class="flex items-center gap-2 mb-1">
<span class="material-symbols-outlined text-primary">bar_chart_4_bars</span>
<h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Synthetic</h3>
</div>
<h2 class="text-2xl font-bold font-display">3DMark Time Spy</h2>
</div>
<div class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            DirectX 12
                        </div>
</div>
<div class="mb-8">
<p class="text-5xl font-bold text-slate-900 dark:text-white mb-2 font-display">19,450</p>
<div class="flex items-center gap-2">
<span class="bg-green-500/10 text-green-500 dark:text-green-400 px-2 py-0.5 rounded text-sm font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]">trending_up</span>
                                Top 4%
                            </span>
<span class="text-slate-500 dark:text-slate-400 text-sm">of all results globally</span>
</div>
</div>
<!-- Comparison Chart -->
<div class="space-y-6">
<!-- Item 1 (NexRig) -->
<div class="relative">
<div class="flex justify-between text-sm mb-2 font-medium">
<span class="flex items-center gap-2 text-slate-900 dark:text-white">
<span class="w-2 h-2 rounded-full bg-primary"></span>
                                    NexRig Pro
                                </span>
<span class="font-bold">19,450</span>
</div>
<div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
<div class="h-full bg-primary rounded-full" style="width: 95%"></div>
</div>
</div>
<!-- Item 2 -->
<div class="relative opacity-75">
<div class="flex justify-between text-sm mb-2 font-medium">
<span class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                                    Competitor A (RTX 4070)
                                </span>
<span>17,800</span>
</div>
<div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
<div class="h-full bg-slate-400 dark:bg-slate-600 rounded-full" style="width: 82%"></div>
</div>
</div>
<!-- Item 3 -->
<div class="relative opacity-75">
<div class="flex justify-between text-sm mb-2 font-medium">
<span class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                                    Avg Gaming PC (2023)
                                </span>
<span>12,500</span>
</div>
<div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
<div class="h-full bg-slate-300 dark:bg-slate-700 rounded-full" style="width: 60%"></div>
</div>
</div>
</div>
</div>
<!-- Cinebench Card -->
<div class="bg-white dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
<div class="flex justify-between items-start mb-4">
<div>
<h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">CPU Multi-Core</h3>
<h2 class="text-xl font-bold font-display">Cinebench R23</h2>
</div>
<div class="text-right">
<p class="text-3xl font-bold text-slate-900 dark:text-white font-display">34,102</p>
<p class="text-xs text-slate-500 dark:text-slate-400">pts</p>
</div>
</div>
<div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
<div class="bg-primary h-2 rounded-full" style="width: 88%"></div>
</div>
</div>
</div>
<!-- Right Column: Game Performance -->
<div class="lg:col-span-7">
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 h-full flex flex-col">
<!-- Header with Tabs -->
<div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<div>
<div class="flex items-center gap-2 mb-1">
<span class="material-symbols-outlined text-primary">sports_esports</span>
<h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Real World</h3>
</div>
<h2 class="text-2xl font-bold font-display">Gaming FPS</h2>
</div>
<!-- Resolution Toggle -->
<div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-lg self-start sm:self-auto">
<button class="px-4 py-2 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm text-sm font-bold transition-all">
                                1440p Ultra
                            </button>
<button class="px-4 py-2 rounded-md text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white text-sm font-medium transition-all">
                                4K High
                            </button>
</div>
</div>
<!-- Games Grid -->
<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 flex-1">
<!-- Game 1 -->
<div class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 aspect-video md:aspect-auto md:min-h-[160px] flex flex-col justify-end p-4">
<!-- Background Image simulation using gradient -->
<div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
<div class="absolute inset-0 bg-cover bg-center" data-alt="Cyberpunk sci-fi city street with neon lights" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBz6vEwAJM7cfEn15bGbZLsoxnvwLasMfWwd1iUKu6Y_ocZxfonsR7w_UkJU2VZ6pOOqc3jyH0izTnaRd9I3T60Rt8he2i6W0J7tzhR5rvn_Rp38rHNAgIsMRShtBu3PobwIIEpANHrMxKmPL5P9KqT9zwhRZ0VyHcQ_x36j832P7dgSzic-KXM7J-XJa3HmtbDdjRdPZguazgLw-sGF7Zi_O9YSnZe1pXSNiYV2cItAJfNWWdrOry-uPF6ObeWFXcGk5ZeP6-p2TSc');"></div>
<div class="relative z-20 flex justify-between items-end">
<div>
<p class="text-slate-300 text-xs font-bold uppercase tracking-wider mb-1">RPG / Action</p>
<h4 class="text-white text-lg font-bold leading-tight">Cyberpunk 2077</h4>
<p class="text-slate-400 text-xs mt-1">Ray Tracing: ON</p>
</div>
<div class="text-right">
<p class="text-white text-3xl font-bold font-display group-hover:text-primary transition-colors">85</p>
<p class="text-slate-400 text-xs font-bold uppercase">FPS</p>
</div>
</div>
</div>
<!-- Game 2 -->
<div class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 aspect-video md:aspect-auto md:min-h-[160px] flex flex-col justify-end p-4">
<div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
<div class="absolute inset-0 bg-cover bg-center" data-alt="FPS shooter game character holding weapon" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA7PdS4ypYagh09HF1wa4350I2c1ktUvde2UgIH4rajZwCej76yJzse8YlBGAXuM0ictYaeXIKQLQ1FRm4dYweFLEGTsQ4jzBcc3Kp4NTBQPongb0EZ37d0sXPFEhwNYNEcIGcL6-pXtLpAPIp3zmSkzz7mNkOuLcd32bPKqM_JbOzju5dMUp87CN1VItBiHYmUdyXz2e_NW6TxHPBM6vu4KVqpjroRqQOYOkIaqXJyCv7YI3L4G7p9CT_ZpVdWx0oeIRFPT5l8l76S');"></div>
<div class="relative z-20 flex justify-between items-end">
<div>
<p class="text-slate-300 text-xs font-bold uppercase tracking-wider mb-1">Competitive FPS</p>
<h4 class="text-white text-lg font-bold leading-tight">Valorant</h4>
<p class="text-slate-400 text-xs mt-1">Settings: Max</p>
</div>
<div class="text-right">
<p class="text-white text-3xl font-bold font-display group-hover:text-primary transition-colors">340</p>
<p class="text-slate-400 text-xs font-bold uppercase">FPS</p>
</div>
</div>
</div>
<!-- Game 3 -->
<div class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 aspect-video md:aspect-auto md:min-h-[160px] flex flex-col justify-end p-4">
<div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
<div class="absolute inset-0 bg-cover bg-center" data-alt="Military tactical game map overview" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAqKMlSn5jdO_gGCPmfwi2Vq9yrMSLO4tZDtSlmx8oa_xgbyz50s-mkqXxiRGemgvxI6hGAk53UgmQ832MrjE5elvv-uWxyNDw3rNOAGWPC8KnAE1esrQyBXN1lZa0urYNIEslz3cxETDE6lkULRrCTqqmmiId67ArAHsdp678ZOOZEwaWO1GmqXBWcZ_cZKpwOEWEi9ETgg1u8xqYW4O_99bXQuBTd4LfxulMNvJjDzapSwMbUfX9WsV3Ab3aTgDNu-2l3D-rsxGYm');"></div>
<div class="relative z-20 flex justify-between items-end">
<div>
<p class="text-slate-300 text-xs font-bold uppercase tracking-wider mb-1">Battle Royale</p>
<h4 class="text-white text-lg font-bold leading-tight">Call of Duty: Warzone</h4>
<p class="text-slate-400 text-xs mt-1">DLSS: Balanced</p>
</div>
<div class="text-right">
<p class="text-white text-3xl font-bold font-display group-hover:text-primary transition-colors">145</p>
<p class="text-slate-400 text-xs font-bold uppercase">FPS</p>
</div>
</div>
</div>
<!-- Game 4 -->
<div class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800 aspect-video md:aspect-auto md:min-h-[160px] flex flex-col justify-end p-4">
<div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
<div class="absolute inset-0 bg-cover bg-center" data-alt="Fantasy open world landscape with ruins" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB-zfDmexrTlVsYkZl0WbtE3F3faknbJRGUZcBcTtFm0NtgUDWyWpGCYwcPVMLtcSUOw-w-wwS9fUl9JaEFF_xGPLDzpgo9mZPF-b6iP_4MHdsfb0kfs6PHqHtPddkwlhC0NYw29zBNB8brvBR6uatp8IzylmVbHAfWKZeZ6zceoDs4Bt-rblDfivJpzsNin-Zyx0MiuTusVK-EsC96BaWavy9cN_Ugc_DNddUlLhwluW5ytRHR6N-_u7bI84nq-xcUbOCDizjwvv6e');"></div>
<div class="relative z-20 flex justify-between items-end">
<div>
<p class="text-slate-300 text-xs font-bold uppercase tracking-wider mb-1">Open World RPG</p>
<h4 class="text-white text-lg font-bold leading-tight">Elden Ring</h4>
<p class="text-slate-400 text-xs mt-1">Settings: Maximum</p>
</div>
<div class="text-right">
<p class="text-white text-3xl font-bold font-display group-hover:text-primary transition-colors">60<span class="text-lg align-top text-slate-400">*</span></p>
<p class="text-slate-400 text-xs font-bold uppercase">FPS</p>
</div>
</div>
</div>
</div>
<div class="px-6 pb-6 pt-0 text-xs text-slate-400 dark:text-slate-500 italic">
                        * Capped by game engine. Averages taken over 3 runs.
                    </div>
</div>
</div>
</div>
<!-- Additional Technical Details / Footer of section -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-lg border border-slate-200 dark:border-slate-800/50 flex items-start gap-4">
<div class="bg-slate-200 dark:bg-slate-800 p-2 rounded-lg">
<span class="material-symbols-outlined text-slate-600 dark:text-slate-300">thermometer</span>
</div>
<div>
<h4 class="font-bold text-slate-900 dark:text-white text-sm">Thermal Performance</h4>
<p class="text-xs text-slate-500 dark:text-slate-400 mt-1">GPU Max Temp: 68°C under load. CPU Max Temp: 72°C.</p>
</div>
</div>
<div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-lg border border-slate-200 dark:border-slate-800/50 flex items-start gap-4">
<div class="bg-slate-200 dark:bg-slate-800 p-2 rounded-lg">
<span class="material-symbols-outlined text-slate-600 dark:text-slate-300">speed</span>
</div>
<div>
<h4 class="font-bold text-slate-900 dark:text-white text-sm">Overclocking Headroom</h4>
<p class="text-xs text-slate-500 dark:text-slate-400 mt-1">+8% potential performance gain with safe OC profiles.</p>
</div>
</div>
<div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-lg border border-slate-200 dark:border-slate-800/50 flex items-start gap-4">
<div class="bg-slate-200 dark:bg-slate-800 p-2 rounded-lg">
<span class="material-symbols-outlined text-slate-600 dark:text-slate-300">memory</span>
</div>
<div>
<h4 class="font-bold text-slate-900 dark:text-white text-sm">Test Configuration</h4>
<p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Intel i9-13900K, 32GB DDR5 6000MHz, RTX 4080.</p>
</div>
</div>
</div>
</div>
</body></html>