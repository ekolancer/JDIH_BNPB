<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CleanupVisitorsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function cleanup_command_runs_successfully(): void
    {
        $this->artisan('visitors:cleanup --days=90')
            ->assertExitCode(0);
    }

    /** @test */
    public function cleanup_command_accepts_custom_days(): void
    {
        $this->artisan('visitors:cleanup --days=30')
            ->expectsOutputToContain('record dihapus')
            ->assertExitCode(0);
    }

    /** @test */
    public function cleanup_preserves_recent_records(): void
    {
        // Buat record hari ini
        $recent = Visitor::create([
            'ip_address' => '203.0.113.99',
            'user_agent' => 'TestAgent',
            'path' => '/test',
            'referrer' => 'Direct',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'suspicious' => false,
            'visited_at' => now(),
        ]);

        $this->artisan('visitors:cleanup --days=90');

        // Record hari ini harus tetap ada
        $this->assertDatabaseHas('visitors', ['id' => $recent->id]);
    }
}
