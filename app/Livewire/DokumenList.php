<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenHukum;
use Illuminate\Support\Facades\Log;

class DokumenList extends Component
{
    use WithPagination;

    public $filters = [];

    public $availableFilters = [];

    public function mount()
    {
        $this->filters = [
            'jenisDok' => [],
            'singkatan' => [],
            'penerbit' => [],
            'subjek' => [],
            'status' => [],
            'bidangHukum' => [],
        ];

        // Ambil data filter unik dari database
        $this->availableFilters = [
            'jenisDok' => DokumenHukum::select('jenisDok')->distinct()->pluck('jenisDok')->toArray(),
            'singkatan' => DokumenHukum::select('singkatan')->distinct()->pluck('singkatan')->toArray(),
            'penerbit' => DokumenHukum::select('penerbit')->distinct()->pluck('penerbit')->toArray(),
            'subjek' => DokumenHukum::select('subjek')->distinct()->pluck('subjek')->toArray(),
            'status' => DokumenHukum::select('status')->distinct()->pluck('status')->toArray(),
            'bidangHukum' => DokumenHukum::select('bidangHukum')->distinct()->pluck('bidangHukum')->toArray(),
        ];
    }

    public function updatedFilters($value)
    {
        Log::info('Filters updated:', $this->filters);
        $this->resetPage();
    }

    public function applyFilters()
    {
        Log::info('Filter diterapkan:', $this->filters);
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filters = [
            'jenisDok' => [],
            'singkatan' => [],
            'penerbit' => [],
            'subjek' => [],
            'status' => [],
            'bidangHukum' => [],
        ];
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'filters')) {
            $this->resetPage(); // Reset pagination agar data tetap konsisten
        }
    }


    public function render()
    {
        $query = DokumenHukum::query();

        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $query->whereIn($key, $value);
            }
        }

        $results = $query->paginate(10);
        return view('livewire.dokumen-list', compact('results'));
    }
}
