<x-filament::page>
    <div class="mb-4 text-sm text-gray-600">
        HELOS command center summary for COD operations and expected profitability signals.
    </div>

    <x-filament::card class="mb-4">
        <h3 class="font-semibold mb-2">
            Operational Focus: Next Actions
        </h3>

        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>
                Review
                <a
                    class="text-primary-600 underline"
                    href="{{ \App\Filament\Resources\OrderResource::getUrl() }}"
                >
                    Order Operations Queue
                </a>
                for pending verification, dispatch tracking, and exceptions.
            </li>

            <li>
                Use
                <a
                    class="text-primary-600 underline"
                    href="{{ \App\Filament\Pages\HELOSImportCenter::getUrl() }}"
                >
                    Import Center
                </a>
                for daily intake, then return to Orders to progress lifecycle states.
            </li>

            <li>
                Update
                <a
                    class="text-primary-600 underline"
                    href="{{ \App\Filament\Resources\MoneyRecordResource::getUrl() }}"
                >
                    Transactions
                </a>
                for monthly operational finance visibility and overhead context.
            </li>
        </ul>
    </x-filament::card>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        @foreach($kpis as $kpi)
            <x-filament::card>
                <p class="text-sm text-gray-500">
                    {{ $kpi['label'] }}
                </p>

                <p class="mt-2 text-2xl font-semibold">
                    {{ $kpi['value'] }}
                </p>
            </x-filament::card>
        @endforeach

        <x-filament::card>
            <p class="text-sm text-gray-500">
                Expected Profit Today
            </p>

            <p class="mt-2 text-2xl font-semibold">
                Rs. {{ number_format($expectedToday, 2) }}
            </p>
        </x-filament::card>

        <x-filament::card>
            <p class="text-sm text-gray-500">
                Expected Profit This Month
            </p>

            <p class="mt-2 text-2xl font-semibold">
                Rs. {{ number_format($expectedMonth, 2) }}
            </p>
        </x-filament::card>

        <x-filament::card>
            <p class="text-sm text-gray-500">
                COD Revenue Today
            </p>

            <p class="mt-2 text-2xl font-semibold">
                Rs. {{ number_format($revenueToday, 2) }}
            </p>
        </x-filament::card>

        <x-filament::card>
            <p class="text-sm text-gray-500">
                COD Revenue This Month
            </p>

            <p class="mt-2 text-2xl font-semibold">
                Rs. {{ number_format($revenueMonth, 2) }}
            </p>
        </x-filament::card>

    </div>

    <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-3">

        <x-filament::card>
            <h3 class="font-semibold mb-3">
                Top profitable products
            </h3>

            @foreach($topProducts as $item)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $item->name }}</span>

                    <span>
                        Rs. {{ number_format($item->expected_profit, 2) }}
                    </span>
                </div>
            @endforeach
        </x-filament::card>

        <x-filament::card>
            <h3 class="font-semibold mb-3">
                Highest return-risk products
            </h3>

            @foreach($highRiskProducts as $item)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $item->name }}</span>

                    <span>
                        {{ number_format($item->return_risk, 2) }}%
                    </span>
                </div>
            @endforeach
        </x-filament::card>

        <x-filament::card>
            <h3 class="font-semibold mb-3">
                Business unit expected profit summary
            </h3>

            @foreach($businessUnitSummary as $item)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $item->name }}</span>

                    <span>
                        Rs. {{ number_format($item->expected_profit, 2) }}
                    </span>
                </div>
            @endforeach
        </x-filament::card>

    </div>

    <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-2">

        <x-filament::card>
            <h3 class="font-semibold mb-3">
                Monthly operational finance summary by category
            </h3>

            @forelse($monthlyFinanceByCategory as $item)
                <div class="flex justify-between text-sm py-1">

                    <span>{{ $item->name }}</span>

                    <span>
                        +Rs. {{ number_format((float) $item->income_total, 2) }}
                        /
                        -Rs. {{ number_format((float) $item->expense_total, 2) }}
                    </span>

                </div>
            @empty
                <p class="text-sm text-gray-500">
                    No monthly operational finance records yet.
                </p>
            @endforelse
        </x-filament::card>

        <x-filament::card>
            <h3 class="font-semibold mb-3">
                Monthly BU-wise operating finance summary
            </h3>

            @forelse($monthlyFinanceByBusinessUnit as $item)

                @php
                    $net = (float) $item->income_total - (float) $item->expense_total;
                @endphp

                <div class="flex justify-between text-sm py-1">

                    <span>{{ $item->name }}</span>

                    <span>
                        Rs. {{ number_format($net, 2) }}
                    </span>

                </div>

            @empty
                <p class="text-sm text-gray-500">
                    No monthly business-unit operating finance records yet.
                </p>
            @endforelse

        </x-filament::card>

    </div>
</x-filament::page>