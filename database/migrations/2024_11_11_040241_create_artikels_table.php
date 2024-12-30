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
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('tipeDok')->default('Artikel Hukum');
            $table->string('jenisDok');
            $table->text('judul');
            $table->string('teuBadan');
            $table->integer('tahunTerbit');
            $table->string('tempatPenetapan');
            $table->string('penerbit');
            $table->date('tglPenetapan');
            $table->date('tglPengundangan');
            $table->string('subjek');
            $table->string('sumber');
            $table->string('bahasa');
            $table->string('lokasi');
            $table->string('bidangHukum');
            $table->string('lampiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
