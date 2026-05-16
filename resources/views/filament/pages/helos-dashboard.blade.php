<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($kpis as $kpi)
            <x-filament::card>
                <p class="text-sm text-gray-500">{{ $kpi['label'] }}</p>
                <p class="mt-2 text-2xl font-semibold">{{ $kpi['value'] }}</p>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::page>
