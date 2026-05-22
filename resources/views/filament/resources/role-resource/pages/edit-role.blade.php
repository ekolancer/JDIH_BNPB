<x-filament-panels::page>
    @php
        $record = $this->record;
        $permissionsCount = $record->permissions->count();
        $usersCount = $record->users()->count();
        $guardName = $record->guard_name;
        $updatedAt = $record->updated_at;
    @endphp

    <div class="space-y-6" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 60)">

        {{-- ══════════════════════════════════════════════
             HERO HEADER CARD
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-2xl border border-white/10 p-6"
             style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
             x-show="shown"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full blur-3xl opacity-10"
                 style="background: linear-gradient(135deg, #6366f1, #8b5cf6);"></div>
            <div class="pointer-events-none absolute -left-10 -bottom-10 h-40 w-40 rounded-full blur-3xl opacity-5"
                 style="background: linear-gradient(135deg, #22c55e, #16a34a);"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                {{-- Role Icon & Name --}}
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl"
                         style="background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.2)); border: 1px solid rgba(99,102,241,0.3);">
                        <svg class="h-7 w-7" style="color: #818cf8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Editing Role</p>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                            {{ \Illuminate\Support\Str::headline($record->name) }}
                        </h1>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                            Guard: <span class="font-medium text-indigo-500">{{ $guardName }}</span>
                            &nbsp;·&nbsp;
                            Terakhir diperbarui: <span class="font-medium">{{ $updatedAt->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             STATS ROW
        ══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3"
             x-show="shown"
             x-transition:enter="transition ease-out duration-500 delay-100"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Permissions Count --}}
            <div class="rounded-2xl border border-white/10 p-4 group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/10"
                 style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl"
                         style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2);">
                        <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Permissions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $permissionsCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Users Count --}}
            <div class="rounded-2xl border border-white/10 p-4 group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10"
                 style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl"
                         style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2);">
                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $usersCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Role ID --}}
            <div class="col-span-2 sm:col-span-1 rounded-2xl border border-white/10 p-4 group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10"
                 style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl"
                         style="background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.2);">
                        <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Role ID</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">#{{ $record->id }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             MAIN FORM AREA
        ══════════════════════════════════════════════ --}}
        <div class="rounded-2xl border border-white/10 overflow-hidden"
             style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
             x-show="shown"
             x-transition:enter="transition ease-out duration-500 delay-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Form Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-white/10">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                     style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2);">
                    <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                        Konfigurasi Role & Permissions
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Atur nama, guard, dan hak akses untuk role ini</p>
                </div>
            </div>

            {{-- Filament Form --}}
            <div class="p-6">
                <form wire:submit="save">
                    {{ $this->form }}

                    {{-- Save Footer --}}
                    <div class="mt-8 flex items-center justify-between rounded-2xl border border-white/10 px-5 py-4"
                         style="background: rgba(99,102,241,0.05);">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <svg class="inline h-3 w-3 mr-1 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            Perubahan akan diterapkan ke semua user yang memiliki role ini.
                        </p>
                        <x-filament::actions
                            :actions="$this->getFormActions()"
                            :alignment="$this->getFormActionsAlignment()"
                            :full-width="$this->hasFullWidthFormActions()"
                        />
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             USERS WITH THIS ROLE (Info Card)
        ══════════════════════════════════════════════ --}}
        @php
            $roleUsers = $record->users()->orderBy('name')->get();
        @endphp
        @if($roleUsers->isNotEmpty())
            <div class="rounded-2xl border border-white/10 p-5"
                 style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
                 x-data="{ open: false }"
                 x-show="shown"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <button type="button"
                        class="flex w-full items-center justify-between text-left"
                        x-on:click="open = !open">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                             style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2);">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white" style="font-family: var(--font-heading);">
                                Users dengan Role Ini
                            </h3>
                            <p class="text-xs text-gray-500">{{ $roleUsers->count() }} user aktif</p>
                        </div>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-300"
                         :class="{ 'rotate-180': open }"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-collapse
                     class="mt-4">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($roleUsers as $user)
                            <div class="flex items-center gap-3 rounded-xl border border-white/5 bg-white/5 px-3 py-2.5 dark:bg-black/10">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-xs font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="truncate text-[10px] text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-filament-panels::page>
