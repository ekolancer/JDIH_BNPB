<?php

namespace App\Filament\Resources\RefTipeDokumenResource\Pages;

use App\Filament\Resources\RefTipeDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefTipeDokumens extends ListRecords
{
    protected static string $resource = RefTipeDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Tipe Dokumen'),
        ];
    }
}
