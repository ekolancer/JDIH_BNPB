<?php

namespace App\Observers;

use App\Models\Infografis;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class InfografisObserver
{
    /**
     * Handle the Infografis "created" event.
     */
    public function created(Infografis $infografis): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Infografis successfully added by {$currentUser}.")
                ->body("Judul: {$infografis->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Infografis "updated" event.
     */
    public function updated(Infografis $infografis): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Infografis updated successfully by {$currentUser}.")
                ->body("Judul: {$infografis->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Infografis "deleted" event.
     */
    public function deleted(Infografis $infografis): void
    {
        $recipient = Auth::user();
        $currentUser = Auth::user()->name; // Mendapatkan nama pengguna yang login

            Notification::make()
                ->title("Infografis successfully deleted by {$currentUser}.")
                ->body("Judul: {$infografis->judul}.")
                ->success()
                ->sendToDatabase($recipient);
    }

    /**
     * Handle the Infografis "restored" event.
     */
    public function restored(Infografis $infografis): void
    {
        //
    }

    /**
     * Handle the Infografis "force deleted" event.
     */
    public function forceDeleted(Infografis $infografis): void
    {
        //
    }
}
