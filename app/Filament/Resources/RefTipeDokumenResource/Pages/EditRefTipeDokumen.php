<?php

namespace App\Filament\Resources\RefTipeDokumenResource\Pages;

use App\Filament\Resources\RefTipeDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefTipeDokumen extends EditRecord
{
    protected static string $resource = RefTipeDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
