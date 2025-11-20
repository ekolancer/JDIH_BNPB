<?php

namespace App\Filament\Resources\RefSubjekResource\Pages;

use App\Filament\Resources\RefSubjekResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefSubjek extends CreateRecord
{
    protected static string $resource = RefSubjekResource::class;

    protected static bool $canCreateAnother = false;
}
