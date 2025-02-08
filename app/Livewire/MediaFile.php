<?php

namespace App\Livewire;

use App\Models\DokumenHukum;
use Livewire\Component;

class MediaFile extends Component
{
    public $dokumenId;
    public $fileUrl;

    // Memuat lampiran berdasarkan dokumen yang dipilih
    public function mount($dokumenId)
    {
        $this->dokumenId = $dokumenId;

        $dokumen = DokumenHukum::find($this->dokumenId);

        if ($dokumen && $dokumen->lampiran) {
            // Menyusun URL file lampiran
            $this->fileUrl = $dokumen->lampiran;
        }
    }

    public function render()
    {
        return view('livewire.media-file');
    }
}
