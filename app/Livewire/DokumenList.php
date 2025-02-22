<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenHukum;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;

#[Layout('app-clear')]
class DokumenList extends Component
{
    use WithPagination;

    public $filters = [
        'judul' => '',
        'tahun' => null,
        'tipeDok' => '',
        'jenisDok' => [],
        'singkatan' => [],
        'penerbit' => [],
        'subjek' => [],
        'status' => [],
        'bidangHukum' => [],
        'nomor' => '',
    ];

    public $availableFilters = [];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $numResults = 0;
    public $queryTime = 0;

    protected $updatesQueryString = ['filters' => ['except' => []]];

    public function mount($filters = [])
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->filters = array_merge($this->filters, request()->query());

        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $key) {
            if (!is_array($this->filters[$key])) {
                $this->filters[$key] = array_filter(explode(',', $this->filters[$key]));
            }
        }

        $this->loadAvailableFilters();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'filters')) {
            $this->resetPage();
        }
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function resetFilters()
    {
        $this->filters = [
            'judul' => '',
            'tahun' => null,
            'jenisDok' => [],
            'singkatan' => [],
            'penerbit' => [],
            'subjek' => [],
            'status' => [],
            'bidangHukum' => [],
            'nomor' => '',
        ];
        $this->resetPage();
    }

    private function loadAvailableFilters()
    {
        $this->availableFilters = Cache::remember('available_filters', 60, function () {
            return [
                'jenisDok' => DokumenHukum::distinct()->pluck('jenisDok')->toArray(),
                'singkatan' => DokumenHukum::distinct()->pluck('singkatan')->toArray(),
                'penerbit' => DokumenHukum::distinct()->pluck('penerbit')->toArray(),
                'subjek' => DokumenHukum::distinct()->pluck('subjek')->toArray(),
                'status' => DokumenHukum::distinct()->pluck('status')->toArray(),
                'bidangHukum' => DokumenHukum::distinct()->pluck('bidangHukum')->toArray(),
            ];
        });
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function getActiveFilters()
    {
        $filters = [];

        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $filters[] = ucfirst($key) . ': ' . $item;
                    }
                } else {
                    $filters[] = ucfirst($key) . ': ' . $value;
                }
            }
        }

        return $filters;
    }

    public function removeFilter($filter)
    {
        foreach ($this->filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    if (ucfirst($key) . ': ' . $item == $filter) {
                        unset($this->filters[$key][$index]);
                    }
                }
                $this->filters[$key] = array_values($this->filters[$key]);
            } elseif (ucfirst($key) . ': ' . $value == $filter) {
                $this->filters[$key] = ($key === 'tahun' || $key === 'nomor') ? null : '';
            }
        }

        $this->updatedFilters();
    }

    public function render()
    {
        $startTime = microtime(true);

        $query = DokumenHukum::query();
        $this->applyFiltersToQuery($query);

        $results = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        $this->numResults = $results->total();
        $this->queryTime = microtime(true) - $startTime;

        return view('livewire.dokumen-list', [
            'results' => $results,
            'numResults' => $this->numResults,
            'queryTime' => round($this->queryTime, 2),
            'activeFilters' => $this->getActiveFilters(),
            'availableFilters' => $this->availableFilters
        ]);
    }

    private function applyFiltersToQuery($query)
    {
        if (!empty($this->filters['judul'])) {
            $query->where('judul', 'like', '%' . $this->filters['judul'] . '%');
        }
        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }
        if (!empty($this->filters['nomor'])) {
            $query->where('nomor', 'like', '%' . $this->filters['nomor'] . '%');
        }
        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $key) {
            if (!empty($this->filters[$key]) && is_array($this->filters[$key])) {
                $query->whereIn($key, $this->filters[$key]);
            }
        }
    }
}
