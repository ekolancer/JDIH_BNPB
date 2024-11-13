<?php

namespace App\Filament\Resources\MonografResource\Pages;

use App\Filament\Resources\MonografResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateMonograf extends CreateRecord
{
    protected static string $resource = MonografResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Mengarahkan ke halaman list setelah create
    }

    protected function getFormActions(): array
    {
        return [$this->getCancelFormAction(),]; // Menghilangkan semua tombol aksi termasuk "Create"
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Add successfully')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body('Data Monograf berhasil ditambah.');
    }
}
