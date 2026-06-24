<?php

namespace App\Console\Commands;

use App\Models\DokumenHukum;
use App\Models\RefTipeDokumen;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExplainDokumenQueries extends Command
{
    protected $signature = 'optimize:explain-dokumen
        {--target=all : Target query: all, dokumen, atau metadata}
        {--judul= : Kata kunci judul untuk pencarian fulltext}
        {--tahun= : Filter tahun}
        {--tipeDok= : Kode tipe dokumen, contoh: Peraturan}
        {--nomor= : Filter nomor dokumen untuk /dokumen}
        {--sort=latest : Sorting /dokumen: latest atau popular}
        {--sort_by=tahun : Sorting /metadata/api: id, tahun, judul, created_at}
        {--sort_order=desc : Arah sorting /metadata/api: asc atau desc}
        {--per_page=50 : Jumlah data per halaman metadata}';

    protected $description = 'Jalankan EXPLAIN untuk query utama /dokumen dan /metadata/api';

    private bool $hasExplainFailure = false;

    public function handle(): int
    {
        $target = $this->option('target');

        if (! in_array($target, ['all', 'dokumen', 'metadata'], true)) {
            $this->error('Target harus salah satu dari: all, dokumen, metadata.');

            return Command::FAILURE;
        }

        if (in_array($target, ['all', 'dokumen'], true)) {
            $this->explain('/dokumen - list query', $this->dokumenQuery());
            $this->newLine();
            $this->explain('/dokumen - count query', $this->countQuery($this->dokumenQuery()));

            foreach ($this->dokumenFilterCountQueries() as $label => $query) {
                $this->newLine();
                $this->explain($label, $query);
            }
        }

        if (in_array($target, ['all', 'metadata'], true)) {
            if ($target === 'all') {
                $this->newLine();
            }

            $this->explain('/metadata/api - list query', $this->metadataQuery());
            $this->newLine();
            $this->explain('/metadata/api - count query', $this->countQuery($this->metadataQuery()));
        }

        return $this->hasExplainFailure ? Command::FAILURE : Command::SUCCESS;
    }

    private function dokumenQuery(): Builder
    {
        return DokumenHukum::query()
            ->with(['jenisDokumen', 'bidangHukumRef', 'subjekRef'])
            ->applyFilters($this->dokumenFilters())
            ->applySorting((string) $this->option('sort'))
            ->limit(10);
    }

    private function dokumenFilterCountQueries(): array
    {
        $baseQuery = DokumenHukum::query()
            ->applyFilters($this->dokumenFilters());

        $matchingDocumentIds = fn () => (clone $baseQuery)
            ->select('dokumen_hukums.id');

        return [
            '/dokumen - jenis filter count query' => DB::table('dokumen_hukums')
                ->select('ref_jenis_dokumen_id', DB::raw('COUNT(*) as total'))
                ->whereIn('id', $matchingDocumentIds())
                ->groupBy('ref_jenis_dokumen_id'),

            '/dokumen - bidang filter count query' => DB::table('dokumen_hukums')
                ->select('ref_bidang_hukum_id', DB::raw('COUNT(*) as total'))
                ->whereIn('id', $matchingDocumentIds())
                ->groupBy('ref_bidang_hukum_id'),

            '/dokumen - subjek filter count query' => DB::table('dokumen_subjek')
                ->select('subjek_id', DB::raw('COUNT(*) as total'))
                ->whereIn('dokumen_hukum_id', $matchingDocumentIds())
                ->groupBy('subjek_id'),

            '/dokumen - status filter count query' => DB::table('dokumen_hukums')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->whereIn('id', $matchingDocumentIds())
                ->whereNotNull('status')
                ->groupBy('status'),
        ];
    }

    private function metadataQuery(): Builder
    {
        $query = DokumenHukum::query()
            ->with([
                'jenisDokumen:id,nama,singkatan',
                'bidangHukumRef:id,nama',
                'subjekRef:id,nama',
            ]);

        if ($this->option('tipeDok')) {
            $query->whereHas(
                'tipeDokumen',
                fn ($q) => $q->where('kode', $this->option('tipeDok'))
            );
        }

        if ($this->option('tahun')) {
            $query->where('tahun', $this->option('tahun'));
        }

        if ($this->option('judul')) {
            $query->whereFullText('judul', $this->option('judul'));
        }

        $sortBy = $this->safeSortBy((string) $this->option('sort_by'));
        $sortOrder = $this->safeSortOrder((string) $this->option('sort_order'));
        $perPage = min((int) $this->option('per_page'), 100);

        return $query
            ->orderBy($sortBy, $sortOrder)
            ->limit($perPage);
    }

    private function dokumenFilters(): array
    {
        return [
            'judul' => $this->option('judul') ?: '',
            'tahun' => $this->option('tahun') ?: null,
            'tipe_dokumen_id' => $this->resolveTipeDokumenId($this->option('tipeDok')),
            'ref_jenis_dokumen_id' => [],
            'ref_bidang_hukum_id' => [],
            'subjek' => [],
            'status' => [],
            'nomor' => $this->option('nomor') ?: '',
        ];
    }

    private function countQuery(Builder $query): Builder
    {
        return (clone $query)
            ->reorder()
            ->limit(null)
            ->selectRaw('count(*) as aggregate');
    }

    private function explain(string $label, Builder|QueryBuilder $query): void
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->line("<info>{$label}</info>");
        $this->line($sql);

        try {
            $rows = DB::select('EXPLAIN '.$sql, $bindings);
        } catch (\Throwable $e) {
            $this->hasExplainFailure = true;
            $this->error('EXPLAIN gagal: '.$e->getMessage());

            return;
        }

        if (empty($rows)) {
            $this->warn('EXPLAIN tidak mengembalikan baris.');

            return;
        }

        $this->table(
            array_keys((array) $rows[0]),
            array_map(fn ($row) => (array) $row, $rows)
        );
    }

    private function resolveTipeDokumenId($value): ?int
    {
        if (! filled($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        try {
            return RefTipeDokumen::query()
                ->where('kode', $value)
                ->orWhereRaw('LOWER(kode) = ?', [Str::lower($value)])
                ->value('id');
        } catch (\Throwable $e) {
            $this->hasExplainFailure = true;
            $this->error('Gagal resolve tipeDok: '.$e->getMessage());

            return null;
        }
    }

    private function safeSortBy(string $sortBy): string
    {
        return in_array($sortBy, ['id', 'tahun', 'judul', 'created_at'], true)
            ? $sortBy
            : 'tahun';
    }

    private function safeSortOrder(string $sortOrder): string
    {
        return in_array($sortOrder, ['asc', 'desc'], true)
            ? $sortOrder
            : 'desc';
    }
}
