<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DokumenHukumResource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DokumenHukum;

class ListDokumenHukums extends ListRecords
{
    protected static string $resource = DokumenHukumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Document'),
        ];
    }

    public function getTitle(): string
    {
        return "Dokumen Hukum";
    }

    public function getTabs(): array
    {
        return [
            'ALL' => Tab::make('All'),
            'Peraturan' => Tab::make('Peraturan')
                ->badge(DokumenHukum::query()->where('tipeDok', 'Peraturan')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tipeDok', 'Peraturan')),
            'Monografi' => Tab::make('Monografi')
                ->badge(DokumenHukum::query()->where('tipeDok', 'Monografi')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tipeDok', 'Monografi')),
            'Artikel' => Tab::make('Artikel')
                ->badge(DokumenHukum::query()->where('tipeDok', 'Artikel')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tipeDok', 'Artikel')),
            'Putusan' => Tab::make('Putusan')
                ->badge(DokumenHukum::query()->where('tipeDok', 'Putusan')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tipeDok', 'Putusan')),
        ];
    }
}
