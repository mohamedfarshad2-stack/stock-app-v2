<x-filament::widget>

<div class="grid grid-cols-4 gap-4">

    <div class="p-4 bg-white rounded shadow">
        <b>Total Orders</b>
        <div>{{ $record->total_orders ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
        <b>Delivered</b>
        <div>{{ $record->delivered_orders ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
        <b>Returned</b>
        <div>{{ $record->returned_orders ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
        <b>Trust Score</b>
        <div>{{ $record->trust_score ?? 0 }}</div>
    </div>

</div>

</x-filament::widget>