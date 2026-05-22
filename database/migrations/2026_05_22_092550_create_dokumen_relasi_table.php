<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_relasi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dokumen_id');
            $table->unsignedBigInteger('related_dokumen_id');

            $table->enum('jenis_relasi', [
                'mencabut',
                'dicabut_oleh',
                'mengubah',
                'diubah_oleh',
                'melaksanakan',
                'dilaksanakan_oleh',
                'melengkapi',
                'dilengkapi_oleh',
                'dasar_hukum',
                'menjadi_dasar',
                'terkait',
            ]);

            $table->text('catatan')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('dokumen_id')
                ->references('id')
                ->on('dokumen_hukums')
                ->onDelete('cascade');

            $table->foreign('related_dokumen_id')
                ->references('id')
                ->on('dokumen_hukums')
                ->onDelete('cascade');

            // Prevent duplicate relations
            $table->unique(['dokumen_id', 'related_dokumen_id', 'jenis_relasi'], 'dokumen_relasi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_relasi');
    }
};
