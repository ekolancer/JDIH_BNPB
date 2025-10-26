<x-filament-widgets::widget class="bg-slate-900 text-red rounded-xl shadow-md p-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-bold">🖥️ Server Status</h2>
        <span class="text-xs opacity-25">Last update: {{ now()->format('H:i:s') }}</span>
    </div>

    <livewire:pulse.servers :cols="$this->cols" :ignore-after="$this->ignoreAfter" />
</x-filament-widgets::widget>
