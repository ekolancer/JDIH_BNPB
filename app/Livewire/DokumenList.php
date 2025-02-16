<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenHukum;

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
    ];

    public $availableFilters = [];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $numResults = 0;
    public $queryTime = 0;

    public function mount()
    {
        $this->loadAvailableFilters();
    }

    public function updatedFilters()
    {
        $this->resetPage();
        $this->dispatch('filtersUpdated');
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'filters')) {
            $this->updatedFilters();
        }
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
        ];
        $this->resetPage();
    }

    private function loadAvailableFilters()
    {
        $this->availableFilters = [
            'jenisDok' => DokumenHukum::distinct()->pluck('jenisDok')->toArray(),
            'singkatan' => DokumenHukum::distinct()->pluck('singkatan')->toArray(),
            'penerbit' => DokumenHukum::distinct()->pluck('penerbit')->toArray(),
            'subjek' => DokumenHukum::distinct()->pluck('subjek')->toArray(),
            'status' => DokumenHukum::distinct()->pluck('status')->toArray(),
            'bidangHukum' => DokumenHukum::distinct()->pluck('bidangHukum')->toArray(),
        ];
    }

    public function applyFilters()
    {
        $this->render();
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
                // Format activeFilter: "JenisDok: Peraturan" → kita harus pecah nama filter dan nilai
                foreach ($value as $index => $item) {
                    if (ucfirst($key) . ': ' . $item == $filter) {
                        unset($this->filters[$key][$index]); // Hapus item yang sesuai
                    }
                }
                // Reset ulang indeks array agar tidak ada key yang kosong
                $this->filters[$key] = array_values($this->filters[$key]);
            } elseif (ucfirst($key) . ': ' . $value == $filter) {
                // Hapus filter yang berbentuk string / angka
                $this->filters[$key] = ($key === 'tahun') ? null : '';
            }
        }

        // Memastikan tampilan diperbarui
        $this->updatedFilters();
    }


    public function render()
    {
        $startTime = microtime(true);

        $query = DokumenHukum::query();

        if (!empty($this->filters['judul'])) {
            $query->where('judul', 'like', '%' . $this->filters['judul'] . '%');
        }
        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }
        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $filter) {
            if (!empty($this->filters[$filter])) {
                $query->whereIn($filter, (array) $this->filters[$filter]);
            }
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $results = $query->paginate(10);
        $this->numResults = $results->total();
        $this->queryTime = microtime(true) - $startTime;

        return view('livewire.dokumen-list', [
            'results' => $results,
            'numResults' => $this->numResults,
            'queryTime' => round($this->queryTime, 2),
            'activeFilters' => $this->getActiveFilters(), // ✅ Mengirim `activeFilters` ke Blade
        ]);
    }
}
