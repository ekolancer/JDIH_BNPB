<?php

namespace App\Filament\Resources\RefTipeDokumenResource\Pages;

use App\Filament\Resources\RefTipeDokumenResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRefTipeDokumen extends CreateRecord
{
    protected static string $resource = RefTipeDokumenResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil Ditambahkan')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body("Data Tipe Dokumen \"{$this->record->nama}\" berhasil ditambahkan.");
    }
}
