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
        Schema::create('dokumen_hukums', function (Blueprint $table) {
            $table->id();
            $table->enum('tipeDok', ['Peraturan', 'Monografi', 'Artikel', 'Putusan']); // 'peraturan', 'monografi', 'artikel', 'putusan'.
            $table->string('jenisDok'); // 'peraturan', 'monografi', 'artikel', 'putusan'.
            $table->text('judul'); // untuk seluruh dokumen.
            $table->string('slug')->unique(); // Kolom slug untuk URL-friendly
            $table->string('teuBadan'); // untuk seluruh dokumen.
            $table->integer('nomor')->nullable(); // Untuk peraturan dan putusan
            $table->integer('tahun'); // untuk seluruh dokumen.
            $table->string('singkatan')->default('-'); // Untuk peraturan dan putusan
            $table->string('tempat'); // untuk seluruh dokumen.
            $table->string('penerbit')->default('-'); // untuk monografi dan artikel.
            $table->date('tglPenetapan')->nullable(); // Untuk peraturan
            $table->date('tglPengundangan')->nullable(); // Untuk peraturan dan putusan
            $table->date('tglDiBacakan')->nullable(); // Untuk putusan
            $table->integer('nomorPanggil')->nullable(); // Untuk monografi
            $table->string('cetakanEdisi')->default('-'); // Untuk monografi
            $table->string('deskripsiFisik')->default('-'); // Untuk monografi
            $table->text('subjek');  // untuk seluruh dokumen.
            $table->string('sumber')->default('-'); // Untuk peraturan dan putusan
            $table->string('isbn')->default('-'); // Untuk monografi
            $table->enum('status', ['BERLAKU', 'TIDAK BERLAKU'])->nullable(); // Untuk peraturan
            $table->string('bahasa'); // untuk seluruh dokumen.
            $table->string('lokasi'); // untuk seluruh dokumen.
            $table->string('bidangHukum'); // untuk seluruh dokumen.
            $table->string('lampiran')->default('-'); // Untuk file peraturan dan putusan
            $table->string('abstrak')->nullable(); // Untuk peraturan
            $table->integer('nomorIndukBuku')->nullable(); // Untuk monografi
            $table->string('penandatangan')->default('-'); // untuk seluruh dokumen.
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumenHukum');
    }
};
