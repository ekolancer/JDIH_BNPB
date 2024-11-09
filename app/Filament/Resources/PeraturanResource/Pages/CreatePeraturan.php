<?php

namespace App\Filament\Resources\PeraturanResource\Pages;

use App\Filament\Resources\PeraturanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeraturan extends CreateRecord
{
    protected static string $resource = PeraturanResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Mengarahkan ke halaman list setelah create
    }
}
