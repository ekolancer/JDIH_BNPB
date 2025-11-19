<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {

            $table->unsignedBigInteger('ref_bidang_hukum_id')
                ->nullable()
                ->after('lokasi');

            $table->foreign('ref_bidang_hukum_id')
                ->references('id')
                ->on('ref_bidang_hukum')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->dropForeign(['ref_bidang_hukum_id']);
            $table->dropColumn('ref_bidang_hukum_id');
        });
    }
};
