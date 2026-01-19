<?php

namespace App\Filament\Resources\RefBidangHukumResource\Pages;

use App\Filament\Resources\RefBidangHukumResource;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefBidangHukum extends CreateRecord
{
    protected static string $resource = RefBidangHukumResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Mengarahkan ke halaman list setelah create
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Add successfully')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body('Bidang hukum berhasil ditambah.');
    }
}
