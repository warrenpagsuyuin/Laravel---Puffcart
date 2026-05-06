@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f9f9f9] px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-[#1a1a1a]">Puffcart</h1>
                <h2 class="text-xl font-semibold text-[#1a1a1a] mt-4">Multi-Factor Authentication</h2>
                <p class="text-[#666666] mt-2">Enter the 6-digit code sent to your email</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.mfa.verify') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="mfa_code" class="block text-sm font-medium text-[#1a1a1a] mb-2">
                        MFA Code
                    </label>
                    <input
                        type="text"
                        name="mfa_code"
                        id="mfa_code"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        class="w-full px-4 py-3 border border-[#e0e0e0] rounded-lg text-center text-2xl font-bold tracking-widest focus:outline-none focus:ring-2 focus:ring-[#0066ff] focus:border-transparent"
                        required
                        autofocus
                    />
                    @error('mfa_code')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#0066ff] hover:bg-[#0052cc] text-white font-semibold py-3 rounded-lg transition duration-200"
                >
                    Verify Code
                </button>

                <p class="text-center text-[#666666] text-sm">
                    Didn't receive the code?
                    <a href="{{ route('admin.login') }}" class="text-[#0066ff] hover:underline font-medium">
                        Start over
                    </a>
                </p>
            </form>

            <div class="mt-6 p-4 bg-[#e6f0ff] rounded-lg">
                <p class="text-sm text-[#0066ff] font-medium">
                    💡 The MFA code is valid for 5 minutes and can only be used once.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    input[type="text"]::-webkit-outer-spin-button,
    input[type="text"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection
