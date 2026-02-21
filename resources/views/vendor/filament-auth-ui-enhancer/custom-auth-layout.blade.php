@php
    $formPanelPosition = filament('filament-auth-ui-enhancer')->getFormPanelPosition();
    $mobileFormPanelPosition = filament('filament-auth-ui-enhancer')->getMobileFormPanelPosition();
    $emptyPanelBackgroundImageUrl = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageUrl();
    $emptyPanelBackgroundImageOpacity = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageOpacity();
    $showEmptyPanelOnMobile = filament('filament-auth-ui-enhancer')->getShowEmptyPanelOnMobile();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

    <div class="relative min-h-screen flex items-center justify-center">

        <!-- FULL BACKGROUND IMAGE -->
        <div class="absolute inset-0">
            @if($emptyPanelBackgroundImageUrl)
                <div class="absolute inset-0 bg-cover bg-center"
                     style="
                        background-image: url('{{ $emptyPanelBackgroundImageUrl }}');
                        opacity: '{{ $emptyPanelBackgroundImageOpacity }}';
                        filter: brightness(0.65);
                     ">
                </div>
            @endif

            <!-- DARK OVERLAY -->
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- GLASS LOGIN BOX -->
        <div class="relative backdrop-blur-xl bg-white/10 border border-white/20
                    rounded-2xl shadow-2xl p-8 sm:p-10 w-[90%] max-w-md">

            <!-- SLOT (FORM LOGIN DARI FILAMENT) -->
            <div class="text-white space-y-4">
                {{ $slot }}
            </div>

        </div>
    </div>

</x-filament-panels::layout.base>
