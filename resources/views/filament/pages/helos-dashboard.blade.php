<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-filament::card><p class="text-sm text-gray-500">Expected Profit Today</p><p class="mt-2 text-2xl font-semibold">Rs. {{ number_format($expectedToday, 2) }}</p></x-filament::card>
        <x-filament::card><p class="text-sm text-gray-500">Expected Profit This Month</p><p class="mt-2 text-2xl font-semibold">Rs. {{ number_format($expectedMonth, 2) }}</p></x-filament::card>
        <x-filament::card><p class="text-sm text-gray-500">COD Revenue Today</p><p class="mt-2 text-2xl font-semibold">Rs. {{ number_format($revenueToday, 2) }}</p></x-filament::card>
        <x-filament::card><p class="text-sm text-gray-500">COD Revenue This Month</p><p class="mt-2 text-2xl font-semibold">Rs. {{ number_format($revenueMonth, 2) }}</p></x-filament::card>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-3">
        <x-filament::card>
            <h3 class="font-semibold mb-3">Top profitable products</h3>
            @foreach($topProducts as $item)
                <div class="flex justify-between text-sm py-1"><span>{{ $item->name }}</span><span>Rs. {{ number_format($item->expected_profit, 2) }}</span></div>
            @endforeach
        </x-filament::card>
        <x-filament::card>
            <h3 class="font-semibold mb-3">Highest return-risk products</h3>
            @foreach($highRiskProducts as $item)
                <div class="flex justify-between text-sm py-1"><span>{{ $item->name }}</span><span>{{ number_format($item->return_risk, 2) }}%</span></div>
            @endforeach
        </x-filament::card>
        <x-filament::card>
            <h3 class="font-semibold mb-3">Business unit expected profit summary</h3>
            @foreach($businessUnitSummary as $item)
                <div class="flex justify-between text-sm py-1"><span>{{ $item->name }}</span><span>Rs. {{ number_format($item->expected_profit, 2) }}</span></div>
            @endforeach
        </x-filament::card>
    </div>
</x-filament::page>
