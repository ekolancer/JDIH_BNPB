@props(['mode' => 'invisible'])

@if(config('turnstile.site_key'))
    @if($mode === 'visible')
        {{-- Visible widget: user melihat challenge --}}
        <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}" data-theme="auto"></div>
    @else
        {{-- Invisible widget: otomatis di background --}}
        <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}" data-size="invisible" data-theme="auto"></div>
    @endif

    @once
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endonce
@endif
