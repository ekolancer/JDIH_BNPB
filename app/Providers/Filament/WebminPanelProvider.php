<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use App\Filament\Auth\Login;
use App\Filament\Pages\HealthCheckResults;
use App\Http\Middleware\RestrictIpMiddleware;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Filament\Navigation\MenuItem;

class WebminPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();

        $gaId = config('services.google_analytics.id');

        if ($gaId) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn(): HtmlString => new HtmlString(<<<HTML
                    <!-- Google Analytics -->
                    <script async src="https://www.googletagmanager.com/gtag/js?id={$gaId}"></script>
                    <script>
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());
                        gtag('config', '{$gaId}');
                    </script>
                HTML),
            );
        }
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('webmin')
            ->path('webmin')
            ->viteTheme('resources/css/filament/webmin/theme.css')
            ->login(Login::class)
            ->passwordReset()
            ->darkMode(true)
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \Filament\Widgets\AccountWidget::class,
                \App\Filament\Widgets\WelcomeBannerWidget::class,
                \App\Filament\Widgets\StatsOverviewWidget::class,
                \App\Filament\Widgets\DokumenDistribusiWidget::class,
                \App\Filament\Widgets\TopViewedDokumenWidget::class,
                \App\Filament\Widgets\TopDownloadedDokumenWidget::class,
            ])
            ->brandName('JDIH BNPB')
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
                RestrictIpMiddleware::class,
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
                FilamentEditProfilePlugin::make()
                    ->shouldRegisterNavigation(false),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class)
                    ->authorize(fn(): bool => \Illuminate\Support\Facades\Auth::user()?->email === 'eko.lancer@gmail.com'),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Edit Profile')
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ])
            ->navigationGroups([
                'Main Menu',
                'Metadata Referensi',
                'Features',
                'User Management',
                'Systems',
            ])
            ->resources([]);
    }
}
