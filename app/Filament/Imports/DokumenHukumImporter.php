<?php

namespace App\Filament\Imports;

use App\Models\DokumenHukum;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DokumenHukumImporter extends Importer
{
    protected static ?string $model = DokumenHukum::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tipeDok')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('jenisDok')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('judul')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('teuBadan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nomor')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('tahun')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('singkatan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tempat')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('penerbit')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tglPenetapan')
                ->rules(['date']),
            ImportColumn::make('tglPengundangan')
                ->rules(['date']),
            ImportColumn::make('tglDiBacakan')
                ->rules(['date']),
            ImportColumn::make('nomorPanggil')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('cetakanEdisi')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('deskripsiFisik')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('subjek')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sumber')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('isbn')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status'),
            ImportColumn::make('bahasa')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('lokasi')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('bidangHukum')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('lampiran')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('abstrak')
                ->rules(['max:255']),
            ImportColumn::make('nomorIndukBuku')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('penandatangan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('view_count')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('download_count')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?DokumenHukum
    {
        // return DokumenHukum::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new DokumenHukum();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your dokumen hukum import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
