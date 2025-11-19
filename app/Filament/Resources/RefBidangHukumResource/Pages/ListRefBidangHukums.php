<?php

namespace App\Filament\Resources\RefBidangHukumResource\Pages;

use App\Filament\Resources\RefBidangHukumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefBidangHukums extends ListRecords
{
    protected static string $resource = RefBidangHukumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add'),
        ];
    }

    public function getTitle(): string
    {
        return "List Bidang Hukum";
    }
}
