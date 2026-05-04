<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PuffCart Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#04050d] text-[#c8f0ff] font-mono min-h-screen flex">

    {{-- Scanlines --}}
    <div class="fixed inset-0 pointer-events-none z-50"
         style="background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,255,231,0.012) 2px,rgba(0,255,231,0.012) 4px)"></div>

    {{-- SIDEBAR --}}
    <aside class="w-44 bg-[#070a14] border-r border-[#00ffe730] flex flex-col shrink-0 min-h-screen sticky top-0"
           style="background-image:linear-gradient(#00ffe708 1px,transparent 1px),linear-gradient(90deg,#00ffe708 1px,transparent 1px);background-size:32px 32px">
        <div class="px-4 py-4 border-b border-[#00ffe718]">
            <div class="font-['Orbitron'] text-xs text-[#00ffe7] tracking-widest" style="text-shadow:0 0 8px #00ffe7">PUFFCART</div>
            <div class="text-[8px] text-[#1e3d52] tracking-[0.12em] mt-0.5">// ADMIN PANEL</div>
        </div>

        <nav class="flex-1 py-2">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',        'label' => 'Dashboard',  'color' => 'cyan'],
                    ['route' => 'admin.orders.index',     'label' => 'Orders',     'color' => 'pink', 'badge' => \App\Models\Order::whereIn('status',['pending','processing'])->count()],
                    ['route' => 'admin.products.index',   'label' => 'Products',   'color' => 'cyan'],
                    ['route' => 'admin.customers.index',  'label' => 'Customers',  'color' => 'cyan'],
                    ['route' => 'admin.inventory.index',  'label' => 'Inventory',  'color' => 'yellow', 'badge' => \App\Models\Product::lowStock()->count()],
                    ['route' => 'admin.payments.index',   'label' => 'Payments',   'color' => 'cyan'],
                    ['route' => 'admin.reports.index',    'label' => 'Reports',    'color' => 'cyan'],
                ];
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-2 px-4 py-2 text-[10px] tracking-wide transition-all
                          {{ request()->routeIs($item['route']) ? 'text-[#00ffe7] bg-[#00ffe708] border-l-2 border-[#00ffe7]' : 'text-[#5a8fa8] hover:text-[#c8f0ff] border-l-2 border-transparent' }}">
                    <span class="{{ request()->routeIs($item['route']) ? 'text-[#00ffe7]' : 'text-[#1e3d52]' }}">■</span>
                    {{ $item['label'] }}
                    @if(!empty($item['badge']) && $item['badge'] > 0)
                        <span class="ml-auto bg-[#ff003c] text-white text-[8px] px-1.5 py-0.5 font-['Orbitron']">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-3 border-t border-[#00ffe718] flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-[#00ffe7] flex items-center justify-center text-[8px] text-[#04050d] font-bold font-['Orbitron']">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div class="text-[10px] text-[#c8f0ff]">{{ auth()->user()->name }}</div>
                <div class="text-[8px] text-[#1e3d52]">Administrator</div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="bg-[#070a14] border-b border-[#00ffe730] h-12 flex items-center justify-between px-5 sticky top-0 z-30">
            <div class="font-['Orbitron'] text-[11px] text-[#c8f0ff] tracking-widest">
                // @yield('page-title', 'DASHBOARD')
            </div>
            <div class="flex items-center gap-2">
                @yield('topbar-actions')
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="font-['Orbitron'] text-[8px] px-3 py-1 border border-[#ff003c55] text-[#ff003c] hover:bg-[#ff003c18] tracking-widest">
                        SIGN_OUT
                    </button>
                </form>
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success'))
            <div class="bg-[#001f1c] border-b border-[#00ffe730] text-[#00ffe7] text-xs px-6 py-2 font-mono">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-[#1a0009] border-b border-[#ff003c55] text-[#ff003c] text-xs px-6 py-2 font-mono">✕ {{ session('error') }}</div>
        @endif

        <main class="flex-1 p-5" style="background-image:linear-gradient(#00ffe708 1px,transparent 1px),linear-gradient(90deg,#00ffe708 1px,transparent 1px);background-size:32px 32px">
            @yield('content')
        </main>
    </div>

</body>
</html>
