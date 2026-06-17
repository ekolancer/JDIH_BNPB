<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
            $now = now();

            return [
                'total' => self::count(),
                'today' => self::whereBetween('visited_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->count(),
                'week' => self::whereBetween('visited_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count(),
                'month' => self::whereBetween('visited_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count(),
                'year' => self::whereBetween('visited_at', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])->count(),
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
