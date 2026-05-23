<?php

namespace App\Filament\Resources\InfografisResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\InfografisResource;

class CreateInfografis extends CreateRecord
{
    protected static string $resource = InfografisResource::class;

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
            ->body("Data Infografis \"{$this->record->judul}\" berhasil ditambahkan.");
    }
}
