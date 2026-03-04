
<x-filament::widget>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        @foreach ($this->getStats() as $stat)
        @php
        $colors = [
        'blue' => [
        'border' => 'border-blue-500',
        'text' => 'text-blue-600',
        'progress' => 'bg-blue-500'
        ],
        'green' => [
        'border' => 'border-emerald-500',
        'text' => 'text-emerald-600',
        'progress' => 'bg-emerald-500'
        ],
        'cyan' => [
        'border' => 'border-cyan-500',
        'text' => 'text-cyan-600',
        'progress' => 'bg-cyan-500'
        ],
        'amber' => [
        'border' => 'border-amber-500',
        'text' => 'text-amber-600',
        'progress' => 'bg-amber-500'
        ],
        ];

        $style = $colors[$stat['color']];
        @endphp

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border-l-4 {{ $style['border'] }}">

            <div class="flex justify-between items-start">

                <div class="w-full">
                    <p class="text-xs font-semibold tracking-wider text-gray-500 dark:text-white-500 uppercase">
                        {{ $stat['label'] }}
                    </p>

                    <div class="flex items-center gap-2 mt-2">

                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white whitespace-nowrap">
                            {{ $stat['value'] }}
                        </h2>

                        @if(isset($stat['progress']))
                        <div class="flex items-center gap-2 flex-1">

                            <div class="relative flex-1 h-2.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">

                                <div
                                    class="absolute left-0 top-0 h-full {{ $style['progress'] }} rounded-full transition-all duration-700 ease-in-out"
                                    @style(["width: {$stat['progress']}%"])>
                                </div>

                            </div>

                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                {{ $stat['progress'] }}%
                            </span>

                        </div>
                        @endif
                    </div>
                </div>

                <div class="opacity-80 ml-4">
                    <x-dynamic-component
                        :component="$stat['icon']"
                        class="w-10 h-10 text-blue-800 dark:text-white" />
                </div>

            </div>
        </div>
        @endforeach

    </div>
</x-filament::widget>