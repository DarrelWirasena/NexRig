<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        set_time_limit(120);// Allow up to 2 minutes for streaming

        // PHASE 1: SEARCH - Pre-filter products based on user input
        $message = strtolower($request->message ?? '');
        $contextTitle = "DAFTAR PRODUK RELEVAN:";

        // INTENT DETECTION: Check for specific user intents like "cheapest"
        $isUseCaseQuery = Str::contains($message, [
            'kuliah', 'kerja', 'editing', 'gaming', 'programming', 'koding', 'coding',
            'teknik', 'desain', 'design', 'render', 'streaming', 'content creator',
            'arsitek', 'mahasiswa', 'pelajar', 'kantor', 'bisnis', 'produktivitas',
            'buat apa', 'untuk apa', 'cocok untuk', 'rekomen', 'saran', 'suggest'
        ]);
        $isRestockQuery = Str::contains($message, [
            'restock', 'kapan ada', 'kapan tersedia', 'kapan masuk', 
            'stok kapan', 'pre order', 'preorder', 'indent', 'waiting list'
        ]);

        if ($isRestockQuery) {
            $with = [
                'series.category', 'images' => fn($q) => $q->where('is_primary', true),
                'components', 'attributes', 'benchmarks.game', 'intendedUses',
            ];

            $contextTitle = "Pengguna menanyakan tentang ketersediaan atau restock produk. "
                . "Berikan informasi yang jujur tentang status stok produk yang ditanyakan. "
                . "Jika produk habis stok dan ada info restock, sampaikan. "
                . "Jika tidak ada info restock, sarankan hubungi admin.";

            // Send ALL products including out of stock so AI knows about them
            $relevantProducts = Product::where('is_active', true)
                ->with($with)
                ->get();

        } elseif ($isUseCaseQuery) {
            $with = [
                'series.category', 'images' => fn($q) => $q->where('is_primary', true),
                'components', 'attributes', 'benchmarks.game', 'intendedUses',
            ];

            $contextTitle = "Pengguna mencari PC untuk kebutuhan spesifik. "
                . "Ranking semua produk berikut dari yang PALING COCOK dan PALING MURAH untuk kebutuhan tersebut. "
                . "Produk dengan status HABIS STOK tetap boleh disebutkan JIKA paling cocok, "
                . "namun wajib informasikan bahwa stok habis dan sarankan pengguna menghubungi admin "
                . "untuk menanyakan ketersediaan. Lalu rekomendasikan alternatif terbaik yang TERSEDIA.";

            $relevantProducts = Product::where('is_active', true)
                ->with($with)
                ->get();

        } elseif (Str::contains($message, ['murah', 'termurah', 'paling murah'])) {
            $with = [
                'series.category', 'images' => fn($q) => $q->where('is_primary', true),
                'components', 'attributes', 'benchmarks.game', 'intendedUses',
            ];

            // Get the absolute cheapest regardless of stock
            $cheapestOverall = Product::where('is_active', true)
                ->orderBy('price', 'asc')
                ->with($with)
                ->first();

            // Get cheapest in-stock
            $cheapestInStock = Product::where('is_active', true)
                ->where(fn($q) => $q->where('track_stock', false)->orWhere('stock', '>', 0))
                ->orderBy('price', 'asc')
                ->with($with)
                ->first();

            $cheapestIsOutOfStock = $cheapestOverall
                && $cheapestInStock
                && $cheapestOverall->id !== $cheapestInStock->id
                && $cheapestOverall->track_stock
                && $cheapestOverall->stock <= 0;

            if ($cheapestIsOutOfStock) {
                $contextTitle = "Produk PALING MURAH di toko adalah {$cheapestOverall->name} "
                    . "(Rp " . number_format($cheapestOverall->price, 0, ',', '.') . ") "
                    . "NAMUN SEDANG HABIS STOK. "
                    . "Beritahu pengguna hal ini, lalu rekomendasikan bahwa produk termurah yang TERSEDIA saat ini adalah {$cheapestInStock->name} "
                    . "(Rp " . number_format($cheapestInStock->price, 0, ',', '.') . ").";

                // Get a few more in-stock products for context
                $relevantProducts = Product::where('is_active', true)
                    ->where(fn($q) => $q->where('track_stock', false)->orWhere('stock', '>', 0))
                    ->orderBy('price', 'asc')
                    ->limit(5)
                    ->with($with)
                    ->get();
            } else {
                $contextTitle = "Berikut adalah beberapa produk kami dengan harga paling terjangkau:";
                $relevantProducts = Product::where('is_active', true)
                    ->where(fn($q) => $q->where('track_stock', false)->orWhere('stock', '>', 0))
                    ->orderBy('price', 'asc')
                    ->limit(5)
                    ->with($with)
                    ->get();
            }
        } else {
            // ORIGINAL SEARCH LOGIC
            $keywords = $this->extractKeywords($message);
            $priceRange = $this->extractPriceRange($message);

            $with = [
                'series.category', 'images' => fn($q) => $q->where('is_primary', true),
                'components', 'attributes', 'benchmarks.game', 'intendedUses',
            ];

            // Step 1: Find ALL matching products regardless of stock
            $matchingProducts = Product::query()
                ->where('is_active', true)
                ->when(count($keywords) > 0, function ($query) use ($keywords) {
                    $query->where(function ($query) use ($keywords) {
                        foreach ($keywords as $word) {
                            $query->where(function ($subQuery) use ($word) {
                                $subQuery->orWhere('name', 'LIKE', "%{$word}%")
                                    ->orWhere('short_description', 'LIKE', "%{$word}%")
                                    ->orWhereHas('components', fn($q) => $q->where('name', 'LIKE', "%{$word}%"))
                                    ->orWhereHas('intendedUses', fn($q) => $q->where('name', 'LIKE', "%{$word}%"));
                            });
                        }
                    });
                })
                ->when($priceRange, function ($query, $priceRange) {
                    $query->whereBetween('price', $priceRange);
                })
                ->with($with)
                ->limit(10)
                ->get();

            // Step 2: Separate in-stock and out-of-stock
            $inStockProducts = $matchingProducts->filter(
                fn($p) => !$p->track_stock || $p->stock > 0
            );
            $outOfStockProducts = $matchingProducts->filter(
                fn($p) => $p->track_stock && $p->stock <= 0
            );

            if ($matchingProducts->isEmpty()) {
                // No matches at all — trigger fallback below
                $relevantProducts = collect();
            } elseif ($inStockProducts->isEmpty() && $outOfStockProducts->isNotEmpty()) {
                // Matches found but ALL out of stock
                $outOfStockNames = $outOfStockProducts->pluck('name')->join(', ');
                $categoryIds = $outOfStockProducts
                    ->map(fn($p) => $p->series->category->id ?? null)
                    ->filter()
                    ->unique();

                $contextTitle = "Produk yang cocok dengan permintaan pengguna SEDANG HABIS STOK. "
                    . "Beritahu pengguna dengan jelas bahwa produk yang mereka cari ({$outOfStockNames}) "
                    . "sedang tidak tersedia, lalu rekomendasikan alternatif terbaik yang tersedia berikut ini:";

                $relevantProducts = Product::where('is_active', true)
                    ->where(fn($q) => $q->where('track_stock', false)->orWhere('stock', '>', 0))
                    ->whereHas('series.category', fn($q) => $q->whereIn('id', $categoryIds))
                    ->with($with)
                    ->limit(5)
                    ->get();

                // If no alternatives in same category, fallback to any in-stock products
                if ($relevantProducts->isEmpty()) {
                    $relevantProducts = Product::where('is_active', true)
                        ->where(fn($q) => $q->where('track_stock', false)->orWhere('stock', '>', 0))
                        ->with($with)
                        ->latest()
                        ->limit(3)
                        ->get();
                }
            } else {
                // Normal flow — only show in-stock matches
                $relevantProducts = $inStockProducts;
            }
        }

        // HYBRID APPROACH: If smart search fails, trigger the fallback mechanism
        if ($relevantProducts->isEmpty()) {
            $contextTitle = "Maaf, permintaan Anda tidak cocok dengan produk spesifik kami. Namun, berikut adalah beberapa produk populer kami untuk referensi:";

            // Fallback: Get a general sample of products (e.g., 3 latest)
            $relevantProducts = Product::where('is_active', true)
                ->where(function ($q) {
                    $q->where('track_stock', false)->orWhere('stock', '>', 0);
                })
                ->latest()
                ->limit(3)
                ->with([
                    'series.category', 'images' => fn($q) => $q->where('is_primary', true),
                    'components', 'attributes', 'benchmarks.game', 'intendedUses',
                ])
                ->get();
            
            // If the entire database is empty, we must give up.
            if ($relevantProducts->isEmpty()) {
                return response()->json([
                    'reply'    => 'Maaf, sepertinya belum ada produk di toko kami. Silakan kembali lagi nanti!',
                    'products' => []
                ]);
            }
        }

        // PHASE 2: RECOMMEND - Use AI to get a recommendation from the (potentially fallback) list
        $productContext = $this->buildProductContext($relevantProducts);

        $systemPrompt = "Kamu adalah asisten toko gaming PC bernama NexRig.
        Jawab dengan ramah dan singkat dalam Bahasa Indonesia.
        Gunakan data produk di bawah untuk memberikan rekomendasi yang tepat. Jika pengguna menanyakan sesuatu yang tidak kamu jual (misal: laptop), jelaskan bahwa kamu adalah toko PC dan berikan alternatif PC yang relevan dari daftar di bawah jika memungkinkan.

        Kemampuan kamu:
        - Rekomendasikan produk berdasarkan kebutuhan (gaming, editing, kuliah, budget, dll)
        - Bandingkan spesifikasi antar produk
        - Jelaskan benchmark FPS untuk game tertentu
        - Informasikan garansi dan detail produk

        {$contextTitle}
        {$productContext}

        ATURAN:
        Jika merekomendasikan produk, wajib sertakan blok ini di akhir jawaban:
        [PRODUCTS]
        [{\"slug\":\"slug-produk-1\"},{\"slug\":\"slug-produk-2\"}]
        [/PRODUCTS]
        - Jika tidak ada rekomendasi produk, jangan sertakan blok [PRODUCTS].
        - Untuk produk HABIS STOK yang tetap relevan: sebutkan nama dan harganya, jelaskan bahwa stok sedang habis, sarankan pengguna menghubungi admin NexRig untuk menanyakan ketersediaan stok, lalu rekomendasikan alternatif yang TERSEDIA.
        - Jangan sertakan produk HABIS STOK di dalam blok [PRODUCTS].";

        $userMessage = $request->message;

        
        return response()->stream(function () use ($userMessage, $systemPrompt) {
            try{
                    $client = new Client();

                    $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                            'Content-Type'  => 'application/json',
                        ],
                        'json' => [
                            'model'    => 'stepfun/step-3.5-flash:free',
                            'stream'   => true,
                            'messages' => [
                                ['role' => 'system', 'content' => $systemPrompt],
                                ['role' => 'user',   'content' => $userMessage],
                            ],
                        ],
                        'stream' => true,
                    ]);

                    $body = $response->getBody();

                    while (!$body->eof()) {
                        $line = trim($this->readLine($body));

                        if (str_starts_with($line, 'data: ')) {
                            $data = substr($line, 6);
                            if ($data === '[DONE]') break;

                            $json = json_decode($data, true);
                            $token = $json['choices'][0]['delta']['content'] ?? '';

                            if ($token !== '') {
                                echo "data: " . json_encode(['token' => $token]) . "\n\n";
                                ob_flush();
                                flush();
                            }
                        }
                    }
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                $status = $e->getResponse()->getStatusCode();
                if ($status === 429) {
                    echo "data: " . json_encode(['token' => 'Maaf, asisten sedang sibuk. Silakan coba lagi dalam beberapa saat.']) . "\n\n";
                } else {
                    echo "data: " . json_encode(['token' => 'Terjadi kesalahan. Silakan coba lagi.']) . "\n\n";
                }
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
            }

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function extractKeywords(string $message): array
    {
        $stopWords = ['ada', 'buat', 'ga', 'gk', 'engga', 'ngga', 'dan', 'di', 'untuk', 'yang', 'saya', 'mau', 'ingin', 'tolong', 'carikan', 'budget', 'harga', 'sekitar', 'jutaan', 'juta', 'jt', 'info', 'rekomendasi', 'laptop'];
        $message = preg_replace('/[^\pL\s\d]+/u', '', $message);
        $words = explode(' ', $message);
        return array_filter(array_diff($words, $stopWords));
    }

    private function extractPriceRange(string $message): ?array
    {
        if (preg_match('/(\d{1,3}(?:\.\d{3})*|\d+)\s*(jt|juta|k)?/', $message, $matches)) {
            $price = (int) str_replace(['.', ','], '', $matches[1]);
            $multiplier = $matches[2] ?? '';

            if (in_array($multiplier, ['jt', 'juta'])) {
                $price *= 1000000;
            } elseif ($multiplier === 'k') {
                $price *= 1000;
            }

            // Detect direction keywords
            $isUnder = Str::contains($message, ['kebawah', 'ke bawah', 'kurang dari', 'dibawah', 'di bawah', 'maksimal', 'max', 'under', 'mentok']);
            $isOver  = Str::contains($message, ['keatas', 'ke atas', 'lebih dari', 'diatas', 'di atas', 'minimal', 'min', 'over']);

            if ($isUnder) {
                return ['min' => 0, 'max' => $price];
            }

            if ($isOver) {
                return ['min' => $price, 'max' => PHP_INT_MAX];
            }

            // No direction — apply buffer as before
            $buffer = $price * 0.20;
            return [
                'min' => max(0, $price - $buffer),
                'max' => $price + $buffer
            ];
        }
        return null;
    }

    private function buildProductContext($products): string
    {
        return $products->map(function ($p) {
            $components = $p->components->pluck('name')->join(', ');
            $attributes = $p->attributes->map(fn($a) => "{$a->name}: {$a->value}")->join(', ');
            $benchmarks = $p->benchmarks->map(fn($b) => ($b->game->name ?? 'Game') . ": {$b->fps} FPS")->join(', ');
            $uses = $p->intendedUses->pluck('name')->join(', ');
            $isOutOfStock = $p->track_stock && $p->stock <= 0;
            $stockStatus = $isOutOfStock ? 'HABIS STOK' : 'TERSEDIA';
            $restockInfo = $isOutOfStock && $p->restock_note 
                ? "\n- Info Restock: {$p->restock_note}" 
                : '';

        return "
        PRODUK: {$p->name}
        - Status Stok: {$stockStatus}{$restockInfo}
        - Harga: Rp " . number_format($p->price, 0, ',', '.') . "
        - Kategori: {$p->series->category->name} | Series: {$p->series->name}
        - Deskripsi: {$p->short_description}
        - Komponen: {$components}
        - Info: {$attributes}
        - Benchmark: {$benchmarks}
        - Cocok untuk: {$uses}
        - Slug: {$p->slug}";
        })->join("\n---");
    }
    private function readLine($body): string
    {
        $line = '';
        while (!$body->eof()) {
            $char = $body->read(1);
            if ($char === "\n") break;
            $line .= $char;
        }
        return $line;
    }

    public function getProductCards(Request $request)
    {
        $slugs = $request->input('slugs', []);

        $products = Product::whereIn('slug', $slugs)
            ->with(['images' => fn($q) => $q->where('is_primary', true), 'series.category'])
            ->get()
            ->map(fn($p) => [
                'slug'     => $p->slug,
                'name'     => $p->name,
                'price'    => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'image'    => $p->images->first()?->full_url,
                'category' => $p->series->category->name ?? '-',
            ]);

        return response()->json(['products' => $products]);
    }
}
