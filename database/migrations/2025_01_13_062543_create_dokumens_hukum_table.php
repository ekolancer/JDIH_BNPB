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

            // UID dokumen (PERKA/BNPB/01/2024)
            $table->string('document_uid', 100)->nullable()->unique();

            // Tipe dokumen utama
            $table->enum('tipeDok', ['Peraturan', 'Monografi', 'Artikel', 'Putusan']);

            // Relasi jenis dokumen (master baru)
            $table->foreignId('ref_jenis_dokumen_id')
                ->nullable()
                ->constrained('ref_jenis_dokumen')
                ->nullOnDelete();

            // Informasi umum dokumen
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('teuBadan')->nullable();

            // Identitas dokumen
            $table->string('nomor')->nullable();
            $table->string('tahun', 4);
            $table->string('tempat')->nullable();
            $table->string('penerbit')->nullable();

            // Tanggal penting
            $table->date('tglPenetapan')->nullable();
            $table->date('tglPengundangan')->nullable();
            $table->date('tglDiBacakan')->nullable();

            // Bidang lain
            $table->string('nomorPanggil')->nullable();
            $table->string('cetakanEdisi')->nullable();
            $table->string('deskripsiFisik')->nullable();
            $table->text('subjek')->nullable();
            $table->string('sumber')->nullable();
            $table->string('isbn')->nullable();
            $table->string('status')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('bidangHukum')->nullable();

            // File
            $table->string('lampiran')->nullable();
            $table->string('abstrak')->nullable();

            // Monografi
            $table->string('nomorIndukBuku')->nullable();

            // Penandatangan
            $table->string('penandatangan')->nullable();

            // Statistik
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            $table->timestamps();
        });

        /**
         * Index tambahan agar cepat pencarian
         */
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->index('tipeDok');
            $table->index('ref_jenis_dokumen_id');
            $table->index('nomor');
            $table->index('tahun');
            $table->index('slug');
            $table->index('document_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_hukums');
    }
};
