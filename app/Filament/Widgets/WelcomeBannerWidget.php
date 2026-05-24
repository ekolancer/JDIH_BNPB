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

    public function getUser(): ?\App\Models\User
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user;
    }

    public function getGreeting(): string
    {
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $hour = Carbon::now($timezone)->hour;

        return match (true) {
            $hour >= 5  && $hour < 12 => 'Selamat Pagi',
            $hour >= 12 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default                   => 'Selamat Malam',
        };
    }

    public function getRole(): string
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return 'User';
        }

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
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $now = Carbon::now($timezone)->locale('id');

        return [
            'day'  => $now->translatedFormat('l'),
            'date' => $now->translatedFormat('d F Y'),
            'time' => $now->format('H:i'),
        ];
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

        $timezone = config('app.timezone', 'Asia/Jakarta');
        return $quotes[Carbon::now($timezone)->dayOfYear % count($quotes)];
    }
}
