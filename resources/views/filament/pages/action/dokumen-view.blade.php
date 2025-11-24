<div class="p-8 space-y-10">

    @php
        $tipeDokValue = match($record->tipeDok) {
            'Peraturan' => 'Peraturan Perundang-Undangan',
            'Monografi'  => 'Monografi Hukum',
            'Artikel'    => 'Artikel Hukum',
            'Putusan'    => 'Putusan Hukum',
            default      => $record->tipeDok
        };

        $informasiUtama = [
            ['label' => 'Tipe Dokumen', 'value' => $tipeDokValue],
            ['label' => 'Jenis Dokumen', 'value' => $record->jenisDokumen->nama ?? '-'],
            ['label' => 'Singkatan', 'value' => $record->singkatan],
            ['label' => 'T.E.U Badan', 'value' => $record->teuBadan],
            ['label' => 'Nomor & Tahun Terbit', 'value' => str_pad($record->nomor, 2, '0', STR_PAD_LEFT) . ' / ' . $record->tahun],
        ];

        $detailDokumen = [
            ['label' => 'Penerbit', 'value' => $record->penerbit],
            ['label' => 'Tempat Terbit', 'value' => $record->tempat],
            ['label' => 'Tanggal Penetapan', 'value' => $record->tglPenetapan ? \Carbon\Carbon::parse($record->tglPenetapan)->format('d-m-Y') : '-'],
            ['label' => 'Tanggal Pengundangan', 'value' => $record->tglPengundangan ? \Carbon\Carbon::parse($record->tglPengundangan)->format('d-m-Y') : '-'],
            ['label' => 'Tanggal Dibacakan', 'value' => $record->tglDibacakan ? \Carbon\Carbon::parse($record->tglDibacakan)->format('d-m-Y') : '-'],
            ['label' => 'Status', 'value' => $record->status],
            ['label' => 'Bahasa', 'value' => $record->bahasa],
            ['label' => 'Sumber', 'value' => $record->sumber],
        ];

        $lampiranTambahan = [
            ['label' => 'Total Download', 'value' => $record->download_count],
            ['label' => 'Total View', 'value' => $record->view_count],
            ['label' => 'Lampiran', 'value' => str_replace('file/', '', $record->lampiran)],
            ['label' => 'Abstrak', 'value' => $record->abstrak ?? 'Belum tersedia'],
        ];
    @endphp

    {{-- ============================================================
         SECTION 1 — Informasi Utama
    ============================================================ --}}
    <div class="detail-panel">

        <h2 class="detail-title">Informasi Utama</h2>
        <div class="detail-accent"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-7 mt-6">

            @foreach ($informasiUtama as $item)
            <div>
                <div class="detail-label">
                    {{ $item['label'] }}
                </div>

                <div class="detail-value">
                    {{ $item['value'] ?: '-' }}
                </div>
            </div>
            @endforeach

        </div>

    </div>


    {{-- ============================================================
         SECTION 2 — Detail Dokumen
    ============================================================ --}}
    <div class="detail-panel">

        <h2 class="detail-title">Detail Dokumen & Metadata</h2>
        <div class="detail-accent"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-7 mt-6">

            @foreach ($detailDokumen as $item)
            <div>
                <div class="detail-label">
                    {{ $item['label'] }}
                </div>

                <div class="detail-value">

                    @if($item['label'] === 'Status')
                        @if($item['value'] === 'BERLAKU')
                            <span class="badge-pill badge-pill-berlaku">Berlaku</span>
                        @elseif($item['value'] === 'TIDAK BERLAKU')
                            <span class="badge-pill badge-pill-tidak">Tidak Berlaku</span>
                        @else
                            {{ $item['value'] ?: '-' }}
                        @endif
                    @else
                        {{ $item['value'] ?: '-' }}
                    @endif

                </div>
            </div>
            @endforeach


            {{-- Bidang Hukum --}}
            <div>
                <div class="detail-label">Bidang Hukum</div>

                @if ($record->bidangHukumRef)
                <span class="badge-pill badge-pill-berlaku mt-1 inline-block">
                    {{ $record->bidangHukumRef->nama }}
                </span>
                @else
                <div class="detail-value">-</div>
                @endif
            </div>


            {{-- Subjek --}}
            <div>
                <div class="detail-label">Subjek</div>

                @if ($record->subjekRef->count())
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach ($record->subjekRef as $subjek)
                        <span class="badge-pill badge-pill-berlaku">
                            {{ $subjek->nama }}
                        </span>
                        @endforeach
                    </div>
                @else
                    <div class="detail-value">-</div>
                @endif

            </div>

        </div>

    </div>




    {{-- ============================================================
         SECTION 3 — Lampiran & Info Tambahan
    ============================================================ --}}
    <div class="detail-panel">

        <h2 class="detail-title">Lampiran & Info Tambahan</h2>
        <div class="detail-accent"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-7 mt-6">

            @foreach ($lampiranTambahan as $item)
            <div>
                <div class="detail-label">
                    {{ $item['label'] }}
                </div>
                <div class="detail-value">
                    {{ $item['value'] ?: '-' }}
                </div>
            </div>
            @endforeach

        </div>

    </div>

</div>
