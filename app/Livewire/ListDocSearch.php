<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;
use App\Models\RefBidangHukum;
use App\Models\RefSubjek;

class ListDocSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public array $filters = [
        'ref_jenis_dokumen_id' => [],
        'ref_bidang_hukum_id' => [],
        'subjek' => [],
        'status' => [],
        'tahun' => null,
        'judul' => null,
    ];

    public array $availableFilters = [];

    public function mount()
    {
        $this->availableFilters = [
            // Jenis Dokumen (REF)
            'jenis_dokumen' => RefJenisDokumen::where('is_active', true)
                ->withCount('dokumenHukum')
                ->orderBy('nama')
                ->get(['id', 'nama', 'singkatan']),

            // Bidang Hukum (REF)
            'bidang_hukum' => RefBidangHukum::where('is_active', true)
                ->withCount('dokumenHukum')
                ->orderBy('nama')
                ->get(['id', 'nama']),

            // Subjek (REF M2M)
            'subjek' => RefSubjek::where('is_active', true)
                ->withCount('dokumenHukum')
                ->orderBy('nama')
                ->get(['id', 'nama']),

            // Status (ENUM-like)
            'status' => DokumenHukum::select('status')
                ->distinct()
                ->pluck('status')
                ->filter()
                ->values()
                ->toArray(),

            // Tahun
            'tahun' => DokumenHukum::select('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun')
                ->filter()
                ->values()
                ->toArray(),
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filters = [
            'ref_jenis_dokumen_id' => [],
            'ref_bidang_hukum_id' => [],
            'subjek' => [],
            'status' => [],
            'tahun' => null,
            'judul' => null,
        ];
    }

    public function render()
    {
        $query = DokumenHukum::query()
            ->with(['jenisDokumen', 'bidangHukumRef', 'subjekRef']);

        // 🔎 Judul
        if (!empty($this->filters['judul'])) {
            $query->where('judul', 'like', '%' . $this->filters['judul'] . '%');
        }

        // 📄 Jenis Dokumen (FK)
        if (!empty($this->filters['ref_jenis_dokumen_id'])) {
            $query->whereIn('ref_jenis_dokumen_id', $this->filters['ref_jenis_dokumen_id']);
        }

        // ⚖️ Bidang Hukum (FK)
        if (!empty($this->filters['ref_bidang_hukum_id'])) {
            $query->whereIn('ref_bidang_hukum_id', $this->filters['ref_bidang_hukum_id']);
        }

        // 🏷️ Subjek (M2M)
        if (!empty($this->filters['subjek'])) {
            $query->whereHas('subjekRef', function ($q) {
                $q->whereIn('ref_subjek.id', $this->filters['subjek']);
            });
        }

        // 📌 Status
        if (!empty($this->filters['status'])) {
            $query->whereIn('status', $this->filters['status']);
        }

        // 📅 Tahun
        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }

        $results = $query
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.list-doc-search', compact('results'));
    }
}
