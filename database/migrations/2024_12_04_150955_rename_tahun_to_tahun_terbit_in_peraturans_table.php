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
        Schema::table('peraturans', function (Blueprint $table) {
            $table->renameColumn('tahun', 'tahunTerbit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('peraturans', function (Blueprint $table) {
            $table->renameColumn('tahunTerbit', 'tahun');
        });
    }
};
