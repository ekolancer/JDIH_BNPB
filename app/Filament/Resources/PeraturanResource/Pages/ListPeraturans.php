<?php

namespace App\Filament\Resources\PeraturanResource\Pages;

use App\Filament\Resources\PeraturanResource;
use App\Models\Peraturan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPeraturans extends ListRecords
{
    protected static string $resource = PeraturanResource::class;

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
            'all' => Tab::make('All'),
            'Berlaku' => Tab::make('Berlaku')
            ->badge(Peraturan::query()->where('status', 'BERLAKU')->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'BERLAKU')),
            'Tidak Berlaku' => Tab::make('Tidak Berlaku')
            ->badge(Peraturan::query()->where('status', 'TIDAK BERLAKU')->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'TIDAK BERLAKU')),
        ];
    }

}