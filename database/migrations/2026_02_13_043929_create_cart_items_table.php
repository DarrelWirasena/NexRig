<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // Hubungkan dengan User
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Hubungkan dengan Product
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Simpan jumlah barang
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Mencegah duplikasi: 1 User tidak boleh punya 2 baris untuk produk yang sama
            // (Harusnya quantity-nya yang bertambah)
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
