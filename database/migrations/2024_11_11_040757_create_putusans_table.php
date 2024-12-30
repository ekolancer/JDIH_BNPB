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
        Schema::create('putusans', function (Blueprint $table) {
            $table->id();
            $table->string('tipeDok')->default('Putusan Hukum');
            $table->string('jenisDok');
            $table->text('judul');
            $table->string('teuBadan');
            $table->string('nomor');
            $table->string('tahunTerbit');
            $table->string('singkatan');
            $table->string('tempatPenetapan');
            $table->string('penerbit');
            $table->date('tglPenetapan');
            $table->date('tglPengundangan');
            $table->string('subjek');
            $table->string('sumber');
            $table->enum('status', ['BERLAKU','TIDAK BERLAKU']);
            $table->string('bahasa');
            $table->string('lokasi');
            $table->string('bidangHukum');
            $table->string('lampiran');
            $table->string('penandatangan');
            $table->string('dokumenTerkait');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('putusans');
    }
};
