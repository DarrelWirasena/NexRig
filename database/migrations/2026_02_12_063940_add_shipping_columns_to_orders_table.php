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
    Schema::table('orders', function (Blueprint $table) {
        // Kita buat kolom nullable dulu untuk jaga-jaga
        $table->string('shipping_name')->nullable()->after('status');
        $table->string('shipping_phone')->nullable()->after('shipping_name');
        $table->text('shipping_address')->nullable()->after('shipping_phone');
        $table->string('shipping_city')->nullable()->after('shipping_address');
        $table->string('shipping_postal_code')->nullable()->after('shipping_city');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'shipping_name', 'shipping_phone', 'shipping_address', 
            'shipping_city', 'shipping_postal_code'
        ]);
    });
}
};
