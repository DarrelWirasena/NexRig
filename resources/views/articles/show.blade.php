@extends('layouts.app')

{{-- ============================================================
     ARTICLE DETAIL PAGE
     File: resources/views/articles/show.blade.php
     ============================================================ --}}

@section('content')

    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>

    <article class="bg-[#050505] min-h-screen pb-20 overflow-hidden">

        {{-- ── HERO ── --}}
        <div class="relative w-full h-[70vh] flex items-end">
            <div class="absolute inset-0 z-0">
                <img src="{{ $article->src }}"
                     id="heroImage"
                     class="w-full h-full object-cover scale-110"
                     alt="{{ $article->title }}">
                <div class="absolute inset-0"
                     style="background: linear-gradient(to top, #050505 0%, rgba(5,5,5,0.5) 50%, transparent 100%)"></div>
                <div class="absolute inset-0"
                     style="background: radial-gradient(ellipse at center, transparent 50%, #050505 100%); opacity:0.6;"></div>
            </div>

            <div class="max-w-7xl mx-auto w-full px-4 pb-16 relative z-10">
                <div class="max-w-4xl">

                    <nav class="flex items-center gap-2 mb-6"
                         style="font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; color:#1337ec;">
                        <a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Intel</a>
                        <span style="color:rgba(255,255,255,0.2)">/</span>
                        <span style="color:rgba(255,255,255,0.6)">{{ $article->category }}</span>
                    </nav>

                    <h1 class="font-gaming text-5xl md:text-7xl text-white mb-8" style="line-height:0.95;">
                        {{ $article->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="meta-pill">
                            <span class="material-symbols-outlined" style="font-size:12px; color:#1337ec;">calendar_today</span>
                            {{ $article->created_at->format('M d, Y') }}
                        </span>
                        <span class="meta-pill">
                            <span class="material-symbols-outlined" style="font-size:12px; color:#1337ec;">schedule</span>
                            {{ $article->reading_time ?? '5' }} Min Read
                        </span>
                        @if($article->category)
                        <span class="meta-pill" style="color:#1337ec; border-color:rgba(19,55,236,0.3);">
                            {{ $article->category }}
                        </span>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="max-w-7xl mx-auto px-4 mt-20 grid grid-cols-1 lg:grid-cols-12 gap-16">

            {{-- LEFT: ARTICLE BODY --}}
            <div class="lg:col-span-8">

                <div class="article-content" id="mainContent">
                    {!! $article->content !!}
                </div>

                {{-- TAGS --}}
                @if($article->tags)
                <div class="mt-16 pt-8" style="border-top:1px solid rgba(255,255,255,0.05)">
                    <p style="font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:0.3em; color:#4b5563; margin-bottom:1rem;">Tagged:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $article->tags) as $tag)
                            <a href="#"
                               class="article-tag px-4 py-2 rounded-lg text-gray-400 hover:text-white transition-all duration-200"
                               style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">
                                #{{ trim($tag) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- SHARE BAR --}}
                <div class="mt-12 flex flex-wrap items-center gap-4 p-6 rounded-2xl glass-panel">
                    <span class="text-white font-black uppercase italic"
                          style="font-size:11px; letter-spacing:0.2em; margin-right:0.5rem;">
                        Deploy this Intel:
                    </span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" aria-label="Share on Facebook">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" aria-label="Share on X">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <button id="copyLinkBtn" class="share-btn" aria-label="Copy link">
                            <span class="material-symbols-outlined" style="font-size:16px;">link</span>
                        </button>
                    </div>
                </div>

            </div>

            {{-- RIGHT: SIDEBAR --}}
            <aside class="lg:col-span-4 space-y-8">
                <div class="sticky top-24 space-y-8">

                    {{-- TABLE OF CONTENTS --}}
                    <div id="toc-container" class="glass-panel rounded-2xl p-6 hidden lg:block">
                        <h3 class="text-white font-black uppercase italic flex items-center gap-2 mb-6"
                            style="font-size:11px; letter-spacing:0.15em;">
                            <span style="width:4px; height:16px; background:#1337ec; border-radius:9999px; display:inline-block;"></span>
                            Data Structure
                        </h3>
                        <ul id="toc-list" class="space-y-1 text-sm font-medium"></ul>
                    </div>

                    {{-- RELATED ARTICLES --}}
                    @if($relatedArticles->count())
                    <div class="glass-panel rounded-2xl p-6">
                        <h3 class="text-white font-black uppercase italic flex items-center gap-2 mb-8"
                            style="font-size:11px; letter-spacing:0.15em;">
                            <span style="width:4px; height:16px; background:#1337ec; border-radius:9999px; display:inline-block;"></span>
                            Similar Dossiers
                        </h3>
                        <div class="space-y-6">
                            @foreach($relatedArticles as $related)
                                <a href="{{ route('articles.show', $related->slug) }}" class="group flex gap-4">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0"
                                         style="border:1px solid rgba(255,255,255,0.1)">
                                        <img src="{{ $related->src }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                             alt="{{ $related->title }}">
                                    </div>
                                    <div class="flex flex-col justify-center gap-1">
                                        <h4 class="text-white font-bold group-hover:text-[#1337ec] transition-colors line-clamp-2 uppercase"
                                            style="font-size:11px; line-height:1.3;">
                                            {{ $related->title }}
                                        </h4>
                                        <span class="font-mono uppercase"
                                              style="font-size:9px; color:#4b5563; letter-spacing:0.05em;">
                                            {{ $related->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- PROMO BANNER --}}
                    <a href="{{ route('products.index') }}"
                       class="relative rounded-2xl overflow-hidden group block"
                       style="border:1px solid rgba(19,55,236,0.2); height:12rem;">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-all duration-700"
                             style="opacity:0.4" alt="System Upgrade">
                        <div class="absolute inset-0 flex flex-col justify-end p-6"
                             style="background:linear-gradient(to top, rgba(19,55,236,0.9) 0%, rgba(19,55,236,0.4) 50%, transparent 100%)">
                            <h4 class="text-white font-black leading-none mb-3" style="font-size:1.15rem;">
                                SYSTEM<br>UPGRADE
                            </h4>
                            <span class="text-white font-black uppercase flex items-center gap-2 group-hover:gap-4 transition-all duration-300"
                                  style="font-size:10px; letter-spacing:0.2em;">
                                Get Started
                                <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                            </span>
                        </div>
                    </a>

                </div>
            </aside>
        </div>
    </article>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. PROGRESS BAR + PARALLAX ─────────────────────────────────
    const progressBar = document.getElementById('myBar');
    const heroImage   = document.getElementById('heroImage');
    let ticking = false;

    window.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const winScroll = document.documentElement.scrollTop;
            const height    = document.documentElement.scrollHeight - document.documentElement.clientHeight;

            if (progressBar) {
                progressBar.style.width = Math.min((winScroll / height) * 100, 100) + '%';
            }

            if (heroImage && window.innerWidth > 768 && winScroll < window.innerHeight) {
                heroImage.style.transform = `translateY(${winScroll * 0.3}px) scale(1.1)`;
            }

            updateActiveToc();
            ticking = false;
        });
    });

    // ── 2. TABLE OF CONTENTS ────────────────────────────────────────
    const mainContent  = document.getElementById('mainContent');
    const tocList      = document.getElementById('toc-list');
    const tocContainer = document.getElementById('toc-container');
    let headingElements = [];

    if (mainContent && tocList) {
        const headings = mainContent.querySelectorAll('h2');

        if (headings.length > 0) {
            headings.forEach((heading, index) => {
                const id  = `section-${index}`;
                heading.id = id;
                headingElements.push(heading);

                const num = String(index + 1).padStart(2, '0');
                const li  = document.createElement('li');
                li.innerHTML = `
                    <a href="#${id}"
                       class="toc-link flex items-center gap-3 py-2 px-2 rounded-lg"
                       style="font-size:12px; color:#6b7280; text-decoration:none;">
                        <span class="toc-num font-mono shrink-0"
                              style="font-size:10px; color:rgba(19,55,236,0.4);">${num}</span>
                        <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${heading.innerText}</span>
                    </a>`;
                tocList.appendChild(li);
            });

            tocList.addEventListener('click', (e) => {
                const link = e.target.closest('.toc-link');
                if (!link) return;
                e.preventDefault();
                const target = document.getElementById(link.getAttribute('href').slice(1));
                // scroll-padding-top: 80px is already set in app.css so scrollIntoView respects it
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

        } else if (tocContainer) {
            tocContainer.style.display = 'none';
        }
    }

    function updateActiveToc() {
        if (!headingElements.length) return;
        let activeIndex = 0;
        headingElements.forEach((h, i) => {
            if (h.getBoundingClientRect().top <= 110) activeIndex = i;
        });
        document.querySelectorAll('.toc-link').forEach((link, i) => {
            const num = link.querySelector('.toc-num');
            if (i === activeIndex) {
                link.classList.add('is-active');
                link.style.color = 'white';
                if (num) num.style.color = '#1337ec';
            } else {
                link.classList.remove('is-active');
                link.style.color = '#6b7280';
                if (num) num.style.color = 'rgba(19,55,236,0.4)';
            }
        });
    }

    // ── 3. COPY LINK — reuses window.showToast from app.js ─────────
    const copyBtn = document.getElementById('copyLinkBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
            } catch {
                const ta = document.createElement('textarea');
                ta.value = window.location.href;
                Object.assign(ta.style, { position: 'fixed', opacity: '0' });
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            window.showToast('Link copied to clipboard!');
        });
    }

});
</script>
@endpush