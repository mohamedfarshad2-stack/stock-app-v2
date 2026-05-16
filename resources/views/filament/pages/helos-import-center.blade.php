<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Download the sample Excel, fill data using Business Unit and Category names, then upload to import.</p>
            {{ $this->form }}
            <x-filament::button wire:click="import">Upload & Import</x-filament::button>
        </div>
    </x-filament::card>
</x-filament::page>
