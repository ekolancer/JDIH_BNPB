<?php

namespace App\Filament\Resources\RefSubjekResource\Pages;

use App\Filament\Resources\RefSubjekResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRefSubjek extends CreateRecord
{
    protected static string $resource = RefSubjekResource::class;

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
            ->body('Subjek berhasil ditambah.');
    }
}
