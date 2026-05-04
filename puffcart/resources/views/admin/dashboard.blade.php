@extends('layouts.admin')

@section('page-title', 'DASHBOARD OVERVIEW')

@section('topbar-actions')
    <a href="{{ route('admin.reports.pdf', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->toDateString()]) }}"
       class="font-['Orbitron'] text-[8px] px-3 py-1 border border-[#00ffe730] text-[#00ffe7] hover:bg-[#00ffe718] tracking-widest">
        EXPORT_PDF
    </a>
    <a href="{{ route('admin.reports.csv', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->toDateString()]) }}"
       class="font-['Orbitron'] text-[8px] px-3 py-1 border border-[#00ffe730] text-[#5a8fa8] hover:bg-[#00ffe708] tracking-widest">
        EXPORT_CSV
    </a>
@endsection

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-4 gap-3 mb-4">
    @php
        $stats = [
            ['label' => 'Total Sales',    'value' => '₱'.number_format($totalSales,2), 'change' => '↑ 12% this month', 'color' => '#00ffe7'],
            ['label' => 'Orders Today',   'value' => $todayOrders,                     'change' => 'vs yesterday',      'color' => '#ff003c'],
            ['label' => 'Customers',      'value' => $totalCustomers,                  'change' => 'registered',        'color' => '#ffe600'],
            ['label' => 'Low Stock',      'value' => $lowStockCount,                   'change' => 'items need restock','color' => '#bf00ff'],
        ];
    @endphp
    @foreach($stats as $stat)
        <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-3 relative overflow-hidden"
             style="border-top: 2px solid {{ $stat['color'] }}">
            <div class="text-[9px] text-[#1e3d52] uppercase tracking-widest mb-1">{{ $stat['label'] }}</div>
            <div class="font-['Orbitron'] text-xl font-bold" style="color:{{ $stat['color'] }};text-shadow:0 0 8px {{ $stat['color'] }}">{{ $stat['value'] }}</div>
            <div class="text-[9px] mt-1" style="color:{{ $stat['color'] }}88">{{ $stat['change'] }}</div>
        </div>
    @endforeach
</div>

{{-- Charts row --}}
<div class="grid grid-cols-2 gap-3 mb-3">

    {{-- Weekly sales --}}
    <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4">
        <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-4 flex justify-between">
            // WEEKLY SALES
            <a href="{{ route('admin.reports.index') }}" class="text-[#00ffe7] hover:underline">Full_Report →</a>
        </div>
        <div class="flex items-end gap-2" style="height:80px">
            @foreach($weeklySales as $i => $day)
                @php $maxVal = $weeklySales->max('amount') ?: 1; $h = max(4, ($day['amount']/$maxVal)*100); $isToday = $i === 6; @endphp
                <div class="flex flex-col items-center gap-1 flex-1">
                    <div class="w-full rounded-t-sm"
                         style="height:{{ $h }}%;background:{{ $isToday ? '#ff003c' : '#00ffe7' }};box-shadow:0 0 4px {{ $isToday ? '#ff003c' : '#00ffe7' }}"></div>
                    <div class="text-[8px] text-[#1e3d52]">{{ $day['day'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Top products --}}
    <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4">
        <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-3">// TOP PRODUCTS</div>
        <div class="flex flex-col gap-2">
            @foreach($topProducts as $i => $product)
                <div class="flex items-center gap-2">
                    <div class="text-[9px] text-[#1e3d52] w-4">{{ $i + 1 }}</div>
                    <div class="text-[10px] text-[#c8f0ff] w-28 truncate">{{ $product->name }}</div>
                    <div class="flex-1 bg-[#14172a] rounded h-1.5">
                        @php $maxSold = $topProducts->max('total_sold') ?: 1; @endphp
                        <div class="h-1.5 rounded bg-[#00ffe7]"
                             style="width:{{ ($product->total_sold / $maxSold) * 100 }}%;box-shadow:0 0 4px #00ffe7"></div>
                    </div>
                    <div class="text-[9px] text-[#1e3d52] w-12 text-right">{{ $product->total_sold ?? 0 }} sold</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent orders --}}
