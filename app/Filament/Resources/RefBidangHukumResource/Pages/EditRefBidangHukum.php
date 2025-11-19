<?php

namespace App\Filament\Resources\RefBidangHukumResource\Pages;

use App\Filament\Resources\RefBidangHukumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRefBidangHukum extends EditRecord
{
    protected static string $resource = RefBidangHukumResource::class;

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
            ->title('Edit successfully')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body('Data berhasil diupdate.');
    }
}
