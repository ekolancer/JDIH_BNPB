<?php

namespace App\Filament\Resources\RefTipeDokumenResource\Pages;

use App\Filament\Resources\RefTipeDokumenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefTipeDokumen extends CreateRecord
{
    protected static string $resource = RefTipeDokumenResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
