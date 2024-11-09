<?php

namespace App\Filament\Resources\PeraturanResource\Pages;

use App\Filament\Resources\PeraturanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeraturans extends ListRecords
{
    protected static string $resource = PeraturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
