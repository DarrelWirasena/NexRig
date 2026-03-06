<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom shipping + user_address_id ke tabel orders.
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Relasi ke alamat yang dipilih saat checkout
            // nullable karena order lama mungkin tidak punya relasi ini
            if (!Schema::hasColumn('orders', 'user_address_id')) {
                $table->foreignId('user_address_id')
                      ->nullable()
                      ->after('user_id')
                      ->constrained('user_addresses')
                      ->nullOnDelete();
            }

            // Snapshot data pengiriman (disalin dari user_addresses saat checkout)
            // Disimpan terpisah agar data tidak berubah jika user edit alamat
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->nullable()->after('user_address_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->nullable()->after('shipping_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_phone');
            }
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->nullable()->after('shipping_city');
            }
            // Koordinat snapshot — untuk live tracking map
            if (!Schema::hasColumn('orders', 'shipping_latitude')) {
                $table->decimal('shipping_latitude', 10, 7)->nullable()->after('shipping_postal_code');
            }
            if (!Schema::hasColumn('orders', 'shipping_longitude')) {
                $table->decimal('shipping_longitude', 10, 7)->nullable()->after('shipping_latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = [
                'user_address_id',
                'shipping_name', 'shipping_phone', 'shipping_address',
                'shipping_city', 'shipping_postal_code',
                'shipping_latitude', 'shipping_longitude',
            ];
            // Drop foreign key dulu sebelum drop kolom
            if (Schema::hasColumn('orders', 'user_address_id')) {
                $table->dropForeign(['user_address_id']);
            }
            $table->dropColumn(array_filter($cols, fn($c) => Schema::hasColumn('orders', $c)));
        });
    }
};