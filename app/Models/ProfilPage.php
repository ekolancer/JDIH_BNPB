<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
    ];
}
