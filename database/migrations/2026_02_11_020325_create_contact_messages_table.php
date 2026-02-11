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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id(); // Primary Key
            
            // Kolom Data
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message'); // Menggunakan 'text' agar bisa menampung pesan panjang
            
            // Kolom Waktu (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};