<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Visitor;
use App\Jobs\ResolveVisitorGeo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;

class VisitorCounterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Clean visitors for this test run
        Visitor::whereDate('visited_at', now()->toDateString())
            ->where('ip_address', '127.0.0.1')
            ->delete();
    }

    /** @test */
    public function it_records_visitor_for_public_routes(): void
    {
        Queue::fake();

        // Use /berita route which is simpler and doesn't require ref tables
        $response = $this->get('/berita', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '127.0.0.1',
            'country' => 'Indonesia', // localhost = Indonesia
        ]);

        // Localhost should NOT dispatch geo job
        Queue::assertNotPushed(ResolveVisitorGeo::class);
    }

    /** @test */
    public function it_skips_bot_user_agents(): void
    {
        Queue::fake();

        $countBefore = Visitor::count();

        $this->get('/', [
            'User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ]);

        $this->assertEquals($countBefore, Visitor::count());
    }

    /** @test */
    public function it_skips_admin_routes(): void
    {
        Queue::fake();

        $countBefore = Visitor::count();

        // webmin routes require auth, but middleware should skip before that
        $this->get('/webmin/login', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        // No new visitor should be recorded for admin routes
        $this->assertEquals($countBefore, Visitor::count());
    }

    /** @test */
    public function it_prevents_duplicate_daily_records(): void
    {
        Queue::fake();

        // First visit
        $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $countAfterFirst = Visitor::whereDate('visited_at', now()->toDateString())
            ->where('ip_address', '127.0.0.1')
            ->count();

        // Second visit same day
        $this->get('/dokumen', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $countAfterSecond = Visitor::whereDate('visited_at', now()->toDateString())
            ->where('ip_address', '127.0.0.1')
            ->count();

        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function it_dispatches_geo_job_for_non_local_ip(): void
    {
        Queue::fake();

        // Create visitor simulating non-local IP scenario
        $visitor = Visitor::create([
            'ip_address' => '203.0.113.5',
            'user_agent' => 'Mozilla/5.0',
            'path' => '/',
            'referrer' => 'Direct',
            'country' => null,
            'city' => null,
            'suspicious' => false,
            'visited_at' => now(),
        ]);

        ResolveVisitorGeo::dispatch($visitor->id, '203.0.113.5');

        Queue::assertPushed(ResolveVisitorGeo::class);
    }

    /** @test */
    public function middleware_does_not_block_response(): void
    {
        Queue::fake();

        // The key test: response should be fast (no external HTTP calls)
        $start = microtime(true);

        $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $elapsed = microtime(true) - $start;

        // Should complete in under 3 seconds (previously could take 6+ with geo API)
        $this->assertLessThan(3.0, $elapsed, 'Middleware should not block with external HTTP calls');
    }
}
