<?php

namespace App\Filament\Auth;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\Login as AuthLogin;
use Illuminate\Validation\ValidationException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Login extends AuthLogin
{
    use HasCustomLayout;

    public function form(Schema $schema): Schema
    {
        $formSchema = [
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ];

        return $schema->schema($formSchema)->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->suffixIcon('heroicon-m-user-circle')
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = 'name';

        return [
            $login_type => $data['login'],
            'password'  => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $username = $this->data['login'] ?? '(kosong)';
        $ip = request()->ip();
        $key = "login_attempts:{$username}:{$ip}";

        // Step 1️⃣: Cek rate limiter
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $message = "Rate limit tercapai untuk {$username} dari IP {$ip}";
            Log::warning($message);

            throw ValidationException::withMessages([
                'data.login' => "Terlalu banyak percobaan gagal. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }

        try {
            Log::info("Percobaan login", ['username' => $username, 'ip' => $ip, 'panel' => filament()->getCurrentPanel()?->getId()]);

            $response = parent::authenticate();

            // Step 2️⃣: Jika lolos autentikasi, cek user, role, dan permission
            $user = Auth::user();
            if ($user) {
                $panelId = filament()->getCurrentPanel()?->getId();
                $hasRole = $user->roles->pluck('name');
                $hasAccess = $user->can("access_{$panelId}");

                Log::info('Login berhasil', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'panel' => $panelId,
                    'roles' => $hasRole,
                    'has_access_permission' => $hasAccess,
                ]);

                // Jika pakai Filament Shield, cek akses panel
                if (config('filament-shield.panel_user.enabled', false) && ! $hasAccess) {
                    Log::warning('User tidak punya permission untuk panel', [
                        'user_id' => $user->id,
                        'panel' => $panelId,
                        'expected_permission' => "access_{$panelId}",
                    ]);

                    Auth::logout();

                    throw ValidationException::withMessages([
                        'data.login' => 'Anda tidak memiliki akses ke panel ini.',
                    ]);
                }
            } else {
                Log::error('Login berhasil tapi Auth::user() null?!', ['username' => $username]);
            }

            RateLimiter::clear($key); // Reset hitungan percobaan
            return $response;
        } catch (ValidationException $e) {
            RateLimiter::hit($key, 300); // 5 menit lockout

            Log::warning('Login gagal', [
                'username' => $username,
                'ip' => $ip,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw ValidationException::withMessages([
                'data.login' => 'Login gagal. Periksa username atau password Anda.',
            ]);
        } catch (Throwable $e) {
            // Tangkap error lain (misal koneksi DB, session, guard error, dll)
            Log::error('Kesalahan sistem saat login', [
                'username' => $username,
                'ip' => $ip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw ValidationException::withMessages([
                'data.login' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.',
            ]);
        }
    }
}
