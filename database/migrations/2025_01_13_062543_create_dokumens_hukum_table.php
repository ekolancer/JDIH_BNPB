<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_hukums', function (Blueprint $table) {
            // EXACT MATCH: Charset + collation
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // varchar(50) nullable unique
            $table->string('document_uid', 50)->nullable()->unique();

            // enum nullable
            $table->enum('tipeDok', ['Peraturan', 'Monografi', 'Artikel', 'Putusan'])
                  ->nullable();

            // old legacy field (masih ada di SQL)
            $table->string('jenisDok', 255);

            // foreign key ref
            $table->unsignedBigInteger('ref_jenis_dokumen_id')->nullable();

            // judul TEXT NOT NULL
            $table->text('judul');

            // slug VARCHAR(255) unique
            $table->string('slug', 255)->unique();

            $table->string('teuBadan', 255);

            $table->integer('nomor')->nullable();
            $table->integer('tahun');

            $table->string('singkatan', 255)->default('-');

            $table->string('tempat', 255);
            $table->string('penerbit', 255)->default('-');

            $table->date('tglPenetapan')->nullable();
            $table->date('tglPengundangan')->nullable();
            $table->date('tglDiBacakan')->nullable();

            $table->integer('nomorPanggil')->nullable();

            $table->string('cetakanEdisi', 255)->default('-');
            $table->string('deskripsiFisik', 255)->default('-');

            $table->text('subjek');

            $table->string('sumber', 255)->default('-');
            $table->string('isbn', 255)->default('-');

            $table->enum('status', ['BERLAKU', 'TIDAK BERLAKU'])
                ->nullable();

            $table->string('bahasa', 255);
            $table->string('lokasi', 255);
            $table->string('bidangHukum', 255);

            $table->string('lampiran', 255)->default('-');
            $table->string('abstrak', 255)->nullable();

            $table->integer('nomorIndukBuku')->nullable();

            $table->string('penandatangan', 255)->default('-');

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            // timestamps (NULL default NULL)
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Foreign key (same name)
            $table->foreign('ref_jenis_dokumen_id', 'fk_dokhukum_jenis')
                  ->references('id')
                  ->on('ref_jenis_dokumen');
        });

        // Index sesuai SQL
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->index('ref_jenis_dokumen_id', 'fk_dokhukum_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_hukums');
    }
};
