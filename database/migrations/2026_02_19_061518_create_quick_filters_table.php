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
        Schema::create('quick_filters', function (Blueprint $table) {
            $table->id();
            $table->string('keyword'); 
            $table->boolean('is_active')->default(true); // Status nyala/mati
            $table->integer('order')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_filters');
    }
};
