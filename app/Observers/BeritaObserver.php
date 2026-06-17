<?php

namespace App\Observers;

use App\Jobs\SendContentNotification;
use App\Models\Berita;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeritaObserver
{
    /**
     * User yang melakukan aksi (actor).
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
        Berita $berita,
        string $actionText, // contoh: "berhasil ditambahkan"
        string $type = 'success',
        array $extraLog = []
    ): void {
        $actor = $this->actor();

        if (! $actor) {
            Log::info('Berita event tanpa user (CLI/Seeder)', [
                'berita_id' => $berita->id,
                'action' => $actionText,
            ]);

            return;
        }

        DB::afterCommit(function () use (
            $berita,
            $actor,
            $actionText,
            $type,
            $extraLog
        ) {
            SendContentNotification::dispatch(
                "Berita {$actionText} oleh {$actor->name}",
                "Judul: {$berita->judul}",
                $type,
                array_merge([
                    'content_type' => 'berita',
                    'berita_id' => $berita->id,
                    'actor_id' => $actor->id,
                    'actor_name' => $actor->name,
                    'action' => $actionText,
                ], $extraLog)
            );

            Log::info('Notifikasi berita masuk antrian', array_merge([
                'berita_id' => $berita->id,
                'actor_id' => $actor->id,
                'actor_name' => $actor->name,
                'action' => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Handle the Berita "created" event.
     */
    public function created(Berita $berita): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $berita,
            'berhasil ditambahkan'
        );
    }

    /**
     * Handle the Berita "updated" event.
     */
    public function updated(Berita $berita): void
    {
        if (empty($berita->getChanges())) {
            return;
        }

        $this->clearHomepageCache();

        $this->notify(
            $berita,
            'berhasil diperbarui',
            'success',
            ['changes' => $berita->getChanges()]
        );
    }

    /**
     * Handle the Berita "deleted" event.
     */
    public function deleted(Berita $berita): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $berita,
            'berhasil dihapus',
            'danger'
        );
    }
}
