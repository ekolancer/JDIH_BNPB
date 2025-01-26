<?php

namespace App\Observers;

use App\Models\Berita;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class BeritaObserver
{
    /**
     * Handle the Berita "created" event.
     */
    public function created(Berita $berita): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Berita successfully added by {$currentUser}.")
                ->body("Judul: {$berita->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Berita "updated" event.
     */
    public function updated(Berita $berita): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Berita updated successfully by {$currentUser}.")
                ->body("Judul: {$berita->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Berita "deleted" event.
     */
    public function deleted(Berita $berita): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Berita successfully deleted by {$currentUser}.")
                ->body("Judul: {$berita->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Berita "restored" event.
     */
    public function restored(Berita $berita): void
    {
        //
    }

    /**
     * Handle the Berita "force deleted" event.
     */
    public function forceDeleted(Berita $berita): void
    {
        //
    }
}
