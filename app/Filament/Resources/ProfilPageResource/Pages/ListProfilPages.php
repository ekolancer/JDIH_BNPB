<?php

namespace App\Filament\Resources\ProfilPageResource\Pages;

use App\Filament\Resources\ProfilPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfilPages extends ListRecords
{
    protected static string $resource = ProfilPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add'),
        ];
    }

    public function getTitle(): string
    {
        return "Editor Website Profil";
    }
}
