<x-filament-widgets::widget>
    <div class="fi-wi-modern-card relative overflow-hidden rounded-2xl border border-white/10 p-6 transition-all duration-300"
         style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
        
        {{-- Header --}}
        <div class="mb-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-orange-500 dark:text-orange-400">Top 5</p>
                <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                    Most Viewed
                </h3>
            </div>
            <div class="flex items-center justify-center rounded-xl bg-orange-500/10 p-2.5 text-orange-500 dark:bg-orange-400/10 dark:text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-3">
            @forelse($this->getTopViewed() as $index => $doc)
                <div class="group flex items-center gap-4 rounded-xl border border-white/5 bg-white/10 p-3 transition-all duration-200 hover:border-orange-500/20 hover:bg-white/20 dark:bg-black/10 dark:hover:bg-black/20">
                    {{-- Rank Badge --}}
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg font-bold
                        @if($index === 0) bg-orange-500/20 text-orange-600 dark:text-orange-400
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

                    {{-- View Count --}}
                    <div class="flex flex-shrink-0 items-center gap-1.5 rounded-lg bg-orange-500/10 px-2.5 py-1.5 text-orange-600 dark:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="text-xs font-bold">{{ number_format($doc['views']) }}</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9.75m3 0H9.75m0 0H9.75m3-3H9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada data.</p>
                </div>
            @endforelse
        </div>

        {{-- Decorative --}}
        <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-orange-500 opacity-10 blur-3xl"></div>
    </div>
</x-filament-widgets::widget>
