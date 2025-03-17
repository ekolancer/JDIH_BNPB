<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Arr;
use App\Models\DokumenHukum;
use Livewire\WithPagination;
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
        $this->filters = array_merge($this->filters, $filters, request()->query());

        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $key) {
            if (!is_array($this->filters[$key])) {
                $this->filters[$key] = array_filter(explode(',', $this->filters[$key]));
            }

            // Validasi isi array hanya boleh berupa string alfanumerik
            $this->filters[$key] = array_filter($this->filters[$key], function ($item) {
                return ctype_alnum(str_replace([' ', '-'], '', $item));
            });
        }

        // Validasi tahun agar hanya angka
        if (!is_numeric($this->filters['tahun'])) {
            $this->filters['tahun'] = null;
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

    // Mengatur field sorting dan arah sorting
    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    // Memuat filter yang tersedia dari cache
    private function loadAvailableFilters()
    {
        $this->availableFilters = Cache::remember('available_filters', now()->addMinutes(30), function () {
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

    // Terapkan filter tanpa mereset pagination
    public function applyFilters()
    {
        // Bersihkan filter agar tidak ada nilai kosong
        $filteredFilters = array_filter($this->filters, function ($value) {
            return is_array($value) ? count($value) > 0 : !empty($value);
        });

        // Konversi array menjadi string sebelum dikirim ke query string
        foreach ($filteredFilters as $key => $value) {
            if (is_array($value)) {
                $filteredFilters[$key] = implode(',', $value);
            }
        }

        // Set pagination ke halaman 1
        $filteredFilters['page'] = 1;

        // Buat query string
        $queryString = Arr::query($filteredFilters);

        // Redirect to the updated URL with filters applied
        return redirect()->to('/dokumen/search-doc?' . $queryString);
    }

    // Reset semua filter ke nilai default dan reset pagination
    public function resetFilters()
    {
        $this->filters = [
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

        $this->filters['page'] = 1;
        $this->resetPage();
        // Redirect to the base URL without filters
        return redirect()->to('/dokumen/search-doc');
    }

    // Menghapus filter tertentu dan reset pagination
    public function removeFilter($filter)
    {
        foreach ($this->filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    if (ucfirst($this->getDisplayKey($key)) . ': ' . $item == $filter) {
                        unset($this->filters[$key][$index]);
                    }
                }
                $this->filters[$key] = array_values($this->filters[$key]);
            } elseif (ucfirst($this->getDisplayKey($key)) . ': ' . $value == $filter) {
                $this->filters[$key] = ($key === 'tahun' || $key === 'nomor') ? null : '';
            }
        }

        // Reset pagination to page 1
        $this->filters['page'] = 1;
        $this->resetPage();

        // Perbarui URL setelah filter dihapus
        $this->applyFilters();
    }

    // Mendapatkan filter aktif dalam format yang dapat dibaca
    public function getActiveFilters()
    {
        $filters = [];

        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $displayKey = $this->getDisplayKey($key);
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $filters[] = ucfirst($displayKey) . ': ' . $item;
                    }
                } else {
                    $filters[] = ucfirst($displayKey) . ': ' . $value;
                }
            }
        }

        // Pastikan pagination direset saat filter aktif diperbarui
        $this->resetPage();

        return $filters;
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
            $value = trim($this->filters[$field] ?? '');
            if (!empty($value) && ctype_alnum(str_replace([' ', '-'], '', $value))) {
                $query->where($field, 'like', '%' . addslashes($value) . '%');
            }
        }

        foreach (['jenisDok', 'singkatan', 'penerbit', 'subjek', 'status', 'bidangHukum'] as $key) {
            $values = array_filter(array_map('trim', $this->filters[$key] ?? []));
            if (!empty($values)) {
                $query->whereIn($key, array_map('addslashes', $values));
            }
        }
    }

    // Mendapatkan nama tampilan untuk filter
    private function getDisplayKey($key)
    {
        $displayKeys = [
            'tipeDok' => 'Tipe',
            'jenisDok' => 'Jenis',
            'singkatan' => 'Singkatan',
            'penerbit' => 'Penerbit',
            'subjek' => 'Subjek',
            'status' => 'Status',
            'bidangHukum' => 'Bidang',
            'judul' => 'Judul',
            'tahun' => 'Tahun',
            'nomor' => 'Nomor',
        ];

        return isset($displayKeys[$key]) ? $displayKeys[$key] : $key;
    }
}
