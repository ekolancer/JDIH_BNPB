<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DokumenHukum;
use Livewire\WithPagination;

class ListDocSearch extends Component
{
    use WithPagination;

    public $showFilters = false; // Toggle filter
    public $q = '';
    public $tipe_dokumen = '';
    public $bentuk_dokumen = '';
    public $tahun = '';

    protected $queryString = ['q', 'tipe_dokumen', 'bentuk_dokumen', 'tahun'];

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function updatingTipeDokumen()
    {
        $this->resetPage();
    }

    public function updatingBentukDokumen()
    {
        $this->resetPage();
    }

    public function updatingTahun()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DokumenHukum::query();

        if (!empty($this->q)) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->q . '%')
                  ->orWhere('nomor', 'like', '%' . $this->q . '%')
                  ->orWhere('tahun', 'like', '%' . $this->q . '%');
            });
        }

        if (!empty($this->tipe_dokumen)) {
            $query->where('tipeDok', $this->tipe_dokumen);
        }

        if (!empty($this->bentuk_dokumen)) {
            $query->where('jenisDok', $this->bentuk_dokumen);
        }

        if (!empty($this->tahun)) {
            $query->where('tahun', $this->tahun);
        }

        $results = $query->paginate(10);

        // Mengambil nilai unik untuk tipeDok dan jenisDok
        $dokumenAttributes = DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']);

        return view('livewire.list-doc-search', compact('results', 'dokumenAttributes'));
    }
}
