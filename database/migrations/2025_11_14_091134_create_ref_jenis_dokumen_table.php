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
        Schema::create('ref_jenis_dokumen', function (Blueprint $table) {
            $table->id();

            // Nama jenis dokumen (misal: Peraturan Badan, Peraturan Kepala)
            $table->string('nama')->unique();

            // Singkatan (misal: PERBAN, PERKA, JUKLAK)
            $table->string('singkatan', 50)->unique();

            // Deskripsi opsional
            $table->text('deskripsi')->nullable();

            // Status aktif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Optional indexing
        Schema::table('ref_jenis_dokumen', function (Blueprint $table) {
            $table->index('nama');
            $table->index('singkatan');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_jenis_dokumen');
    }
};