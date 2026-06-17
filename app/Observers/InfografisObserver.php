<?php

namespace App\Observers;

use App\Jobs\SendContentNotification;
use App\Models\Infografis;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InfografisObserver
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
        Infografis $infografis,
        string $actionText, // contoh: "berhasil ditambahkan"
        string $type = 'success',
        array $extraLog = []
    ): void {
        $actor = $this->actor();

        if (! $actor) {
            Log::info('Infografis event tanpa user (CLI/Seeder)', [
                'infografis_id' => $infografis->id,
                'action' => $actionText,
            ]);

            return;
        }

        DB::afterCommit(function () use (
            $infografis,
            $actor,
            $actionText,
            $type,
            $extraLog
        ) {
            SendContentNotification::dispatch(
                "Infografis {$actionText} oleh {$actor->name}",
                "Judul: {$infografis->judul}",
                $type,
                array_merge([
                    'content_type' => 'infografis',
                    'infografis_id' => $infografis->id,
                    'actor_id' => $actor->id,
                    'actor_name' => $actor->name,
                    'action' => $actionText,
                ], $extraLog)
            );

            Log::info('Notifikasi infografis masuk antrian', array_merge([
                'infografis_id' => $infografis->id,
                'actor_id' => $actor->id,
                'actor_name' => $actor->name,
                'action' => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Handle the Infografis "created" event.
     */
    public function created(Infografis $infografis): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $infografis,
            'berhasil ditambahkan'
        );
    }

    /**
     * Handle the Infografis "updated" event.
     */
    public function updated(Infografis $infografis): void
    {
        if (empty($infografis->getChanges())) {
            return;
        }

        $this->clearHomepageCache();

        $this->notify(
            $infografis,
            'berhasil diperbarui',
            'success',
            ['changes' => $infografis->getChanges()]
        );
    }

    /**
     * Handle the Infografis "deleted" event.
     */
    public function deleted(Infografis $infografis): void
    {
        $this->clearHomepageCache();

        $this->notify(
            $infografis,
            'berhasil dihapus',
            'danger'
        );
    }
}
