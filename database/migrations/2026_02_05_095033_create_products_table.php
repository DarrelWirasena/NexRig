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
            // Foreign Key ke Categories
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 15, 2); // Harga Jual
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true); // Status tayang
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
