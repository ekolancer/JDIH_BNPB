<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->dropColumn(['jenisDok', 'singkatan', 'bidangHukum', 'subjek']);
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->string('jenisDok', 255)->nullable()->after('tipeDok');
            $table->string('singkatan', 255)->default('-')->after('tahun');
            $table->string('bidangHukum', 255)->nullable()->after('lokasi');
            $table->text('subjek')->nullable()->after('deskripsiFisik');
        });
    }
};
