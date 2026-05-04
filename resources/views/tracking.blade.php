@extends('layouts.app')

@section('title', 'Tracking')

@section('content')
<style>
body{background:#04050d;color:#c8f0ff;margin:0}
.nav{display:flex;justify-content:space-between;padding:15px 40px;background:#070a14;border-bottom:1px solid #00ffe730}
.logo{color:#00ffe7;letter-spacing:3px;font-weight:bold}
.nav a{color:#5a8fa8;margin-left:20px;text-decoration:none}
.wrap{padding:60px}
.box{background:#0a0e1c;border:1px solid #00ffe730;padding:30px}
.step{padding:15px;border-left:3px solid #00ffe7;margin:15px 0}
h1{color:#00ffe7}
</style>

<div class="nav">
    <div class="logo">PUFFCART</div>
    <div>
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/cart">Cart</a>
    </div>
</div>

<div class="wrap">
    <div class="box">
        <h1>ORDER TRACKING</h1>
        <h2>#ORD-0091</h2>
        <p>Status: <span style="color:#00ffe7;">OUT FOR DELIVERY</span></p>

        <div class="step">✅ Order Placed</div>
        <div class="step">✅ Order Packed</div>
        <div class="step">🚚 Out for Delivery</div>
        <div class="step">○ Delivered</div>
    </div>
</div>
@endsection