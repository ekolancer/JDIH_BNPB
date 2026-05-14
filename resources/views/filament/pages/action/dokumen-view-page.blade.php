<x-filament-panels::page>
@php
    $record = $this->record;

    $tipeDokLabel = match($record->tipeDok) {
        'Peraturan' => 'Peraturan Perundang-Undangan',
        'Monografi'  => 'Monografi Hukum',
        'Artikel'    => 'Artikel Hukum',
        'Putusan'    => 'Putusan Hukum',
        default      => $record->tipeDok
    };

    $tipeDokColor = match($record->tipeDok) {
        'Peraturan' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'dot' => 'bg-blue-400'],
        'Monografi'  => ['bg' => 'bg-purple-500/15', 'text' => 'text-purple-400', 'border' => 'border-purple-500/30', 'dot' => 'bg-purple-400'],
        'Artikel'    => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'dot' => 'bg-emerald-400'],
        'Putusan'    => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'dot' => 'bg-amber-400'],
        default      => ['bg' => 'bg-gray-500/15', 'text' => 'text-gray-400', 'border' => 'border-gray-500/30', 'dot' => 'bg-gray-400'],
    };

    $statusColor = $record->status === 'BERLAKU'
        ? ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'dot' => 'bg-emerald-400']
        : ['bg' => 'bg-red-500/15', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'dot' => 'bg-red-400'];

    $statusLabel = $record->status === 'BERLAKU' ? 'Berlaku' : 'Tidak Berlaku';

    $lampiranSize = $record->lampiran_size ?? ['kb' => 0, 'mb' => 0];
    $fileTersedia = $record->file_tersedia ?? false;
@endphp

<style>
.dv-shell { position: relative; }
.dv-shell::before {
    content: "";
    position: fixed;
    top: 10%;
    right: 5%;
    width: 380px;
    height: 380px;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}
.dv-shell::after {
    content: "";
    position: fixed;
    bottom: 15%;
    left: 3%;
    width: 280px;
    height: 280px;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(139,92,246,0.07) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

/* Glass card */
.dv-card {
    position: relative;
    z-index: 1;
    border-radius: 20px;
    border: 1px solid rgba(148,163,184,0.12);
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s ease;
}
.dark .dv-card {
    background: rgba(15,23,42,0.55);
    border-color: rgba(148,163,184,0.08);
    box-shadow: 0 4px 24px rgba(0,0,0,0.25);
}
.dv-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
.dark .dv-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.4); }

/* Hero card */
.dv-hero {
    padding: 2rem;
    border-radius: 24px;
    background: linear-gradient(135deg, rgba(255,255,255,0.75) 0%, rgba(241,245,249,0.6) 100%);
    border: 1px solid rgba(148,163,184,0.15);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 8px 40px rgba(0,0,0,0.06);
    overflow: hidden;
    position: relative;
    z-index: 1;
}
.dark .dv-hero {
    background: linear-gradient(135deg, rgba(15,23,42,0.75) 0%, rgba(30,41,59,0.65) 100%);
    border-color: rgba(148,163,184,0.1);
    box-shadow: 0 8px 40px rgba(0,0,0,0.35);
}
.dv-hero::before {
    content: "";
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    pointer-events: none;
}

/* Section header */
.dv-section-kicker {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--color-primary-500, #3b82f6);
    margin-bottom: 0.3rem;
}
.dark .dv-section-kicker {
    color: var(--color-primary-400, #60a5fa);
}
.dv-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-gray-900);
    margin: 0;
}
.dark .dv-section-title { color: var(--color-gray-100); }

