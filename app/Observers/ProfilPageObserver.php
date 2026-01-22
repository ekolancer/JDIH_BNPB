<?php

namespace App\Observers;

use App\Models\ProfilPage;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfilPageObserver
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
        ProfilPage $profilPage,
        string $actionText,
        string $type = 'success',
        array $extraLog = []
    ): void {
        $actor = $this->actor();

        if (!$actor) {
            Log::info('ProfilPage event tanpa user (CLI/Seeder)', [
                'profil_page_id' => $profilPage->id,
                'action'         => $actionText,
            ]);
            return;
        }

        $recipients = $this->recipients();

        DB::afterCommit(function () use (
            $profilPage,
            $actor,
            $recipients,
            $actionText,
            $type,
            $extraLog
        ) {
            foreach ($recipients as $recipient) {
                Notification::make()
                    ->title("Profil halaman {$actionText} oleh {$actor->name}")
                    ->{$type}()
                    ->sendToDatabase($recipient);
            }

            Log::info('Notifikasi profil halaman terkirim', array_merge([
                'profil_page_id' => $profilPage->id,
                'actor_id'       => $actor->id,
                'actor_name'     => $actor->name,
                'recipients'     => $recipients->pluck('id')->toArray(),
                'action'         => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Handle the ProfilPage "created" event.
     */
    public function created(ProfilPage $profilPage): void
    {
        $this->notify(
            $profilPage,
            'berhasil ditambahkan'
        );
    }

    /**
     * Handle the ProfilPage "updated" event.
     */
    public function updated(ProfilPage $profilPage): void
    {
        if (empty($profilPage->getChanges())) {
            return;
        }

        $this->notify(
            $profilPage,
            'berhasil diperbarui',
            'success',
            ['changes' => $profilPage->getChanges()]
        );
    }

    /**
     * Handle the ProfilPage "deleted" event.
     */
    public function deleted(ProfilPage $profilPage): void
    {
        $this->notify(
            $profilPage,
            'berhasil dihapus',
            'danger'
        );
    }
}
