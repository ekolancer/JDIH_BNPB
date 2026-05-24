<x-filament-widgets::widget>
    @php
    $user = $this->getUser();
    $greeting = $this->getGreeting();
    $role = $this->getRole();
    $dt = $this->getDateTime();
    $quote = $this->getMotivation();
    $initials = collect(explode(' ', $user->name))
    ->take(2)
    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
    ->implode('');

    // Menggunakan CDN avatar dari alohe/avatars dengan kelompok 'teams' secara rolling sesuai jam (24 jam)
    $timezone = config('app.timezone', 'Asia/Jakarta');
    $currentHour = \Carbon\Carbon::now($timezone)->hour;
    $avatarIndex = ($currentHour % 9) + 1;
    $avatarUrl = "https://cdn.jsdelivr.net/gh/alohe/avatars/png/teams_{$avatarIndex}.png";
    @endphp

    <div
        class="welcome-banner relative isolate overflow-hidden rounded-2xl border border-white/10 p-6 sm:p-8"
        style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur));"
        x-data="{
            shown: false,
            time: '{{ $dt['time'] }}',
            tick() {
                const now = new Date();
                const h = String(now.getHours()).padStart(2,'0');
                const m = String(now.getMinutes()).padStart(2,'0');
                this.time = h + ':' + m;
            }
        }"
        x-init="
            setTimeout(() => shown = true, 30);
            setInterval(() => tick(), 10000);
        ">
        {{-- ── Animated gradient background ── --}}
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden rounded-2xl">
            {{-- Primary blob --}}
            <div class="absolute -left-20 -top-20 h-72 w-72 animate-[pulse_8s_ease-in-out_infinite] rounded-full blur-3xl"
                style="background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);"></div>
            {{-- Secondary blob --}}
            <div class="absolute -bottom-16 -right-16 h-64 w-64 animate-[pulse_10s_ease-in-out_infinite_2s] rounded-full blur-3xl"
                style="background: radial-gradient(circle, rgba(16,185,129,0.14) 0%, transparent 70%);"></div>
            {{-- Subtle grid pattern --}}
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>

        {{-- ── Top accent line ── --}}
        <div class="absolute inset-x-0 top-0 h-px rounded-t-2xl"
            style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.6), rgba(16,185,129,0.4), transparent);"></div>

        {{-- ── Main content ── --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            {{-- Left: Avatar + Greeting --}}
            <div class="flex items-center gap-5"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">

                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    <img class="h-16 w-16 rounded-2xl object-cover shadow-lg border border-white/10"
                         src="{{ $avatarUrl }}"
                         alt="{{ $user->name }}"
                         style="
                             box-shadow: 0 8px 24px rgba(99,102,241,0.25);
                             background: linear-gradient(135deg, #1e293b, #0f172a);
                         ">
                    {{-- Online dot --}}
                    <span class="absolute -bottom-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-white dark:bg-gray-900">
                        <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-emerald-500"></span>
                    </span>
                </div>

                {{-- Text --}}
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-3xl"
                        style="font-family: var(--font-heading); letter-spacing: -0.03em;">
                        {{ $greeting }}, {{ $user->name }}
                    </h2>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider"
                            style="background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.25);">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                            </svg>
                            {{ $role }}
                        </span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Right: Date + Time + Quote --}}
            <div class="flex flex-col items-start gap-3 lg:items-end"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500 delay-150"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">

                {{-- Clock --}}
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold tabular-nums tracking-tight text-gray-900 dark:text-white"
                        style="font-family: var(--font-heading);"
                        x-text="time">{{ $dt['time'] }}</span>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $dt['day'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $dt['date'] }}</p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px w-full lg:w-64"
                    style="background: linear-gradient(90deg, transparent, rgba(148,163,184,0.3), transparent);"></div>

                {{-- Quote --}}
                <p class="max-w-xs text-right text-xs italic leading-relaxed text-gray-400 dark:text-gray-500 lg:max-w-sm">
                    "{{ $quote }}"
                </p>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>