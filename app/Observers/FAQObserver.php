<?php

namespace App\Observers;

use App\Models\Faq;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FAQObserver
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
        Faq $faq,
        string $actionText,
        string $type = 'success',
        array $extraLog = []
    ): void {
        $actor = $this->actor();

        if (!$actor) {
            Log::info('FAQ event tanpa user (CLI/Seeder)', [
                'faq_id' => $faq->id,
                'action' => $actionText,
            ]);
            return;
        }

        $recipients = $this->recipients();

        DB::afterCommit(function () use (
            $faq,
            $actor,
            $recipients,
            $actionText,
            $type,
            $extraLog
        ) {
            foreach ($recipients as $recipient) {
                Notification::make()
                    ->title("FAQ {$actionText} oleh {$actor->name}")
                    ->{$type}()
                    ->sendToDatabase($recipient);
            }

            Log::info('Notifikasi FAQ terkirim', array_merge([
                'faq_id'      => $faq->id,
                'actor_id'    => $actor->id,
                'actor_name'  => $actor->name,
                'recipients'  => $recipients->pluck('id')->toArray(),
                'action'      => $actionText,
            ], $extraLog));
        });
    }

    /**
     * Handle the Faq "created" event.
     */
    public function created(Faq $faq): void
    {
        $this->notify(
            $faq,
            'berhasil ditambahkan'
        );
    }

    /**
     * Handle the Faq "updated" event.
     */
    public function updated(Faq $faq): void
    {
        if (empty($faq->getChanges())) {
            return;
        }

        $this->notify(
            $faq,
            'berhasil diperbarui',
            'success',
            ['changes' => $faq->getChanges()]
        );
    }

    /**
     * Handle the Faq "deleted" event.
     */
    public function deleted(Faq $faq): void
    {
        $this->notify(
            $faq,
            'berhasil dihapus',
            'danger'
        );
    }
}
