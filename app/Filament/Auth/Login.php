<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\LoginResponse;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Afatmustafa\FilamentTurnstile\Forms\Components\Turnstile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class Login extends AuthLogin
{
    use HasCustomLayout;

    public function form(Form $form): Form
    {
        return $form
            ->schema([ 
                $this->getLoginFormComponent(), 
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Turnstile::make('turnstile')
                    ->theme('light')
                    ->size('normal')
                    ->language('en-US'),
            ])
            ->statePath('data');
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
        $username = $this->data['login'];
        $ip = request()->ip();
        $key = "login_attempts:{$username}:{$ip}";
    
        // Check rate limiter
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'data.login' => "Terlalu banyak percobaan gagal. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }
    
        try {
            $response = parent::authenticate();
            RateLimiter::clear($key); // Reset on success
            return $response;
        } catch (ValidationException) {
            RateLimiter::hit($key, 300); // 5 minutes lockout
            Log::warning('Login gagal', ['username' => $username, 'ip' => $ip, 'time' => now()]);
            throw ValidationException::withMessages([
                'data.login' => 'Login gagal. Periksa username atau password Anda.',
            ]);
        }
    }
}