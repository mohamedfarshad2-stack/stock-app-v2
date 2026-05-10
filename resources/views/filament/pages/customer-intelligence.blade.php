<x-filament::page>
<div class="space-y-6 text-gray-900">

    {{-- CONNECTS --}}
    <div class="flex justify-end">
        <div class="bg-white px-4 py-2 rounded-xl shadow text-sm font-semibold">
            Connects: {{ auth()->user()->connects }}
        </div>
    </div>

    {{-- NO CONNECT WARNING --}}
    @if(auth()->user()->connects <= 0 && auth()->user()->role != 1)
        <div class="bg-red-100 text-red-700 p-4 rounded-xl font-semibold">
            🚫 No connects remaining. Please upgrade your plan.
        </div>
    @endif

    {{-- SEARCH --}}
    <div class="rounded-2xl border bg-white p-6 shadow-md">
        {{ $this->form }}
    </div>

    {{-- NO RESULT --}}
    @if(!$result && filled(data_get($this->form->getState(), 'search')))
        <div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700 font-medium">
            No customer found for this number.
        </div>
    @endif

    @if($result)
        @php
            $customer = $result['primary_customer'];
            $orders = $result['orders'];
            $summary = $result['summary'];
            $financial = $result['financial'];
            $risk = $result['risk'];
            $prediction = $result['prediction'];
            $segment = $result['segment'];
            $recommendation = $result['recommendation'];
        @endphp

        {{-- CUSTOMER CARD --}}
        <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 p-6 shadow-lg ">
            <div class="flex justify-between items-center">
              
                <div class="text-sm font-semibold flex flex-wrap gap-2 items-center">

    <span class="text-lg font-bold">
        {{ data_get($customer, 'name', 'Unknown Customer') }}
    </span>

    <span>|</span>

    <span>📞 {{ data_get($customer, 'phone', '-') }}</span>

    @if(data_get($customer, 'alternate_phone'))
        <span>|</span>
        <span>📱 {{ data_get($customer, 'alternate_phone') }}</span>
    @endif

    @if(data_get($customer, 'city'))
        <span>|</span>
        <span>📍 {{ data_get($customer, 'city') }}</span>
    @endif

</div>

                <div class="text-right">
                    <div class="text-3xl font-bold">
                        {{ $prediction['delivery_probability'] }}%
                    </div>
                    <div class="text-xs text-white/80">Delivery Chance</div>
                </div>
            </div>

            <div class="mt-4 flex gap-2 flex-wrap">
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs">
                    {{ $segment }}
                </span>

                <span class="px-3 py-1 bg-white/20 rounded-full text-xs">
                    Risk: {{ $risk['risk_level'] }}
                </span>

                @if(data_get($customer, 'is_blacklisted'))
                    <span class="px-3 py-1 bg-red-500/40 rounded-full text-xs">
                        🚫 Blacklisted
                    </span>
                @endif
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-2 md:grid-cols-7 gap-4">

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Orders</div>
                <div class="text-xl font-bold">{{ $summary['total_orders'] }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Delivered</div>
                <div class="text-xl font-bold text-green-600">{{ $summary['delivered_orders'] }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Cancelled</div>
                <div class="text-xl font-bold text-red-600">{{ $summary['cancelled_orders'] }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">No Answer</div>
                <div class="text-xl font-bold text-yellow-600">
                    {{ $summary['no_answer_count'] ?? 0 }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Fake Orders</div>
                <div class="text-xl font-bold text-red-600">
                    {{ $summary['fake_order_count'] ?? 0 }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Returns</div>
                <div class="text-xl font-bold">
                    {{ $summary['returned_orders'] }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center">
                <div class="text-xs">Success %</div>
                <div class="text-xl font-bold">
                    {{ $summary['success_rate'] }}%
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow text-center col-span-2">
                <!-- <div class="text-xs">LTV</div> -->
             @php 
    $isPremium = auth()->user()->client_type === 'premium';
    $ltv = $financial['ltv'] ?? 0;
@endphp

<div class="bg-white p-4 rounded-xl shadow text-center col-span-2">

    <div class="text-xs text-gray-500 font-medium flex items-center justify-center gap-1">
        LTV
        @if(!$isPremium)
            <span class="text-gray-400">🔒</span>
        @endif
    </div>

    <div class="text-xl font-bold relative">

        @if(!$isPremium)
            <!-- Blurred fake value -->
            <span class="blur-sm select-none">
                Rs. 25,000.00
            </span>

            <!-- Overlay actual shown value -->
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-gray-900">
                    Rs. 1.00
                </span>
            </div>

        @else
            Rs. {{ number_format((float)$ltv, 2) }}
        @endif

    </div>

    @if(!$isPremium)
        <div class="text-xs text-blue-600 mt-1 font-medium cursor-pointer">
            🔓 Upgrade to unlock real value
        </div>
    @endif

</div>
            </div>

        </div>

        {{-- RECOMMENDATION --}}
        <div class="rounded-2xl p-6 shadow-md
            @if($recommendation['color'] === 'success') bg-green-100
            @elseif($recommendation['color'] === 'warning') bg-yellow-100
            @else bg-red-100 @endif
        ">
            <div class="flex justify-between">
                <h3 class="text-lg font-bold">🚀 Dispatch Decision</h3>
                <span class="font-bold">
                    {{ $recommendation['label'] }}
                </span>
            </div>

            <p class="mt-2">
                {{ $recommendation['message'] }}
            </p>
        </div>

        {{-- ORDER TABLE --}}
        <div class="rounded-2xl border bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-4">Order History</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Address</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-t">
                                <td class="px-4 py-2">#{{ data_get($order, 'id') }}</td>

                                <td class="px-4 py-2">
                                    {{ data_get($order, 'created_at') 
                                        ? \Carbon\Carbon::parse(data_get($order, 'created_at'))->format('Y-m-d') 
                                        : '-' }}
                                </td>

                                <td class="px-4 py-2">
                                    Rs. {{ number_format(data_get($order, 'total_amount', 0), 2) }}
                                </td>

                                <td class="px-4 py-2">
                                    {{ ucfirst(data_get($order, 'delivery_status', '-')) }}
                                </td>

                                <td class="px-4 py-2 text-xs">
                                    {{ data_get($order, 'address', '-') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
</x-filament::page>