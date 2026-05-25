<x-filament-panels::page>
    @php
    $record = $this->record;
    $properties = $record->properties?->toArray() ?? [];
    $attributes = $properties['attributes'] ?? [];
    $old = $properties['old'] ?? [];
    $causer = $record->causer;
    $subject = $record->subject;

    $eventColors = [
        'created' => ['bg' => '#10b98122', 'text' => '#10b981', 'border' => '#10b98144'],
        'updated' => ['bg' => '#f59e0b22', 'text' => '#f59e0b', 'border' => '#f59e0b44'],
        'deleted' => ['bg' => '#ef444422', 'text' => '#ef4444', 'border' => '#ef444444'],
    ];
    $ec = $eventColors[$record->event] ?? ['bg' => '#6b728022', 'text' => '#6b7280', 'border' => '#6b728044'];

    $subjectMap = [
        'App\Models\Berita' => 'Berita',
        'App\Models\DokumenHukum' => 'Dokumen Hukum',
        'App\Models\DokumenRelasi' => 'Relasi Dokumen',
        'App\Models\Faq' => 'FAQ',
        'App\Models\Infografis' => 'Infografis',
        'App\Models\ProfilPage' => 'Halaman Profil',
        'App\Models\RefBidangHukum' => 'Referensi Bidang Hukum',
        'App\Models\RefJenisDokumen' => 'Referensi Jenis Dokumen',
        'App\Models\RefSubjek' => 'Referensi Subjek',
        'App\Models\RefTipeDokumen' => 'Referensi Tipe Dokumen',
        'App\Models\Survey' => 'Survei',
        'App\Models\User' => 'Pengguna',
        'App\Models\Visitor' => 'Pengunjung',
    ];
    $subjectLabel = $record->subject_type ? ($subjectMap[$record->subject_type] ?? class_basename($record->subject_type)) : '-';
    @endphp

    <div class="space-y-6" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 50)">

        {{-- Hero Card --}}
        <div class="relative overflow-hidden rounded-2xl border border-white/10 p-6"
            style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
            x-show="shown"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full blur-3xl"
                {!! 'style="background: ' . $ec['text'] . '; opacity: 0.08;"' !!}></div>
 
            <div class="flex items-start gap-4 min-w-0">
                {{-- Event badge --}}
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl"
                    {!! 'style="background: ' . $ec['bg'] . '; border: 1px solid ' . $ec['border'] . ';"' !!}>
                    @if($record->event === 'created')
                    <svg class="h-7 w-7" {!! 'style="color: ' . $ec['text'] . ';"' !!} fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    @elseif($record->event === 'updated')
                    <svg class="h-7 w-7" {!! 'style="color: ' . $ec['text'] . ';"' !!} fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                    </svg>
                    @elseif($record->event === 'deleted')
                    <svg class="h-7 w-7" {!! 'style="color: ' . $ec['text'] . ';"' !!} fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    @else
                    <svg class="h-7 w-7" {!! 'style="color: ' . $ec['text'] . ';"' !!} fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate" style="font-family: var(--font-heading);">
                        {{ ucfirst($record->event ?? 'activity') }}
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400 break-words text-justify">
                        {{ $record->description }}
                    </p>
                    
                    {{-- Footer Date --}}
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-gray-400 dark:text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <span>{{ $record->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500 delay-100"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- User --}}
            <div class="rounded-2xl border border-white/10 p-4"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">User</p>
                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $causer?->name ?? '-' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $causer?->email ?? '' }}</p>
            </div>

            {{-- Subject --}}
            <div class="rounded-2xl border border-white/10 p-4"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Subject</p>
                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $subjectLabel }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $record->subject_id ?? '-' }}</p>
            </div>

            {{-- Log Name --}}
            <div class="rounded-2xl border border-white/10 p-4"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Log Name</p>
                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $record->log_name ?? '-' }}
                </p>
            </div>

            {{-- Batch --}}
            <div class="rounded-2xl border border-white/10 p-4"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Batch UUID</p>
                <p class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $record->batch_uuid ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Changes Section --}}
        @if(!empty($attributes) || !empty($old))
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2"
            x-show="shown"
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Old Values --}}
            @if(!empty($old))
            <div class="rounded-2xl border border-white/10 p-5"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <div class="mb-4 flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500/10">
                        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Old Values</h3>
                </div>
                <div class="space-y-2">
                    @foreach($old as $key => $value)
                    <div class="flex items-start justify-between gap-3 rounded-xl border border-white/5 bg-white/5 px-3 py-2 dark:bg-black/10">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $key }}</span>
                        <span class="text-right text-xs font-semibold text-red-600 dark:text-red-400 max-w-[60%] break-all">
                            {{ is_array($value) ? json_encode($value) : ($value ?? 'null') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- New Values --}}
            @if(!empty($attributes))
            <div class="rounded-2xl border border-white/10 p-5"
                style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <div class="mb-4 flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10">
                        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">New Values</h3>
                </div>
                <div class="space-y-2">
                    @foreach($attributes as $key => $value)
                    <div class="flex items-start justify-between gap-3 rounded-xl border border-white/5 bg-white/5 px-3 py-2 dark:bg-black/10">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $key }}</span>
                        <span class="text-right text-xs font-semibold text-emerald-600 dark:text-emerald-400 max-w-[60%] break-all">
                            {{ is_array($value) ? json_encode($value) : ($value ?? 'null') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

    </div>
</x-filament-panels::page>