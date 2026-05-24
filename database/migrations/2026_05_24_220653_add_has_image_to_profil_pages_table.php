<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profil_pages', function (Blueprint $table) {
            $table->boolean('has_image')->default(false)->after('content');
        });

        // Set has_image = true untuk record yang sudah memiliki gambar
        DB::table('profil_pages')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->update(['has_image' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_pages', function (Blueprint $table) {
            $table->dropColumn('has_image');
        });
    }
};
