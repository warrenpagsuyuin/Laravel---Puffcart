@extends('layouts.app')

@section('title', 'Home')

@section('content')

<style>
body {
    background: #04050d;
    color: #c8f0ff;
    margin: 0;
}

/* simple neon styling */
.nav {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 40px;
    border-bottom:1px solid #00ffe730;
}

.logo {
    font-family: Orbitron, monospace;
    color:#00ffe7;
    letter-spacing:2px;
    font-size:18px;
}

.nav a {
    color:#5a8fa8;
    margin-left:20px;
    text-decoration:none;
}

.nav a:hover {
    color:#00ffe7;
}

/* hero */
.hero {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:80px 60px;
}

.hero-text h1 {
    font-family: Orbitron;
    font-size:40px;
    color:#00ffe7;
    text-shadow:0 0 20px #00ffe7;
}

.hero-text h2 {
    color:#ff003c;
    letter-spacing:2px;
}

.hero-text p {
    color:#5a8fa8;
    max-width:400px;
    margin-top:10px;
}

.btn {
    margin-top:20px;
    padding:12px 25px;
    border:1px solid #00ffe7;
    color:#00ffe7;
    text-decoration:none;
    display:inline-block;
}

.btn:hover {
    background:#00ffe7;
    color:#04050d;
}

/* card */
.card {
    background:#0a0e1c;
    padding:20px;
    border:1px solid #00ffe730;
}

.price {
    color:#00ffe7;
    font-size:20px;
}

/* section */
.section {
    padding:60px;
}

.grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
}

.product {
    background:#0a0e1c;
    padding:15px;
    border:1px solid #00ffe730;
}

.product:hover {
    border-color:#00ffe7;
}
</style>

<!-- NAV -->
<div class="nav">
    <div class="logo">PUFFCART</div>
    <div>
    <a href="{{ url('/') }}">Home</a>
    <a href="{{ url('/shop') }}">Shop</a>
    <a href="{{ url('/cart') }}">Cart</a>
    <a href="{{ url('/login') }}">Login</a>
    <a href="{{ url('/tracking') }}">Tracking</a>
    <a href="{{ url('/admin') }}">Admin</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-text">
        <h1>YOUR PREMIUM</h1>
        <h2>VAPE DESTINATION</h2>
        <p>
            Explore premium vape devices, liquids, and accessories.
            Fast delivery. Secure checkout.
        </p>

        <a href="/shop" class="btn">SHOP NOW →</a>
    </div>

    <div class="card">
        <p>Featured Device</p>
        <h3>XROS 4 MINI</h3>
        <div class="price">₱1,299</div>
    </div>
</div>

<!-- PRODUCTS -->
<div class="section">
    <h2 style="color:#00ffe7;font-family:Orbitron;">FEATURED PRODUCTS</h2>

    <div class="grid">

        <div class="product">
            <h4>XROS 4</h4>
            <p>Pod System</p>
            <div class="price">₱1,299</div>
        </div>

        <div class="product">
            <h4>LAVA FLOW</h4>
            <p>E-Liquid</p>
            <div class="price">₱450</div>
        </div>

        <div class="product">
            <h4>DRAG S PRO</h4>
            <p>Box Mod</p>
            <div class="price">₱2,100</div>
        </div>

        <div class="product">
            <h4>CALIBURN A3S</h4>
            <p>Pod Mod</p>
            <div class="price">₱1,650</div>
        </div>

    </div>
</div>

@endsection