@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f9f9f9] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-[#1a1a1a] mb-2">Payment Cancelled</h1>
        <p class="text-[#666666] mb-6">
            Your payment has been cancelled. No charges have been made to your account.
        </p>

        <div class="bg-[#e6f0ff] border border-[#0066ff] rounded-lg p-4 mb-6">
            <p class="text-[#0066ff] font-semibold">Order #{{ $order->order_number }}</p>
            <p class="text-[#0066ff] text-sm mt-2">Amount: ₱{{ number_format($order->total, 2) }}</p>
        </div>

        <p class="text-[#666666] text-sm mb-6">
            Your cart has been saved. You can try checking out again or contact us if you need assistance.
        </p>

        <div class="space-y-3">
            <a href="{{ route('cart') }}" class="block w-full bg-[#0066ff] hover:bg-[#0052cc] text-white font-semibold py-3 rounded-lg transition">
                Back to Cart
            </a>
            <a href="{{ route('shop') }}" class="block w-full bg-[#f9f9f9] hover:bg-[#e0e0e0] text-[#1a1a1a] font-semibold py-3 rounded-lg transition border border-[#e0e0e0]">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
