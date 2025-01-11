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
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Data Peraturan";
    }

    public function getTabs(): array
    {
        return [
            'ALL' => Tab::make('All'),
            'Berlaku' => Tab::make('BERLAKU')
                ->badge(DokumenHukum::query()->where('status', 'Berlaku')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Berlaku')),
            'Tidak Berlaku' => Tab::make('TIDAK BERLAKU')
                ->badge(DokumenHukum::query()->where('status', 'Tidak Berlaku')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Tidak Berlaku')),
        ];
    }
}
