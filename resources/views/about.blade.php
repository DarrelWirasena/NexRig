@extends('layouts.app')

@section('content')
    {{-- Custom Styles --}}
    <style>
        .clip-diagonal { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
        .clip-card { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
        .text-outline { -webkit-text-stroke: 1px rgba(255, 255, 255, 0.1); color: transparent; }
        
        .scanline {
            width: 100%;
            height: 100px;
            z-index: 10;
            background: linear-gradient(0deg, rgba(0,0,0,0) 0%, rgba(59, 130, 246, 0.1) 50%, rgba(0,0,0,0) 100%);
            opacity: 0.1;
            position: absolute;
            bottom: 100%;
            animation: scanline 8s linear infinite;
            pointer-events: none;
        }
        @keyframes scanline {
            0% { bottom: 100%; }
            100% { bottom: -100%; }
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }
    </style>

    <div class="bg-[#050505] min-h-screen text-white overflow-hidden font-sans selection:bg-blue-500 selection:text-white">

        {{-- SECTION 1: HERO MANIFESTO --}}
        <div class="relative py-40 px-4 flex items-center justify-center overflow-hidden min-h-[85vh]">
            <div class="absolute inset-0 z-0">
                {{-- Background Image --}}
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1624705002806-5d72df19c3ad?q=80&w=2070')] bg-cover bg-center opacity-20 grayscale mix-blend-luminosity"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/90 to-transparent"></div>
                <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
                <div class="scanline"></div>
            </div>

            <div class="relative z-10 text-center max-w-5xl mx-auto px-4">
                <div class="flex justify-center mb-8">
                    <span class="inline-flex items-center gap-2 py-1 px-4 rounded-full border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-bold tracking-[0.3em] uppercase animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        System Manifesto v.2.0
                    </span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black uppercase italic tracking-tighter leading-none mb-8 drop-shadow-2xl">
                    Engineered for <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-white to-gray-500">Dominance.</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-light">
                    Kami bukan sekadar toko komputer. Kami adalah laboratorium performa. 
                    Setiap NexRig dirakit dengan presisi bedah untuk satu tujuan: 
                    <strong class="text-white">Menghancurkan batasan FPS.</strong>
                </p>
            </div>
        </div>

        {{-- SECTION 2: STATS STRIP --}}
        <div class="border-y border-white/5 bg-[#0a0a0a] py-12 relative z-10">
            <div class="max-w-[1440px] mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/5">
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">500+</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Rigs Deployed</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">0.1%</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">RMA Rate</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">24/7</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Elite Support</span>
                </div>
                <div class="p-4 group">
                    <span class="block text-4xl md:text-5xl font-black text-white mb-2 group-hover:text-blue-500 transition-colors">100%</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">Performance</span>
                </div>
            </div>
        </div>

        {{-- SECTION 3: ORIGIN SEQUENCE (STORY) --}}
        <div class="py-24 px-4 relative overflow-hidden">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Text Content --}}
                <div class="order-2 lg:order-1">
                    <h2 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-6">The Origin Sequence</h2>
                    <div class="space-y-6 text-gray-400 leading-relaxed text-sm md:text-base">
                        <p>
                            NexRig lahir di tengah kekacauan "Silicon Shortage" tahun 2020. Saat itu, gamer dipaksa memilih antara PC pre-built yang mahal dengan komponen murahan, atau merakit sendiri dengan harga komponen yang tidak masuk akal.
                        </p>
                        <p>
                            Kami menolak kedua pilihan itu.
                        </p>
                        <p>
                            Dimulai dari sebuah garasi kecil dengan 3 teknisi obsesif, kami mulai merakit PC dengan filosofi sederhana: <span class="text-white font-bold">Rakit seolah-olah itu milik sendiri.</span> Tanpa kabel semrawut. Tanpa power supply "bom waktu". Tanpa kompromi.
                        </p>
                        <div class="pt-4">
                            <div class="h-1 w-20 bg-blue-600"></div>
                        </div>
                    </div>
                </div>

                {{-- Image Visual --}}
                <div class="order-1 lg:order-2 relative">
                    <div class="absolute -inset-4 bg-blue-600/20 blur-3xl rounded-full opacity-20"></div>
                    <div class="relative aspect-video bg-[#111] border border-white/10 p-2 clip-card">
                        <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=1000" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700">
                        {{-- Overlay UI --}}
                        <div class="absolute bottom-4 left-4 bg-black/80 backdrop-blur px-3 py-1 border-l-2 border-blue-500">
                            <span class="text-[10px] font-mono text-blue-400">EST. 2020 // JAKARTA_HQ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: THE FORGE PROTOCOL (PROCESS REPLACEMENT) --}}
        <div class="py-24 bg-[#080808] relative">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div class="max-w-[1440px] mx-auto px-4 relative z-10">
                
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic tracking-tighter">The Forge Protocol</h2>
                    <p class="text-gray-500 mt-4 max-w-xl mx-auto">Standar operasional kami lebih ketat daripada militer. Setiap sistem melewati 4 tahap kritis sebelum menyentuh meja Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Step 1 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">01</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Component Selection</h3>
                            <p class="text-gray-500 text-sm">Kami hanya menggunakan komponen Tier-A. Tidak ada PSU generik atau motherboard murah yang membatasi performa.</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">02</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">precision_manufacturing</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Precision Assembly</h3>
                            <p class="text-gray-500 text-sm">Manajemen kabel yang obsesif. Alur udara yang dikalkulasi. Setiap baut dikencangkan dengan torsi yang tepat.</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">03</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">bug_report</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">The Gauntlet</h3>
                            <p class="text-gray-500 text-sm">Stress test 24 jam. Cinebench, 3DMark, Furmark. Kami menyiksa PC Anda untuk memastikan ia tidak akan pernah gagal saat Anda bermain.</p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="group bg-[#050505] p-8 border border-white/10 hover:border-blue-600 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 group-hover:text-blue-600 transition-all">
                            <span class="text-6xl font-black text-outline">04</span>
                        </div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-600/10 rounded flex items-center justify-center mb-6 text-blue-500">
                                <span class="material-symbols-outlined">rocket_launch</span>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase mb-2">Armored Shipping</h3>
                            <p class="text-gray-500 text-sm">Dikemas dengan Instapak foam yang mengikuti bentuk komponen. Aman dari guncangan kurir hingga sampai di meja Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