<div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4 mb-3">
    <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-3 flex justify-between">
        // RECENT ORDERS
        <a href="{{ route('admin.orders.index') }}" class="text-[#00ffe7] hover:underline">Manage_All →</a>
    </div>
    <div class="flex flex-col gap-1.5">
        @foreach($recentOrders as $order)
            @php
                $statusColors = ['completed'=>'#00ffe7','processing'=>'#ff003c','pending'=>'#ffe600','cancelled'=>'#bf00ff','packed'=>'#00ffe7','out_for_delivery'=>'#00ffe7'];
                $sc = $statusColors[$order->status] ?? '#5a8fa8';
            @endphp
            <div class="flex items-center gap-3 bg-[#14172a] rounded-sm px-3 py-2">
                <div class="font-['Orbitron'] text-[10px] text-[#00ffe7] w-24">{{ $order->order_number }}</div>
                <div class="text-[10px] text-[#c8f0ff] flex-1">{{ $order->user->name }}</div>
                <div class="text-[10px] text-[#c8f0ff] w-20 text-right">₱{{ number_format($order->total, 2) }}</div>
                <span class="text-[8px] px-2 py-0.5 font-['Orbitron'] tracking-wide border"
                      style="color:{{ $sc }};border-color:{{ $sc }}44;background:{{ $sc }}12">
                    {{ strtoupper($order->status) }}
                </span>
                <a href="{{ route('admin.orders.show', $order) }}" class="text-[9px] text-[#00ffe7] hover:underline ml-2">View →</a>
            </div>
        @endforeach
    </div>
</div>

{{-- Bottom row --}}
<div class="grid grid-cols-3 gap-3">

    {{-- Inventory alerts --}}
    <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4">
        <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-3">// INVENTORY ALERTS</div>
        @foreach($lowStockItems as $item)
            @php $pct = ($item->stock / max(1, $item->reorder_level * 3)) * 100; $col = $item->stock <= 5 ? '#ff003c' : '#ffe600'; @endphp
            <div class="mb-3">
                <div class="flex justify-between text-[9px] mb-1">
                    <span class="text-[#5a8fa8] truncate w-32">{{ $item->name }}</span>
                    <span style="color:{{ $col }}">{{ $item->stock }} left</span>
                </div>
                <div class="h-1 bg-[#14172a] rounded">
                    <div class="h-1 rounded" style="width:{{ min(100,$pct) }}%;background:{{ $col }};box-shadow:0 0 4px {{ $col }}"></div>
                </div>
            </div>
        @endforeach
        <a href="{{ route('admin.inventory.index') }}" class="text-[9px] text-[#00ffe7] hover:underline font-['Orbitron'] tracking-wide">Manage_Inventory →</a>
    </div>

    {{-- Payment breakdown --}}
    <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4">
        <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-3">// PAYMENT METHODS</div>
        @php $totalPayments = $paymentBreakdown->sum('count') ?: 1; @endphp
        @foreach($paymentBreakdown as $pm)
            <div class="flex justify-between items-center mb-2">
                <span class="text-[9px] text-[#5a8fa8] uppercase">{{ $pm->method }}</span>
                <span class="font-['Orbitron'] text-[10px] text-[#00ffe7]">{{ round(($pm->count / $totalPayments) * 100) }}%</span>
            </div>
        @endforeach
    </div>

    {{-- Order status --}}
    <div class="bg-[#0a0e1c] border border-[#00ffe718] rounded-sm p-4">
        <div class="font-['Orbitron'] text-[9px] text-[#c8f0ff] tracking-widest mb-3">// ORDER STATUS</div>
        @php
            $statusDisplay = ['completed'=>['#00ffe7','Completed'],'processing'=>['#ff003c','Processing'],'pending'=>['#ffe600','Pending'],'cancelled'=>['#bf00ff','Cancelled']];
        @endphp
        @foreach($statusDisplay as $key => [$color, $label])
            <div class="flex justify-between items-center mb-2">
                <span class="text-[9px] text-[#5a8fa8]">{{ $label }}</span>
                <span class="font-['Orbitron'] text-[10px]" style="color:{{ $color }}">{{ $orderStatusCounts[$key] ?? 0 }}</span>
            </div>
        @endforeach
        <div class="border-t border-[#00ffe718] pt-2 flex justify-between">
            <span class="text-[9px] text-[#5a8fa8]">Total</span>
            <span class="font-['Orbitron'] text-[10px] text-[#c8f0ff]">{{ $orderStatusCounts->sum() }}</span>
        </div>
    </div>

</div>

@endsection
