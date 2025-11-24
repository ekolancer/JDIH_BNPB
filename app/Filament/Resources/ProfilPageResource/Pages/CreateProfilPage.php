<?php

namespace App\Filament\Resources\ProfilPageResource\Pages;

use App\Filament\Resources\ProfilPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfilPage extends CreateRecord
{
    protected static string $resource = ProfilPageResource::class;

    protected static bool $canCreateAnother = false;
}
