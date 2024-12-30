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
        Schema::table('berita', function (Blueprint $table) {
            // Ubah kolom status menjadi boolean dengan default false
            $table->boolean('status')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            // Kembalikan tipe data ke enum (atau tipe sebelumnya)
            $table->enum('status', ['Draft', 'Publikasi'])->default('Draft')->change();
        });
    }
};
