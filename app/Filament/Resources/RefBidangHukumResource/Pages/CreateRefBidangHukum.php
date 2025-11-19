<?php

namespace App\Filament\Resources\RefBidangHukumResource\Pages;

use App\Filament\Resources\RefBidangHukumResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefBidangHukum extends CreateRecord
{
    protected static string $resource = RefBidangHukumResource::class;

    protected static bool $canCreateAnother = false;
}
