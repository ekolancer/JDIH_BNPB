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

            {{-- Chart Bars --}}
            @php
                $jenisData = $this->getDistribusiJenis();
                $maxJenis = collect($jenisData)->max('value') ?: 1;
                $jenisColors = ['bg-blue-500', 'bg-cyan-500', 'bg-indigo-500', 'bg-sky-500', 'bg-teal-500', 'bg-violet-500'];
            @endphp

            <div class="space-y-3">
                @forelse($jenisData as $index => $item)
                    <div class="group">
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[70%]">
                                {{ $item['label'] }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($item['value']) }}
                            </span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200/50 dark:bg-gray-700/50">
                            <div class="h-full rounded-full transition-all duration-700 ease-out {{ $jenisColors[$index % count($jenisColors)] }}"
                                 style="width: {{ ($item['value'] / $maxJenis) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data distribusi.</p>
                @endforelse
            </div>

            {{-- Total footer --}}
            <div class="mt-5 flex items-center justify-between rounded-xl border border-white/10 bg-white/20 px-4 py-2.5 dark:bg-black/20">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Dokumen</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($this->getTotalDokumen()) }}</span>
            </div>

            {{-- Decorative --}}
            <div class="pointer-events-none absolute -bottom-8 -right-8 h-32 w-32 rounded-full bg-blue-500 opacity-10 blur-3xl"></div>
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

            {{-- Chart Bars --}}
            @php
                $bidangData = $this->getDistribusiBidang();
                $maxBidang = collect($bidangData)->max('value') ?: 1;
                $bidangColors = ['bg-emerald-500', 'bg-green-500', 'bg-lime-500', 'bg-teal-500', 'bg-cyan-500', 'bg-emerald-400'];
            @endphp

            <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1" style="scrollbar-width: thin;">
                @forelse($bidangData as $index => $item)
                    <div class="group">
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[70%]">
                                {{ $item['label'] }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($item['value']) }}
                            </span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200/50 dark:bg-gray-700/50">
                            <div class="h-full rounded-full transition-all duration-700 ease-out {{ $bidangColors[$index % count($bidangColors)] }}"
                                 style="width: {{ ($item['value'] / $maxBidang) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data distribusi.</p>
                @endforelse
            </div>

            {{-- Total footer --}}
            <div class="mt-5 flex items-center justify-between rounded-xl border border-white/10 bg-white/20 px-4 py-2.5 dark:bg-black/20">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Bidang Hukum</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ count($bidangData) }} bidang</span>
            </div>

            {{-- Decorative --}}
            <div class="pointer-events-none absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-emerald-500 opacity-10 blur-3xl"></div>
        </div>
    </div>
</x-filament-widgets::widget>
