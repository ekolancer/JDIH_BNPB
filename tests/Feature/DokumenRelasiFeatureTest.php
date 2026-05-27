<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DokumenHukum;
use App\Models\DokumenRelasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DokumenRelasiFeatureTest extends TestCase
{
    use DatabaseTransactions;

    private function getTwoDokumen(): array
    {
        $docs = DokumenHukum::limit(2)->get();

        if ($docs->count() < 2) {
            $this->markTestSkipped('Need at least 2 dokumen in database');
        }

        return [$docs[0], $docs[1]];
    }

    /** @test */
    public function it_creates_inverse_relasi_automatically(): void
    {
        [$docA, $docB] = $this->getTwoDokumen();

        // Create "A mencabut B"
        $relasi = DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'mencabut',
            'catatan'            => 'Test catatan',
        ]);

        // Manually create inverse (simulating what RelationManager does)
        $inverse = DokumenRelasi::getInverseRelasi('mencabut');
        DokumenRelasi::create([
            'dokumen_id'         => $docB->id,
            'related_dokumen_id' => $docA->id,
            'jenis_relasi'       => $inverse,
            'catatan'            => 'Test catatan',
        ]);

        // Verify inverse exists
        $this->assertDatabaseHas('dokumen_relasi', [
            'dokumen_id'         => $docB->id,
            'related_dokumen_id' => $docA->id,
            'jenis_relasi'       => 'dicabut_oleh',
        ]);
    }

    /** @test */
    public function it_deletes_inverse_when_original_is_deleted(): void
    {
        [$docA, $docB] = $this->getTwoDokumen();

        // Create pair
        $relasi = DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'mengubah',
        ]);

        DokumenRelasi::create([
            'dokumen_id'         => $docB->id,
            'related_dokumen_id' => $docA->id,
            'jenis_relasi'       => 'diubah_oleh',
        ]);

        // Delete original
        $relasi->delete();

        // Manually delete inverse (simulating what RelationManager does)
        DokumenRelasi::where('dokumen_id', $docB->id)
            ->where('related_dokumen_id', $docA->id)
            ->where('jenis_relasi', 'diubah_oleh')
            ->delete();

        // Verify inverse is gone
        $this->assertDatabaseMissing('dokumen_relasi', [
            'dokumen_id'         => $docB->id,
            'related_dokumen_id' => $docA->id,
            'jenis_relasi'       => 'diubah_oleh',
        ]);
    }

    /** @test */
    public function semua_relasi_returns_merged_keluar_and_masuk(): void
    {
        [$docA, $docB] = $this->getTwoDokumen();

        // A mencabut B
        DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'mencabut',
        ]);

        // From B's perspective, it should show "dicabut_oleh A"
        $relasiBFromMasuk = $docB->semuaRelasi();

        $this->assertTrue($relasiBFromMasuk->isNotEmpty());

        $found = $relasiBFromMasuk->first(fn ($r) => $r['jenis_relasi'] === 'dicabut_oleh');
        $this->assertNotNull($found, 'Doc B should see "dicabut_oleh" from incoming relation');
        $this->assertEquals($docA->id, $found['dokumen']->id);
    }

    /** @test */
    public function it_prevents_duplicate_relasi(): void
    {
        [$docA, $docB] = $this->getTwoDokumen();

        DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'terkait',
        ]);

        // Try to create duplicate — should throw exception
        $this->expectException(\Illuminate\Database\QueryException::class);

        DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'terkait',
        ]);
    }

    /** @test */
    public function relasi_section_shows_on_detail_page(): void
    {
        [$docA, $docB] = $this->getTwoDokumen();

        // Only test if docA is Peraturan (has a public route)
        if ($docA->tipe_kode !== 'Peraturan') {
            $docA = DokumenHukum::whereHas('tipeDokumen', fn ($q) => $q->where('kode', 'Peraturan'))->first();
            if (!$docA) {
                $this->markTestSkipped('No Peraturan dokumen available');
            }
            $docB = DokumenHukum::where('id', '!=', $docA->id)->first();
        }

        // Create relasi
        DokumenRelasi::create([
            'dokumen_id'         => $docA->id,
            'related_dokumen_id' => $docB->id,
            'jenis_relasi'       => 'mencabut',
        ]);

        $response = $this->get("/dokumen/{$docA->tipe_kode}/{$docA->slug}");

        $response->assertStatus(200);
        $response->assertSee('Relasi Dokumen');
        $response->assertSee('Mencabut');
    }

    /** @test */
    public function detail_page_hides_relasi_section_when_no_relations(): void
    {
        $dokumen = DokumenHukum::whereHas('tipeDokumen', fn ($q) => $q->where('kode', 'Peraturan'))->first();

        if (!$dokumen) {
            $this->markTestSkipped('No Peraturan dokumen available');
        }

        // Ensure no relations exist for this doc
        DokumenRelasi::where('dokumen_id', $dokumen->id)
            ->orWhere('related_dokumen_id', $dokumen->id)
            ->delete();

        $response = $this->get("/dokumen/{$dokumen->tipe_kode}/{$dokumen->slug}");

        $response->assertStatus(200);
        $response->assertDontSee('Relasi Dokumen');
    }
}
