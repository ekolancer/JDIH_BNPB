<?php

namespace App\Observers;

use App\Jobs\SendContentNotification;
use App\Models\DokumenHukum;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DokumenHukumObserver
{
    /**
     * Ambil user yang sedang login (pelaku aksi).
     * Return null jika CLI / Seeder / Queue.
     */
    private function actor(): ?User
    {
        return Auth::user();
    }

    /**
     * Bersihkan cache homepage setelah data publik berubah.
     */
    private function clearHomepageCache(): void
    {
        DB::afterCommit(fn () => app(DashboardService::class)->clearCache());
    }

    /**
     * Helper kirim notifikasi + logging
     */
    private function notify(
        DokumenHukum $dokumen,
        string $actionText, // contoh: "berhasil diperbarui"
        string $type = 'success',
        array $extraLog = []
    ): void {
        $actor = $this->actor();

        if (! $actor) {
            Log::info('Dokumen event tanpa user (CLI/Seeder)', [
                'dokumen_id' => $dokumen->id,
                'action' => $actionText,
            ]);

            return;
        }

        DB::afterCommit(function () use (
            $dokumen,
            $actor,
            $actionText,
            $type,
            $extraLog
        ) {
            SendContentNotification::dispatch(
                "{$dokumen->tipe_kode} {$actionText} oleh {$actor->name}",
                "Judul: {$dokumen->judul}",
                $type,
                array_merge([
                    'content_type' => 'dokumen_hukum',
                    'dokumen_id' => $dokumen->id,
                    'actor_id' => $actor->id,
                    'actor_name' => $actor->name,
                    'action' => $actionText,
                ], $extraLog)
            );

            Log::info('Notifikasi dokumen masuk antrian', array_merge([
                'dokumen_id' => $dokumen->id,
                'actor_id' => $actor->id,
                'actor_name' => $actor->name,
                'action' => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Created
     */
    public function created(DokumenHukum $dokumen): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $dokumen,
            'berhasil ditambahkan'
        );
    }

    /**
     * Updated
     */
    public function updated(DokumenHukum $dokumen): void
    {
        if (empty($dokumen->getChanges())) {
            return;
        }

        $this->clearHomepageCache();

        $this->notify(
            $dokumen,
            'berhasil diperbarui',
            'success',
            ['changes' => $dokumen->getChanges()]
        );
    }

    /**
     * Deleted
     */
    public function deleted(DokumenHukum $dokumen): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $dokumen,
            'berhasil dihapus',
            'danger'
        );
    }
}
