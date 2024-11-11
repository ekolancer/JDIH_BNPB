<?php

namespace App\Filament\Resources\MonografResource\Pages;

use App\Filament\Resources\MonografResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonografs extends ListRecords
{
    protected static string $resource = MonografResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Data Monograf";
    }
}
