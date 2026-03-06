<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom wilayah Indonesia (province, district, village)
     * + koordinat (latitude, longitude) ke tabel user_addresses.
     *
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {

            // ── Wilayah ───────────────────────────────────────
            // province — setelah city (sudah ada di migration sebelumnya, skip jika sudah ada)
            if (!Schema::hasColumn('user_addresses', 'province')) {
                $table->string('province')->nullable()->after('city');
            }

            // district (kecamatan) — setelah province
            if (!Schema::hasColumn('user_addresses', 'district')) {
                $table->string('district')->nullable()->after('province');
            }

            // village (kelurahan/desa) — setelah district
            if (!Schema::hasColumn('user_addresses', 'village')) {
                $table->string('village')->nullable()->after('district');
            }

            // ── Koordinat untuk live tracking map ────────────
            if (!Schema::hasColumn('user_addresses', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('full_address');
            }
            if (!Schema::hasColumn('user_addresses', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('user_addresses', 'province')  ? 'province'  : null,
                Schema::hasColumn('user_addresses', 'district')  ? 'district'  : null,
                Schema::hasColumn('user_addresses', 'village')   ? 'village'   : null,
                Schema::hasColumn('user_addresses', 'latitude')  ? 'latitude'  : null,
                Schema::hasColumn('user_addresses', 'longitude') ? 'longitude' : null,
            ]));
        });
    }
};