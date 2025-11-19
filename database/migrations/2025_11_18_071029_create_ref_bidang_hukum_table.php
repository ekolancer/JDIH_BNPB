<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_bidang_hukum', function (Blueprint $table) {

            // Collation sesuai sistem produksi
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // Nama bidang hukum (unik)
            $table->string('nama', 150)->unique();

            // Deskripsi opsional
            $table->text('deskripsi')->nullable();

            // Status aktif/nonaktif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_bidang_hukum');
    }
};
