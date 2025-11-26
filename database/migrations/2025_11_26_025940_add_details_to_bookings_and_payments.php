<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Cek dulu: Kalau kolom ktp_foto BELUM ada, baru buat
            if (!Schema::hasColumn('bookings', 'ktp_foto')) {
                $table->string('ktp_foto')->nullable()->after('durasi_sewa');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            // Cek dulu: Kalau kolom keterangan_bulan BELUM ada, baru buat
            if (!Schema::hasColumn('payments', 'keterangan_bulan')) {
                $table->string('keterangan_bulan')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'ktp_foto')) {
                $table->dropColumn('ktp_foto');
            }
        });
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'keterangan_bulan')) {
                $table->dropColumn('keterangan_bulan');
            }
        });
    }
};
