@extends('layouts.app')

@section('title', 'Create Account')

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
        <div class="bg-[#0a0e1c] border border-[#00ffe730] rounded-sm p-7 w-full max-w-sm relative overflow-y-auto max-h-screen">
            <div class="absolute top-0 left-0 w-3 h-3 border-t-2 border-l-2 border-[#00ffe7]"></div>
            <div class="absolute top-0 right-0 w-3 h-3 border-t-2 border-r-2 border-[#00ffe7]"></div>
            <div class="absolute bottom-0 left-0 w-3 h-3 border-b-2 border-l-2 border-[#00ffe7]"></div>
            <div class="absolute bottom-0 right-0 w-3 h-3 border-b-2 border-r-2 border-[#00ffe7]"></div>

            {{-- Tabs --}}
            <div class="flex bg-[#14172a] rounded-sm p-1 mb-5 border border-[#00ffe718]">
                <a href="{{ route('login') }}" class="flex-1 text-center font-['Orbitron'] text-[9px] py-2 text-[#1e3d52] tracking-widest hover:text-[#5a8fa8]">SIGN_IN</a>
                <span class="flex-1 text-center font-['Orbitron'] text-[9px] py-2 bg-[#00ffe7] text-[#04050d] font-bold rounded-sm tracking-widest cursor-pointer">REGISTER</span>
            </div>

            @if($errors->any())
                <div class="bg-[#1a0009] border border-[#ff003c55] text-[#ff003c] text-[10px] p-3 mb-4 rounded-sm">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Full Name</div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your Name"
                           class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] px-3 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7] @error('name') border-[#ff003c] @enderror">
                </div>
                <div class="mb-3">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Email</div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                           class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] px-3 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7] @error('email') border-[#ff003c] @enderror">
                </div>
                <div class="mb-3">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Phone (Optional)</div>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX"
                           class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] px-3 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7]">
                </div>
                <div class="mb-3">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Password</div>
                    <div class="relative">
                        <input id="auth_register_password" type="password" name="password" placeholder="••••••••"
                               class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] pl-3 pr-10 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7] @error('password') border-[#ff003c] @enderror">
                        <button type="button" data-password-toggle="auth_register_password" aria-label="Show password" aria-pressed="false" class="absolute right-1 top-1/2 -translate-y-1/2 grid place-items-center w-8 h-8 text-[#5a8fa8] hover:text-[#00ffe7]">
                            <svg class="icon-eye w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off hidden w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-[8px] text-[#1e3d52] tracking-widest uppercase mb-1">// Confirm Password</div>
                    <div class="relative">
                        <input id="auth_register_password_confirmation" type="password" name="password_confirmation" placeholder="••••••••"
                               class="w-full bg-[#14172a] border border-[#00ffe718] text-[#c8f0ff] text-[12px] pl-3 pr-10 py-2 rounded-sm outline-none font-mono focus:border-[#00ffe7]">
                        <button type="button" data-password-toggle="auth_register_password_confirmation" aria-label="Show password" aria-pressed="false" class="absolute right-1 top-1/2 -translate-y-1/2 grid place-items-center w-8 h-8 text-[#5a8fa8] hover:text-[#00ffe7]">
                            <svg class="icon-eye w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off hidden w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit"
                        class="w-full font-['Orbitron'] text-[10px] py-3 bg-[#00ffe7] text-[#04050d] font-bold tracking-widest hover:bg-[#00ccb8] transition-colors"
                        style="clip-path:polygon(6px 0,100% 0,100% calc(100% - 6px),calc(100% - 6px) 100%,0 100%,0 6px)">
                    CREATE_ACCOUNT →
                </button>
            </form>

            <div class="text-center mt-4 text-[9px] text-[#1e3d52]">
                Already have an account? <a href="{{ route('login') }}" class="text-[#00ffe7] hover:underline">Sign In →</a>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.passwordToggle);
                if (!input) {
                    return;
                }

                const shouldShow = input.type === 'password';
                input.type = shouldShow ? 'text' : 'password';
                this.querySelector('.icon-eye')?.classList.toggle('hidden', shouldShow);
                this.querySelector('.icon-eye-off')?.classList.toggle('hidden', !shouldShow);
                this.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                this.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
            });
        });
    });
</script>
@endsection
