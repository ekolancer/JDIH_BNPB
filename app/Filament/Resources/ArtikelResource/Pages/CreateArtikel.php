<?php

namespace App\Filament\Resources\ArtikelResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArtikelResource;

class CreateArtikel extends CreateRecord
{
    protected static string $resource = ArtikelResource::class;

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
            ->body('Data Artikel berhasil ditambah.');
    }
}
