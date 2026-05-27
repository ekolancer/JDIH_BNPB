<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;
use App\Models\RefBidangHukum;
use App\Models\RefSubjek;

#[Layout('app-clear')]
class DokumenList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /* ==========================================================
     *  FILTER STATE (INTERNAL)
     * ========================================================*/
    public array $filters = [
        'judul' => '',
        'tahun' => null,
        'tipe_dokumen_id' => null,
        'ref_jenis_dokumen_id' => [],
        'ref_bidang_hukum_id' => [],
        'subjek' => [],
        'status' => [],
        'nomor' => '',
    ];

    /* ==========================================================
     *  URL ALIAS (PUBLIC CONTRACT)
     * ========================================================*/
    protected array $filterAliases = [
        'judul'                => 'judul',
        'tahun'                => 'tahun',
        'tipe_dokumen_id'      => 'type',
        'ref_jenis_dokumen_id' => 'bentuk',
        'ref_bidang_hukum_id'  => 'bidang',
        'subjek'               => 'subjek',
        'status'               => 'status',
        'nomor'                => 'no',
    ];

    public array $availableFilters = [];
    public int $numResults = 0;
    public float $queryTime = 0;
    public string $sortBy = 'latest';

    /* ==========================================================
     *  MOUNT
     * ========================================================*/
    public function mount(?string $tipeDok = null)
    {
        $this->loadAvailableFilters();

        $query = $this->mapAliasToFilters(request()->query());
        $query['tipe_dokumen_id'] = $query['tipe_dokumen_id'] ?? $tipeDok;

        $this->filters = array_merge($this->filters, $query);
        $this->sortBy  = request()->query('sort', request()->query('sortBy', 'latest'));

        foreach (['ref_jenis_dokumen_id', 'ref_bidang_hukum_id', 'subjek', 'status'] as $key) {
            if (!is_array($this->filters[$key])) {
                $this->filters[$key] = array_filter(
                    explode(',', (string) $this->filters[$key])
                );
            }
        }

        $this->normalizeSlugFiltersToIds();
        $this->filters['tipe_dokumen_id'] = $this->resolveTipeDokumenId($this->filters['tipe_dokumen_id']);

        if (!is_numeric($this->filters['tahun'])) {
            $this->filters['tahun'] = null;
        }
    }

    /* ==========================================================
     *  URL ALIAS MAPPER
     * ========================================================*/
    private function mapAliasToFilters(array $query): array
    {
        $reverse = array_flip($this->filterAliases);

        foreach ($query as $key => $value) {
            if (isset($reverse[$key]) && $reverse[$key] !== $key) {
                $query[$reverse[$key]] = $value;
                unset($query[$key]);
            }
        }

        return $query;
    }

    /* ==========================================================
     *  AVAILABLE FILTERS (REF TABLE)
     * ========================================================*/
    private function loadAvailableFilters(): void
    {
        $this->availableFilters = Cache::remember(
            'dokumen.available_filters.v5',
            now()->addMinutes(30),
            fn() => [
                'jenis_dokumen' => RefJenisDokumen::where('is_active', true)
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'singkatan']),

                'bidang_hukum' => RefBidangHukum::where('is_active', true)
                    ->orderBy('nama')
                    ->get(['id', 'nama']),

                'subjek' => RefSubjek::orderBy('nama')
                    ->get(['id', 'nama']),

                'status' => DokumenHukum::select('status')
                    ->distinct()
                    ->whereNotNull('status')
                    ->pluck('status')
                    ->values()
                    ->toArray(),
            ]
        );
    }

    private function normalizeSlugFiltersToIds(): void
    {
        $this->filters['ref_jenis_dokumen_id'] = $this->resolveReferenceIds(
            $this->filters['ref_jenis_dokumen_id'],
            'jenis_dokumen'
        );

        $this->filters['ref_bidang_hukum_id'] = $this->resolveReferenceIds(
            $this->filters['ref_bidang_hukum_id'],
            'bidang_hukum'
        );

        $this->filters['subjek'] = $this->resolveReferenceIds(
            $this->filters['subjek'],
            'subjek'
        );

        $this->filters['status'] = $this->resolveStatuses($this->filters['status']);
    }

    private function resolveReferenceIds(array $values, string $filterKey): array
    {
        $options = collect($this->availableFilters[$filterKey] ?? []);

        return collect($values)
            ->map(function ($value) use ($options, $filterKey) {
                if (is_numeric($value)) {
                    return (int) $value;
                }

                return $options
                    ->first(fn($option) => $this->referenceSlug($option, $filterKey) === $value)
                    ?->id;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function resolveStatuses(array $values): array
    {
        $statuses = collect($this->availableFilters['status'] ?? []);

        return collect($values)
            ->map(function ($value) use ($statuses) {
                return $statuses->first(fn($status) => Str::slug($status) === $value) ?? $value;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function resolveTipeDokumenId($value): ?int
    {
        if (!filled($value)) {
            return null;
        }

        // Already an ID
        if (is_numeric($value)) {
            return (int) $value;
        }

        // Resolve slug/kode to ID
        return \App\Models\RefTipeDokumen::query()
            ->where('kode', $value)
            ->orWhereRaw('LOWER(kode) = ?', [Str::lower($value)])
            ->value('id');
    }

    /* ==========================================================
     *  FILTER ACTIONS
     * ========================================================*/
    public function applyFilters()
    {
        $filtered = collect($this->filters)
            ->filter(fn($v) => is_array($v) ? count($v) : filled($v))
            ->mapWithKeys(function ($value, $key) {
                $alias = $this->filterAliases[$key] ?? $key;

                return [
                    $alias => is_array($value)
                        ? implode(',', $this->toUrlFilterValues($key, $value))
                        : $this->toUrlFilterValue($key, $value)
                ];
            })
            ->toArray();

        $filtered['sort'] = $this->sortBy;

        $filtered['page']   = 1;

        return redirect()->to('/dokumen?' . Arr::query($filtered));
    }

    private function toUrlFilterValues(string $key, array $values): array
    {
        return collect($values)
            ->map(fn($value) => $this->toUrlFilterValue($key, $value))
            ->filter()
            ->values()
            ->all();
    }

    private function toUrlFilterValue(string $key, $value): string
    {
        return match ($key) {
            'tipe_dokumen_id' => $this->urlSlugForTipeDokumen($value),
            'ref_jenis_dokumen_id' => $this->urlSlugById('jenis_dokumen', $value),
            'ref_bidang_hukum_id' => $this->urlSlugById('bidang_hukum', $value),
            'subjek' => $this->urlSlugById('subjek', $value),
            'status' => Str::slug((string) $value),
            default => (string) $value,
        };
    }

    private function urlSlugForTipeDokumen($id): string
    {
        if (!$id) return '';
        $tipe = \App\Models\RefTipeDokumen::find($id);
        return $tipe ? Str::slug($tipe->kode) : '';
    }

    private function urlSlugById(string $filterKey, $id): string
    {
        $option = collect($this->availableFilters[$filterKey] ?? [])
            ->firstWhere('id', (int) $id);

        return $option ? $this->referenceSlug($option, $filterKey) : '';
    }

    private function referenceSlug($option, string $filterKey): string
    {
        $source = $filterKey === 'jenis_dokumen'
            ? ($option->singkatan ?: $option->nama)
            : $option->nama;

        return Str::slug((string) $source);
    }

    public function resetFilters()
    {
        return redirect()->to('/dokumen');
    }

    public function updateSortBy(string $sortBy)
    {
        $this->sortBy = $sortBy;
        $this->applyFilters();
    }

    public function removeFilter(string $key, $value = null)
    {
        if (is_array($this->filters[$key])) {
            if ($value !== null) {
                // hapus satu nilai dari array
                $this->filters[$key] = array_values(
                    array_diff($this->filters[$key], [$value])
                );
            } else {
                // hapus semua
                $this->filters[$key] = [];
            }
        } else {
            // scalar
            $this->filters[$key] = null;
        }

        $this->resetPage();

        // jika masih pakai redirect
        if (method_exists($this, 'applyFilters')) {
            $this->applyFilters();
        }
    }

    //==========================================================
    // FILTER COUNTS (DYNAMIC)
    //==========================================================
    private function loadFilterCounts(): array
    {
        // Cache berbasis filter aktif
        $hash = md5(json_encode([
            'jenis'  => $this->filters['ref_jenis_dokumen_id'] ?? [],
            'bidang' => $this->filters['ref_bidang_hukum_id'] ?? [],
            'tahun'  => $this->filters['tahun'] ?? null,
            'nomor'  => $this->filters['nomor'] ?? null,
            'judul'  => $this->filters['judul'] ?? null,
            'status' => $this->filters['status'] ?? [],
            'subjek' => $this->filters['subjek'] ?? [],
        ]));

        return Cache::remember(
            "dokumen.filter_counts.$hash",
            now()->addMinutes(10),
            function () {

                // 1️⃣ Query dasar yang SUDAH TERFILTER
                $baseQuery = DokumenHukum::query()
                    ->applyFilters($this->filters);

                $matchingDocumentIds = fn() => (clone $baseQuery)
                    ->select('dokumen_hukums.id');

                return [

                    // ===============================
                    // JENIS DOKUMEN
                    // ===============================
                    'jenis_dokumen' => DB::table('dokumen_hukums')
                        ->select('ref_jenis_dokumen_id', DB::raw('COUNT(*) as total'))
                        ->whereIn('id', $matchingDocumentIds())
                        ->groupBy('ref_jenis_dokumen_id')
                        ->pluck('total', 'ref_jenis_dokumen_id'),

                    // ===============================
                    // BIDANG HUKUM
                    // ===============================
                    'bidang_hukum' => DB::table('dokumen_hukums')
                        ->select('ref_bidang_hukum_id', DB::raw('COUNT(*) as total'))
                        ->whereIn('id', $matchingDocumentIds())
                        ->groupBy('ref_bidang_hukum_id')
                        ->pluck('total', 'ref_bidang_hukum_id'),

                    // ===============================
                    // SUBJEK (INI KUNCI)
                    // ===============================
                    'subjek' => DB::table('dokumen_subjek')
                        ->select('subjek_id', DB::raw('COUNT(*) as total'))
                        ->whereIn('dokumen_hukum_id', $matchingDocumentIds())
                        ->groupBy('subjek_id')
                        ->pluck('total', 'subjek_id'),

                    // ===============================
                    // STATUS
                    // ===============================
                    'status' => DB::table('dokumen_hukums')
                        ->select('status', DB::raw('COUNT(*) as total'))
                        ->whereIn('id', $matchingDocumentIds())
                        ->whereNotNull('status')
                        ->groupBy('status')
                        ->pluck('total', 'status'),

                ];
            }
        );
    }

    /* ==========================================================
     *  RENDER
     * ========================================================*/
    public function render()
    {
        $start = microtime(true);

        $query = DokumenHukum::query()
            ->with(['jenisDokumen', 'bidangHukumRef', 'subjekRef'])
            ->applyFilters($this->filters)
            ->applySorting($this->sortBy);

        $results = $query->paginate(10);

        // Bangun query params untuk pagination (pertahankan filter aktif)
        $paginationParams = collect($this->filters)
            ->filter(fn($v) => is_array($v) ? count($v) : filled($v))
            ->mapWithKeys(function ($value, $key) {
                $alias = $this->filterAliases[$key] ?? $key;
                return [
                    $alias => is_array($value)
                        ? implode(',', $this->toUrlFilterValues($key, $value))
                        : $this->toUrlFilterValue($key, $value),
                ];
            })
            ->toArray();

        $paginationParams['sort'] = $this->sortBy;

        $results->withPath('/dokumen');
        $results->appends($paginationParams);

        $this->numResults = $results->total();

        $this->queryTime = round(microtime(true) - $start, 3);

        $filterCounts = $this->loadFilterCounts();

        return view('livewire.dokumen-list', [
            'results'          => $results,
            'numResults'       => $this->numResults,
            'queryTime'        => $this->queryTime,
            'availableFilters' => $this->availableFilters,
            'filterCounts'     => $filterCounts,
            'sort'           => $this->sortBy,
        ]);
    }
}
