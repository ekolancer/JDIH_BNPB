<?php

namespace App\Models;

use App\Observers\ProfilPageObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ProfilPageObserver::class])]
class ProfilPage extends Model
{
    use Notifiable;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
    ];
}
