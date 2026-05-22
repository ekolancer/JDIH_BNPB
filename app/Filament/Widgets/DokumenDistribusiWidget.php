<?php

namespace App\Filament\Widgets;

use App\Models\DokumenHukum;
use App\Models\RefBidangHukum;
use App\Models\RefJenisDokumen;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class DokumenDistribusiWidget extends Widget
{
    protected string $view = 'filament.widgets.dokumen-distribusi-widget';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function getDistribusiJenis(): array
    {
        $data = DB::table('dokumen_hukums')
            ->join('ref_jenis_dokumen', 'dokumen_hukums.ref_jenis_dokumen_id', '=', 'ref_jenis_dokumen.id')
            ->select('ref_jenis_dokumen.nama', DB::raw('COUNT(*) as total'))
            ->groupBy('ref_jenis_dokumen.nama')
            ->orderByDesc('total')
            ->get();

        return $data->map(fn($item) => [
            'label' => $item->nama,
            'value' => $item->total,
        ])->toArray();
    }

    public function getDistribusiBidang(): array
    {
        $data = DB::table('dokumen_hukums')
            ->join('ref_bidang_hukum', 'dokumen_hukums.ref_bidang_hukum_id', '=', 'ref_bidang_hukum.id')
            ->select('ref_bidang_hukum.nama', DB::raw('COUNT(*) as total'))
            ->groupBy('ref_bidang_hukum.nama')
            ->orderByDesc('total')
            ->get();

        return $data->map(fn($item) => [
            'label' => $item->nama,
            'value' => $item->total,
        ])->toArray();
    }

    public function getTotalDokumen(): int
    {
        return DokumenHukum::count();
    }
}
