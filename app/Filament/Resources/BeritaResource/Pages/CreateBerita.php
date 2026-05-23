<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

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
            ->title('Berhasil Ditambahkan')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body("Data Berita \"{$this->record->judul}\" berhasil ditambahkan.");
    }
}
