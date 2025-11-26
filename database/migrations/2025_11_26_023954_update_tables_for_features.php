<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom KTP di tabel bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('ktp_foto')->nullable()->after('durasi_sewa');
        });

        // 2. Tambah kolom keterangan bulan di tabel payments
        Schema::table('payments', function (Blueprint $table) {
            $table->string('keterangan_bulan')->nullable()->after('status'); // Misal: "Maret 2025"
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('ktp_foto');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('keterangan_bulan');
        });
    }
};
