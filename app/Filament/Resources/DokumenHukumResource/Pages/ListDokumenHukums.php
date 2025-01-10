<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use App\Filament\Resources\DokumenHukumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenHukums extends ListRecords
{
    protected static string $resource = DokumenHukumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
