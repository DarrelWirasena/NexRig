<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom midtrans_order_id setelah kolom status
            $table->string('midtrans_order_id')->nullable()->after('status');
            
            // Opsional: Jika kamu butuh menyimpan payment_type (misal: bank_transfer, qris)
            // Uncomment baris di bawah ini jika di tabel orders belum ada kolom payment_type
            // $table->string('payment_type')->nullable()->after('status'); 
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('midtrans_order_id');
        });
    }
};