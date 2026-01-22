<?php

namespace App\Observers;

use App\Models\Berita;
use App\Models\User;
use Filament\Notifications\Notification;
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
     * Penerima notifikasi.
     * Default: semua user
     */
    private function recipients()
    {
        return User::all();
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

        if (!$actor) {
            Log::info('Berita event tanpa user (CLI/Seeder)', [
                'berita_id' => $berita->id,
                'action'    => $actionText,
            ]);
            return;
        }

        $recipients = $this->recipients();

        DB::afterCommit(function () use (
            $berita,
            $actor,
            $recipients,
            $actionText,
            $type,
            $extraLog
        ) {
            foreach ($recipients as $recipient) {
                Notification::make()
                    ->title("Berita {$actionText} oleh {$actor->name}")
                    ->body("Judul: {$berita->judul}")
                    ->{$type}()
                    ->sendToDatabase($recipient);
            }

            Log::info('Notifikasi berita terkirim', array_merge([
                'berita_id'   => $berita->id,
                'actor_id'    => $actor->id,
                'actor_name'  => $actor->name,
                'recipients'  => $recipients->pluck('id')->toArray(),
                'action'      => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Handle the Berita "created" event.
     */
    public function created(Berita $berita): void
    {
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
        $this->notify(
            $berita,
            'berhasil dihapus',
            'danger'
        );
    }
}
