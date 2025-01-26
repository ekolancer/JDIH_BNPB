<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Auth\Login;
use Filament\Support\Colors\Color;
use App\Filament\Pages\HealthCheckResults;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Carbon\Carbon;
use Niladam\FilamentAutoLogout\AutoLogoutPlugin;

class WebminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('webmin')
            ->path('webmin')
            ->login(Login::class)
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::hex('#496edc'),
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->darkMode(false)
            ->databaseNotifications()
            ->viteTheme('resources/css/filament/webmin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->brandLogo(asset('img/logo_jdih.png'))
            ->brandName('JDIH BNPB')
            ->brandLogoHeight('3rem')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                AuthUIEnhancerPlugin::make()
                    ->showEmptyPanelOnMobile(false)
                    ->formPanelPosition('right')
                    ->formPanelWidth('30%')
                    ->emptyPanelBackgroundImageOpacity('80%')
                    ->formPanelBackgroundColor(Color::hex('#d5d2d2'))
                    ->emptyPanelBackgroundImageUrl(asset('img/gBNPB.webp')),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class)
                    ->authorize(fn(): bool => auth()->user()->email === 'eko.lancer@gmail.com'),
                AutoLogoutPlugin::make()
                    ->color(Color::Emerald)                         // Set the color. Defaults to Zinc
                    ->logoutAfter(Carbon::SECONDS_PER_MINUTE * 5)   // Logout the user after 5 minutes
                    ->withoutTimeLeft()                             // Disable the time left
                    ->timeLeftText('Oh no. Kicking you in...')      // Change the time left text
                    ->timeLeftText('')
            ])
            ->navigationGroups([
                'Main Menu',
                'Informasi Hukum',
                'User Management',
                'Features',
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->renderHook(
                'panels::footer',
                fn() => view('filament.pages.action.footer')
            );
    }
}
