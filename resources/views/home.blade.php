@extends('layouts.app')

{{-- 1. CSS KHUSUS HALAMAN INI --}}
@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .text-glow { text-shadow: 0 0 20px rgba(19, 55, 236, 0.5); }
        .clip-corner { clip-path: polygon(0 0, 100% 0, 100% 85%, 95% 100%, 0 100%); }
        .clip-button { clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px); }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #232948 1px, transparent 1px),
                              linear-gradient(to bottom, #232948 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        }
        /* Marquee Animation */
        @keyframes infinite-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }
        .animate-infinite-scroll {
            animation: infinite-scroll 30s linear infinite; 
            display: flex;
            width: max-content;
        }
        /* Fade In Up Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0; 
        }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(19, 55, 236, 0.5); border-radius: 10px; }
    </style>
@endpush

{{-- 2. KONTEN UTAMA --}}
@section('content')

    {{-- SECTION 1: HERO --}}
    @include('sections.home.hero')

    {{-- SECTION 2: HYPE BAR --}}
    @include('sections.home.hype-bar', ['hypes' => $hypes])

    {{-- SECTION 3: BENTO GRID --}}
    @include('sections.home.bento-grid', ['featured' => $featured])

    {{-- SECTION 4: DNA --}}
    @include('sections.home.dna')

    {{-- SECTION 5: LATEST DEPLOYMENTS --}}
    {{-- Perhatikan: Kita kirim variabel $products ke partials ini --}}
    @include('sections.home.deployments', ['products' => $products])

    {{-- SECTION 6: GALLERY --}}
    @include('sections.home.gallery')
    
    {{-- SECTION 7: INTEL (ARTICLES) --}}
    @include('sections.home.intel', ['intelArticles' => $intelArticles])

    {{-- SECTION 8: CTA --}}
    @include('sections.home.cta')

@endsection

{{-- 3. SCRIPT KHUSUS HALAMAN INI --}}
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1 
            };

            const observerCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        entry.target.classList.remove('opacity-0');
                        observer.unobserve(entry.target);
                    }
                });
            };

            const observer = new IntersectionObserver(observerCallback, observerOptions);
            const hiddenElements = document.querySelectorAll('.scroll-trigger');
            hiddenElements.forEach((el) => observer.observe(el));
        });
    </script>
@endpush