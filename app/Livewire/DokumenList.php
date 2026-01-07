<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Arr;
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
        'tipeDok' => '',
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
        'judul'                => 'title',
        'tahun'                => 'year',
        'tipeDok'              => 'type',
        'ref_jenis_dokumen_id' => 'jenis',
        'ref_bidang_hukum_id'  => 'bidang',
        'subjek'               => 'subject',
        'status'               => 'status',
        'nomor'                => 'number',
    ];

    public array $availableFilters = [];
    public int $numResults = 0;
    public float $queryTime = 0;
    public string $sortBy = 'latest';

    /* ==========================================================
     *  MOUNT
     * ========================================================*/
    public function mount()
    {
        $query = $this->mapAliasToFilters(request()->query());

        $this->filters = array_merge($this->filters, $query);
        $this->sortBy  = request()->query('sortBy', 'latest');

        foreach (['ref_jenis_dokumen_id', 'ref_bidang_hukum_id', 'subjek', 'status'] as $key) {
            if (!is_array($this->filters[$key])) {
                $this->filters[$key] = array_filter(
                    explode(',', (string) $this->filters[$key])
                );
            }
        }

        if (!is_numeric($this->filters['tahun'])) {
            $this->filters['tahun'] = null;
        }

        $this->loadAvailableFilters();
    }

    /* ==========================================================
     *  URL ALIAS MAPPER
     * ========================================================*/
    private function mapAliasToFilters(array $query): array
    {
        $reverse = array_flip($this->filterAliases);

        foreach ($query as $key => $value) {
            if (isset($reverse[$key])) {
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
            'dokumen.available_filters.v3',
            now()->addMinutes(30),
            fn() => [
                'jenis_dokumen' => RefJenisDokumen::where('is_active', true)
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'singkatan']),

                'bidang_hukum' => RefBidangHukum::where('is_active', true)
                    ->orderBy('nama')
                    ->get(['id', 'nama']),

                'subjek' => RefSubjek::where('is_active', true)
                    ->orderBy('nama')
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
                        ? implode(',', $value)
                        : $value
                ];
            })
            ->toArray();

        $filtered['sortBy'] = $this->sortBy;

        $filtered['page']   = 1;

        return redirect()->to('/dokumen?' . Arr::query($filtered));
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

    private function loadFilterCounts(): array
    {
        return Cache::remember('dokumen.filter_counts.v1', now()->addMinutes(30), function () {
            return [
                'jenis_dokumen' => DB::table('dokumen_hukums')
                    ->select('ref_jenis_dokumen_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('ref_jenis_dokumen_id')
                    ->pluck('total', 'ref_jenis_dokumen_id'),

                'bidang_hukum' => DB::table('dokumen_hukums')
                    ->select('ref_bidang_hukum_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('ref_bidang_hukum_id')
                    ->pluck('total', 'ref_bidang_hukum_id'),

                'subjek' => DB::table('dokumen_subjek')
                    ->select('subjek_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('subjek_id')
                    ->pluck('total', 'subjek_id'),
            ];
        });
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

        $this->numResults = $results->total();

        $this->queryTime = round(microtime(true) - $start, 3);

        $filterCounts = $this->loadFilterCounts();

        return view('livewire.dokumen-list', [
            'results'          => $results,
            'numResults'       => $this->numResults,
            'queryTime'        => $this->queryTime,
            'availableFilters' => $this->availableFilters,
            'filterCounts'     => $filterCounts,
            'sortBy'           => $this->sortBy,
        ]);
    }
}
