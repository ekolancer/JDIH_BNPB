<?php

namespace App\Filament\Resources\RefSubjekResource\Pages;

use App\Filament\Resources\RefSubjekResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefSubjeks extends ListRecords
{
    protected static string $resource = RefSubjekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah'),
        ];
    }
    
    public function getTitle(): string
    {
        return "List Subjek Hukum";
    }
}
