<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Use the header buttons to download separate sample Excel files for Money Records and Finance Categories. Upload files below and run the relevant import action.</p>
            {{ $this->form }}
            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="import">Upload & Import Money Records</x-filament::button>
                <x-filament::button color="secondary" wire:click="importCategories">Upload & Import Categories</x-filament::button>
            </div>
        </div>
    </x-filament::card>
</x-filament::page>
