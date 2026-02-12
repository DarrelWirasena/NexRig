<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'RTX 5090: Melompati Batas Realitas Digital',
                'category' => 'Hardware',
                'image_url' => 'https://images.unsplash.com/photo-1624705002806-5d72df8343f7?q=80&w=2000',
                'excerpt' => 'Arsitektur Blackwell terbaru dari NVIDIA menjanjikan peningkatan performa hingga 2x lipat dalam Ray Tracing.',
                'content' => '<h2>Masa Depan Grafis Telah Tiba</h2><p>Dengan peluncuran arsitektur Blackwell, NVIDIA kembali mendominasi pasar kartu grafis dunia. RTX 5090 bukan sekadar peningkatan kecil, melainkan lompatan kuantum dalam komputasi visual.</p><blockquote>"Ini adalah kartu grafis pertama yang benar-benar mampu menjalankan Path Tracing pada resolusi 4K native tanpa bantuan DLSS."</blockquote><p>Fitur utama yang menjadi sorotan adalah memori GDDR7 yang lebih cepat dan efisiensi daya yang lebih baik meskipun performanya meningkat drastis.</p>',
                'tags' => 'nvidia,gpu,blackwell,rtx5090',
            ],
            [
                'title' => 'Panduan Memilih Liquid Cooling untuk Prosedor I9 Generasi Terbaru',
                'category' => 'Guides',
                'image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=2000',
                'excerpt' => 'Jangan biarkan rig kamu kepanasan. Inilah daftar AIO Cooler terbaik untuk menjaga suhu tetap stabil.',
                'content' => '<h2>Suhu Rendah, Performa Maksimal</h2><p>Prosesor kelas atas membutuhkan solusi pendinginan yang serius. Menggunakan pendingin standar akan mengakibatkan thermal throttling yang merugikan FPS Anda.</p><ul><li>360mm Radiator minimal untuk seri i9.</li><li>Pastikan casing memiliki airflow yang cukup.</li><li>Gunakan thermal paste berkualitas tinggi seperti Thermal Grizzly.</li></ul>',
                'tags' => 'cooling,aio,tech,setup',
            ],
            [
                'title' => 'Mengapa RAM DDR5 6400MHz Menjadi Standar Baru Gaming 2026',
                'category' => 'Hardware',
                'image_url' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?q=80&w=2000',
                'excerpt' => 'Latency rendah dan bandwidth tinggi. DDR5 bukan lagi sekadar kemewahan, tapi kebutuhan.',
                'content' => '<h2>Transisi Besar Memori</h2><p>Tahun 2026 menandai berakhirnya dominasi DDR4. Dengan bandwidth yang jauh lebih lebar, DDR5 memungkinkan pertukaran data antara CPU dan RAM terjadi tanpa hambatan berarti.</p><p>Hasil benchmark menunjukkan peningkatan minimum FPS sebesar 15% pada game-game open world yang berat seperti GTA VI.</p>',
                'tags' => 'ram,ddr5,memory,tech',
            ],
            [
                'title' => 'Seni Cable Management: Membuat Rig Terlihat Futuristik',
                'category' => 'Setup',
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2000',
                'excerpt' => 'Kabel yang berantakan menghalangi aliran udara dan merusak estetika. Ikuti tips dari NexRig berikut.',
                'content' => '<h2>Bersih Itu Indah</h2><p>Cable management bukan hanya soal estetika, tapi juga soal kesehatan komponen Anda. Sirkulasi udara yang baik hanya bisa dicapai jika tidak ada kabel yang menghalangi jalur kipas.</p><p>Gunakan velcro ties daripada zip ties agar lebih mudah diatur ulang di masa depan.</p>',
                'tags' => 'cablemanagement,setup,pcbuild',
            ],
            [
                'title' => 'Review: Monitor OLED 480Hz - Apakah Mata Manusia Bisa Merasakannya?',
                'category' => 'Reviews',
                'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?q=80&w=2000',
                'excerpt' => 'Panel OLED dengan refresh rate ekstrem menjanjikan kejelasan gerakan yang tak tertandingi.',
                'content' => '<h2>Kecepatan Cahaya di Meja Anda</h2><p>OLED dikenal dengan contrast ratio yang sempurna, tapi kali ini mereka fokus pada kecepatan. Dengan 480Hz, motion blur hampir tidak terlihat sama sekali.</p><p>Bagi pemain esports profesional, monitor ini adalah senjata baru yang memberikan keunggulan kompetitif milidetik yang berharga.</p>',
                'tags' => 'monitor,oled,display,gaming',
            ],
            [
                'title' => 'Membangun PC Gaming dengan Budget Terbatas di Tahun 2026',
                'category' => 'Guides',
                'image_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=2000',
                'excerpt' => 'Membangun beast tidak selalu harus menguras tabungan. Inilah pilihan part budget terbaik.',
                'content' => '<h2>Performa Maksimal, Harga Minimal</h2><p>Pasar komponen bekas dan rilis hardware kelas menengah tahun ini memberikan opsi menarik bagi gamer dengan budget terbatas.</p><p>Memilih GPU generasi sebelumnya namun dengan VRAM besar seringkali lebih menguntungkan daripada kartu grafis terbaru kelas entry-level.</p>',
                'tags' => 'budget,pcbuild,gaming,tips',
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'category' => $article['category'],
                'image_url' => $article['image_url'],
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'author' => 'NexRig Admin',
                'reading_time' => rand(5, 12),
                'tags' => $article['tags'],
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}