<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use App\Filament\Resources\DokumenHukumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenHukum extends EditRecord
{
    protected static string $resource = DokumenHukumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
