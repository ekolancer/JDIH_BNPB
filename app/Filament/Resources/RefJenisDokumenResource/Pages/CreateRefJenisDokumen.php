<?php

namespace App\Filament\Resources\RefJenisDokumenResource\Pages;

use App\Filament\Resources\RefJenisDokumenResource;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefJenisDokumen extends CreateRecord
{
    protected static string $resource = RefJenisDokumenResource::class;

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
            ->body('Jenis Dokumen berhasil ditambah.');
    }

}
