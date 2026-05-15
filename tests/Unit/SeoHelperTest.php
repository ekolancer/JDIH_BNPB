<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\SeoHelper;
use App\Models\Berita;
use App\Models\Infografis;
use App\Models\DokumenHukum;

class SeoHelperTest extends TestCase
{
    /** @test */
    public function it_generates_berita_seo_meta_using_konten_field(): void
    {
        $berita = new Berita();
        $berita->judul = 'Judul Berita Test';
        $berita->konten = '<p>Ini adalah konten berita yang cukup panjang untuk testing SEO helper description field.</p>';
        $berita->gambar = 'lampiran/berita/test.jpg';

        $meta = SeoHelper::meta($berita);

        $this->assertStringContainsString('Judul Berita Test', $meta['title']);
        $this->assertStringContainsString('Ini adalah konten berita', $meta['description']);
        $this->assertStringNotContainsString('<p>', $meta['description']); // HTML stripped
        $this->assertEquals('article', $meta['type']);
        $this->assertStringContainsString('storage/lampiran/berita/test.jpg', $meta['image']);
    }

    /** @test */
    public function it_generates_berita_seo_with_fallback_image(): void
    {
        $berita = new Berita();
        $berita->judul = 'Berita Tanpa Gambar';
        $berita->konten = 'Konten singkat.';
        $berita->gambar = null;

        $meta = SeoHelper::meta($berita);

        $this->assertStringContainsString('og_image.png', $meta['image']);
    }

    /** @test */
    public function it_generates_infografis_seo_meta(): void
    {
        $infografis = new Infografis();
        $infografis->judul = 'Infografis Hukum Pidana';
        $infografis->gambar = 'infografis/test.png';

        $meta = SeoHelper::meta($infografis);

        $this->assertStringContainsString('Infografis Hukum Pidana', $meta['title']);
        $this->assertEquals('article', $meta['type']);
    }

    /** @test */
    public function it_returns_default_meta_for_unknown_model(): void
    {
        $unknown = new \stdClass();

        $meta = SeoHelper::meta($unknown);

        $this->assertArrayHasKey('title', $meta);
        $this->assertArrayHasKey('description', $meta);
        $this->assertArrayHasKey('url', $meta);
    }
}
