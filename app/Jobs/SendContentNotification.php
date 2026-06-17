<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendContentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $backoff = 10;

    public function __construct(
        protected string $title,
        protected ?string $body,
        protected string $type,
        protected array $logContext = []
    ) {}

    public function handle(): void
    {
        $recipients = User::role(['super_admin', 'Admin'])->get();

        foreach ($recipients as $recipient) {
            $notification = Notification::make()
                ->title($this->title)
                ->{$this->type}();

            if (! empty($this->body)) {
                $notification->body($this->body);
            }

            $notification->sendToDatabase($recipient);
        }

        Log::info('Notifikasi konten terkirim', array_merge($this->logContext, [
            'recipients' => $recipients->pluck('id')->toArray(),
        ]));
    }
}
