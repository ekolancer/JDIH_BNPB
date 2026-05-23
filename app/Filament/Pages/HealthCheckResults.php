<?php

namespace App\Filament\Pages;
 
use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;
use Illuminate\Contracts\Support\Htmlable;
 
class HealthCheckResults extends BaseHealthCheckResults
{
    public function getHeading(): string | Htmlable
    {
        return 'JDIH Health Check Results';
    }
 
    public static function getNavigationGroup(): ?string
    {
        return 'Systems';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

}