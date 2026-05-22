<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Visitor extends Model
{
    use HasFactory, LogsActivity;

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
        return Cache::remember('visitor_stats', 600, function () {
            return [
                'total' => self::count(),
                'today' => self::whereDate('visited_at', today())->count(),
                'week' => self::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'month' => self::whereMonth('visited_at', now()->month)->count(),
                'year' => self::whereYear('visited_at', now()->year)->count(),
            ];
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
