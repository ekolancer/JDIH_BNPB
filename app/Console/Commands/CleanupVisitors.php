<?php

namespace App\Console\Commands;

use App\Models\Visitor;
use Illuminate\Console\Command;

class CleanupVisitors extends Command
{
    protected $signature = 'visitors:cleanup {--days=90 : Hapus record lebih tua dari N hari}';
    protected $description = 'Hapus data visitor yang lebih tua dari N hari (default: 90)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $this->info("Menghapus visitor records sebelum {$cutoff->toDateString()}...");

        $deleted = Visitor::where('visited_at', '<', $cutoff)->delete();

        $this->info("✅ {$deleted} record dihapus.");

        return Command::SUCCESS;
    }
}
