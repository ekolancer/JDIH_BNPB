<div class="fi-login-shell">
    <style>
        .fi-login-page {
            background:
                linear-gradient(135deg, rgba(8, 15, 32, 0.82), rgba(17, 38, 74, 0.68)),
                url("{{ asset('img/gBNPB.webp') }}") center center / cover no-repeat fixed !important;
        }
    </style>
    <section class="fi-login-layout">
        <div class="fi-login-card">
            <div class="fi-login-card__inner">
                <div class="fi-login-card__header">
                    <img src="{{ asset('img/new_jdih.png') }}" alt="Logo">
                </div>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

                <form id="form" wire:submit="authenticate" class="fi-login-form fi-sc-form">
                    {{ $this->form }}

                    {{-- Cloudflare Turnstile (visible mode untuk login) --}}
                    <x-turnstile mode="visible" />

                    <x-filament::actions
                        :actions="$this->getFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </form>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

            </div>
        </div>
    </section>

    <x-filament-actions::modals />
</div>
