<x-filament-panels::page>
    <div class="space-y-4">

        {{-- Error State --}}
        @if($error)
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/20 dark:border-red-800 p-4 flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500 mt-0.5 shrink-0" />
                <div>
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">Koneksi Google Analytics Gagal</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $error }}</p>
                </div>
            </div>
        @else

        {{-- Period Selector --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-1 shadow-sm">
                @foreach([['7','7 Hari'], ['14','14 Hari'], ['30','30 Hari'], ['90','90 Hari'], ['365','1 Tahun']] as [$val, $label])
                    <button
                        wire:click="setPeriod('{{ $val }}')"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                            {{ $period === $val
                                ? 'bg-primary-600 text-white shadow-sm'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                <x-heroicon-o-clock class="w-3.5 h-3.5 inline -mt-0.5" />
                Cache 5 menit &bull; Data dari Google Analytics 4
            </p>
        </div>

        {{-- Overview Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
            @php
                $stats = [
                    ['label' => 'Total Sesi',       'value' => number_format($overview['sessions'] ?? 0),    'icon' => 'heroicon-o-cursor-arrow-rays', 'color' => 'blue'],
                    ['label' => 'Total Pengguna',   'value' => number_format($overview['users'] ?? 0),       'icon' => 'heroicon-o-users',            'color' => 'violet'],
                    ['label' => 'Pengguna Baru',    'value' => number_format($overview['new_users'] ?? 0),   'icon' => 'heroicon-o-user-plus',        'color' => 'emerald'],
                    ['label' => 'Halaman Dilihat',  'value' => number_format($overview['pageviews'] ?? 0),   'icon' => 'heroicon-o-eye',              'color' => 'amber'],
                    ['label' => 'Bounce Rate',      'value' => ($overview['bounce_rate'] ?? 0) . '%',        'icon' => 'heroicon-o-arrow-uturn-left', 'color' => 'rose'],
                    ['label' => 'Rata-rata Durasi', 'value' => $overview['avg_session_duration'] ?? '0m 0s', 'icon' => 'heroicon-o-clock',           'color' => 'cyan'],
                ];
                $colorMap = [
                    'blue'    => ['bg' => 'bg-blue-50 dark:bg-blue-950/30',     'icon' => 'text-blue-500',    'border' => 'border-blue-100 dark:border-blue-900'],
                    'violet'  => ['bg' => 'bg-violet-50 dark:bg-violet-950/30', 'icon' => 'text-violet-500',  'border' => 'border-violet-100 dark:border-violet-900'],
                    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/30','icon'=> 'text-emerald-500', 'border' => 'border-emerald-100 dark:border-emerald-900'],
                    'amber'   => ['bg' => 'bg-amber-50 dark:bg-amber-950/30',   'icon' => 'text-amber-500',   'border' => 'border-amber-100 dark:border-amber-900'],
                    'rose'    => ['bg' => 'bg-rose-50 dark:bg-rose-950/30',     'icon' => 'text-rose-500',    'border' => 'border-rose-100 dark:border-rose-900'],
                    'cyan'    => ['bg' => 'bg-cyan-50 dark:bg-cyan-950/30',     'icon' => 'text-cyan-500',    'border' => 'border-cyan-100 dark:border-cyan-900'],
                ];
            @endphp
            @foreach($stats as $stat)
                @php $c = $colorMap[$stat['color']]; @endphp
                <div class="rounded-xl border {{ $c['border'] }} {{ $c['bg'] }} p-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</span>
                        <x-dynamic-component :component="$stat['icon']" class="w-4 h-4 {{ $c['icon'] }}" />
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Sessions Over Time --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tren Kunjungan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Sesi, pengguna, dan halaman dilihat per hari</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-blue-500 inline-block rounded"></span>Sesi</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-violet-500 inline-block rounded"></span>Pengguna</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-emerald-500 inline-block rounded"></span>Halaman</span>
                </div>
            </div>
            <div class="p-5">
                <canvas id="ga-sessions-chart" height="80"></canvas>
            </div>
        </div>

        {{-- Row 2: Pie Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Traffic Sources --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Sumber Traffic</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Berdasarkan channel</p>
                </div>
                <div class="p-5 flex flex-col items-center gap-4">
                    <div class="relative w-48 h-48">
                        <canvas id="ga-traffic-chart"></canvas>
                    </div>
                    <div id="ga-traffic-legend" class="w-full flex flex-col gap-1.5"></div>
                </div>
            </div>

            {{-- Devices --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Perangkat</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Desktop, mobile, tablet</p>
                </div>
                <div class="p-5 flex flex-col items-center gap-4">
                    <div class="relative w-48 h-48">
                        <canvas id="ga-devices-chart"></canvas>
                    </div>
                    <div id="ga-devices-legend" class="w-full flex flex-col gap-1.5"></div>
                </div>
            </div>

            {{-- New vs Returning --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Pengguna Baru vs Kembali</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Komposisi pengguna</p>
                </div>
                <div class="p-5 flex flex-col items-center gap-4">
                    <div class="relative w-48 h-48">
                        <canvas id="ga-nvr-chart"></canvas>
                    </div>
                    <div id="ga-nvr-legend" class="w-full flex flex-col gap-1.5"></div>
                </div>
            </div>
        </div>

        {{-- Row 3: Top Pages + Countries + Browsers --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Top Pages --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Halaman Terpopuler</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Top 10 halaman berdasarkan pageviews</p>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($topPages as $i => $page)
                        @php
                            $maxViews = $topPages[0]['views'] ?? 1;
                            $pct = $maxViews > 0 ? round(($page['views'] / $maxViews) * 100) : 0;
                        @endphp
                        <div class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-xs font-bold text-gray-300 dark:text-gray-600 w-4 shrink-0">{{ $i + 1 }}</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">{{ $page['title'] ?: $page['path'] }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $page['path'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ number_format($page['views']) }}</p>
                                    <p class="text-xs text-gray-400">views</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1">
                                <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-gray-400">Tidak ada data</div>
                    @endforelse
                </div>
            </div>

            {{-- Countries + Browsers --}}
            <div class="flex flex-col gap-4">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden flex-1">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Negara Teratas</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Berdasarkan jumlah pengguna</p>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($countries as $i => $country)
                            @php
                                $maxUsers = $countries[0]['users'] ?? 1;
                                $pct = $maxUsers > 0 ? round(($country['users'] / $maxUsers) * 100) : 0;
                            @endphp
                            <div class="px-5 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-300 dark:text-gray-600 w-4">{{ $i + 1 }}</span>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $country['country'] }}</span>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ number_format($country['users']) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1 ml-6">
                                    <div class="bg-emerald-500 h-1 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-center text-xs text-gray-400">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Browser</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Top browser pengguna</p>
                    </div>
                    <div class="p-5">
                        <canvas id="ga-browsers-chart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @endif

    </div>{{-- end .space-y-4 --}}

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        function initCharts() {
            if (typeof Chart === 'undefined') { setTimeout(initCharts, 50); return; }

            var isDark    = function() { return document.documentElement.classList.contains('dark'); };
            var gridColor = function() { return isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)'; };
            var lblColor  = function() { return isDark() ? '#9ca3af' : '#6b7280'; };
            var tooltipBase = function() {
                return {
                    backgroundColor : isDark() ? '#1f2937' : '#fff',
                    titleColor      : lblColor(),
                    bodyColor       : lblColor(),
                    borderColor     : isDark() ? '#374151' : '#e5e7eb',
                    borderWidth     : 1,
                    padding         : 10,
                };
            };

            var PALETTE = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#84cc16'];

            function makeChart(id, config) {
                var el = document.getElementById(id);
                if (!el) return null;
                var ex = Chart.getChart(el);
                if (ex) ex.destroy();
                return new Chart(el, config);
            }

            // ── Radial gradient ────────────────────────────────────────────
            function hexToRgb(hex) {
                return [parseInt(hex.slice(1,3),16), parseInt(hex.slice(3,5),16), parseInt(hex.slice(5,7),16)].join(',');
            }
            function buildGradients(canvasEl, colors) {
                var ctx = canvasEl.getContext('2d');
                var s   = canvasEl.width / 2;
                return colors.map(function(hex) {
                    var g = ctx.createRadialGradient(s, s, s * 0.2, s, s, s);
                    g.addColorStop(0, 'rgba(' + hexToRgb(hex) + ',0.5)');
                    g.addColorStop(1, hex);
                    return g;
                });
            }

            // ── HTML legend ────────────────────────────────────────────────
            window.__gaCharts = window.__gaCharts || {};
            window.__gaLegendToggle = function(legendId, index) {
                var chart = window.__gaCharts[legendId];
                if (!chart) return;
                var meta = chart.getDatasetMeta(0);
                meta.data[index].hidden = !meta.data[index].hidden;
                chart.update();
                var items = document.querySelectorAll('[data-ga-legend="' + legendId + '"]');
                if (items[index]) items[index].classList.toggle('opacity-40');
            };
            function buildLegend(legendId, chartInstance, flatColors) {
                var container = document.getElementById(legendId);
                if (!container) return;
                var labels   = chartInstance.data.labels;
                var datasets = chartInstance.data.datasets;
                var total    = datasets[0].data.reduce(function(a,b){ return a+b; }, 0);
                container.innerHTML = labels.map(function(label, i) {
                    var val   = datasets[0].data[i];
                    var pct   = total > 0 ? ((val / total) * 100).toFixed(1) : '0.0';
                    var color = flatColors[i % flatColors.length];
                    return '<div class="flex items-center justify-between gap-2 cursor-pointer group transition-opacity duration-200"'
                         + ' data-ga-legend="' + legendId + '"'
                         + ' onclick="window.__gaLegendToggle(\'' + legendId + '\',' + i + ')">'
                         + '<div class="flex items-center gap-2 min-w-0">'
                         + '<span class="w-2.5 h-2.5 rounded-full shrink-0 transition-transform duration-200 group-hover:scale-125" style="background:' + color + '"></span>'
                         + '<span class="text-xs text-gray-600 dark:text-gray-400 truncate group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors">' + label + '</span>'
                         + '</div>'
                         + '<div class="flex items-center gap-2 shrink-0">'
                         + '<span class="text-xs font-semibold text-gray-700 dark:text-gray-300">' + val.toLocaleString() + '</span>'
                         + '<span class="text-xs text-gray-400 w-10 text-right">' + pct + '%</span>'
                         + '</div></div>';
                }).join('');
            }

            // ── Pie chart factory ──────────────────────────────────────────
            function makePieChart(canvasId, legendId, labels, values, colors, delayMs) {
                var el = document.getElementById(canvasId);
                if (!el || !values.length) return;
                var gradients = buildGradients(el, colors);
                var instance  = makeChart(canvasId, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data            : values,
                            backgroundColor : gradients,
                            borderWidth     : 2,
                            borderColor     : isDark() ? '#111827' : '#ffffff',
                            hoverOffset     : 12,
                            hoverBorderWidth: 0,
                        }],
                    },
                    options: {
                        responsive         : true,
                        maintainAspectRatio: true,
                        animation: {
                            animateRotate: true,
                            animateScale : true,
                            duration     : 900,
                            delay        : delayMs || 0,
                            easing       : 'easeInOutQuart',
                        },
                        plugins: {
                            legend : { display: false },
                            tooltip: Object.assign({}, tooltipBase(), {
                                mode     : 'nearest',
                                intersect: true,
                                callbacks: {
                                    label: function(ctx) {
                                        var total = ctx.dataset.data.reduce(function(a,b){ return a+b; }, 0);
                                        var pct   = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                        return '  ' + ctx.label + ': ' + ctx.parsed.toLocaleString() + ' (' + pct + '%)';
                                    },
                                },
                            }),
                        },
                    },
                });
                if (instance) {
                    window.__gaCharts[legendId] = instance;
                    buildLegend(legendId, instance, colors);
                }
            }

            // ── 1. Sessions Over Time ──────────────────────────────────────
            var sd = @json($sessionsChart);
            if (sd && sd.labels && sd.labels.length) {
                makeChart('ga-sessions-chart', {
                    type: 'line',
                    data: {
                        labels: sd.labels,
                        datasets: [
                            { label:'Sesi',     data:sd.sessions,  borderColor:'#3b82f6', backgroundColor:'rgba(59,130,246,0.08)',  borderWidth:2, pointRadius:0, pointHoverRadius:4, fill:true, tension:0.4 },
                            { label:'Pengguna', data:sd.users,     borderColor:'#8b5cf6', backgroundColor:'rgba(139,92,246,0.06)',  borderWidth:2, pointRadius:0, pointHoverRadius:4, fill:true, tension:0.4 },
                            { label:'Halaman',  data:sd.pageviews, borderColor:'#10b981', backgroundColor:'rgba(16,185,129,0.06)', borderWidth:2, pointRadius:0, pointHoverRadius:4, fill:true, tension:0.4 },
                        ],
                    },
                    options: {
                        responsive: true,
                        interaction: { mode:'index', intersect:false },
                        plugins: { legend:{ display:false }, tooltip: tooltipBase() },
                        scales: {
                            x: { grid:{ color:gridColor() }, ticks:{ color:lblColor(), maxTicksLimit:10, font:{size:11} } },
                            y: { grid:{ color:gridColor() }, ticks:{ color:lblColor(), font:{size:11} }, beginAtZero:true },
                        },
                    },
                });
            }

            // ── 2. Traffic Sources (pie) ───────────────────────────────────
            var td = @json($trafficSources);
            if (td && td.length) {
                makePieChart(
                    'ga-traffic-chart', 'ga-traffic-legend',
                    td.map(function(d){ return d.channel; }),
                    td.map(function(d){ return d.sessions; }),
                    PALETTE.slice(0, td.length), 0
                );
            }

            // ── 3. Devices (pie) ───────────────────────────────────────────
            var dd = @json($devices);
            if (dd && dd.length) {
                makePieChart(
                    'ga-devices-chart', 'ga-devices-legend',
                    dd.map(function(d){ return d.device; }),
                    dd.map(function(d){ return d.sessions; }),
                    ['#3b82f6','#10b981','#f59e0b'], 150
                );
            }

            // ── 4. New vs Returning (pie) ──────────────────────────────────
            var nvr = @json($newVsReturning);
            if (nvr && (nvr.new || nvr.returning)) {
                makePieChart(
                    'ga-nvr-chart', 'ga-nvr-legend',
                    ['Pengguna Baru', 'Pengguna Kembali'],
                    [nvr.new || 0, nvr.returning || 0],
                    ['#3b82f6','#8b5cf6'], 300
                );
            }

            // ── 5. Browsers (horizontal bar) ──────────────────────────────
            var bd = @json($browsers);
            if (bd && bd.length) {
                makeChart('ga-browsers-chart', {
                    type: 'bar',
                    data: {
                        labels: bd.map(function(d){ return d.browser; }),
                        datasets: [{
                            label: 'Pengguna',
                            data: bd.map(function(d){ return d.users; }),
                            backgroundColor: PALETTE.slice(0, bd.length),
                            borderRadius: 6,
                            borderSkipped: false,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: { legend:{ display:false }, tooltip: tooltipBase() },
                        scales: {
                            x: { grid:{ color:gridColor() }, ticks:{ color:lblColor(), font:{size:11} }, beginAtZero:true },
                            y: { grid:{ display:false }, ticks:{ color:lblColor(), font:{size:11} } },
                        },
                    },
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }

        document.addEventListener('livewire:navigated', initCharts);
        window.addEventListener('ga-data-updated', initCharts);
    })();
    </script>
    @endpush

</x-filament-panels::page>
