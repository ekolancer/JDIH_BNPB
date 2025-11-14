<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DokumenHukum;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DokumenHukumObserver
{
    /**
     * Helper untuk mendeteksi user yang sedang login.
     * Jika tidak ada user (CLI / Tinker), return null agar observer skip notifikasi.
     */
    private function actor()
    {
        return Auth::user() ?? null;
    }

    /**
     * Handle the DokumenHukum "created" event.
     */
    public function created(DokumenHukum $dokumenHukum): void
    {
        $user = $this->actor();

        // Jika update berasal dari CLI/Tinker, skip notifikasi
        if (!$user) {
            Log::info("Dokumen created via CLI/Tinker", [
                'id'    => $dokumenHukum->id,
                'judul' => $dokumenHukum->judul,
            ]);
            return;
        }

        Notification::make()
            ->title("{$dokumenHukum->tipeDok} berhasil ditambahkan")
            ->body("Judul: {$dokumenHukum->judul} oleh {$user->name}")
            ->success()
            ->sendToDatabase($user);

        Log::info("Dokumen created", [
            'id'    => $dokumenHukum->id,
            'judul' => $dokumenHukum->judul,
            'oleh'  => $user->name,
        ]);
    }

    /**
     * Handle the DokumenHukum "updated" event.
     */
    public function updated(DokumenHukum $dokumenHukum): void
    {
        $user = $this->actor();

        if (!$user) {
            Log::info("Dokumen updated via CLI/Tinker", [
                'id'    => $dokumenHukum->id,
                'judul' => $dokumenHukum->judul,
            ]);
            return;
        }

        Notification::make()
            ->title("{$dokumenHukum->tipeDok} berhasil diperbarui")
            ->body("Judul: {$dokumenHukum->judul} oleh {$user->name}")
            ->success()
            ->sendToDatabase($user);

        Log::info("Dokumen updated", [
            'id'    => $dokumenHukum->id,
            'judul' => $dokumenHukum->judul,
            'oleh'  => $user->name,
            'changes' => $dokumenHukum->getChanges(),
        ]);
    }

    /**
     * Handle the DokumenHukum "deleted" event.
     */
    public function deleted(DokumenHukum $dokumenHukum): void
    {
        $user = $this->actor();

        if (!$user) {
            Log::warning("Dokumen deleted via CLI/Tinker", [
                'id'    => $dokumenHukum->id,
                'judul' => $dokumenHukum->judul,
            ]);
            return;
        }

        Notification::make()
            ->title("{$dokumenHukum->tipeDok} berhasil dihapus")
            ->body("Judul: {$dokumenHukum->judul} oleh {$user->name}")
            ->danger()
            ->sendToDatabase($user);

        Log::warning("Dokumen deleted", [
            'id'    => $dokumenHukum->id,
            'judul' => $dokumenHukum->judul,
            'oleh'  => $user->name,
        ]);
    }
}
