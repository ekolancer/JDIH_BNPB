<?php

namespace App\Filament\Resources\ProfilPageResource\Pages;

use App\Filament\Resources\ProfilPageResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;


class EditProfilPage extends EditRecord
{
    protected static string $resource = ProfilPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
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
            ->title('Berhasil Diperbarui')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body("Data Halaman Profil \"{$this->record->title}\" berhasil diperbarui.");
    }
}
