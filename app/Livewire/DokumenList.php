<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;
use App\Models\RefBidangHukum;
use App\Models\RefSubjek;

#[Layout('app-clear')]
class DokumenList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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

    public array $availableFilters = [];
    public int $numResults = 0;
    public float $queryTime = 0;
    public string $sortBy = 'latest';

    protected $updatesQueryString = [
        'filters' => ['except' => []],
        'sortBy' => ['except' => 'latest'],
    ];

    /* ==========================================================
     *  MOUNT
     * ========================================================*/
    public function mount($filters = [], $sortBy = 'latest')
    {
        $this->filters = array_merge($this->filters, $filters, request()->query());
        $this->sortBy  = request()->query('sortBy', $sortBy);

        // Normalisasi filter array
        foreach (['ref_jenis_dokumen_id', 'ref_bidang_hukum_id', 'subjek', 'status'] as $key) {
            if (!is_array($this->filters[$key])) {
                $this->filters[$key] = array_filter(explode(',', (string) $this->filters[$key]));
            }
        }

        if (!is_numeric($this->filters['tahun'])) {
            $this->filters['tahun'] = null;
        }

        $this->loadAvailableFilters();
    }

    /* ==========================================================
     *  AVAILABLE FILTERS (FROM REF TABLE)
     * ========================================================*/
    private function loadAvailableFilters(): void
    {
        $this->availableFilters = Cache::remember('dokumen.available_filters', now()->addMinutes(30), function () {
            return [
                'jenis_dokumen' => RefJenisDokumen::where('is_active', true)
                    ->withCount('dokumenHukum')
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'singkatan']),

                'bidang_hukum' => RefBidangHukum::where('is_active', true)
                    ->withCount('dokumenHukum')
                    ->orderBy('nama')
                    ->get(['id', 'nama']),

                'subjek' => RefSubjek::where('is_active', true)
                    ->withCount('dokumenHukum')
                    ->orderBy('nama')
                    ->get(['id', 'nama']),

                'status' => DokumenHukum::select('status')
                    ->distinct()
                    ->whereNotNull('status')
                    ->pluck('status')
                    ->values()
                    ->toArray(),
            ];
        });
    }

    /* ==========================================================
     *  FILTER ACTIONS
     * ========================================================*/
    public function updated($property)
    {
        if (str_starts_with($property, 'filters')) {
            $this->resetPage();
        }
    }

    public function updateSortBy(string $sortBy)
    {
        $this->sortBy = $sortBy;
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $filtered = array_filter($this->filters, fn ($v) => is_array($v) ? count($v) : $v !== '' && $v !== null);

        foreach ($filtered as $k => $v) {
            if (is_array($v)) {
                $filtered[$k] = implode(',', $v);
            }
        }

        $filtered['sortBy'] = $this->sortBy;
        $filtered['page']   = 1;

        return redirect()->to('/dokumen?' . Arr::query($filtered));
    }

    public function resetFilters()
    {
        $this->filters = [
            'judul' => '',
            'tahun' => null,
            'tipeDok' => '',
            'ref_jenis_dokumen_id' => [],
            'ref_bidang_hukum_id' => [],
            'subjek' => [],
            'status' => [],
            'nomor' => '',
        ];

        return redirect()->to('/dokumen');
    }

    /* ==========================================================
     *  QUERY RENDER
     * ========================================================*/
    public function render()
    {
        $start = microtime(true);

        $query = DokumenHukum::query()
            ->with(['jenisDokumen', 'bidangHukumRef', 'subjekRef']);

        $this->applyFiltersToQuery($query);

        $results = $query->paginate(10);

        $this->numResults = $results->total();
        
        $this->queryTime = round(microtime(true) - $start, 3);

        return view('livewire.dokumen-list', [
            'results' => $results,
            'numResults' => $this->numResults,
            'queryTime' => $this->queryTime,
            'availableFilters' => $this->availableFilters,
            'sortBy' => $this->sortBy,
        ]);
    }

    /* ==========================================================
     *  APPLY FILTERS TO QUERY
     * ========================================================*/
    private function applyFiltersToQuery($query): void
    {
        // Simple fields
        foreach (['judul', 'tipeDok', 'nomor'] as $field) {
            if (!empty($this->filters[$field])) {
                $query->where($field, 'like', '%' . $this->filters[$field] . '%');
            }
        }

        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }

        // FK filters
        if ($this->filters['ref_jenis_dokumen_id']) {
            $query->whereIn('ref_jenis_dokumen_id', $this->filters['ref_jenis_dokumen_id']);
        }

        if ($this->filters['ref_bidang_hukum_id']) {
            $query->whereIn('ref_bidang_hukum_id', $this->filters['ref_bidang_hukum_id']);
        }

        // M2M subjek
        if ($this->filters['subjek']) {
            $query->whereHas('subjekRef', fn ($q) =>
                $q->whereIn('ref_subjek.id', $this->filters['subjek'])
            );
        }

        if ($this->filters['status']) {
            $query->whereIn('status', $this->filters['status']);
        }

        // Sorting
        match ($this->sortBy) {
            'popular' => $query->orderByDesc('view_count'),
            default   => $query->orderByDesc('tahun')->orderByDesc('nomor'),
        };
    }
}
