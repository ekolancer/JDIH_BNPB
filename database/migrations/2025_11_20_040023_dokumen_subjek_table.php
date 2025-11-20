<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_subjek', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dokumen_hukum_id')
                ->constrained('dokumen_hukums')
                ->cascadeOnDelete();

            $table->foreignId('subjek_id')
                ->constrained('ref_subjek')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['dokumen_hukum_id', 'subjek_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_subjek');
    }
};
