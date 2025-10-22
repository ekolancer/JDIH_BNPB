<?php

namespace App\Providers;

use Spatie\Health\Facades\Health;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\View\Composers\BreadcrumbComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Health::checks([
            EnvironmentCheck::new(),
            DebugModeCheck::new(),
            OptimizedAppCheck::new(),
            UsedDiskSpaceCheck::new(),
            PingCheck::new()->url('https://bnpb.go.id/')->timeout(2)->retryTimes(3),
            DatabaseCheck::new(),
        ]);

        // Atur sesuai locale yang diinginkan
        Carbon::setLocale(config('app.locale'));

        // View composer untuk breadcrumbs dinamis
        View::composer('partials.breadcrumbs', BreadcrumbComposer::class);
    }
}
