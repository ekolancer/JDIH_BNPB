<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\File;

class GenerateSitemapTest extends TestCase
{
    /** @test */
    public function sitemap_command_runs_successfully(): void
    {
        $this->artisan('sitemap:generate')
            ->assertExitCode(0);
    }

    /** @test */
    public function sitemap_command_generates_valid_xml(): void
    {
        $this->artisan('sitemap:generate');

        $path = public_path('sitemap.xml');
        $this->assertFileExists($path);

        $content = File::get($path);

        // Valid XML structure
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);
        $this->assertStringContainsString('</urlset>', $content);

        // Contains static pages
        $this->assertStringContainsString('<loc>', $content);
        $this->assertStringContainsString('<lastmod>', $content);
        $this->assertStringContainsString('<priority>', $content);
    }

    /** @test */
    public function sitemap_contains_main_pages(): void
    {
        $this->artisan('sitemap:generate');

        $content = File::get(public_path('sitemap.xml'));
        $baseUrl = config('app.url');

        $this->assertStringContainsString($baseUrl . '/', $content);
        $this->assertStringContainsString('/berita', $content);
        $this->assertStringContainsString('/infografis', $content);
        $this->assertStringContainsString('/dokumen', $content);
    }
}
