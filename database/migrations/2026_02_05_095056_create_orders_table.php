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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Pastikan tabel 'users' sudah ada dari migrasi bawaan Laravel yang kita edit tadi
            $table->foreignId('user_id')->constrained('users'); 
            
            $table->dateTime('order_date');
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, shipped, done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
