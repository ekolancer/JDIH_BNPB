<?php

namespace App\Filament\Resources\RefJenisDokumenResource\Pages;

use App\Filament\Resources\RefJenisDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRefJenisDokumen extends EditRecord
{
    protected static string $resource = RefJenisDokumenResource::class;

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
            ->body("Data Jenis Dokumen \"{$this->record->nama}\" berhasil diperbarui.");
    }
}
