<?php

namespace App\Filament\Resources\DokumenHukumResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DokumenHukumResource;

class EditDokumenHukum extends EditRecord
{
    protected static string $resource = DokumenHukumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Edit successfully')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body('Data Peraturan berhasil diupdate.');
    }
}
