<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">

            <p class="text-sm text-gray-600">
                Choose the import type, download the matching sample, fill it, then upload and import.
            </p>

            {{ $this->form }}

            <div class="flex flex-wrap gap-3">

                <x-filament::button
                    color="secondary"
                    wire:click="downloadSample"
                >
                    {{ $this->sampleButtonLabel }}
                </x-filament::button>

                <x-filament::button wire:click="import">
                    Upload & Import
                </x-filament::button>

            </div>
        </div>
    </x-filament::card>
</x-filament::page>