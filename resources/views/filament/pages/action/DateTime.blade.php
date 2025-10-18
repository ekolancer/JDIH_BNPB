<div class="inline-flex items-center gap-3 px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <div id="clock-date" class="text-[11px] opacity-80 leading-none"></div>
        <div id="clock-time" class="text-sm font-semibold leading-tight"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const dateEl = document.getElementById('clock-date');
    const timeEl = document.getElementById('clock-time');

    function updateClock() {
        const now = new Date();
        timeEl.textContent = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        dateEl.textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    updateClock();
    setInterval(updateClock, 1000);
});
</script>
