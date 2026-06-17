<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_subjek', function (Blueprint $table) {
            $table->index(['subjek_id', 'dokumen_hukum_id'], 'idx_dokumen_subjek_subjek_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_subjek', function (Blueprint $table) {
            $table->dropIndex('idx_dokumen_subjek_subjek_dokumen');
        });
    }
};
