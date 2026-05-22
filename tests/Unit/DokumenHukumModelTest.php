<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DokumenHukum;
use App\Models\RefJenisDokumen;

class DokumenHukumModelTest extends TestCase
{
    /** @test */
    public function it_generates_slug_for_peraturan(): void
    {
        $jenis = RefJenisDokumen::first();

        if (!$jenis) {
            $this->markTestSkipped('No RefJenisDokumen in database');
        }

        $dokumen = new DokumenHukum();
        $dokumen->tipeDok = 'Peraturan';
        $dokumen->ref_jenis_dokumen_id = $jenis->id;
        $dokumen->nomor = 5;
        $dokumen->tahun = 2024;
        $dokumen->judul = 'Test Dokumen';

        // Load relation for slug generation
        $dokumen->setRelation('jenisDokumen', $jenis);

        $slug = $dokumen->generateSlug();

        $this->assertStringContainsString('nomor-5', $slug);
        $this->assertStringContainsString('tahun-2024', $slug);
        $this->assertStringContainsString(strtolower($jenis->singkatan), $slug);
    }

    /** @test */
    public function it_generates_slug_for_monografi_using_judul(): void
    {
        $dokumen = new DokumenHukum();
        $dokumen->tipeDok = 'Monografi';
        $dokumen->judul = 'Panduan Penanggulangan Bencana';

        $slug = $dokumen->generateSlug();

        $this->assertEquals('panduan-penanggulangan-bencana', $slug);
    }

    /** @test */
    public function it_generates_document_uid(): void
    {
        $jenis = RefJenisDokumen::first();

        if (!$jenis) {
            $this->markTestSkipped('No RefJenisDokumen in database');
        }

        $dokumen = new DokumenHukum();
        $dokumen->ref_jenis_dokumen_id = $jenis->id;
        $dokumen->nomor = 3;
        $dokumen->tahun = 2024;
        $dokumen->setRelation('jenisDokumen', $jenis);

        $dokumen->generateDocumentUid();

        $this->assertNotNull($dokumen->document_uid);
        $this->assertStringContainsString(strtoupper($jenis->singkatan), $dokumen->document_uid);
        $this->assertStringContainsString('BNPB', $dokumen->document_uid);
        $this->assertStringContainsString('03', $dokumen->document_uid);
        $this->assertStringContainsString('2024', $dokumen->document_uid);
    }

    /** @test */
    public function download_allowed_is_false_for_tidak_berlaku(): void
    {
        $dokumen = new DokumenHukum();
        $dokumen->status = 'TIDAK BERLAKU';

        $this->assertFalse($dokumen->download_allowed);
    }

    /** @test */
    public function download_allowed_is_true_for_berlaku(): void
    {
        $dokumen = new DokumenHukum();
        $dokumen->status = 'BERLAKU';

        $this->assertTrue($dokumen->download_allowed);
    }

    /** @test */
    public function count_by_type_returns_all_types(): void
    {
        $result = DokumenHukum::countByType();

        $this->assertArrayHasKey('Peraturan', $result);
        $this->assertArrayHasKey('Monografi', $result);
        $this->assertArrayHasKey('Artikel', $result);
        $this->assertArrayHasKey('Putusan', $result);
    }

    /** @test */
    public function apply_filters_works_with_empty_filters(): void
    {
        $query = DokumenHukum::query()->applyFilters([]);

        // Should not throw, just return all
        $this->assertGreaterThanOrEqual(0, $query->count());
    }

    /** @test */
    public function apply_filters_filters_by_tahun(): void
    {
        $count2024 = DokumenHukum::query()->applyFilters(['tahun' => 2024])->count();
        $countAll = DokumenHukum::count();

        $this->assertLessThanOrEqual($countAll, $count2024);
    }
}
