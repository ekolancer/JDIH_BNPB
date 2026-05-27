<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel-tabel legacy/orphan yang sudah tidak direferensikan di codebase.
     */
    private array $tables = [
        'peraturans',
        'putusans',
        'monografs',
        'artikels',
        'imports',
        'pulse_aggregates',
        'pulse_entries',
        'pulse_values',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // Tabel-tabel ini adalah legacy tanpa struktur yang terdokumentasi.
        // Rollback tidak akan membuat ulang tabel — restore dari backup jika diperlukan.
    }
};
