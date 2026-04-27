<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $indexes = [
        'idx_dokumen_hukums_view_count' => ['view_count'],
        'idx_dokumen_hukums_download_count' => ['download_count'],
        'idx_dokumen_hukums_tahun' => ['tahun'],
        'idx_dokumen_hukums_status' => ['status'],
        'idx_dokumen_hukums_status_tahun' => ['status', 'tahun'],
        'idx_dokumen_hukums_ref_bidang_hukum_id' => ['ref_bidang_hukum_id'],
    ];

    public function up(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            foreach ($this->indexes as $name => $columns) {
                if (!$this->indexExists($name) && !$this->indexExistsForColumns($columns)) {
                    $table->index($columns, $name);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            foreach (array_keys($this->indexes) as $name) {
                if ($this->indexExists($name)) {
                    $table->dropIndex($name);
                }
            }
        });
    }

    private function indexExists(string $name): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', 'dokumen_hukums')
            ->where('index_name', $name)
            ->exists();
    }

    private function indexExistsForColumns(array $columns): bool
    {
        $database = DB::getDatabaseName();
        $expected = implode(',', $columns);

        $indexes = DB::table('information_schema.statistics')
            ->select('index_name', DB::raw("GROUP_CONCAT(column_name ORDER BY seq_in_index SEPARATOR ',') as columns"))
            ->where('table_schema', $database)
            ->where('table_name', 'dokumen_hukums')
            ->groupBy('index_name')
            ->pluck('columns');

        return $indexes->contains($expected);
    }
};
