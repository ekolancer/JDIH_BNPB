<?php

namespace Tests\Feature;

use Tests\TestCase;

class RobotsTxtTest extends TestCase
{
    /** @test */
    public function robots_txt_exists_in_public_directory(): void
    {
        $this->assertFileExists(public_path('robots.txt'));

        $content = file_get_contents(public_path('robots.txt'));
        $this->assertNotEmpty($content);
        $this->assertStringContainsString('User-agent:', $content);
    }

    /** @test */
    public function robots_txt_disallows_admin_panel(): void
    {
        $content = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /webmin/', $content);
    }

    /** @test */
    public function robots_txt_contains_sitemap(): void
    {
        $content = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Sitemap:', $content);
        $this->assertStringContainsString('sitemap.xml', $content);
    }

    /** @test */
    public function robots_txt_disallows_storage_files(): void
    {
        $content = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /storage/file/', $content);
    }
}
