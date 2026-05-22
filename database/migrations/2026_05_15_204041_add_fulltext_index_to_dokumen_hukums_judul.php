<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FULLTEXT index pada kolom judul untuk pencarian yang lebih cepat
     * dibanding LIKE '%keyword%' yang tidak bisa memanfaatkan indeks B-Tree.
     */
    public function up(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->fullText('judul', 'ft_dokumen_judul');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->dropFullText('ft_dokumen_judul');
        });
    }
};
