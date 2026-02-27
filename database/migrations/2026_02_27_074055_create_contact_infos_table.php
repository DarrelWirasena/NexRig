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
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, whatsapp, address
            $table->string('label'); // "Email Support", "WhatsApp Chat", dll
            $table->string('title')->nullable(); // "NexRig Experience Center"
            $table->string('value'); // isi kontak / alamat
            $table->string('url')->nullable(); // mailto:, wa.me, google maps link
            $table->string('display_value')->nullable(); // "Lihat di Peta", "+62 895..."
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
