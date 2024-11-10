<?php

namespace App\Filament\Resources\PeraturanResource\Pages;


use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PeraturanResource;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

class CreatePeraturan extends CreateRecord
{
    protected static string $resource = PeraturanResource::class;

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
            ->body('Data Peraturan berhasil ditambah.');
    }
}