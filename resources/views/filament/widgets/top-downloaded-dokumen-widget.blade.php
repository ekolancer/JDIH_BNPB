<x-filament-widgets::widget>
    <div class="fi-wi-modern-card relative overflow-hidden rounded-2xl border border-white/10 p-6 transition-all duration-300"
         style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
        
        {{-- Header --}}
        <div class="mb-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-rose-500 dark:text-rose-400">Top 5</p>
                <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                    Most Downloaded
                </h3>
            </div>
            <div class="flex items-center justify-center rounded-xl bg-rose-500/10 p-2.5 text-rose-500 dark:bg-rose-400/10 dark:text-rose-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-3">
            @forelse($this->getTopDownloaded() as $index => $doc)
                <div class="group flex items-center gap-4 rounded-xl border border-white/5 bg-white/10 p-3 transition-all duration-200 hover:border-rose-500/20 hover:bg-white/20 dark:bg-black/10 dark:hover:bg-black/20">
                    {{-- Rank Badge --}}
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg font-bold
                        @if($index === 0) bg-rose-500/20 text-rose-600 dark:text-rose-400
                        @elseif($index === 1) bg-gray-400/20 text-gray-600 dark:text-gray-300
                        @elseif($index === 2) bg-amber-500/20 text-amber-700 dark:text-amber-400
                        @else bg-gray-200/30 text-gray-500 dark:bg-gray-700/30 dark:text-gray-400
                        @endif"
                        style="font-family: var(--font-heading);">
                        {{ $index + 1 }}
                    </div>

                    {{-- Document Info --}}
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $doc['judul'] }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            {{ $doc['jenis'] }} {{ $doc['nomor'] ? 'No. ' . $doc['nomor'] : '' }} {{ $doc['tahun'] ? 'Tahun ' . $doc['tahun'] : '' }}
                        </p>
                    </div>

                    {{-- Download Count --}}
                    <div class="flex flex-shrink-0 items-center gap-1.5 rounded-lg bg-rose-500/10 px-2.5 py-1.5 text-rose-600 dark:text-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span class="text-xs font-bold">{{ number_format($doc['downloads']) }}</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada data.</p>
                </div>
            @endforelse
        </div>

        {{-- Decorative --}}
        <div class="pointer-events-none absolute -left-6 -bottom-6 h-28 w-28 rounded-full bg-rose-500 opacity-10 blur-3xl"></div>
    </div>
</x-filament-widgets::widget>
