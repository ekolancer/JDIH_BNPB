<?php

namespace App\Models;

use App\Observers\FAQObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([FAQObserver::class])]
class Faq extends Model
{
    use Notifiable;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
    ];
}
