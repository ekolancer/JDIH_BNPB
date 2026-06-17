<x-filament-widgets::widget>
    <div class="fi-wi-category grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Distribusi per Jenis Dokumen --}}
        <div class="fi-wi-category__visual-panel relative overflow-hidden rounded-2xl border border-white/10 p-6 transition-all duration-300"
             style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
            
            {{-- Header --}}
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-500 dark:text-blue-400">Distribusi</p>
                    <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                        Per Jenis Dokumen
                    </h3>
                </div>
                <div class="flex items-center justify-center rounded-xl bg-blue-500/10 p-2.5 text-blue-500 dark:bg-blue-400/10 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
                    </svg>
                </div>
            </div>

            {{-- Pie Chart --}}
            @php
                $jenisData = $this->getDistribusiJenis();
                $totalJenis = collect($jenisData)->sum('value');
                $jenisColors = ['#3b82f6', '#06b6d4', '#6366f1', '#0ea5e9', '#14b8a6', '#8b5cf6'];
                $jenisStart = 0;
                $jenisSegments = [];

                foreach ($jenisData as $index => $item) {
                    $percentage = $totalJenis > 0 ? ($item['value'] / $totalJenis) * 100 : 0;
                    $jenisEnd = $jenisStart + $percentage;
                    $jenisSegments[] = "{$jenisColors[$index % count($jenisColors)]} {$jenisStart}% {$jenisEnd}%";
                    $jenisStart = $jenisEnd;
                }
            @endphp

            @if($totalJenis > 0)
                <div class="flex flex-col gap-6 md:flex-row md:items-center">
                    <div class="relative mx-auto h-48 w-48 shrink-0 rounded-full shadow-lg"
                         style="background: conic-gradient({{ implode(', ', $jenisSegments) }});">
                        <div class="absolute inset-10 flex flex-col items-center justify-center rounded-full bg-white/90 text-center shadow-inner dark:bg-gray-900/90">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total</span>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalJenis) }}</span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 space-y-3">
                        @foreach($jenisData as $index => $item)
                            @php
                                $percentage = $totalJenis > 0 ? round(($item['value'] / $totalJenis) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 shrink-0 rounded-full"
                                      style="background: {{ $jenisColors[$index % count($jenisColors)] }}"></span>
                                <span class="min-w-0 flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item['label'] }}
                                </span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ number_format($item['value']) }}
                                </span>
                                <span class="w-12 text-right text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data distribusi.</p>
            @endif

            {{-- Total footer --}}
            <div class="mt-5 flex items-center justify-between rounded-xl border border-white/10 bg-white/20 px-4 py-2.5 dark:bg-black/20">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Dokumen</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($this->getTotalDokumen()) }}</span>
            </div>
        </div>

        {{-- Distribusi per Bidang Hukum --}}
        <div class="fi-wi-category__visual-panel relative overflow-hidden rounded-2xl border border-white/10 p-6 transition-all duration-300"
             style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
            
            {{-- Header --}}
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-500 dark:text-emerald-400">Distribusi</p>
                    <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                        Per Bidang Hukum
                    </h3>
                </div>
                <div class="flex items-center justify-center rounded-xl bg-emerald-500/10 p-2.5 text-emerald-500 dark:bg-emerald-400/10 dark:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                    </svg>
                </div>
            </div>

            {{-- Pie Chart --}}
            @php
                $bidangData = $this->getDistribusiBidang();
                $totalBidang = collect($bidangData)->sum('value');
                $bidangColors = ['#10b981', '#22c55e', '#84cc16', '#14b8a6', '#06b6d4', '#34d399', '#0f766e', '#65a30d'];
                $bidangStart = 0;
                $bidangSegments = [];

                foreach ($bidangData as $index => $item) {
                    $percentage = $totalBidang > 0 ? ($item['value'] / $totalBidang) * 100 : 0;
                    $bidangEnd = $bidangStart + $percentage;
                    $bidangSegments[] = "{$bidangColors[$index % count($bidangColors)]} {$bidangStart}% {$bidangEnd}%";
                    $bidangStart = $bidangEnd;
                }
            @endphp

            @if($totalBidang > 0)
                <div class="flex flex-col gap-6 md:flex-row md:items-center">
                    <div class="relative mx-auto h-48 w-48 shrink-0 rounded-full shadow-lg"
                         style="background: conic-gradient({{ implode(', ', $bidangSegments) }});">
                        <div class="absolute inset-10 flex flex-col items-center justify-center rounded-full bg-white/90 text-center shadow-inner dark:bg-gray-900/90">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total</span>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalBidang) }}</span>
                        </div>
                    </div>

                    <div class="max-h-60 min-w-0 flex-1 space-y-3 overflow-y-auto pr-1" style="scrollbar-width: thin;">
                        @foreach($bidangData as $index => $item)
                            @php
                                $percentage = $totalBidang > 0 ? round(($item['value'] / $totalBidang) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 shrink-0 rounded-full"
                                      style="background: {{ $bidangColors[$index % count($bidangColors)] }}"></span>
                                <span class="min-w-0 flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item['label'] }}
                                </span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ number_format($item['value']) }}
                                </span>
                                <span class="w-12 text-right text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data distribusi.</p>
            @endif

            {{-- Total footer --}}
            <div class="mt-5 flex items-center justify-between rounded-xl border border-white/10 bg-white/20 px-4 py-2.5 dark:bg-black/20">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Bidang Hukum</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ count($bidangData) }} bidang</span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
