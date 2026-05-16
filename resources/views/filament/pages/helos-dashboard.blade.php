<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-filament::card>Today Income: Rs. {{ number_format($todayIncome, 2) }}</x-filament::card>
        <x-filament::card>Today Expenses: Rs. {{ number_format($todayExpenses, 2) }}</x-filament::card>
        <x-filament::card>This Month Income: Rs. {{ number_format($monthIncome, 2) }}</x-filament::card>
        <x-filament::card>This Month Expenses: Rs. {{ number_format($monthExpenses, 2) }}</x-filament::card>
        <x-filament::card>This Month Net: Rs. {{ number_format($monthIncome - $monthExpenses, 2) }}</x-filament::card>
        <x-filament::card>Receivables: Rs. {{ number_format($receivables, 2) }}</x-filament::card>
        <x-filament::card>Payables: Rs. {{ number_format($payables, 2) }}</x-filament::card>
    </div>

    <x-filament::card>
        <h3 class="text-lg font-semibold mb-2">Expenses by Business Unit (This Month)</h3>
        <table class="w-full text-sm"><tbody>
            @foreach($expensesByUnit as $row)
                <tr><td class="py-1">{{ $row->unit }}</td><td class="text-right">{{ number_format($row->total, 2) }}</td></tr>
            @endforeach
        </tbody></table>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-lg font-semibold mb-2">Expenses by Category (This Month)</h3>
        <table class="w-full text-sm"><tbody>
            @foreach($expensesByCategory as $row)
                <tr><td class="py-1">{{ $row->category }}</td><td class="text-right">{{ number_format($row->total, 2) }}</td></tr>
            @endforeach
        </tbody></table>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-lg font-semibold mb-2">Income by Business Unit (This Month)</h3>
        <table class="w-full text-sm"><tbody>
            @foreach($incomeByUnit as $row)
                <tr><td class="py-1">{{ $row->unit }}</td><td class="text-right">{{ number_format($row->total, 2) }}</td></tr>
            @endforeach
        </tbody></table>
    </x-filament::card>
</x-filament::page>
