<?php

namespace App\Filament\Resources\MonografResource\Pages;

use App\Filament\Resources\MonografResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonograf extends EditRecord
{
    protected static string $resource = MonografResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
