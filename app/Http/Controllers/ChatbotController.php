<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        // Ambil semua produk dengan relasi lengkap
        $products = Product::with([
            'series.category',
            'images'       => fn($q) => $q->where('is_primary', true),
            'components',   // spesifikasi hardware
            'attributes',   // garansi, warna, dimensi, dll
            'benchmarks',   // FPS di game tertentu
            'intendedUses', // cocok untuk apa (gaming, editing, dll)
        ])
        ->where('is_active', true)
        ->get()
        ->map(function($p) {
            // Komponen (CPU, GPU, RAM, dll)
            $components = $p->components->map(fn($c) => $c->name)->join(', ');

            // Attributes (Garansi, Warna, dll)
            $attributes = $p->attributes->map(fn($a) => "{$a->name}: {$a->value}")->join(', ');

            // Benchmarks (FPS di game)
            $benchmarks = $p->benchmarks->map(fn($b) => "{$b->game}: {$b->fps} FPS")->join(', ');

            // Intended Uses (Gaming, Editing, Programming, dll)
            $uses = $p->intendedUses->map(fn($u) => $u->name)->join(', ');

            return [
                'name'        => $p->name,
                'price'       => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'category'    => $p->series->category->name ?? '-',
                'series'      => $p->series->name ?? '-',
                'description' => $p->short_description ?? '-',
                'slug'        => $p->slug,
                'image'       => $p->images->first()?->full_url ?? null,
                'components'  => $components ?: '-',
                'attributes'  => $attributes ?: '-',
                'benchmarks'  => $benchmarks ?: '-',
                'uses'        => $uses ?: '-',
            ];
        });

        // Format sebagai teks yang mudah dibaca AI
        $productContext = $products->map(function($p) {
            return "
PRODUK: {$p['name']}
- Harga: {$p['price']}
- Kategori: {$p['category']} | Series: {$p['series']}
- Deskripsi: {$p['description']}
- Komponen: {$p['components']}
- Info: {$p['attributes']}
- Benchmark: {$p['benchmarks']}
- Cocok untuk: {$p['uses']}
- Slug: {$p['slug']}";
        })->join("\n---");

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => 'stepfun/step-3.5-flash:free',
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => "Kamu adalah asisten toko gaming PC bernama SAKA.
Jawab dengan ramah dan singkat dalam Bahasa Indonesia.
Gunakan data produk di bawah untuk memberikan rekomendasi yang tepat.

Kemampuan kamu:
- Rekomendasikan produk berdasarkan kebutuhan (gaming, editing, kuliah, budget, dll)
- Bandingkan spesifikasi antar produk
- Jelaskan benchmark FPS untuk game tertentu
- Informasikan garansi dan detail produk

DAFTAR PRODUK:
{$productContext}

ATURAN:
Jika merekomendasikan produk, wajib sertakan blok ini di akhir jawaban:
[PRODUCTS]
[{\"slug\":\"slug-produk-1\"},{\"slug\":\"slug-produk-2\"}]
[/PRODUCTS]

Jika tidak ada rekomendasi produk, jangan sertakan blok [PRODUCTS]."
                ],
                [
                    'role'    => 'user',
                    'content' => $request->message
                ]
            ]
        ]);

        $rawReply = $response->json('choices.0.message.content') ?? '';

        // Pisahkan teks dan data produk
        $productCards = [];
        $textReply    = $rawReply;

        if (preg_match('/\[PRODUCTS\](.*?)\[\/PRODUCTS\]/s', $rawReply, $matches)) {
            $textReply = trim(preg_replace('/\[PRODUCTS\].*?\[\/PRODUCTS\]/s', '', $rawReply));
            $slugs     = json_decode($matches[1], true) ?? [];

            foreach ($slugs as $item) {
                $product = $products->firstWhere('slug', $item['slug']);
                if ($product) {
                    $productCards[] = $product;
                }
            }
        }

        return response()->json([
            'reply'    => $textReply ?: 'Maaf, saya tidak bisa menjawab saat ini.',
            'products' => $productCards
        ]);
    }
}