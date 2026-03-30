{{-- ══════════════════════════════════════════════════════════════════
     PARTIAL: SECTION ULASAN & RATING (Preview — maks. 3 ulasan)
     Simpan di: resources/views/sections/product/review.blade.php

     Cara panggil di products/show.blade.php:
         @include('sections.product.review', [
             'product'            => $product,
             'ratingDistribution' => $ratingDistribution,
             'eligibleOrder'      => $eligibleOrder,
             'existingReview'     => $existingReview,
         ])
════════════════════════════════════════════════════════════════════ --}}

<section class="mt-32 py-20 border-t border-white/5" id="reviews">
    <div class="max-w-[1440px] mx-auto">

        {{-- ── HEADER ── --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
            <div>
                <h3 class="text-primary font-mono text-xs tracking-[0.4em] uppercase mb-4">/// Customer Reviews</h3>
                <h2 class="text-4xl md:text-5xl font-black text-white italic uppercase leading-tight">
                    Ulasan <span class="text-blue-500">Pembeli</span>
                </h2>
            </div>

            {{-- Rating Summary Box --}}
            <div class="flex items-center gap-8 bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 shrink-0">
                <div class="text-center">
                    <div class="text-6xl font-black text-white leading-none mb-1">
                        {{ number_format($product->average_rating, 1) }}
                    </div>
                    <div class="flex justify-center gap-0.5 mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-lg
                                         {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-700' }}"
                                  style="font-variation-settings: 'FILL' 1">star</span>
                        @endfor
                    </div>
                    <p class="text-gray-500 text-xs">{{ $product->rating_count }} ulasan</p>
                </div>

                {{-- Bar Distribusi --}}
                <div class="space-y-1.5 min-w-[160px]">
                    @foreach ($ratingDistribution as $star => $data)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 w-3 shrink-0">{{ $star }}</span>
                        <span class="material-symbols-outlined text-amber-400 text-[13px]"
                              style="font-variation-settings: 'FILL' 1">star</span>
                        <div class="flex-1 h-1.5 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full transition-all duration-700"
                                 style="width: {{ $data['percent'] }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 w-6 text-right shrink-0">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- ══════════════════════════════════════════
                 KOLOM KIRI — FORM TULIS ULASAN
            ══════════════════════════════════════════ --}}
            <div class="lg:col-span-1">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 sticky top-28">

                    @auth

                        @if ($eligibleOrder && !$existingReview)
                        {{-- ✅ BISA REVIEW --}}
                        <h4 class="font-black text-white uppercase tracking-wider text-sm mb-1">Tulis Ulasan</h4>
                        <p class="text-gray-500 text-xs mb-6">
                            Dari pesanan <span class="text-white font-bold">#{{ $eligibleOrder->id }}</span>
                        </p>

                        <form action="{{ route('reviews.store', $product->id) }}"
                              method="POST"
                              id="reviewForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $eligibleOrder->id }}">

                            {{-- Pilih Bintang --}}
                            <div class="mb-5">
                                <label class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-3">
                                    Rating *
                                </label>
                                <div class="flex gap-1" id="starPicker">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                        onclick="setRating({{ $i }})"
                                        onmouseover="hoverRating({{ $i }})"
                                        onmouseleave="resetHover()"
                                        class="w-10 h-10 rounded-lg bg-white/5 border border-white/10
                                               flex items-center justify-center transition-all
                                               hover:border-amber-400/50 hover:bg-amber-400/10 group">
                                        <span class="material-symbols-outlined text-gray-600
                                                     group-hover:text-amber-400 transition-colors text-xl"
                                              style="font-variation-settings: 'FILL' 0"
                                              id="star-icon-{{ $i }}">star</span>
                                    </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" required>
                                <p class="text-xs text-gray-600 mt-2" id="ratingLabel">Pilih rating...</p>
                            </div>

                            {{-- Judul --}}
                            <div class="mb-4">
                                <label class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">
                                    Judul
                                    <span class="text-gray-600 normal-case font-normal">(opsional)</span>
                                </label>
                                <input type="text" name="title" maxlength="100"
                                    placeholder="Ringkasan pengalamanmu..."
                                    value="{{ old('title') }}"
                                    class="w-full bg-white/5 border border-white/10 focus:border-blue-500
                                           text-white text-sm rounded-lg px-4 py-2.5 outline-none
                                           focus:ring-1 focus:ring-blue-500/30 placeholder-gray-600 transition-all">
                            </div>

                            {{-- Isi Ulasan --}}
                            <div class="mb-5">
                                <label class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">
                                    Ulasan
                                    <span class="text-gray-600 normal-case font-normal">(opsional)</span>
                                </label>
                                <textarea name="body" rows="4" maxlength="2000"
                                    placeholder="Ceritakan pengalamanmu menggunakan produk ini..."
                                    class="w-full bg-white/5 border border-white/10 focus:border-blue-500
                                           text-white text-sm rounded-lg px-4 py-2.5 outline-none
                                           focus:ring-1 focus:ring-blue-500/30 placeholder-gray-600
                                           transition-all resize-none">{{ old('body') }}</textarea>
                                <p class="text-right text-[10px] text-gray-600 mt-1">maks. 2000 karakter</p>
                            </div>

                            <button type="submit"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold
                                       uppercase tracking-widest text-sm rounded-xl transition-all
                                       shadow-[0_0_20px_rgba(37,99,235,0.3)]
                                       hover:shadow-[0_0_30px_rgba(37,99,235,0.5)]
                                       flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">send</span>
                                Kirim Ulasan
                            </button>
                        </form>

                        @elseif ($existingReview)
                        {{-- ✅ SUDAH REVIEW --}}
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-green-400 text-lg">check_circle</span>
                            <h4 class="font-black text-white uppercase tracking-wider text-sm">Ulasanmu</h4>
                        </div>
                        <div class="flex gap-0.5 mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-base
                                         {{ $i <= $existingReview->rating ? 'text-amber-400' : 'text-gray-700' }}"
                                  style="font-variation-settings: 'FILL' 1">star</span>
                            @endfor
                        </div>
                        @if ($existingReview->title)
                        <p class="text-white font-bold text-sm mb-1">{{ $existingReview->title }}</p>
                        @endif
                        @if ($existingReview->body)
                        <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $existingReview->body }}</p>
                        @endif
                        <p class="text-gray-600 text-xs mb-4">{{ $existingReview->created_at->format('d M Y') }}</p>
                        <form action="{{ route('reviews.destroy', $existingReview->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2.5 bg-red-600/10 hover:bg-red-600/20
                                       border border-red-500/30 text-red-400 text-xs font-bold
                                       rounded-lg transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                Hapus Ulasan
                            </button>
                        </form>

                        @else
                        {{-- ❌ BELUM BELI --}}
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-4xl text-gray-700 mb-3 block">rate_review</span>
                            <p class="text-gray-400 text-sm font-bold mb-1">Belum bisa memberikan ulasan</p>
                            <p class="text-gray-600 text-xs leading-relaxed">
                                Kamu harus membeli dan menerima produk ini terlebih dahulu.
                            </p>
                            <a href="{{ route('products.index') }}"
                               class="inline-block mt-4 px-4 py-2 bg-white/5 border border-white/10
                                      text-white text-xs font-bold rounded-lg hover:bg-white/10 transition-all">
                                Lihat Katalog
                            </a>
                        </div>
                        @endif

                    @else
                    {{-- ❌ BELUM LOGIN --}}
                    <div class="text-center py-4">
                        <span class="material-symbols-outlined text-4xl text-gray-700 mb-3 block">lock</span>
                        <p class="text-gray-400 text-sm font-bold mb-1">Login untuk Memberikan Ulasan</p>
                        <p class="text-gray-600 text-xs leading-relaxed mb-4">
                            Masuk ke akunmu untuk menulis ulasan produk ini.
                        </p>
                        <a href="{{ route('login') }}"
                           class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-500
                                  text-white text-xs font-bold rounded-lg transition-all
                                  shadow-[0_0_15px_rgba(37,99,235,0.3)]">
                            Login Sekarang
                        </a>
                    </div>
                    @endauth

                    {{-- Flash Messages --}}
                    @if (session('success'))
                    <div class="mt-4 p-3 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-400 text-sm">check_circle</span>
                        <p class="text-green-400 text-xs font-bold">{{ session('success') }}</p>
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-400 text-sm">error</span>
                        <p class="text-red-400 text-xs font-bold">{{ session('error') }}</p>
                    </div>
                    @endif

                </div>
            </div>

            {{-- ══════════════════════════════════════════
                 KOLOM KANAN — PREVIEW 3 ULASAN
            ══════════════════════════════════════════ --}}
            <div class="lg:col-span-2">

                @php
                    $previewReviews = $product->reviews()->with('user')->latest()->take(3)->get();
                @endphp

                @if ($previewReviews->count() > 0)
                <div class="space-y-5">
                    @foreach ($previewReviews as $review)
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
                    <div class="bg-[#0a0a0a] border rounded-2xl p-6 group
                                hover:border-white/20 transition-all duration-300
                                {{ $isOwn ? 'border-blue-500/20 bg-blue-500/[0.02]' : 'border-white/10' }}">

                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-800
                                            flex items-center justify-center shrink-0 border border-blue-500/30">
                                    <span class="text-white font-black text-sm">
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
                                    <p class="text-gray-600 text-xs">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-0.5 shrink-0">
                                @for ($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-sm
                                             {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-700' }}"
                                      style="font-variation-settings: 'FILL' 1">star</span>
                                @endfor
                            </div>
                        </div>

                        @if ($review->title)
                        <h5 class="text-white font-bold text-sm mb-2">{{ $review->title }}</h5>
                        @endif
                        @if ($review->body)
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $review->body }}</p>
                        @endif
                        @if (!$review->title && !$review->body)
                        <p class="text-gray-600 text-sm italic">Tidak ada komentar tambahan.</p>
                        @endif

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

                {{-- ── TOMBOL LIHAT SEMUA ULASAN ── --}}
                @if ($product->rating_count > 3)
                <div class="mt-8 text-center">
                    <a href="{{ route('products.reviews', $product->slug) }}"
                       class="inline-flex items-center gap-3 px-8 py-3.5
                              bg-white/5 hover:bg-white/10
                              border border-white/10 hover:border-white/30
                              text-white font-bold text-sm uppercase tracking-widest
                              rounded-xl transition-all duration-300 group">
                        <span class="material-symbols-outlined text-lg text-blue-400
                                     group-hover:translate-x-0.5 transition-transform">reviews</span>
                        Lihat Semua {{ $product->rating_count }} Ulasan
                        <span class="material-symbols-outlined text-lg text-gray-500
                                     group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                @endif

                @else
                <div class="flex flex-col items-center justify-center py-20
                            border border-dashed border-white/10 rounded-2xl">
                    <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">rate_review</span>
                    <p class="text-gray-500 font-bold text-lg mb-1">Belum ada ulasan</p>
                    <p class="text-gray-600 text-sm">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@auth