{{-- SECTION 5: COMPONENT ALLIANCE (Partners) --}}
<div class="scroll-trigger opacity-0 py-24 px-4 border-t border-white/5 bg-[#050014]">
    <div class="max-w-[1440px] mx-auto text-center">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em] mb-16">
            Strategic Hardware Alliance
        </p>
        
        {{-- Container Utama: Menggunakan flex-wrap agar logo turun otomatis di layar HP --}}
        <div class="flex flex-wrap justify-center items-end gap-y-16 gap-x-12 md:gap-x-24 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700">
            
            {{-- 1. NVIDIA --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#76b900]">
                <div class="h-8 md:h-11">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M8.948 8.798v-1.43a6.7 6.7 0 0 1 .424-.018c3.922-.124 6.493 3.374 6.493 3.374s-2.774 3.851-5.75 3.851c-.398 0-.787-.062-1.158-.185v-4.346c1.528.185 1.837.857 2.747 2.385l2.04-1.714s-1.492-1.952-4-1.952a6.016 6.016 0 0 0-.796.035m0-4.735v2.138l.424-.027c5.45-.185 9.01 4.47 9.01 4.47s-4.08 4.964-8.33 4.964c-.37 0-.733-.035-1.095-.097v1.325c.3.035.61.062.91.062 3.957 0 6.82-2.023 9.593-4.408.459.371 2.34 1.263 2.73 1.652-2.633 2.208-8.772 3.984-12.253 3.984-.335 0-.653-.018-.971-.053v1.864H24V4.063zm0 10.326v1.131c-3.657-.654-4.673-4.46-4.673-4.46s1.758-1.944 4.673-2.262v1.237H8.94c-1.528-.186-2.73 1.245-2.73 1.245s.68 2.412 2.739 3.11M2.456 10.9s2.164-3.197 6.5-3.533V6.201C4.153 6.59 0 10.653 0 10.653s2.35 6.802 8.948 7.42v-1.237c-4.84-.6-6.492-5.936-6.492-5.936z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">NVIDIA</span>
            </div>

            {{-- 2. INTEL --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#0071c5]">
                <div class="h-10 md:h-14">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M20.42 7.345v9.18h1.651v-9.18zM0 7.475v1.737h1.737V7.474zm9.78.352v6.053c0 .513.044.945.13 1.292.087.34.235.618.44.828.203.21.475.359.803.451.334.093.754.136 1.255.136h.216v-1.533c-.24 0-.445-.012-.593-.037a.672.672 0 0 1-.39-.173.693.693 0 0 1-.173-.377 4.002 4.002 0 0 1-.037-.606v-2.182h1.193v-1.416h-1.193V7.827zm-3.505 2.312c-.396 0-.76.08-1.082.241-.327.161-.6.384-.822.668l-.087.117v-.902H2.658v6.256h1.639v-3.214c.018-.588.16-1.02.433-1.299.29-.297.642-.445 1.044-.445.476 0 .841.149 1.082.433.235.284.359.686.359 1.2v3.324h1.663V12.97c.006-.89-.229-1.595-.686-2.09-.458-.495-1.1-.742-1.917-.742zm10.065.006a3.252 3.252 0 0 0-2.306.946c-.29.29-.525.637-.692 1.033a3.145 3.145 0 0 0-.254 1.273c0 .452.08.878.241 1.274.161.395.39.742.674 1.032.284.29.637.526 1.045.693.408.173.86.26 1.342.26 1.397 0 2.262-.637 2.782-1.23l-1.187-.904c-.248.297-.841.699-1.583.699-.464 0-.847-.105-1.138-.321a1.588 1.588 0 0 1-.593-.872l-.019-.056h4.915v-.587c0-.451-.08-.872-.235-1.267a3.393 3.393 0 0 0-.661-1.033 3.013 3.013 0 0 0-1.02-.692 3.345 3.345 0 0 0-1.311-.248zm-16.297.118v6.256h1.651v-6.256zm16.278 1.286c1.132 0 1.664.797 1.664 1.255l-3.32.006c0-.458.525-1.255 1.656-1.261zm7.073 3.814a.606.606 0 0 0-.606.606.606.606 0 0 0 .606.606.606.606 0 0 0 .606-.606.606.606 0 0 0-.606-.606zm-.008.105a.5.5 0 0 1 .002 0 .5.5 0 0 1 .5.501.5.5 0 0 1-.5.5.5.5 0 0 1-.5-.5.5.5 0 0 1 .498-.5zm-.233.155v.699h.13v-.285h.093l.173.285h.136l-.18-.297a.191.191 0 0 0 .118-.056c.03-.03.05-.074.05-.136 0-.068-.02-.117-.063-.154-.037-.038-.105-.056-.185-.056zm.13.099h.154c.019 0 .037.006.056.012a.064.064 0 0 1 .037.031c.013.013.012.031.012.056a.124.124 0 0 1-.012.055.164.164 0 0 1-.037.031c-.019.006-.037.013-.056.013h-.154Z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">INTEL</span>
            </div>

            {{-- 3. AMD --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#ed1c24]">
                <div class="h-7 md:h-10">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M0 12.002L12 0v5.824l-6.176 6.176L12 18.178V24zM12.002 24l5.823-5.822h-5.823zm5.823-5.822L24 12.002h-5.823zM24 12.002L17.825 5.826h5.823z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">AMD</span>
            </div>

            {{-- 4. MSI --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#FF0000]">
                <div class="h-10 md:h-14">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M11.884 2.038c-.987.086-2.62.314-3.63.506L5.418 3.08l-.526.462c-.29.254-.548.525-.574.602-.026.077-.115.39-.197.696l-.148.556-.458 1.938c-1.086 4.606-1.075 8.79.033 11.558.552 1.38 1.568 2.673 2.74 3.492 1.23.859 3.288 1.508 5.28 1.665.96.076 2.31.017 3.21-.14 2.475-.432 4.438-1.455 5.627-2.93.738-.917 1.373-2.22 1.617-3.32.143-.646.418-4.887.418-6.46 0-1.08-.038-1.37-.253-1.964-.43-1.183-1.148-1.998-2.295-2.603l-.565-.298-.772-.14c-.424-.077-1.274-.173-1.888-.214-.615-.04-1.61-.128-2.214-.195l-1.098-.122-1.47-.03zm1.294 1.89l.246.073.342.508c1.17 1.74 1.568 2.915 1.48 4.368-.038.63-.223 1.38-.478 1.927l-.194.422.134.32c.313.755.893 1.393 1.667 1.835.497.284 1.33.53 1.682.495.204-.02.26-.07.26-.233 0-.135-.042-.208-.206-.335-.418-.323-.565-.585-.563-1.005.002-.39.157-.775.458-1.124.238-.277.27-.35.21-.49-.063-.15-.23-.173-.714-.097-.87.136-1.795-.308-2.018-.968-.137-.404.015-.777.434-1.063.353-.242.445-.257.866-.142.326.088.654.128.832.1.338-.053.75-.436.828-.77.03-.13.112-.236.183-.236.11 0 .214.273.287.75.16 1.06.64 1.74 1.458 2.072.435.176 1.246.265 1.64.18.29-.064.406-.026.665.216.29.27.315.364.177.685-.21.49-.26.964-.147 1.432.072.294.06.373-.084.538-.163.186-.212.345-.26.86-.063.705-.27 1.294-.73 2.078-1.118 1.913-2.973 3.092-5.357 3.405-.656.086-2.125.038-2.68-.09-2.613-.59-4.523-2.656-5.143-5.56-.432-2.03-.295-4.813.324-6.663.18-.537.503-1.235.718-1.55.452-.664 1.253-1.42 1.868-1.762.383-.213 1.532-.69 1.682-.7.135-.007.366-.04.515-.073.148-.033.29-.037.314-.008.046.053-.232.718-.537 1.285-.515.953-.666 1.484-.64 2.273.025.724.174 1.238.543 1.883.498.87 1.438 1.61 2.448 1.926l.445.14-.134.405c-.288.875-.304 1.097-.096 1.403.257.378.755.493 1.506.347.634-.123 1.183-.512 1.472-1.044.158-.29.168-.383.054-.53-.23-.296-1.12-.634-1.418-.538-.138.045-.266.014-.43-.106-.383-.28-1.032-1.28-1.237-1.905-.177-.537-.225-1.614-.09-1.998.19-.53.792-1.293 1.393-1.762.373-.293.695-.512.716-.488.02.024-.075.315-.21.646-.367.894-.367 2.032 0 2.773.242.487.635.856 1.17.1.097.212.186.24.262.328.183.21.265.24.53.176.37-.09.58-.286.713-.66.16-.45.163-1.88.006-2.49-.06-.242-.326-.752-.59-1.134l-.482-.695-.298.11c-.163.06-.44.14-.615.176-.174.037-.386.115-.47.174-.143.096-.205.08-.44-.113-.27-.22-.29-.294-.147-.553.085-.153.404-.458.71-.677.602-.433 1.336-.743 2.16-.91.47-.097.525-.122.607-.266.083-.148.097-.28.038-.357-.08-.105-.458.024-.798.273-.232.17-.502.31-.6.31-.204 0-1.38-1.06-1.682-1.518-.078-.118-.133-.214-.123-.214.01 0 .195.075.412.168l.394.168.607-.635c.56-.584.656-.63.994-.46.186.094.26.08.47-.09.297-.242.376-.373.302-.503-.042-.073-.216-.11-.57-.12-.573-.017-1.16-.212-1.453-.482-.137-.125-.254-.197-.31-.186-.055.01-.1.066-.1.123 0 .136-.47.324-.865.347-.45.024-.497.048-.404.202.057.097.053.133-.023.23-.136.175-.138.18-.027.38.063.113.153.35.2.527.08.302.058.364-.158.46-.194.084-.244.068-.41-.136-.11-.137-.286-.272-.39-.304-.223-.066-.312.02-.59.576-.16.32-.36.624-.443.673-.22.133-1.063.133-1.29 0-.142-.083-.574-.763-.746-1.174-.08-.193-.212-.382-.295-.42-.223-.106-1.456-.553-1.63-.59-.113-.026-.412-.033-.666-.017z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">MSI GAMING</span>
            </div>

            {{-- 5. AORUS --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#FF5722]">
                <div class="h-9 md:h-12">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M12 .093l-1.168 2.665C6.145 4.908 2.967 9.11.21 14.723h23.58C21.034 9.11 17.855 4.908 13.168 2.758L12 .093zm0 2.498l1.753 4.029h-3.506L12 2.59zm0 5.746l3.505 8.058h-7.01L12 8.336zM1.924 16.04h20.153L12 23.907 1.924 16.04z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">AORUS</span>
            </div>

            {{-- 6. ROG --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-white">
                <div class="h-10 md:h-14">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M12.87 1.34L1.13 14.88h11.23l2.5-2.88H8.38l2.96-3.42h7.32l2.21-2.54H12.87zM22.87 9.12l-2.21 2.54h2.21l-5.64 6.48H6.01l-1.13 1.3h12.35l6.77-7.78V9.12z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">ROG ELITE</span>
            </div>

            {{-- 7. CORSAIR --}}
            <div class="flex flex-col items-center gap-4 group transition-colors hover:text-[#F5C900]">
                <div class="h-10 md:h-14">
                    <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto fill-current">
                        <path d="M11.235 5.224-1.112 2.34c-1.038 2.184-1.817 4.484-2.31 6.853l2.369-1.101c.436-1.982 1.104-3.913 1.986-5.76l-.933-2.332zm5.893-1.66L16 5.896c1.983.436 3.913 1.104 5.76 1.986l-1.1-2.368c-2.185-1.039-4.485-1.818-6.854-2.311l-2.332.933zm-7.877 8.72c-.453-1.545-.693-3.167-.693-4.834l-2.588.021c0 2.01.292 3.965.84 5.822l2.441-.992v-.017zm5.498 0 .016.033 2.442.992c.547-1.857.839-3.812.839-5.822l-2.587-.021c0 1.667-.24 3.29-.693 4.834l-.017-.016zm-1.657 4.07c-1.033-.863-1.966-1.869-2.77-2.995l-2.089 1.53c.969 1.362 2.1 2.578 3.357 3.62l1.502-2.155zm5.003-2.996c-.804 1.126-1.737 2.132-2.77 2.995l1.502 2.155c1.257-1.042 2.388-2.258 3.357-3.62l-2.089-1.53zM12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 21.986c-5.514 0-10-4.486-10-10S6.486 1.986 12 1.986s10 4.486 10 10-4.486 10-10 10z"/>
                    </svg>
                </div>
                <span class="font-gaming text-[10px] md:text-xs tracking-[0.2em]">CORSAIR</span>
            </div>

        </div>
    </div>
</div>
        {{-- SECTION 6: CTA --}}
      <x-cta-section />

    </div>
@endsection