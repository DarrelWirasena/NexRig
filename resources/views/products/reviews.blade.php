@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 md:px-10 py-12 pb-24">

    {{-- ── BREADCRUMB ── --}}
    <nav class="flex text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Catalog</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-white transition-colors">
            {{ $product->name }}
        </a>
        <span class="mx-2">/</span>
        <span class="text-white">Ulasan</span>
    </nav>

    {{-- ── HEADER ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10
                border-b border-white/10 pb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.show', $product->slug) }}#reviews"
               class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center
                      text-gray-400 hover:bg-blue-600 hover:text-white transition-all shrink-0">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <p class="text-primary font-mono text-[10px] tracking-[0.3em] uppercase mb-1">
                    Customer Reviews
                </p>
                <h1 class="text-2xl md:text-3xl font-black text-white uppercase italic tracking-tight">
                    {{ $product->name }}
                </h1>
            </div>
        </div>

        {{-- Rating ringkas di header --}}
        <div class="flex items-center gap-3 bg-[#0a0a0a] border border-white/10
                    rounded-xl px-5 py-3 shrink-0">
            <span class="text-4xl font-black text-white">
                {{ number_format($product->average_rating, 1) }}
            </span>
            <div>
                <div class="flex gap-0.5 mb-1">
                    @for ($i = 1; $i <= 5; $i++)
                    <span class="material-symbols-outlined text-base
                                 {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-700' }}"
                          style="font-variation-settings: 'FILL' 1">star</span>
                    @endfor
                </div>
                <p class="text-gray-500 text-xs">{{ $totalReviews }} ulasan</p>
            </div>
        </div>
    </div>

    {{-- ── FILTER BINTANG ── --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
        <span class="text-gray-500 text-xs font-bold uppercase tracking-wider shrink-0">Filter:</span>

        {{-- Semua --}}
        <a href="{{ route('products.reviews', $product->slug) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold
                  uppercase tracking-wider border transition-all
                  {{ !request('rating')
                      ? 'bg-blue-600 border-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.3)]'
                      : 'bg-white/5 border-white/10 text-gray-400 hover:border-white/30 hover:text-white' }}">
            <span class="material-symbols-outlined text-sm">reviews</span>
            Semua
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black
                         {{ !request('rating') ? 'bg-white/20' : 'bg-white/10' }}">
                {{ $totalReviews }}
            </span>
        </a>

        {{-- Filter per bintang --}}
        @foreach ($ratingDistribution as $star => $data)
        <a href="{{ route('products.reviews', ['slug' => $product->slug, 'rating' => $star]) }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold
                  uppercase tracking-wider border transition-all
                  {{ request('rating') == $star
                      ? 'bg-amber-500/20 border-amber-500/50 text-amber-400'
                      : 'bg-white/5 border-white/10 text-gray-400 hover:border-white/30 hover:text-white' }}">
            <span class="material-symbols-outlined text-sm text-amber-400"
                  style="font-variation-settings: 'FILL' 1">star</span>
            {{ $star }}
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black
                         {{ request('rating') == $star ? 'bg-amber-500/20' : 'bg-white/10' }}">
                {{ $data['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- ── DAFTAR ULASAN ── --}}
    @if ($reviews->count() > 0)

    <div class="space-y-5 mb-10">
        @foreach ($reviews as $review)
        @php
            $isOwn = auth()->check() && $review->user_id === auth()->id();
            $ratingText = match($review->rating) {
                5 => ['label' => 'Sangat Puas',  'class' => 'bg-green-500/10 text-green-400 border-green-500/20'],
                4 => ['label' => 'Puas',         'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                3 => ['label' => 'Cukup',        'class' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'],
                2 => ['label' => 'Kurang',       'class' => 'bg-orange-500/10 text-orange-400 border-orange-500/20'],
                1 => ['label' => 'Tidak Puas',   'class' => 'bg-red-500/10 text-red-400 border-red-500/20'],
                default => ['label' => '-',      'class' => 'bg-gray-500/10 text-gray-400 border-gray-500/20'],
            };
        @endphp

        <div class="bg-[#0a0a0a] border rounded-2xl p-6
                    hover:border-white/20 transition-all duration-300
                    {{ $isOwn ? 'border-blue-500/20 bg-blue-500/[0.02]' : 'border-white/10' }}">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-blue-800
                                flex items-center justify-center shrink-0 border border-blue-500/30">
                        <span class="text-white font-black">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm flex items-center gap-2">
                            {{ $review->user->name }}
                            @if ($isOwn)
                            <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5
                                         rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                Kamu
                            </span>
                            @endif
                        </p>
                        <p class="text-gray-600 text-xs">{{ $review->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Bintang + hapus (jika milik sendiri) --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="flex gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-base
                                     {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-700' }}"
                              style="font-variation-settings: 'FILL' 1">star</span>
                        @endfor
                    </div>
                    @if ($isOwn)
                    <form action="{{ route('reviews.destroy', $review->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20
                                   border border-red-500/20 hover:border-red-500/50
                                   flex items-center justify-center text-red-400
                                   transition-all" title="Hapus ulasan">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Konten --}}
            @if ($review->title)
            <h5 class="text-white font-bold text-sm mb-2">{{ $review->title }}</h5>
            @endif
            @if ($review->body)
            <p class="text-gray-400 text-sm leading-relaxed">{{ $review->body }}</p>
            @endif
            @if (!$review->title && !$review->body)
            <p class="text-gray-600 text-sm italic">Tidak ada komentar tambahan.</p>
            @endif

            {{-- Footer badge --}}
            <div class="mt-4 pt-4 border-t border-white/5 flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-wider
                             px-2.5 py-1 rounded-full border {{ $ratingText['class'] }}">
                    {{ $ratingText['label'] }}
                </span>
                <span class="text-[10px] text-gray-600">Verified Purchase</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    {{ $reviews->appends(request()->query())->links() }}

    @else

    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-24
                border border-dashed border-white/10 rounded-2xl">
        <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">
            {{ request('rating') ? 'star_half' : 'rate_review' }}
        </span>
        <p class="text-gray-500 font-bold text-lg mb-1">
            {{ request('rating') ? 'Tidak ada ulasan bintang ' . request('rating') : 'Belum ada ulasan' }}
        </p>
        <p class="text-gray-600 text-sm mb-6">
            {{ request('rating') ? 'Coba filter bintang lain.' : 'Jadilah yang pertama memberikan ulasan.' }}
        </p>
        @if (request('rating'))
        <a href="{{ route('products.reviews', $product->slug) }}"
           class="px-5 py-2.5 bg-white/5 border border-white/10 text-white text-xs
                  font-bold rounded-lg hover:bg-white/10 transition-all">
            Lihat Semua Ulasan
        </a>
        @endif
    </div>

    @endif

</div>

@endsection