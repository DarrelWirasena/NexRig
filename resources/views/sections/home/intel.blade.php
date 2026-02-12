<div>
    {{-- SECTION 6: NEXRIG INTEL (LATEST ARTICLES) --}}
    <section class="scroll-trigger opacity-0 bg-[#080808] py-24 border-t border-white/5 relative overflow-hidden">
        
        {{-- Background Pattern Halus --}}
        <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-10">
            {{-- Header Section --}}
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-primary font-bold text-sm tracking-widest uppercase mb-2 block animate-pulse">/// System Logs</span>
                    <h2 class="text-3xl md:text-5xl font-black text-white uppercase italic tracking-tighter">
                        Latest <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">Intel</span>
                    </h2>
                </div>
                <a href="{{ route('articles.index') }}" class="hidden md:flex items-center gap-2 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors group">
                    View All Logs 
                    <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">arrow_forward</span>
                </a>
            </div>

            {{-- GRID ARTICLES (HOME VERSION) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                @foreach($intelArticles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="group relative h-[400px] w-full overflow-hidden block clip-corner border border-white/10 hover:border-primary/50 transition-all duration-500">
                        
                        {{-- 1. Image Background --}}
                        <div class="absolute inset-0 overflow-hidden">
                            {{-- Sesuaikan dengan nama kolom di database Anda: image_url --}}
                            <img src="{{ $article->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:contrast-125">
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            
                            {{-- Top Label (Category) --}}
                            <div class="absolute top-6 left-6">
                                <span class="bg-primary/20 backdrop-blur border border-primary/30 text-white text-[10px] font-black uppercase px-3 py-1 tracking-widest">
                                    {{ $article->category }}
                                </span>
                            </div>

                            {{-- Date (Format sesuai keinginan, misal: OCT 12) --}}
                            <div class="text-gray-400 text-[10px] font-mono mb-2 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-px bg-primary"></span> 
                                {{ $article->published_at ? $article->published_at->format('M d') : $article->created_at->format('M d') }}
                            </div>

                            {{-- Title --}}
                            <h3 class="text-2xl font-black text-white uppercase italic leading-tight mb-4">
                                <span class="bg-left-bottom bg-gradient-to-r from-primary to-primary bg-[length:0%_2px] bg-no-repeat group-hover:bg-[length:100%_2px] transition-all duration-500 ease-out pb-1">
                                    {{ $article->title }}
                                </span>
                            </h3>

                            {{-- Read More Indicator --}}
                            <div class="flex items-center gap-2 text-gray-400 text-xs font-bold uppercase tracking-wider group-hover:text-primary transition-colors translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 duration-500">
                                Read Dossier <span class="material-symbols-outlined text-sm">arrow_outward</span>
                            </div>
                        </div>
                    </a>
                 @endforeach
            </div>

            {{-- Mobile "View All" Button --}}
            <div class="mt-8 md:hidden">
                <a href="{{ route('articles.index') }}" class="w-full block text-center py-4 border border-white/20 text-white font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all clip-button text-xs">
                    View All Logs
                </a>
            </div>
        </div>
    </section>
</div>
