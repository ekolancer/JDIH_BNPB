<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-banner-widget';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    public function getUser(): object
    {
        return Auth::user();
    }

    public function getGreeting(): string
    {
        $hour = Carbon::now()->hour;

        return match (true) {
            $hour >= 5  && $hour < 12 => 'Selamat Pagi',
            $hour >= 12 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default                   => 'Selamat Malam',
        };
    }

    public function getGreetingIcon(): string
    {
        $hour = Carbon::now()->hour;

        return match (true) {
            $hour >= 5  && $hour < 12 => '🌤️',
            $hour >= 12 && $hour < 15 => '☀️',
            $hour >= 15 && $hour < 18 => '🌇',
            default                   => '🌙',
        };
    }

    public function getRole(): string
    {
        $user  = Auth::user();
        $roles = $user->getRoleNames();

        if ($roles->isEmpty()) {
            return 'User';
        }

        return $roles->map(fn ($r) => match ($r) {
            'super_admin' => 'Super Admin',
            'Admin'       => 'Administrator',
            default       => ucfirst($r),
        })->implode(', ');
    }

    public function getDateTime(): array
    {
        $now = Carbon::now()->locale('id');

        return [
            'day'  => $now->translatedFormat('l'),
            'date' => $now->translatedFormat('d F Y'),
            'time' => $now->format('H:i'),
        ];
    }

    public function getLastLogin(): string
    {
        $user = Auth::user();

        if (empty($user->last_login_at)) {
            return 'Pertama kali login';
        }

        return $user->last_login_at->locale('id')->diffForHumans();
    }

    public function getMotivation(): string
    {
        $quotes = [
            'Dokumentasi hukum yang baik adalah fondasi keadilan.',
            'Setiap dokumen yang dikelola dengan baik melayani masyarakat.',
            'Transparansi informasi hukum dimulai dari pengelolaan yang teliti.',
            'Data yang akurat adalah kunci pelayanan publik yang prima.',
            'Kerja keras hari ini adalah warisan hukum untuk generasi mendatang.',
        ];

        return $quotes[Carbon::now()->dayOfYear % count($quotes)];
    }
}
