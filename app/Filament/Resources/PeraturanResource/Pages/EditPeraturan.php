<?php

namespace App\Filament\Resources\PeraturanResource\Pages;

use App\Filament\Resources\PeraturanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPeraturan extends EditRecord
{
    protected static string $resource = PeraturanResource::class;

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
