<div class="inline-flex items-center gap-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3.5 py-2 shadow-sm">

    {{-- Icon circle --}}
    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </svg>
    </div>

    {{-- Text --}}
    <div>
        <div id="clock-date"
            class="text-[11px] leading-snug text-gray-500 dark:text-gray-400 tracking-wide">
        </div>
        <div id="clock-time"
            class="text-[15px] font-medium leading-snug text-gray-900 dark:text-white tracking-widest tabular-nums">
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const dateEl = document.getElementById('clock-date');
        const timeEl = document.getElementById('clock-time');

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function tick() {
            const now = new Date();

            dateEl.textContent =
                HARI[now.getDay()] + ', ' +
                now.getDate() + ' ' +
                BULAN[now.getMonth()] + ' ' +
                now.getFullYear();

            timeEl.textContent =
                pad(now.getHours()) + ':' +
                pad(now.getMinutes()) + ':' +
                pad(now.getSeconds());
        }

        tick();
        setInterval(tick, 1000);
    });
</script>