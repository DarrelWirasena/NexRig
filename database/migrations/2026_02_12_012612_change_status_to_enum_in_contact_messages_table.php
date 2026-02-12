<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Kita ubah kolom status menjadi enum
            // Catatan: Jika ada data lama, pastikan datanya sesuai dengan pilihan enum ini
            $table->enum('status', ['sent', 'replied', 'closed'])
                  ->default('sent')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Kembalikan ke tipe data sebelumnya (misal integer) jika di-rollback
            $table->integer('status')->default(0)->change();
        });
    }
};