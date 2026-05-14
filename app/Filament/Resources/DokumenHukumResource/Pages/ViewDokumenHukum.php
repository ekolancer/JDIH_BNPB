<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use App\Filament\Resources\DokumenHukumResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDokumenHukum extends ViewRecord
{
    protected static string $resource = DokumenHukumResource::class;

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
        ];
    }

    public function getView(): string
    {
        return 'filament.pages.action.dokumen-view-page';
    }

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getBreadcrumb(): string
    {
        return 'Detail';
    }
}
