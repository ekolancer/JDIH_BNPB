<?php

namespace App\Filament\Resources\RefJenisDokumenResource\Pages;

use App\Filament\Resources\RefJenisDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefJenisDokumens extends ListRecords
{
    protected static string $resource = RefJenisDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add'),
        ];
    }
    
    public function getTitle(): string
    {
        return "List Jenis Dokumen";
    }
}
