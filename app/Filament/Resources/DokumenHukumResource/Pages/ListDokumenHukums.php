<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use Filament\Actions;
use App\Models\DokumenHukum;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
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
        $tipes = \App\Models\RefTipeDokumen::active()->ordered()->get();

        $tabs = ['ALL' => Tab::make('All')];

        foreach ($tipes as $tipe) {
            $tabs[$tipe->kode] = Tab::make($tipe->kode)
                ->badge(DokumenHukum::query()->where('tipe_dokumen_id', $tipe->id)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tipe_dokumen_id', $tipe->id));
        }

        return $tabs;
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
