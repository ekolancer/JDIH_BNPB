<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class DatabaseBackup extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationLabel = 'Database Backup';
    protected static ?string $title = 'Database Backup';
    protected static ?string $slug = 'database-backup';
    protected static ?int $navigationSort = 99;

    public static function getNavigationGroup(): ?string
    {
        return 'Systems';
    }

    public function getView(): string
    {
        return 'filament.pages.database-backup';
    }

    public function getBackups(): array
    {
        $disk = Storage::disk('local');
        $backupPath = config('backup.backup.name', 'Laravel');

        if (!$disk->exists($backupPath)) {
            return [];
        }

        $files = $disk->files($backupPath);

        $backups = collect($files)
            ->filter(fn($file) => str_ends_with($file, '.zip'))
            ->map(function ($file) use ($disk) {
                return [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => $disk->size($file),
                    'size_human' => Number::fileSize($disk->size($file)),
                    'date' => $disk->lastModified($file),
                    'date_human' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))
                        ->locale('id')
                        ->translatedFormat('d F Y, H:i'),
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->toArray();

        return $backups;
    }

    public function getHealthStatus(): array
    {
        $disk = Storage::disk('local');
        $backupPath = config('backup.backup.name', 'Laravel');

        $backups = $this->getBackups();
        $latestBackup = $backups[0] ?? null;

        $status = [
            'total_backups' => count($backups),
            'total_size' => Number::fileSize(
                collect($backups)->sum('size')
            ),
            'latest_backup' => $latestBackup ? $latestBackup['date_human'] : 'Belum ada backup',
            'is_healthy' => false,
        ];

        // Healthy jika ada backup dalam 7 hari terakhir
        if ($latestBackup) {
            $lastBackupDate = \Carbon\Carbon::createFromTimestamp($latestBackup['date']);
            $status['is_healthy'] = $lastBackupDate->isAfter(now()->subDays(7));
        }

        return $status;
    }

    public function createBackup(): void
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);

            Notification::make()
                ->title('Backup berhasil dibuat')
                ->body('Database backup telah selesai.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Backup gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadBackup(string $path): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!Storage::disk('local')->exists($path)) {
            Notification::make()
                ->title('File tidak ditemukan')
                ->danger()
                ->send();
            return response()->noContent();
        }

        return Storage::disk('local')->download($path, basename($path));
    }

    public function deleteBackup(string $path): void
    {
        Storage::disk('local')->delete($path);

        Notification::make()
            ->title('Backup dihapus')
            ->body(basename($path) . ' telah dihapus.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label('Buat Backup Sekarang')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Buat Database Backup')
                ->modalDescription('Proses backup akan membuat file ZIP dari database. Lanjutkan?')
                ->action(fn() => $this->createBackup()),
        ];
    }
}
