<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PuffCart') — PuffCart Vape Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#04050d] text-[#c8f0ff] font-mono min-h-screen">

    {{-- Scanline overlay --}}
    <div class="fixed inset-0 pointer-events-none z-50"
         style="background: repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,255,231,0.012) 2px,rgba(0,255,231,0.012) 4px)"></div>

    {{-- NAV --}}
    <nav class="bg-[#070a14] border-b border-[#00ffe730] sticky top-0 z-40 relative">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex flex-col">
                <span class="font-['Orbitron'] text-base font-bold text-[#00ffe7] tracking-widest"
                      style="text-shadow: 0 0 12px #00ffe7">PUFFCART</span>
                <span class="text-[9px] text-[#007a6e] tracking-[0.12em]">// CLOUDPUFFS VAPE SHOP</span>
            </a>

            <div class="hidden md:flex items-center gap-6 text-[11px] text-[#5a8fa8] tracking-wide">
                <a href="{{ route('home') }}" class="hover:text-[#00ffe7] transition-colors">Home</a>
                <a href="{{ route('shop') }}" class="hover:text-[#00ffe7] transition-colors">Shop</a>
                <a href="{{ route('shop') }}?category=devices" class="hover:text-[#00ffe7] transition-colors">Devices</a>
                <a href="{{ route('shop') }}?category=e-liquids" class="hover:text-[#00ffe7] transition-colors">E-Liquids</a>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('cart.index') }}"
                       class="font-['Orbitron'] text-[9px] px-3 py-1.5 border border-[#00ffe7] text-[#00ffe7] tracking-widest hover:bg-[#00ffe718]">
                        CART [{{ auth()->user()->cartItems()->sum('quantity') }}]
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="font-['Orbitron'] text-[9px] px-3 py-1.5 text-[#5a8fa8] hover:text-[#00ffe7] tracking-widest">
                        ORDERS
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="font-['Orbitron'] text-[9px] px-3 py-1.5 bg-[#ff003c] text-white tracking-widest border border-[#ff003c]">
                            SIGN_OUT
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="font-['Orbitron'] text-[9px] px-3 py-1.5 border border-[#00ffe7] text-[#00ffe7] tracking-widest hover:bg-[#00ffe718]">
                        SIGN_IN
                    </a>
                    <a href="{{ route('register') }}"
                       class="font-['Orbitron'] text-[9px] px-3 py-1.5 bg-[#ff003c] text-white tracking-widest">
                        REGISTER
                    </a>
                @endauth
            </div>
        </div>
        {{-- Neon bottom line --}}
        <div class="absolute bottom-0 left-0 right-0 h-px"
             style="background: linear-gradient(90deg, transparent, #00ffe7, transparent)"></div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-[#001f1c] border border-[#00ffe730] text-[#00ffe7] text-xs px-6 py-3 text-center font-mono">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-[#1a0009] border border-[#ff003c55] text-[#ff003c] text-xs px-6 py-3 text-center font-mono">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#070a14] border-t border-[#00ffe718] mt-16"
            style="background-image: linear-gradient(#00ffe708 1px,transparent 1px),linear-gradient(90deg,#00ffe708 1px,transparent 1px);background-size:32px 32px">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-4 gap-8">
            <div>
                <div class="font-['Orbitron'] text-sm text-[#00ffe7] mb-2 tracking-widest">PUFFCART</div>
                <p class="text-[10px] text-[#1e3d52] leading-relaxed">Your trusted cyberpunk vape destination. Quality products. Fast delivery.</p>
                <span class="inline-block mt-3 bg-[#1a0009] text-[#ff003c] text-[9px] px-3 py-1 border border-[#cc002f] font-['Orbitron'] tracking-widest">18+ ONLY</span>
            </div>
            <div>
                <div class="text-[9px] text-[#1e3d52] tracking-[0.15em] uppercase mb-3">// Shop</div>
                @foreach(['Devices','E-Liquids','Coils & Pods','Accessories'] as $link)
                    <a href="{{ route('shop') }}" class="block text-[11px] text-[#1e3d52] hover:text-[#00ffe7] mb-2">{{ $link }}</a>
                @endforeach
            </div>
            <div>
                <div class="text-[9px] text-[#1e3d52] tracking-[0.15em] uppercase mb-3">// Account</div>
                @foreach(['My Orders' => 'orders.index', 'Profile' => 'profile.index'] as $label => $route)
                    <a href="{{ route($route) }}" class="block text-[11px] text-[#1e3d52] hover:text-[#00ffe7] mb-2">{{ $label }}</a>
                @endforeach
            </div>
            <div>
                <div class="text-[9px] text-[#1e3d52] tracking-[0.15em] uppercase mb-3">// Payments</div>
                @foreach(['GCash', 'Maya', 'Cash on Delivery', 'Bank Transfer'] as $pm)
                    <div class="text-[11px] text-[#1e3d52] mb-2">{{ $pm }}</div>
                @endforeach
            </div>
        </div>
        <div class="border-t border-[#00ffe710] px-6 py-3 flex justify-between items-center max-w-7xl mx-auto">
            <span class="text-[9px] text-[#1e3d52]">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#00ffe7] mr-1 align-middle"></span>
                SYSTEM ONLINE · © {{ date('Y') }} PuffCart — CloudPuffs Shop
            </span>
            <span class="text-[9px] text-[#1e3d52]">Laravel v11 · PHP 8.2</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
