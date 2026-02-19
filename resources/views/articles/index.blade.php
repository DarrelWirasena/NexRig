@extends('layouts.app')

@section('content')

    {{-- CUSTOM STYLES --}}
    <style>
        .clip-corner-sm { clip-path: polygon(0 0, 100% 0, 100% 90%, 92% 100%, 0 100%); }
        .clip-button { clip-path: polygon(0 0, 100% 0, 100% 70%, 90% 100%, 0 100%); }
        .text-glow { text-shadow: 0 0 10px rgba(19, 55, 236, 0.5); }
    </style>

    {{-- SECTION 1: HEADER & HERO --}}
    <div class="relative bg-[#050505] pt-32 pb-16 px-4 overflow-hidden border-b border-white/5">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#111_1px,transparent_1px),linear-gradient(to_bottom,#111_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-40 pointer-events-none"></div>

        <div class="max-w-[1440px] mx-auto relative z-10">
            
            {{-- HEADER ROW: Title & Search --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded border border-primary/30 bg-primary/10 backdrop-blur-md mb-4">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-primary text-[10px] font-bold tracking-widest uppercase">Knowledge Base</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-none">
                        NexRig <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-cyan-400 text-glow">INTEL</span>
                    </h1>
                </div>

                {{-- Search Bar --}}
                <form action="{{ route('articles.index') }}" method="GET" class="w-full lg:w-96 relative">
                    {{-- Pertahankan kategori saat mencari --}}
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search intel..." 
                           class="w-full bg-black/50 border border-white/20 text-white px-5 py-3 focus:border-primary focus:outline-none uppercase text-xs tracking-wider font-bold placeholder-gray-600 transition-colors">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>
                </form>
            </div>

            {{-- FILTER ROW: Dynamic Categories --}}
            <div class="flex flex-wrap gap-2 mb-12">
                <a href="{{ route('articles.index', request()->except(['category', 'page'])) }}" 
                   class="px-4 py-2 text-xs font-bold uppercase tracking-wider border transition-all clip-button
                   {{ !request('category') ? 'border-primary text-white bg-primary/10' : 'border-white/10 text-gray-400 hover:text-white hover:border-primary' }}">
                    All
                </a>
                
                @foreach(['Engineering', 'Hardware', 'Reviews', 'Community'] as $filter)
                    @php
                        $isActive = request('category') == strtolower($filter);
                        $url = $isActive 
                            ? route('articles.index', request()->except(['category', 'page'])) 
                            : route('articles.index', array_merge(request()->except('page'), ['category' => strtolower($filter)]));
                    @endphp
                    <a href="{{ $url }}" 
                       class="px-4 py-2 text-xs font-bold uppercase tracking-wider border transition-all clip-button
                       {{ $isActive ? 'border-primary text-white bg-primary/10' : 'border-white/10 text-gray-400 hover:text-white hover:border-primary' }}">
                        {{ $filter }}
                    </a>
                @endforeach
            </div>

            {{-- FEATURED ARTICLE: Hanya muncul di Halaman 1 dan jika tidak sedang mencari keyword tertentu --}}
            @if($articles->currentPage() == 1 && !request('search') && $articles->count() > 0)
                @php $featured = $articles->first(); @endphp
                <div class="relative w-full h-[400px] md:h-[500px] rounded-2xl overflow-hidden group border border-white/10 hover:border-primary/50 transition-colors duration-500">
                    <a href="{{ route('articles.show', $featured->slug) }}" class="absolute inset-0 z-20"></a>
                    
                    <img src="{{ $featured->src }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-transparent"></div>

                    <div class="absolute bottom-0 left-0 p-6 md:p-12 max-w-4xl z-10 text-left">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="bg-primary text-white text-[10px] font-black uppercase px-2 py-1 rounded">{{ $featured->category }}</span>
                            <span class="text-gray-400 text-xs font-mono uppercase tracking-widest">
                                {{ $featured->published_at->format('M d, Y') }} • {{ $featured->reading_time }} MIN READ
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic leading-tight mb-4 group-hover:text-primary transition-colors duration-300">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-gray-300 text-sm md:text-lg max-w-2xl line-clamp-2 mb-6">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="inline-flex items-center gap-2 text-primary font-bold uppercase tracking-widest text-xs group-hover:translate-x-2 transition-transform">
                            Read Full Dossier <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 2: ARTICLE GRID --}}
    <div class="bg-[#050505] py-16 px-4 min-h-[400px]">
        <div class="max-w-[1440px] mx-auto">
            
            @if($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Lewati item pertama HANYA jika kita di halaman 1 dan tidak sedang nge-search --}}
                    @php 
                        $gridArticles = ($articles->currentPage() == 1 && !request('search')) ? $articles->skip(1) : $articles; 
                    @endphp

                    @foreach($gridArticles as $article)
                        <article class="group relative bg-[#0a0a0a] border border-white/5 hover:border-primary/50 flex flex-col clip-corner-sm h-full hover:-translate-y-1 transition-all duration-300">
                            <a href="{{ route('articles.show', $article->slug) }}" class="absolute inset-0 z-20"></a>
                            
                            <div class="relative h-56 overflow-hidden">
                                <img src="{{ $article->src }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:contrast-110 opacity-80 group-hover:opacity-100">
                                
                                <div class="absolute top-4 left-4">
                                    <span class="bg-black/60 backdrop-blur border border-white/10 text-white text-[9px] font-bold uppercase px-2 py-1 rounded tracking-wider">
                                        {{ $article->category }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-1 text-left">
                                <div class="flex justify-between items-center mb-3 border-b border-white/5 pb-3">
                                    <span class="text-gray-500 text-[10px] font-mono uppercase">{{ $article->published_at->format('M d, Y') }}</span>
                                    <span class="text-primary text-[10px] font-bold uppercase">{{ $article->reading_time }} MIN READ</span>
                                </div>

                                <h3 class="text-xl font-bold text-white uppercase italic leading-tight mb-3 group-hover:text-primary transition-colors">
                                    {{ $article->title }}
                                </h3>

                                <p class="text-gray-400 text-xs leading-relaxed line-clamp-3 mb-6">
                                    {{ $article->excerpt }}
                                </p>

                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-white text-[10px] font-bold uppercase tracking-widest group-hover:underline">Read Article</span>
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined text-sm">arrow_outward</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-16 flex justify-center custom-pagination">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="text-center py-20 border border-white/10 border-dashed rounded-2xl bg-[#0a0a0a]">
                    <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">article</span>
                    <h3 class="text-2xl font-black text-white uppercase italic mb-2">No Intel Found</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">We couldn't find any dossiers matching your current filters or search query.</p>
                    <a href="{{ route('articles.index') }}" class="inline-flex px-6 py-3 bg-primary text-white text-xs font-bold uppercase tracking-widest clip-button hover:bg-primary/80 transition-colors">
                        Clear All Filters
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- SECTION 3: NEWSLETTER CTA --}}
    <div class="bg-gradient-to-b from-[#050505] to-[#0a0a0a] py-24 border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 text-center"> {{-- Menghapus text-left yang bentrok --}}
            <span class="material-symbols-outlined text-6xl text-primary mb-4 animate-bounce">mail</span>
            <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic mb-4">Don't Miss the Drop</h2>
            <p class="text-gray-400 mb-8 max-w-lg mx-auto">Get the latest build guides, hardware reviews, and exclusive NexRig offers delivered directly to your inbox.</p>
            
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto relative group">
                <input type="email" placeholder="ENTER YOUR EMAIL" class="flex-1 bg-black border border-white/20 text-white px-6 py-4 focus:border-primary focus:outline-none uppercase text-sm tracking-wider font-bold placeholder-gray-600 transition-colors">
                <button type="button" class="bg-white text-black px-8 py-4 font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-colors clip-button">
                    Subscribe
                </button>
            </form>
            <p class="text-gray-600 text-[10px] mt-4 uppercase tracking-widest text-center">No spam. Only high-performance content.</p>
        </div>
    </div>

@endsection