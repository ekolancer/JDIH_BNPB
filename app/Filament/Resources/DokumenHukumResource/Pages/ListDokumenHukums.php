<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use Filament\Actions;
use App\Models\DokumenHukum;
use Filament\Actions\Action;
use App\Imports\DokumenHukumsImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Components\Tab;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DokumenHukumResource;
use Filament\Notifications\Notification;

class ListDokumenHukums extends ListRecords
{
    protected static string $resource = DokumenHukumResource::class;

    public function getTitle(): string
    {
        return "List Dokumen Hukum";
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

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
