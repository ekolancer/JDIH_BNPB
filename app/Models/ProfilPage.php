<?php

namespace App\Models;

use App\Observers\ProfilPageObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


#[ObservedBy([ProfilPageObserver::class])]
class ProfilPage extends Model
{
    use Notifiable, LogsActivity;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
