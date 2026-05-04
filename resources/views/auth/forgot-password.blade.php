@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen grid grid-cols-2"
     style="background-image:linear-gradient(#00ffe708 1px,transparent 1px),linear-gradient(90deg,#00ffe708 1px,transparent 1px);background-size:32px 32px">

    {{-- Left brand panel --}}
    <div class="bg-[#070a14] border-r border-[#00ffe730] flex flex-col items-center justify-center p-10">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-[#001f1c] border border-[#007a6e] rounded-sm flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" viewBox="0 0 16 16" fill="#00ffe7">
                    <rect x="5" y="1" width="6" height="10" rx="2"/>
                    <rect x="6" y="11" width="4" height="3" rx="1"/>
                    <circle cx="8" cy="3.5" r="1.2"/>
                </svg>
            </div>
            <div class="font-['Orbitron'] text-2xl text-[#00ffe7] tracking-widest" style="text-shadow:0 0 14px #00ffe7">PUFFCART</div>
            <div class="text-[9px] text-[#1e3d52] tracking-widest mt-1">// CLOUDPUFFS ONLINE SHOP</div>
        </div>
        <div class="space-y-3 w-full max-w-xs">
            @foreach(['Browse 200+ vape products' => '#00ffe7', 'Same-day Metro Manila delivery' => '#00ffe7', 'GCash, Maya & COD accepted' => '#ff003c', 'Real-time order tracking' => '#ffe600', 'AI-powered VaultBot assistant' => '#bf00ff'] as $feat => $color)
                <div class="flex items-center gap-3 text-[11px] text-[#5a8fa8]">
                    <span style="color:{{ $color }}">■</span> {{ $feat }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right auth panel --}}
    <div class="flex items-center justify-center p-10">
        <div class="bg-[#0a0e1c] border border-[#00ffe730] rounded-sm p-7 w-full max-w-sm relative">
            <div class="absolute top-0 left-0 w-3 h-3 border-t-2 border-l-2 border-[#00ffe7]"></div>
            <div class="absolute top-0 right-0 w-3 h-3 border-t-2 border-r-2 border-[#00ffe7]"></div>
            <div class="absolute bottom-0 left-0 w-3 h-3 border-b-2 border-l-2 border-[#00ffe7]"></div>
            <div class="absolute bottom-0 right-0 w-3 h-3 border-b-2 border-r-2 border-[#00ffe7]"></div>

            <h2 class="font-['Orbitron'] text-[13px] text-[#00ffe7] tracking-widest mb-1">RESET_PASSWORD</h2>
            <p class="text-[9px] text-[#5a8fa8] mb-6">Enter your email to receive a password reset link.</p>

            @if(session('status'))
                <div class="bg-[#001a09] border border-[#00ffe755] text-[#00ffe7] text-[10px] p-3 mb-4 rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#1a0009] border border-[#ff003c55] text-[#ff003c] text-[10px] p-3 mb-4 rounded-sm">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Email Address</div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                           class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] px-3 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7] @error('email') border-[#ff003c] @enderror">
                </div>
                <button type="submit"
                        class="w-full font-['Orbitron'] text-[10px] py-3 bg-[#00ffe7] text-[#04050d] font-bold tracking-widest hover:bg-[#00ccb8] transition-colors"
                        style="clip-path:polygon(6px 0,100% 0,100% calc(100% - 6px),calc(100% - 6px) 100%,0 100%,0 6px)">
                    SEND_RESET_LINK →
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-[9px] text-[#00ffe7] hover:underline">Back to Sign In →</a>
            </div>
        </div>
    </div>
</div>
@endsection
