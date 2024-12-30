<?php

namespace App\Filament\Resources\PutusanResource\Pages;

use App\Filament\Resources\PutusanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPutusan extends EditRecord
{
    protected static string $resource = PutusanResource::class;

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

    protected function getFormActions(): array
    {
        return [$this->getCancelFormAction(),]; // Menghilangkan semua tombol aksi termasuk "Create"
    }
}
