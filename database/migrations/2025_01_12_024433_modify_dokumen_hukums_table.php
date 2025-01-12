<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            // Hapus primary key yang ada (jika ada)
            $table->dropPrimary();

            // Ubah tipe data 'id' menjadi string (misalnya, VARCHAR atau CHAR)
            $table->string('id', 36)->primary()->change(); // Sesuaikan panjang string sesuai kebutuhan

            // Mengubah tipe data 'tipeDok' menjadi ENUM
            $table->enum('tipeDok', ['Peraturan', 'Monografi', 'Artikel', 'Putusan'])->change();
        });
    }

    public function down()
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            // Kembalikan kolom 'id' ke tipe integer dengan auto increment
            $table->integer('id')->autoIncrement()->primary()->change();

            // Kembalikan tipe data 'tipeDok' ke string atau tipe sebelumnya
            $table->string('tipeDok')->change();
        });
    }
};
