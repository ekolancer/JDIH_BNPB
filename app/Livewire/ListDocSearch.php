<?php
// 1. Buat Livewire Component dengan command:
// php artisan make:livewire ListDocSearch

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenHukum;

class ListDocSearch extends Component
{
    use WithPagination;
    
    public $filters = [];
    public $availableFilters;

    public function mount()
    {
        // Ambil data unik untuk masing-masing filter dari database
        $this->availableFilters = [
            'jenisDok' => DokumenHukum::select('jenisDok')->distinct()->pluck('jenisDok')->toArray(),
            'singkatan' => DokumenHukum::select('singkatan')->distinct()->pluck('singkatan')->toArray(),
            'penerbit' => DokumenHukum::select('penerbit')->distinct()->pluck('penerbit')->toArray(),
            'subjek' => DokumenHukum::select('subjek')->distinct()->pluck('subjek')->toArray(),
            'status' => DokumenHukum::select('status')->distinct()->pluck('status')->toArray(),
            'bidangHukum' => DokumenHukum::select('bidangHukum')->distinct()->pluck('bidangHukum')->toArray(),
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filters = [];
    }

    public function render()
    {
        $query = DokumenHukum::query();

        // Terapkan filter jika ada
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $query->whereIn($key, $value);
            }
        }

        $results = $query->paginate(10);
        return view('livewire.list-doc-search', compact('results'));
    }
}