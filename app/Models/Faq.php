<?php

namespace App\Models;

use App\Observers\FAQObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


#[ObservedBy([FAQObserver::class])]
class Faq extends Model
{
    use Notifiable, LogsActivity;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
