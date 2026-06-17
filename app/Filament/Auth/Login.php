<?php

namespace App\Filament\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as AuthLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Throwable;

class Login extends AuthLogin
{
    /**
     * @var view-string
     */
    protected string $view = 'filament.pages.auth.login';

    protected Width|string|null $maxWidth = 'full';

    protected array $extraBodyAttributes = [
        'class' => 'fi-login-page',
    ];

    public function getTitle(): string|Htmlable
    {
        return 'Login Admin';
    }

    public function hasLogo(): bool
    {
        return false;
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['login'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $username = $this->data['login'] ?? '(kosong)';
        $ip = request()->ip();
        $key = "login_attempts:{$username}:{$ip}";

        // Step 0️⃣: Verifikasi Turnstile
        $this->verifyTurnstile();

        // Step 1️⃣: Cek rate limiter
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $message = "Rate limit tercapai untuk {$username} dari IP {$ip}";
            Log::warning($message);

            throw ValidationException::withMessages([
                'data.login' => 'Terlalu banyak percobaan gagal. Silakan coba lagi dalam '.ceil($seconds / 60).' menit.',
            ]);
        }

        try {
            Log::info('Percobaan login', ['username' => $username, 'ip' => $ip, 'panel' => filament()->getCurrentPanel()?->getId()]);

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

    /**
     * Verifikasi Cloudflare Turnstile token.
     * Skip jika belum dikonfigurasi (development).
     */
    protected function verifyTurnstile(): void
    {
        $secretKey = config('turnstile.secret_key');

        // Skip jika belum dikonfigurasi
        if (empty($secretKey)) {
            return;
        }

        $token = request()->input('cf-turnstile-response');

        if (empty($token)) {
            throw ValidationException::withMessages([
                'data.login' => 'Verifikasi keamanan diperlukan. Silakan selesaikan challenge.',
            ]);
        }

        try {
            $response = Http::asForm()->post(config('turnstile.verify_url'), [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (! ($result['success'] ?? false)) {
                Log::warning('Turnstile login verification failed', [
                    'ip' => request()->ip(),
                    'errors' => $result['error-codes'] ?? [],
                ]);

                throw ValidationException::withMessages([
                    'data.login' => 'Verifikasi keamanan gagal. Silakan coba lagi.',
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Turnstile API unreachable', ['error' => $e->getMessage()]);

            if (config('turnstile.fail_open')) {
                return;
            }

            throw ValidationException::withMessages([
                'data.login' => 'Verifikasi keamanan sedang tidak tersedia. Silakan coba lagi nanti.',
            ]);
        }
    }
}
