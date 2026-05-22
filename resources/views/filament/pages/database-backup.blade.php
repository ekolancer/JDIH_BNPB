<x-filament-panels::page>
    <div class="space-y-4">

        @php
            $health  = $this->getHealthStatus();
            $backups = $this->getBackups();
        @endphp

        {{-- ── Health Status Cards ─────────────────────────────────────── --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">

            @php
                $cards = [
                    [
                        'label' => 'Total Backup',
                        'value' => $health['total_backups'],
                        'icon'  => 'heroicon-o-circle-stack',
                        'color' => 'blue',
                    ],
                    [
                        'label' => 'Total Ukuran',
                        'value' => $health['total_size'],
                        'icon'  => 'heroicon-o-server',
                        'color' => 'violet',
                    ],
                    [
                        'label' => 'Backup Terakhir',
                        'value' => $health['latest_backup'],
                        'icon'  => 'heroicon-o-clock',
                        'color' => 'amber',
                        'small' => true,
                    ],
                    [
                        'label'   => 'Status Health',
                        'value'   => $health['is_healthy'] ? 'Sehat' : 'Perlu Backup',
                        'icon'    => $health['is_healthy'] ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-circle',
                        'color'   => $health['is_healthy'] ? 'emerald' : 'rose',
                        'prefix'  => $health['is_healthy'] ? '✓' : '✗',
                    ],
                ];

                $colorMap = [
                    'blue'    => ['bg' => 'bg-blue-50 dark:bg-blue-950/30',      'icon' => 'text-blue-500',    'border' => 'border-blue-100 dark:border-blue-900'],
                    'violet'  => ['bg' => 'bg-violet-50 dark:bg-violet-950/30',  'icon' => 'text-violet-500',  'border' => 'border-violet-100 dark:border-violet-900'],
                    'amber'   => ['bg' => 'bg-amber-50 dark:bg-amber-950/30',    'icon' => 'text-amber-500',   'border' => 'border-amber-100 dark:border-amber-900'],
                    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/30','icon' => 'text-emerald-500', 'border' => 'border-emerald-100 dark:border-emerald-900'],
                    'rose'    => ['bg' => 'bg-rose-50 dark:bg-rose-950/30',      'icon' => 'text-rose-500',    'border' => 'border-rose-100 dark:border-rose-900'],
                ];
            @endphp

            @foreach($cards as $card)
                @php $c = $colorMap[$card['color']]; @endphp
                <div class="rounded-xl border {{ $c['border'] }} {{ $c['bg'] }} p-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</span>
                        <x-dynamic-component :component="$card['icon']" class="w-4 h-4 {{ $c['icon'] }}" />
                    </div>
                    <p class="{{ isset($card['small']) ? 'text-base' : 'text-2xl' }} font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                        {{ isset($card['prefix']) ? $card['prefix'] . ' ' : '' }}{{ $card['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- ── Info Bar ─────────────────────────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                <x-heroicon-o-information-circle class="w-3.5 h-3.5 inline" />
                Backup otomatis berjalan setiap hari pukul 01:00 &bull; Cleanup otomatis pukul 01:30
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                <x-heroicon-o-folder-open class="w-3.5 h-3.5 inline" />
                Disimpan di: <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-xs">storage/app/{{ config('backup.backup.name', 'Laravel') }}/</code>
            </p>
        </div>

        {{-- ── Backup List ──────────────────────────────────────────────── --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Daftar Backup</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ count($backups) }} file tersedia</p>
                </div>
                <x-heroicon-o-circle-stack class="w-5 h-5 text-gray-300 dark:text-gray-600" />
            </div>

            {{-- Empty State --}}
            @if(count($backups) === 0)
                <div class="flex flex-col items-center justify-center py-16 gap-3 text-center px-6">
                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <x-heroicon-o-circle-stack class="w-7 h-7 text-gray-300 dark:text-gray-600" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum ada backup</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Klik tombol <strong>"Buat Backup Sekarang"</strong> di atas untuk memulai.</p>
                    </div>
                </div>

            {{-- Table --}}
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50">
                                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama File
                                </th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">
                                    Ukuran
                                </th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                                    Tanggal
                                </th>
                                <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach($backups as $i => $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150 group">

                                    {{-- Nama File --}}
                                    <td class="py-3.5 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900 flex items-center justify-center shrink-0">
                                                <x-heroicon-o-document-arrow-down class="w-4 h-4 text-blue-500" />
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-mono text-xs font-medium text-gray-700 dark:text-gray-300 truncate max-w-xs">
                                                    {{ $backup['name'] }}
                                                </p>
                                                {{-- Mobile: tampilkan ukuran & tanggal di bawah nama --}}
                                                <p class="text-xs text-gray-400 mt-0.5 sm:hidden">
                                                    {{ $backup['size_human'] }} &bull; {{ $backup['date_human'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Ukuran --}}
                                    <td class="py-3.5 px-4 hidden sm:table-cell">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-md">
                                            <x-heroicon-o-server class="w-3 h-3" />
                                            {{ $backup['size_human'] }}
                                        </span>
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="py-3.5 px-4 hidden md:table-cell">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <x-heroicon-o-calendar class="w-3.5 h-3.5 shrink-0" />
                                            {{ $backup['date_human'] }}
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-3.5 px-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Download --}}
                                            <button
                                                wire:click="downloadBackup('{{ $backup['path'] }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400
                                                       border border-blue-100 dark:border-blue-900
                                                       hover:bg-blue-100 dark:hover:bg-blue-900/50
                                                       transition-colors duration-150"
                                            >
                                                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />
                                                <span class="hidden sm:inline">Download</span>
                                            </button>

                                            {{-- Delete --}}
                                            <button
                                                wire:click="deleteBackup('{{ $backup['path'] }}')"
                                                wire:confirm="Yakin ingin menghapus backup '{{ $backup['name'] }}'? Tindakan ini tidak dapat dibatalkan."
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400
                                                       border border-rose-100 dark:border-rose-900
                                                       hover:bg-rose-100 dark:hover:bg-rose-900/50
                                                       transition-colors duration-150"
                                            >
                                                <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Menampilkan {{ count($backups) }} backup &bull; Diurutkan dari terbaru
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
