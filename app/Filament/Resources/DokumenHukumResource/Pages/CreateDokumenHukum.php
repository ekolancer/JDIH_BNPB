<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DokumenHukumResource;

class CreateDokumenHukum extends CreateRecord
{
    protected static string $resource = DokumenHukumResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Add successfully')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body('Dokumen hukum berhasil ditambah.');
    }
}
