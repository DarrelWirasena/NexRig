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
    Schema::table('contact_messages', function (Blueprint $table) {
        // Kita tambahkan kolom user_id
        // PENTING: Harus 'nullable()' karena data lama tidak punya user_id
        // 'after(id)' agar kolomnya muncul rapi setelah ID
        $table->foreignId('user_id')
              ->nullable() 
              ->after('id')
              ->constrained('users')
              ->cascadeOnDelete(); 
    });
}

public function down()
{
    Schema::table('contact_messages', function (Blueprint $table) {
        // Perintah untuk membatalkan (menghapus kolom jika rollback)
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
