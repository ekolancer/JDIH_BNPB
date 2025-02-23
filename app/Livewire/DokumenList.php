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

    public $selectedTipeDok;

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

    // Perbarui tipeDok secara dinamis saat dipilih di navbar
    public function updateTipeDok($tipeDok)
    {
        $this->filters['tipeDok'] = $tipeDok;
        $this->resetPage();
    }

    // Reset pagination saat filter diperbarui
    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'filters')) {
            $this->resetPage();
        }
    }

    // Fungsi ini tidak diperlukan karena logikanya sudah ada di fungsi updated
    // public function updatedFilters()
    // {
    //     $this->resetPage();
    // }

    // Mengatur field sorting dan arah sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    // Reset semua filter ke nilai default
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

    // Memuat filter yang tersedia dari cache
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

    // Terapkan filter dan reset pagination
    public function applyFilters()
    {
        $this->resetPage();
    }

    // Mendapatkan filter aktif dalam format yang dapat dibaca
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

    // Menghapus filter tertentu
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

        $this->updated('filters');
    }

    // Render tampilan dengan hasil query dan informasi lainnya
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
            'availableFilters' => $this->availableFilters,
        ]);
    }

    // Terapkan filter ke query
    private function applyFiltersToQuery($query)
    {
        $filterableFields = ['tipeDok', 'judul', 'tahun', 'nomor'];
        foreach ($filterableFields as $field) {
            if (!empty($this->filters[$field])) {
                $query->where($field, 'like', '%' . $this->filters[$field] . '%');
            }
        }

        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $key) {
            if (!empty($this->filters[$key]) && is_array($this->filters[$key])) {
                $query->whereIn($key, $this->filters[$key]);
            }
        }
    }
}
