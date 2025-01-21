<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DokumenHukum;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DokumenHukumObserver
{
    /**
     * Handle the DokumenHukum "created" event.
     */
    public function created(DokumenHukum $dokumenHukum): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("{$dokumenHukum->tipeDok} successfully added by {$currentUser}.")
                ->body("Judul: {$dokumenHukum->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the DokumenHukum "updated" event.
     */
    public function updated(DokumenHukum $dokumenHukum): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("{$dokumenHukum->tipeDok} updated successfully by {$currentUser}.")
                ->body("Judul: {$dokumenHukum->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the DokumenHukum "deleted" event.
     */
    public function deleted(DokumenHukum $dokumenHukum): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("{$dokumenHukum->tipeDok} successfully deleted by {$currentUser}.")
                ->body("Judul: {$dokumenHukum->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the DokumenHukum "restored" event.
     */
    public function restored(DokumenHukum $dokumenHukum): void
    {
        //
    }

    /**
     * Handle the DokumenHukum "force deleted" event.
     */
    public function forceDeleted(DokumenHukum $dokumenHukum): void
    {
        //
    }
}
