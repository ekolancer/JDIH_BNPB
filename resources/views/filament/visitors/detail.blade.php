@php
    use hisorange\BrowserDetect\Parser as Browser;

    // Deteksi browser dan OS dari user agent
    $browserName = Browser::browserFamily($visitor->user_agent);
    $osName = Browser::platformFamily($visitor->user_agent);

    // Tentukan ikon berdasarkan browser
    $browserIcon = match (true) {
        str_contains(strtolower($browserName), 'chrome') => 'heroicon-o-globe-alt',
        str_contains(strtolower($browserName), 'firefox') => 'heroicon-o-fire',
        str_contains(strtolower($browserName), 'safari') => 'heroicon-o-device-phone-mobile',
        str_contains(strtolower($browserName), 'edge') => 'heroicon-o-arrow-path-rounded-square',
        str_contains(strtolower($browserName), 'opera') => 'heroicon-o-cog',
        default => 'heroicon-o-computer-desktop',
    };

    // Tentukan emoji bendera dari kode negara
    function countryFlag($country) {
        if (!$country) return '🏳️';
        $countryCode = strtoupper(substr($country, 0, 2));
        return mb_convert_encoding(
            '&#' . (127397 + ord($countryCode[0])) . ';', 'UTF-8', 'HTML-ENTITIES'
        ) .
        mb_convert_encoding(
            '&#' . (127397 + ord($countryCode[1])) . ';', 'UTF-8', 'HTML-ENTITIES'
        );
    }
@endphp

<div class="p-6 bg-gray-50 rounded-2xl shadow-sm space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <x-heroicon-o-globe-asia-australia class="w-8 h-8 text-primary-600" />
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Detail Pengunjung</h2>
            <p class="text-sm text-gray-500">Informasi lengkap aktivitas kunjungan</p>
        </div>
    </div>

    {{-- Grid Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Lokasi --}}
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <h3 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <x-heroicon-o-map-pin class="w-5 h-5 text-primary-500" />
                Lokasi Pengunjung
            </h3>
            <div class="space-y-1 text-sm">
                <p><strong>IP Address:</strong> {{ $visitor->ip_address }}</p>
                <p><strong>Negara:</strong> {{ countryFlag($visitor->country) }} {{ $visitor->country ?? '-' }}</p>
                <p><strong>Kota:</strong> {{ $visitor->city ?? '-' }}</p>
            </div>
        </div>

        {{-- Akses --}}
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <h3 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <x-heroicon-o-link class="w-5 h-5 text-primary-500" />
                Akses Halaman
            </h3>
            <div class="space-y-1 text-sm">
                <p><strong>Path:</strong> <span class="font-mono text-gray-700">{{ $visitor->path }}</span></p>
                <p><strong>Referrer:</strong> 
                    @if ($visitor->referrer)
                        <a href="{{ $visitor->referrer }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $visitor->referrer }}
                        </a>
                    @else
                        <span class="text-gray-500">Direct</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Perangkat --}}
        <div class="bg-white p-4 rounded-xl border shadow-sm md:col-span-2">
            <h3 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <x-dynamic-component :component="$browserIcon" class="w-5 h-5 text-primary-500" />
                Perangkat & Browser
            </h3>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>Browser:</strong> {{ $browserName }}</p>
                <p><strong>Sistem Operasi:</strong> {{ $osName }}</p>
                <p><strong>User Agent:</strong> <span class="text-gray-500 break-all">{{ $visitor->user_agent }}</span></p>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white p-4 rounded-xl border shadow-sm md:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-1">Status Kunjungan</h3>
                    <p class="text-sm text-gray-500">Terakhir dikunjungi pada {{ $visitor->visited_at->format('d M Y H:i') }}</p>
                </div>

                @if ($visitor->suspicious)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-red-100 text-red-700 font-semibold text-sm animate-pulse">
                        <x-heroicon-o-shield-exclamation class="w-4 h-4" />
                        Aktivitas mencurigakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-green-100 text-green-700 font-semibold text-sm">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        Aman
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Animasi sederhana --}}
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out;
}
</style>