/* Field item */
.dv-field {
    padding: 0.85rem 1rem;
    border-radius: 14px;
    background: rgba(248,250,252,0.8);
    border: 1px solid rgba(226,232,240,0.8);
    transition: border-color 0.15s ease, background 0.15s ease;
}
.dark .dv-field {
    background: rgba(30,41,59,0.5);
    border-color: rgba(51,65,85,0.6);
}
.dv-field:hover {
    border-color: rgba(59,130,246,0.3);
    background: rgba(239,246,255,0.6);
}
.dark .dv-field:hover {
    border-color: rgba(59,130,246,0.25);
    background: rgba(30,58,138,0.15);
}
.dv-field-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--color-gray-400);
    margin-bottom: 0.3rem;
}
.dv-field-value {
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--color-gray-800);
    line-height: 1.5;
}
.dark .dv-field-value { color: var(--color-gray-200); }

/* Badge pill */
.dv-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    border: 1px solid transparent;
}
.dv-badge-dot {
    width: 6px; height: 6px;
    border-radius: 999px;
    flex-shrink: 0;
}

/* Stat card */
.dv-stat {
    padding: 1.1rem 1.25rem;
    border-radius: 18px;
    background: rgba(248,250,252,0.9);
    border: 1px solid rgba(226,232,240,0.8);
    display: flex;
    align-items: center;
    gap: 1rem;
}
.dark .dv-stat {
    background: rgba(30,41,59,0.6);
    border-color: rgba(51,65,85,0.6);
}
.dv-stat-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dv-stat-num {
    font-size: 1.5rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: var(--color-gray-900);
    line-height: 1;
}
.dark .dv-stat-num { color: var(--color-gray-100); }
.dv-stat-label {
    font-size: 0.75rem;
    color: var(--color-gray-500);
    margin-top: 0.2rem;
}

/* File card */
.dv-file-card {
    padding: 1.25rem;
    border-radius: 18px;
    border: 1px dashed rgba(148,163,184,0.35);
    background: rgba(248,250,252,0.6);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
}
.dark .dv-file-card {
    background: rgba(30,41,59,0.4);
    border-color: rgba(51,65,85,0.5);
}
.dv-file-card:hover {
    border-color: rgba(59,130,246,0.4);
    background: rgba(239,246,255,0.5);
}
.dark .dv-file-card:hover {
    border-color: rgba(59,130,246,0.3);
    background: rgba(30,58,138,0.12);
}

/* Divider */
.dv-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(148,163,184,0.2), transparent);
    margin: 0.5rem 0;
}

