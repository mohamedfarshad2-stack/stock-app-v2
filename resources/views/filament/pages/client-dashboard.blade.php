<!-- resources/views/filament/pages/client-dashboard.blade.php -->

<x-filament::page>

@php
    $userId = auth()->id();

    $totalSearches = \Illuminate\Support\Facades\DB::table('search_logs')
        ->where('user_id', $userId)
        ->count();

    $highRisk = \Illuminate\Support\Facades\DB::table('search_logs')
        ->where('user_id', $userId)
        ->whereIn('risk_level', ['High Risk', 'Very High Risk'])
        
        ->count();

    $good = \Illuminate\Support\Facades\DB::table('search_logs')
        ->where('user_id', $userId)
        ->whereIn('risk_level', ['Good', 'Very Safe'])

        ->count();

    $safeRate = $totalSearches > 0 ? round(($good / $totalSearches) * 100) : 0;

    $returnCost = 500;
    $moneySaved = $highRisk * $returnCost;
$phones = \Illuminate\Support\Facades\DB::table('search_logs')
    ->where('user_id', $userId)
    ->whereIn('risk_level', ['High Risk', 'Very High Risk'])
    ->pluck('searched_phone');
$customers = \App\Models\Customer::whereIn('phone', $phones)
    ->orderByDesc('returned_orders')
    ->limit(10)
    ->get();
   
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <div class="text-sm text-gray-500">Customers Checked</div>
        <div class="text-xl font-bold">{{ number_format($totalSearches) }}</div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <div class="text-sm text-gray-500">High Risk Found</div>
        <div class="text-xl font-bold text-red-500">{{ $highRisk }}</div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <div class="text-sm text-gray-500">Safe Customers</div>
        <div class="text-xl font-bold text-green-600">{{ $safeRate }}%</div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <div class="text-sm text-gray-500">Money Saved</div>
        <div class="text-xl font-bold text-green-600">
            Rs {{ number_format($moneySaved) }}
        </div>
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-bold">Estimated Savings</h2>

        <p class="text-sm text-gray-500 mb-2">
            Based on COD performance data
        </p>

        <div class="text-xl font-bold text-green-600 mb-2">
            Rs 30,000 – 80,000 / month
        </div>

        <div class="text-xs text-gray-400">
            Based on analysis of 100,000+ customer records
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-bold mb-3">High Risk Customers</h2>

        @if($customers->isEmpty())
            <div class="text-gray-500 text-sm">
                No high-risk customers detected<br>
                Your orders look safe to dispatch
            </div>
        @else
            @foreach($customers as $c)
                <!-- @php
                    if ($c->returned_orders >= 3) {
                        $risk = 'High Risk';
                        $color = 'red';
                    } elseif ($c->returned_orders == 2) {
                        $risk = 'Medium Risk';
                        $color = 'orange';
                    } else {
                        $risk = 'Low Risk';
                        $color = 'green';
                    }
                @endphp -->

                <div class="flex justify-between py-2 border-b">
                    <span class="text-sm">{{ $c->phone }}</span>

                    <!-- <span style="color: {{ $color }}; font-weight: 600;">
                        {{ $risk }} – {{ $c->returned_orders }} returns
                    </span> -->
                </div>
            @endforeach

            <div class="mt-4 text-xs text-gray-500">
                Recommendation: Call these customers before dispatch or avoid COD
            </div>
        @endif
    </div>

</div>

</x-filament::page>