<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">
                Choose the import type, upload one Excel file, and run one import at a time.
            </p>
            {{ $this->form }}
            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="import">Upload & Import</x-filament::button>
            </div>
        </div>
    </x-filament::card>
</x-filament::page>
