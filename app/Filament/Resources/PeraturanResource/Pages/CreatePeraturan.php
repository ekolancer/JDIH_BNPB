<?php

namespace App\Filament\Resources\PeraturanResource\Pages;


use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PeraturanResource;
use Filament\Forms\Components\Actions\Action;

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
}
