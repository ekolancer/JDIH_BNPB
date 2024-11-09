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
        Schema::create('peraturans', function (Blueprint $table) {
            $table->id();
            $table->string('tipeDok')->default('Peraturan Perundang - Undangan');
            $table->string('jenisDok');
            $table->string('singkatan');
            $table->text('judul');
            $table->string('teuBadan');
            $table->integer('nomor');
            $table->integer('tahun');
            $table->string('tempatPenetapan');
            $table->date('tglPenetapan');
            $table->date('tglPengundangan');
            $table->string('sumber');
            $table->text('subjek');
            $table->string('bidangHukum');
            $table->string('bahasa');
            $table->enum('status', ['BERLAKU','TIDAK BERLAKU']);
            $table->string('lokasi');
            $table->string('penandatangan');
            $table->string('lampiran');
            $table->string('urusanPemerintahan')->nullable()->default('-');
            $table->string('pemrakarsa')->nullable()->default('-');
            $table->string('peraturanTerkait')->nullable()->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturans');
    }
};
