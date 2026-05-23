<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel ref_tipe_dokumen
        Schema::create('ref_tipe_dokumen', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        // 2. Seed 4 tipe dokumen default (sesuai enum lama)
        DB::table('ref_tipe_dokumen')->insert([
            ['kode' => 'Peraturan', 'nama' => 'Peraturan Perundang-undangan', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'Monografi', 'nama' => 'Monografi Hukum', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'Artikel',   'nama' => 'Artikel Hukum', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'Putusan',   'nama' => 'Putusan Pengadilan', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Tambah FK tipe_dokumen_id di ref_jenis_dokumen
        Schema::table('ref_jenis_dokumen', function (Blueprint $table) {
            $table->unsignedBigInteger('tipe_dokumen_id')->nullable()->after('id');
            $table->foreign('tipe_dokumen_id')->references('id')->on('ref_tipe_dokumen')->nullOnDelete();
            $table->index('tipe_dokumen_id');
        });

        // 4. Tambah FK tipe_dokumen_id di dokumen_hukums
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->unsignedBigInteger('tipe_dokumen_id')->nullable()->after('tipeDok');
            $table->foreign('tipe_dokumen_id')->references('id')->on('ref_tipe_dokumen')->nullOnDelete();
            $table->index('tipe_dokumen_id');
        });

        // 5. Migrasi data: isi tipe_dokumen_id berdasarkan enum tipeDok lama
        $mapping = DB::table('ref_tipe_dokumen')->pluck('id', 'kode');
        foreach ($mapping as $kode => $id) {
            DB::table('dokumen_hukums')
                ->where('tipeDok', $kode)
                ->update(['tipe_dokumen_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->dropForeign(['tipe_dokumen_id']);
            $table->dropColumn('tipe_dokumen_id');
        });

        Schema::table('ref_jenis_dokumen', function (Blueprint $table) {
            $table->dropForeign(['tipe_dokumen_id']);
            $table->dropColumn('tipe_dokumen_id');
        });

        Schema::dropIfExists('ref_tipe_dokumen');
    }
};
