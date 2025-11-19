<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ref_jenis_dokumen', function (Blueprint $table) {
            // Charset & Collation mengikuti SQL manual
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // Sesuai SQL manual (VARCHAR 150)
            $table->string('nama', 150)->unique();

            // Sesuai SQL manual (VARCHAR 50)
            $table->string('singkatan', 50)->unique();

            $table->text('deskripsi')->nullable();

            // BOOLEAN → TINYINT(1)
            $table->tinyInteger('is_active')->default(1);

            // TIMESTAMP DEFAULT CURRENT_TIMESTAMP (sesuai SQL)
            $table->timestamp('created_at')->nullable()->useCurrent();

            // TIMESTAMP ON UPDATE CURRENT_TIMESTAMP (sesuai SQL)
            $table->timestamp('updated_at')->nullable()
                ->useCurrent()->useCurrentOnUpdate();
        });

        // Index tambahan (sama seperti SQL UNIQUE)
        Schema::table('ref_jenis_dokumen', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_jenis_dokumen');
    }
};
