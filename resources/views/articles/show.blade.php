@extends('layouts.app')

@push('styles')
    <style>
        .article-content h2 { @apply text-2xl md:text-3xl font-bold text-white mt-10 mb-4 tracking-tight; }
        .article-content h3 { @apply text-xl font-bold text-white mt-8 mb-3; }
        .article-content p { @apply text-gray-400 leading-relaxed mb-6 text-lg; }
        .article-content ul { @apply list-disc list-inside text-gray-400 mb-6 space-y-2; }
        .article-content blockquote { @apply border-l-4 border-primary bg-white/5 p-6 italic my-8 rounded-r-xl; }
        
        /* Progress Bar */
        .progress-container {
            position: fixed;
            top: 0;
            z-index: 150;
            width: 100%;
            height: 4px;
            background: transparent;
        }
        .progress-bar {
            height: 4px;
            background: theme('colors.primary');
            width: 0%;
        }
    </style>
@endpush

@section('content')
    {{-- Reading Progress Bar --}}
    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>

    <article class="bg-[#050505] min-h-screen pb-20">
        {{-- HERO SECTION --}}
        <div class="relative w-full h-[60vh] md:h-[70vh] overflow-hidden">
            <img src="{{ $article->src }}" 
                 class="w-full h-full object-cover opacity-60" 
                 alt="{{ $article->title }}">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#050505]/50 to-[#050505]"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end px-4 pb-12">
                <div class="max-w-4xl mx-auto w-full text-center">
                    <div class="inline-block px-4 py-1 rounded-full border border-primary/30 bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest mb-6">
                        {{ $article->category }}
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none mb-6">
                        {{ $article->title }}
                    </h1>
                    <div class="flex items-center justify-center gap-6 text-gray-400 text-sm font-medium">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            {{ $article->created_at->format('M d, Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            {{ $article->reading_time ?? '5' }} Min Read
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="max-w-7xl mx-auto px-4 mt-12 grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- LEFT SIDE: CONTENT --}}
            <div class="lg:col-span-8">
                <div class="article-content">
                    {!! $article->content !!}
                </div>

                <hr class="border-white/10 my-12">

                {{-- TAGS & SHARE --}}
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex gap-2">
                        @foreach(explode(',', $article->tags) as $tag)
                            <span class="px-3 py-1 bg-white/5 border border-white/10 rounded text-gray-500 text-xs hover:text-white transition-colors cursor-pointer">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Share:</span>
                        <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-facebook-f"></i></button>
                        <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-twitter"></i></button>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE: SIDEBAR --}}
            <aside class="lg:col-span-4 space-y-12">
                {{-- RELATED ARTICLES --}}
                <div class="bg-white/5 rounded-2xl border border-white/10 p-6">
                    <h3 class="text-white font-bold mb-6 flex items-center gap-2 italic uppercase tracking-wider">
                        <span class="w-2 h-2 bg-primary"></span> Related Intel
                    </h3>
                    <div class="space-y-6">
                        @foreach($relatedArticles as $related)
                            <a href="{{ route('articles.show', $related->slug) }}" class="group flex gap-4">
                                <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0">
                                    <img src="{{ $related->src }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-sm leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                        {{ $related->title }}
                                    </h4>
                                    <p class="text-gray-500 text-xs mt-2 italic">{{ $related->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- BANNER AD / CTA --}}
                <div class="relative rounded-2xl overflow-hidden group border border-primary/20">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1000" class="w-full h-64 object-cover opacity-50 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-6">
                        <h4 class="text-white font-black text-xl mb-2">BUILD YOUR BEAST</h4>
                        <p class="text-white/80 text-sm mb-4">Configure your ultimate gaming system today.</p>
                        <a href="{{ route('products.index') }}" class="w-full py-3 bg-white text-black font-bold text-center rounded-lg text-xs tracking-widest uppercase clip-button">Configure Now</a>
                    </div>
                </div>
            </aside>
        </div>
    </article>
@endsection

@push('scripts')
    <script>
        // Progress Bar Logic
        window.onscroll = function() { myFunction() };

        function myFunction() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        }
    </script>
@endpush