@if ($eligibleOrder && !$existingReview)
<script>
(function () {
    let currentRating = 0;
    const ratingLabels = ['', 'Sangat Tidak Puas', 'Kurang Puas', 'Cukup', 'Puas', 'Sangat Puas'];

    window.setRating = function (star) {
        currentRating = star;
        document.getElementById('ratingInput').value = star;
        const label = document.getElementById('ratingLabel');
        label.textContent = ratingLabels[star];
        label.classList.remove('text-red-400');
        renderStars(star);
    };

    window.hoverRating = function (star) { renderStars(star); };
    window.resetHover  = function ()     { renderStars(currentRating); };

    function renderStars(upTo) {
        for (let i = 1; i <= 5; i++) {
            const icon = document.getElementById('star-icon-' + i);
            if (!icon) continue;
            if (i <= upTo) {
                icon.style.fontVariationSettings = "'FILL' 1";
                icon.classList.add('text-amber-400');
                icon.classList.remove('text-gray-600');
            } else {
                icon.style.fontVariationSettings = "'FILL' 0";
                icon.classList.remove('text-amber-400');
                icon.classList.add('text-gray-600');
            }
        }
    }

    document.getElementById('reviewForm')?.addEventListener('submit', function (e) {
        if (!currentRating) {
            e.preventDefault();
            const label = document.getElementById('ratingLabel');
            label.textContent = '⚠ Pilih rating terlebih dahulu';
            label.classList.add('text-red-400');
        }
    });
})();
</script>
@endif
@endauth