/* Animate in */
@keyframes dv-fade-up {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dv-animate { animation: dv-fade-up 0.4s ease both; }
.dv-animate-1 { animation-delay: 0.05s; }
.dv-animate-2 { animation-delay: 0.1s; }
.dv-animate-3 { animation-delay: 0.15s; }
.dv-animate-4 { animation-delay: 0.2s; }
</style>

<div class="dv-shell space-y-5">

    {{-- ═══════════════════════════════════════════════
         HERO — Judul + Badge + Stats
    ═══════════════════════════════════════════════ --}}
    <div class="dv-hero dv-animate">

        {{-- Top row: badges --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="dv-badge {{ $tipeDokColor['bg'] }} {{ $tipeDokColor['text'] }} {{ $tipeDokColor['border'] }}">
                <span class="dv-badge-dot {{ $tipeDokColor['dot'] }}"></span>
                {{ $tipeDokLabel }}
            </span>
            <span class="dv-badge {{ $statusColor['bg'] }} {{ $statusColor['text'] }} {{ $statusColor['border'] }}">
                <span class="dv-badge-dot {{ $statusColor['dot'] }}"></span>
                {{ $statusLabel }}
            </span>
            @if($record->document_uid)
            <span class="dv-badge bg-gray-500/10 text-gray-500 border-gray-500/20 font-mono tracking-wider">
                {{ $record->document_uid }}
            </span>
            @endif
        </div>

        {{-- Judul --}}
        <h1 class="text-xl md:text-2xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-gray-100 mb-5" style="letter-spacing:-0.03em">
            {{ $record->judul }}
        </h1>

        <div class="dv-divider mb-5"></div>

        {{-- Stats row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="dv-stat">
                <div class="dv-stat-icon bg-amber-500/10">
                    <x-heroicon-o-hashtag class="w-5 h-5 text-amber-500" />
                </div>
                <div>
                    <div class="dv-stat-num text-base">{{ str_pad($record->nomor ?? '0', 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="dv-stat-label">Nomor Dokumen</div>
                </div>
            </div>
            <div class="dv-stat">
                <div class="dv-stat-icon bg-violet-500/10">
                    <x-heroicon-o-calendar-days class="w-5 h-5 text-violet-500" />
                </div>
                <div>
                    <div class="dv-stat-num text-base">{{ $record->tahun ?? '-' }}</div>
                    <div class="dv-stat-label">Tahun Terbit</div>
                </div>
            </div>
            <div class="dv-stat">
                <div class="dv-stat-icon bg-blue-500/10">
                    <x-heroicon-o-eye class="w-5 h-5 text-blue-500" />
                </div>
                <div>
                    <div class="dv-stat-num">{{ number_format($record->view_count ?? 0) }}</div>
                    <div class="dv-stat-label">Total Dilihat</div>
                </div>
            </div>
            <div class="dv-stat">
                <div class="dv-stat-icon bg-emerald-500/10">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-emerald-500" />
                </div>
                <div>
                    <div class="dv-stat-num">{{ number_format($record->download_count ?? 0) }}</div>
                    <div class="dv-stat-label">Total Unduhan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         MAIN GRID — 2 kolom
    ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Kolom kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- SECTION: Informasi Utama --}}
            <div class="dv-card p-5 dv-animate dv-animate-1">
                <div class="mb-4">
                    <p class="dv-section-kicker">Identitas Dokumen</p>
                    <h2 class="dv-section-title">Informasi Utama</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="dv-field">
                        <div class="dv-field-label">Tipe Dokumen</div>
                        <div class="dv-field-value">{{ $tipeDokLabel }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Jenis Dokumen</div>
                        <div class="dv-field-value">{{ $record->jenisDokumen->nama ?? '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Singkatan</div>
                        <div class="dv-field-value font-mono">{{ $record->jenisDokumen->singkatan ?? '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">T.E.U Badan</div>
                        <div class="dv-field-value">{{ $record->teuBadan ?: '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION: Detail & Metadata --}}
            <div class="dv-card p-5 dv-animate dv-animate-2">
                <div class="mb-4">
                    <p class="dv-section-kicker">Metadata</p>
                    <h2 class="dv-section-title">Detail Dokumen</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="dv-field">
                        <div class="dv-field-label">Penerbit</div>
                        <div class="dv-field-value">{{ $record->penerbit ?: '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Tempat Terbit</div>
                        <div class="dv-field-value">{{ $record->tempat ?: '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Sumber</div>
                        <div class="dv-field-value">{{ $record->sumber ?: '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Bahasa</div>
                        <div class="dv-field-value">{{ $record->bahasa ?: '-' }}</div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Tanggal Penetapan</div>
                        <div class="dv-field-value">
                            {{ $record->tglPenetapan ? \Carbon\Carbon::parse($record->tglPenetapan)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    <div class="dv-field">
                        <div class="dv-field-label">Tanggal Pengundangan</div>
                        <div class="dv-field-value">
                            {{ $record->tglPengundangan ? \Carbon\Carbon::parse($record->tglPengundangan)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    @if($record->tglDibacakan)
                    <div class="dv-field">
                        <div class="dv-field-label">Tanggal Dibacakan</div>
                        <div class="dv-field-value">
                            {{ \Carbon\Carbon::parse($record->tglDibacakan)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    @endif
                    <div class="dv-field">
                        <div class="dv-field-label">Status</div>
                        <div class="dv-field-value">
                            <span class="dv-badge {{ $statusColor['bg'] }} {{ $statusColor['text'] }} {{ $statusColor['border'] }}">
                                <span class="dv-badge-dot {{ $statusColor['dot'] }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom kanan (1/3) --}}
        <div class="space-y-5">

            {{-- SECTION: Klasifikasi --}}
            <div class="dv-card p-5 dv-animate dv-animate-2">
                <div class="mb-4">
                    <p class="dv-section-kicker">Klasifikasi</p>
                    <h2 class="dv-section-title">Bidang & Subjek</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="dv-field-label mb-2">Bidang Hukum</div>
                        @if($record->bidangHukumRef)
                            <span class="dv-badge bg-blue-500/10 text-blue-500 border-blue-500/20">
                                <x-heroicon-o-scale class="w-3.5 h-3.5" />
                                {{ $record->bidangHukumRef->nama }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>

                    <div class="dv-divider"></div>

                    <div>
                        <div class="dv-field-label mb-2">Subjek Hukum</div>
                        @if($record->subjekRef->count())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($record->subjekRef as $subjek)
                                <span class="dv-badge bg-violet-500/10 text-violet-500 border-violet-500/20 text-xs">
                                    {{ $subjek->nama }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SECTION: File --}}
            <div class="dv-card p-5 dv-animate dv-animate-3">
                <div class="mb-4">
                    <p class="dv-section-kicker">Berkas</p>
                    <h2 class="dv-section-title">Lampiran Dokumen</h2>
                </div>

                @if($fileTersedia)
                <div class="dv-file-card mb-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0">
                        <x-heroicon-o-document-text class="w-5 h-5 text-red-500" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                            {{ basename($record->lampiran) }}
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            PDF &bull; {{ $lampiranSize['mb'] > 0 ? $lampiranSize['mb'] . ' MB' : $lampiranSize['kb'] . ' KB' }}
                        </div>
                    </div>
                    <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-500 flex-shrink-0" />
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('dokumen.view', $record->slug) }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                              bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20
                              text-sm font-semibold hover:bg-blue-500/20 transition-colors">
                        <x-heroicon-o-eye class="w-4 h-4" />
                        Baca
                    </a>
                    <a href="{{ route('dokumen.unduhLampiran', $record->slug) }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl
                              bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20
                              text-sm font-semibold hover:bg-emerald-500/20 transition-colors">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        Unduh
                    </a>
                </div>
                @else
                <div class="dv-file-card opacity-60 cursor-not-allowed">
                    <div class="w-10 h-10 rounded-xl bg-gray-500/10 flex items-center justify-center flex-shrink-0">
                        <x-heroicon-o-document class="w-5 h-5 text-gray-400" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-500">Berkas tidak tersedia</div>
                        <div class="text-xs text-gray-400 mt-0.5">Belum ada file yang diunggah</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- SECTION: Info Tambahan --}}
            <div class="dv-card p-5 dv-animate dv-animate-4">
                <div class="mb-4">
                    <p class="dv-section-kicker">Sistem</p>
                    <h2 class="dv-section-title">Info Tambahan</h2>
                </div>
                <div class="space-y-2">
                    <div class="dv-field">
                        <div class="dv-field-label">Ditambahkan</div>
                        <div class="dv-field-value text-sm">{{ $record->created_at_formatted ?? '-' }}</div>
                    </div>
                    @if($record->penandatangan)
                    <div class="dv-field">
                        <div class="dv-field-label">Penandatangan</div>
                        <div class="dv-field-value text-sm">{{ $record->penandatangan }}</div>
                    </div>
                    @endif
                    @if($record->nomorIndukBuku)
                    <div class="dv-field">
                        <div class="dv-field-label">Nomor Induk Buku</div>
                        <div class="dv-field-value font-mono text-sm">{{ $record->nomorIndukBuku }}</div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         ABSTRAK
    ═══════════════════════════════════════════════ --}}
    @if($record->abstrak && !str_starts_with($record->abstrak ?? '', 'file/'))
    <div class="dv-card p-5 dv-animate dv-animate-4">
        <div class="mb-4">
            <p class="dv-section-kicker">Ringkasan</p>
            <h2 class="dv-section-title">Abstrak</h2>
        </div>
        <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">
            {{ $record->abstrak }}
        </p>
    </div>
    @endif

</x-filament-panels::page>
