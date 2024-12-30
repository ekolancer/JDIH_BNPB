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
        $tables = ['peraturans', 'monografs', 'artikels', 'putusans'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('slug')->nullable()->after('judul');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['peraturans', 'monografs', 'artikels', 'putusans'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};