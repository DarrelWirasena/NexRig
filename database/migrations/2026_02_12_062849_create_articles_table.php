<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('slug')->unique(); // Untuk URL: nexrig.com/intel/rtx-5090-review
            $blueprint->string('category')->default('General'); // Intel, Tech, News, etc.
            $blueprint->string('image_url')->nullable(); // Thumbnail artikel
            $blueprint->text('excerpt')->nullable(); // Ringkasan singkat untuk kartu artikel
            $blueprint->longText('content'); // Konten utama (HTML dari WYSIWYG)
            $blueprint->string('author')->default('NexRig Admin');
            $blueprint->integer('reading_time')->default(5); // Estimasi waktu baca (menit)
            $blueprint->string('tags')->nullable(); // Disimpan sebagai string koma: "gaming,gpu,nvidia"
            
            // Metadata SEO (Penting!)
            $blueprint->string('meta_title')->nullable();
            $blueprint->string('meta_description')->nullable();
            
            $blueprint->enum('status', ['draft', 'published'])->default('draft');
            $blueprint->timestamp('published_at')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};