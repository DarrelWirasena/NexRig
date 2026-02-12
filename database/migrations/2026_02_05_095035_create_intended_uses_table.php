<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master: Menyimpan jenis-jenis penggunaan (Esports, Work, dll)
        Schema::create('intended_uses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Contoh: "Esports Gaming"
            $table->text('description')->nullable();
            $table->string('icon_url')->nullable(); // Nama Material Symbol
            $table->timestamps();
        });

        // 2. Tabel Pivot: Menghubungkan Produk dengan Intended Use
        Schema::create('intended_use_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('intended_use_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intended_use_product');
        Schema::dropIfExists('intended_uses');
    }
};