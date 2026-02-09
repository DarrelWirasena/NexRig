<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Produk sekarang nempel ke Series, bukan langsung Category
            $table->foreignId('product_series_id')->constrained('product_series')->onDelete('cascade');
            
            $table->string('name'); // Contoh: "Horizon II Elite"
            $table->string('slug')->unique();
            
            // Opsional: Kolom 'tier' untuk membedakan varian (Core/Pro/Elite) secara eksplisit
            $table->enum('tier', ['Core', 'Pro', 'Elite', 'Creator', 'Extreme'])->nullable(); 
            
            $table->decimal('price', 15, 2);
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
