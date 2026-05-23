<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Mengarahkan ke halaman list setelah create
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil Ditambahkan')
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->body("Data FAQ \"{$this->record->question}\" berhasil ditambahkan.");
    }
}