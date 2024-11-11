<?php

namespace App\Filament\Resources\PutusanResource\Pages;

use App\Filament\Resources\PutusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPutusan extends EditRecord
{
    protected static string $resource = PutusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
