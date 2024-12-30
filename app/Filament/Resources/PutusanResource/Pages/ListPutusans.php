<?php

namespace App\Filament\Resources\PutusanResource\Pages;

use App\Models\Putusan;
use App\Filament\Resources\PutusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;


class ListPutusans extends ListRecords
{
    protected static string $resource = PutusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Data Putusan";
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'Berlaku' => Tab::make('Berlaku')
            ->badge(Putusan::query()->where('status', 'BERLAKU')->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'BERLAKU')),
            'Tidak Berlaku' => Tab::make('Tidak Berlaku')
            ->badge(Putusan::query()->where('status', 'TIDAK BERLAKU')->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'TIDAK BERLAKU')),
        ];
    }
}