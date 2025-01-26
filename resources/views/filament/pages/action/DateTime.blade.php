<div class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-lg">
    <span id="current-time"></span>
</div>

<script>
    function updateTime() {
        const timeElement = document.getElementById('current-time');
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        };
        timeElement.textContent = now.toLocaleDateString('id-ID', options);
    }

    // Update waktu setiap detik
    setInterval(updateTime, 1000);
    updateTime();
</script>
