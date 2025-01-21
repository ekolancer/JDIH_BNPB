<?php

namespace App\Providers;

use App\Models\DokumenHukum;
use App\Observers\DokumenHukumObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       //
    }
}
