<?php

namespace App\Filament\Resources\RefJenisDokumenResource\Pages;

use App\Filament\Resources\RefJenisDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefJenisDokumen extends CreateRecord
{
    protected static string $resource = RefJenisDokumenResource::class;

    protected static bool $canCreateAnother = false;

}
