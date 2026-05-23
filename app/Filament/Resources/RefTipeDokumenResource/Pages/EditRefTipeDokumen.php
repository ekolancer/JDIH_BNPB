<?php

namespace App\Filament\Resources\RefTipeDokumenResource\Pages;

use App\Filament\Resources\RefTipeDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

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

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil Diperbarui')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body("Data Tipe Dokumen \"{$this->record->nama}\" berhasil diperbarui.");
    }
}
