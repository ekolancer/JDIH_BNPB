<div class="p-8 space-y-12">

    @php
    $tipeDokValue = match($record->tipeDok) {
    'Peraturan' => 'Peraturan Perundang-Undangan',
    'Monografi' => 'Monografi Hukum',
    'Artikel' => 'Artikel Hukum',
    'Putusan' => 'Putusan Hukum',
    default => $record->tipeDok
    };

    // Group 1 — Informasi Utama
    $informasiUtama = [
    ['label' => 'Tipe Dokumen', 'value' => $tipeDokValue],
    ['label' => 'Jenis Dokumen', 'value' => $record->jenisDokumen->nama ?? '-'],
    ['label' => 'Singkatan', 'value' => $record->singkatan],
    ['label' => 'T.E.U Badan', 'value' => $record->teuBadan],
    ['label' => 'Nomor & Tahun Terbit', 'value' => str_pad($record->nomor, 2, '0', STR_PAD_LEFT) . ' / ' . $record->tahun],
    ];

    // Group 2 — Detail Dokumen + Metadata Tambahan
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

    // Group 3 — Lampiran & Info Tambahan (dibuat paling bawah)
    $lampiranTambahan = [
    ['label' => 'Total Download', 'value' => $record->download_count],
    ['label' => 'Total View', 'value' => $record->view_count],
    ['label' => 'Lampiran', 'value' => str_replace('file/', '', $record->lampiran)],
    ['label' => 'Abstrak', 'value' => $record->abstrak ?? 'Belum tersedia'],
    ];
    @endphp


    {{-- ============================
         SECTION 1 — INFORMASI UTAMA
         ============================ --}}
    <div class="bg-slate-50 border border-slate-300 rounded-xl shadow-md shadow-slate-300/40 p-8 transition-all duration-300 hover:shadow-lg hover:-translate-y-[1px]">

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-800 tracking-tight">Informasi Utama</h2>
            <div class="mt-2 w-14 h-1.5 bg-blue-600 rounded-md"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8">
            @foreach ($informasiUtama as $item)
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                    {{ $item['label'] }}
                    <x-heroicon-o-check-badge class="w-4 h-4 text-green-500" />
                </div>
                <div class="mt-1 text-[15px] font-semibold text-slate-900 leading-snug">
                    {{ $item['value'] ?: '-' }}
                </div>
            </div>
            @endforeach
        </div>

    </div>



    {{-- ===========================================
         SECTION 2 — DETAIL DOKUMEN + METADATA
         =========================================== --}}
    <div class="bg-slate-50 border border-slate-300 rounded-xl shadow-md shadow-slate-300/40 p-8 transition-all duration-300 hover:shadow-lg hover:-translate-y-[1px]">

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-800 tracking-tight">Detail Dokumen & Metadata</h2>
            <div class="mt-2 w-20 h-1.5 bg-blue-600 rounded-md"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8">

            {{-- Detail Dokumen Items --}}
            @foreach ($detailDokumen as $item)
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                    {{ $item['label'] }}
                    <x-heroicon-o-check-badge class="w-4 h-4 text-green-500" />
                </div>
                <div class="mt-1 text-[15px] font-semibold text-slate-900 leading-snug">
                    @if($item['label'] == 'Status')
                        @if($item['value'] == 'BERLAKU')
                            <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-lg border border-blue-300 shadow-sm">
                                Berlaku
                            </span>
                        @elseif($item['value'] == 'TIDAK BERLAKU')
                            <span class="inline-flex px-3 py-1 bg-red-100 text-red-800 text-sm rounded-lg border border-red-300 shadow-sm">
                                Tidak Berlaku
                            </span>
                        @else
                            {{ $item['value'] ?: '-' }}
                        @endif
                    @else
                        {{ $item['value'] ?: '-' }}
                    @endif
                </div>
            </div>
            @endforeach


            {{-- ========== Metadata Tambahan ========== --}}

            {{-- Bidang Hukum --}}
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                    <x-heroicon-o-academic-cap class="w-4 h-4 text-slate-500" />
                    Bidang Hukum
                </div>

                @if ($record->bidangHukumRef)
                <span class="inline-flex mt-2 px-3 py-1 bg-blue-100 text-emerald-800 text-sm rounded-lg border border-blue-300 shadow-sm">
                    {{ $record->bidangHukumRef->nama }}
                </span>
                @else
                <div class="text-slate-900 font-semibold mt-1">-</div>
                @endif
            </div>

            {{-- Subjek --}}
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                    <x-heroicon-o-tag class="w-4 h-4 text-slate-500" />
                    Subjek
                </div>

                @if ($record->subjekRef->count())
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($record->subjekRef as $subjek)
                    <span class="inline-flex mt-2 px-3 py-1 bg-blue-100 text-emerald-800 text-sm rounded-lg border border-blue-300 shadow-sm">
                        {{ $subjek->nama }}
                    </span>
                    @endforeach
                </div>
                @else
                <div class="text-slate-900 font-semibold mt-1">-</div>
                @endif
            </div>

        </div>

    </div>



    {{-- ===========================================
         SECTION 3 — LAMPIRAN & INFO TAMBAHAN
         =========================================== --}}
    <div class="bg-slate-50 border border-slate-300 rounded-xl shadow-md shadow-slate-300/40 p-8 transition-all duration-300 hover:shadow-lg hover:-translate-y-[1px]">

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-800 tracking-tight">Lampiran & Info Tambahan</h2>
            <div class="mt-2 w-20 h-1.5 bg-blue-600 rounded-md"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8">
            @foreach ($lampiranTambahan as $item)
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                    {{ $item['label'] }}
                    <x-heroicon-o-check-badge class="w-4 h-4 text-green-500" />
                </div>
                <div class="mt-1 text-[15px] font-semibold text-slate-900 leading-snug">
                    {{ $item['value'] ?: '-' }}
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>