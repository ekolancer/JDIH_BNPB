@php
    use hisorange\BrowserDetect\Parser as Browser;

    $browserName = Browser::browserFamily($visitor->user_agent);
    $osName = Browser::platformFamily($visitor->user_agent);

    $browserIcon = match (true) {
        str_contains(strtolower($browserName), 'chrome') => 'heroicon-o-globe-alt',
        str_contains(strtolower($browserName), 'firefox') => 'heroicon-o-fire',
        str_contains(strtolower($browserName), 'safari') => 'heroicon-o-device-phone-mobile',
        str_contains(strtolower($browserName), 'edge') => 'heroicon-o-arrow-path-rounded-square',
        str_contains(strtolower($browserName), 'opera') => 'heroicon-o-cog',
        default => 'heroicon-o-computer-desktop',
    };

    function countryFlag($country) {
        if (!$country) return '🏳️';
        $countryCode = strtoupper(substr($country, 0, 2));
        return mb_convert_encoding('&#' . (127397 + ord($countryCode[0])) . ';', 'UTF-8', 'HTML-ENTITIES')
            . mb_convert_encoding('&#' . (127397 + ord($countryCode[1])) . ';', 'UTF-8', 'HTML-ENTITIES');
    }
@endphp

<div class="p-6 space-y-6 rounded-xl
    bg-gray-50 text-gray-800
    dark:bg-[var(--c-primary-dark)] dark:text-[var(--c-text)]
    shadow-sm animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <x-heroicon-o-globe-asia-australia
            class="w-8 h-8 text-primary-600 dark:text-primary-400" />
        <div>
            <h2 class="text-lg font-semibold">Detail Pengunjung</h2>
            <p class="text-sm text-gray-500 dark:text-[var(--c-text-muted)]">
                Informasi lengkap aktivitas kunjungan
            </p>
        </div>
    </div>

    {{-- Grid Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Lokasi --}}
        <div class="p-4 rounded-xl border
            bg-white border-gray-200
            dark:bg-[var(--c-bg-secondary)] dark:border-[var(--c-border)]">
            <h3 class="font-semibold mb-2 flex items-center gap-2
                text-gray-700 dark:text-[var(--c-text)]">
                <x-heroicon-o-map-pin class="w-5 h-5 text-primary-500" />
                Lokasi Pengunjung
            </h3>
            <div class="space-y-1 text-sm text-gray-700 dark:text-[var(--c-text-muted)]">
                <p><strong>IP Address:</strong> {{ $visitor->ip_address }}</p>
                <p><strong>Negara:</strong> {{ countryFlag($visitor->country) }} {{ $visitor->country ?? '-' }}</p>
                <p><strong>Kota:</strong> {{ $visitor->city ?? '-' }}</p>
            </div>
        </div>

        {{-- Akses --}}
        <div class="p-4 rounded-xl border
            bg-white border-gray-200
            dark:bg-[var(--c-bg-secondary)] dark:border-[var(--c-border)]">
            <h3 class="font-semibold mb-2 flex items-center gap-2
                text-gray-700 dark:text-[var(--c-text)]">
                <x-heroicon-o-link class="w-5 h-5 text-primary-500" />
                Akses Halaman
            </h3>
            <div class="space-y-1 text-sm text-gray-700 dark:text-[var(--c-text-muted)]">
                <p><strong>Path:</strong>
                    <span class="font-mono break-all">{{ $visitor->path }}</span>
                </p>
                <p><strong>Referrer:</strong>
                    @if ($visitor->referrer)
                        <a href="{{ $visitor->referrer }}" target="_blank"
                           class="text-blue-600 dark:text-primary-400 hover:underline">
                            {{ $visitor->referrer }}
                        </a>
                    @else
                        <span class="opacity-70">Direct</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Perangkat --}}
        <div class="p-4 rounded-xl border md:col-span-2
            bg-white border-gray-200
            dark:bg-[var(--c-bg-secondary)] dark:border-[var(--c-border)]">
            <h3 class="font-semibold mb-2 flex items-center gap-2
                text-gray-700 dark:text-[var(--c-text)]">
                <x-dynamic-component :component="$browserIcon"
                    class="w-5 h-5 text-primary-500" />
                Perangkat & Browser
            </h3>
            <div class="text-sm space-y-1 text-gray-700 dark:text-[var(--c-text-muted)]">
                <p><strong>Browser:</strong> {{ $browserName }}</p>
                <p><strong>Sistem Operasi:</strong> {{ $osName }}</p>
                <p><strong>User Agent:</strong>
                    <span class="break-all opacity-70">{{ $visitor->user_agent }}</span>
                </p>
            </div>
        </div>

        {{-- Status --}}
        <div class="p-4 rounded-xl border md:col-span-2
            bg-white border-gray-200
            dark:bg-[var(--c-bg-secondary)] dark:border-[var(--c-border)]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-700 dark:text-[var(--c-text)]">
                        Status Kunjungan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-[var(--c-text-muted)]">
                        Terakhir dikunjungi pada {{ $visitor->visited_at->format('d M Y H:i') }}
                    </p>
                </div>

                @if ($visitor->suspicious)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full
                        bg-red-100 text-red-700
                        dark:bg-red-500/20 dark:text-red-400
                        font-semibold text-sm animate-pulse">
                        <x-heroicon-o-shield-exclamation class="w-4 h-4" />
                        Aktivitas mencurigakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full
                        bg-green-100 text-green-700
                        dark:bg-green-500/20 dark:text-green-400
                        font-semibold text-sm">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        Aman
                    </span>
                @endif
            </div>
        </div>

    </div>
</div>
