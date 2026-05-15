<x-filament-panels::page>
    {{-- Health Status Cards --}}
    @php $health = $this->getHealthStatus(); @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-filament::section>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600">{{ $health['total_backups'] }}</div>
                <div class="text-sm text-gray-500">Total Backup</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600">{{ $health['total_size'] }}</div>
                <div class="text-sm text-gray-500">Total Ukuran</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600 text-sm">{{ $health['latest_backup'] }}</div>
                <div class="text-sm text-gray-500">Backup Terakhir</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                @if($health['is_healthy'])
                    <div class="text-2xl font-bold text-success-600">✓ Sehat</div>
                @else
                    <div class="text-2xl font-bold text-danger-600">✗ Perlu Backup</div>
                @endif
                <div class="text-sm text-gray-500">Status Health</div>
            </div>
        </x-filament::section>
    </div>

    {{-- Backup List Table --}}
    <x-filament::section>
        <x-slot name="heading">Daftar Backup</x-slot>

        @php $backups = $this->getBackups(); @endphp

        @if(count($backups) === 0)
            <div class="text-center py-8 text-gray-500">
                <x-heroicon-o-circle-stack class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p>Belum ada backup. Klik tombol "Buat Backup Sekarang" untuk memulai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">Nama File</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">Ukuran</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">Tanggal</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-600 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-document-arrow-down class="w-5 h-5 text-gray-400" />
                                        <span class="font-mono text-xs">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $backup['size_human'] }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $backup['date_human'] }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-filament::button
                                            size="xs"
                                            color="info"
                                            icon="heroicon-o-arrow-down-tray"
                                            wire:click="downloadBackup('{{ $backup['path'] }}')"
                                        >
                                            Download
                                        </x-filament::button>

                                        <x-filament::button
                                            size="xs"
                                            color="danger"
                                            icon="heroicon-o-trash"
                                            wire:click="deleteBackup('{{ $backup['path'] }}')"
                                            wire:confirm="Yakin ingin menghapus backup ini?"
                                        >
                                            Hapus
                                        </x-filament::button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
