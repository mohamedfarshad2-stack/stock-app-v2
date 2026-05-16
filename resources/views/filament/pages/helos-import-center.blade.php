<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">
 codex/create-helos-finance-module-foundation-98xta3
            <p class="text-sm text-gray-600">Use the header buttons to download separate sample Excel files for Money Records and Finance Categories. Upload files below and run the relevant import action.</p>
            {{ $this->form }}
            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="import">Upload & Import Money Records</x-filament::button>
                <x-filament::button color="secondary" wire:click="importCategories">Upload & Import Categories</x-filament::button>
            </div>

            <p class="text-sm text-gray-600">Download the sample Excel, fill data using Business Unit and Category names, then upload to import.</p>
            {{ $this->form }}
            <x-filament::button wire:click="import">Upload & Import</x-filament::button>
 main
        </div>
    </x-filament::card>
</x-filament::page>
