<x-filament-widgets::widget>
    @php
        $stats = $this->getStats();

        $gradients = [
            'blue'    => ['from' => '#3b82f6', 'to' => '#6366f1', 'glow' => 'rgba(99,102,241,0.35)'],
            'emerald' => ['from' => '#10b981', 'to' => '#06b6d4', 'glow' => 'rgba(16,185,129,0.35)'],
            'violet'  => ['from' => '#8b5cf6', 'to' => '#ec4899', 'glow' => 'rgba(139,92,246,0.35)'],
            'amber'   => ['from' => '#f59e0b', 'to' => '#ef4444', 'glow' => 'rgba(245,158,11,0.35)'],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 50)">

        @foreach ($stats as $i => $stat)
            @php $g = $gradients[$stat['color']] ?? $gradients['blue']; @endphp

            <div
                class="relative isolate overflow-hidden rounded-2xl border border-white/10 p-5 cursor-default transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-white/20"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition.delay.{{ $i * 100 }}ms
            >
                {{-- Shimmer on hover --}}
                <div class="pointer-events-none absolute inset-0 -translate-x-full skew-x-[-20deg] bg-gradient-to-r from-transparent via-white/5 to-transparent transition-transform duration-700 group-hover:translate-x-[200%]"
                     style="z-index:1;"></div>

                {{-- Top glow --}}
                <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full blur-2xl transition-all duration-500 hover:scale-150"
                     style="background: {{ $g['glow'] }}; opacity: 0.2;"></div>

                {{-- Bottom accent --}}
                <div class="pointer-events-none absolute -bottom-4 -left-4 h-16 w-16 rounded-full blur-xl"
                     style="background: {{ $g['from'] }}; opacity: 0.1;"></div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col gap-4">

                    {{-- Icon + pill --}}
                    <div class="flex items-start justify-between">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl transition-transform duration-300 hover:scale-110 hover:-rotate-3"
                             style="background: linear-gradient(135deg, {{ $g['from'] }}22, {{ $g['to'] }}33); border: 1px solid {{ $g['from'] }}44;">
                            @if($stat['icon'] === 'document-text')
                                <svg class="h-5 w-5" style="color:{{ $g['from'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                </svg>
                            @elseif($stat['icon'] === 'document-check')
                                <svg class="h-5 w-5" style="color:{{ $g['from'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            @elseif($stat['icon'] === 'tag')
                                <svg class="h-5 w-5" style="color:{{ $g['from'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                                </svg>
                            @elseif($stat['icon'] === 'arrow-down-tray')
                                <svg class="h-5 w-5" style="color:{{ $g['from'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            @endif
                        </div>

                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest"
                              style="background: {{ $g['from'] }}18; color: {{ $g['from'] }}; border: 1px solid {{ $g['from'] }}30;">
                            {{ $stat['label'] }}
                        </span>
                    </div>

                    {{-- Value (server-rendered, no Alpine override) --}}
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                           style="font-family: var(--font-heading); letter-spacing: -0.04em;">
                            {{ $stat['value'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $stat['description'] }}
                        </p>
                    </div>

                    {{-- Progress bar or divider --}}
                    @if(!is_null($stat['progress']))
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kelengkapan</span>
                                <span class="text-[11px] font-bold" style="color: {{ $g['from'] }}">{{ $stat['progress'] }}%</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-200/50 dark:bg-gray-700/40">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ $stat['progress'] }}%; background: linear-gradient(90deg, {{ $g['from'] }}, {{ $g['to'] }}); box-shadow: 0 0 8px {{ $g['glow'] }};">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="h-px w-full rounded-full"
                             style="background: linear-gradient(90deg, {{ $g['from'] }}40, transparent); opacity: 0.4;"></div>
                    @endif

                </div>
            </div>
        @endforeach

    </div>
</x-filament-widgets::widget>
