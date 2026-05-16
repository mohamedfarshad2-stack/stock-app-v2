<x-filament::page>
    <x-filament::card>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">
                Download the sample files from the header actions, fill them using existing Business Unit and Category names,
                then upload and run the relevant import.
            </p>
            {{ $this->form }}
            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="import">Upload & Import Money Records</x-filament::button>
                <x-filament::button color="secondary" wire:click="importCategories">Upload & Import Cost Categories</x-filament::button>
            </div>
        </div>
    </x-filament::card>
</x-filament::page>
