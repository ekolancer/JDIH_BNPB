<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

   protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at',
        'path',
        'referrer',
        'country',
        'city',
        'suspicious',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'suspicious' => 'boolean',
    ];

    public static function getVisitorStats()
    {
        return [
            'total' => self::count(),
            'today' => self::whereDate('visited_at', today())->count(),
            'week' => self::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => self::whereMonth('visited_at', now()->month)->count(),
            'year' => self::whereYear('visited_at', now()->year)->count(),
        ];
    }
}
