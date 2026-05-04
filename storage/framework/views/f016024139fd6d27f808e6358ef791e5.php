

<?php $__env->startSection('title', 'Cart'); ?>

<?php $__env->startSection('content'); ?>
<style>
body{background:#04050d;color:#c8f0ff;margin:0}
.nav{display:flex;justify-content:space-between;padding:15px 40px;background:#070a14;border-bottom:1px solid #00ffe730}
.logo{color:#00ffe7;letter-spacing:3px;font-weight:bold}
.nav a{color:#5a8fa8;margin-left:20px;text-decoration:none}
.wrap{display:grid;grid-template-columns:2fr 1fr;gap:30px;padding:60px}
.card{background:#0a0e1c;border:1px solid #00ffe730;padding:25px;margin-bottom:15px}
.price,total{color:#00ffe7}
.btn{display:block;text-align:center;margin-top:20px;padding:12px;border:1px solid #00ffe7;color:#00ffe7;text-decoration:none}
.btn:hover{background:#00ffe7;color:#04050d}
</style>

<div class="nav">
    <div class="logo">PUFFCART</div>
    <div>
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/tracking">Tracking</a>
    </div>
</div>

<div class="wrap">
    <div>
        <h1 style="color:#00ffe7;">YOUR CART</h1>

        <div class="card">
            <h3>XROS 4 MINI</h3>
            <p>Quantity: 1</p>
            <p style="color:#00ffe7;">₱1,299</p>
        </div>

        <div class="card">
            <h3>LAVA FLOW</h3>
            <p>Quantity: 2</p>
            <p style="color:#00ffe7;">₱900</p>
        </div>
    </div>

    <div class="card">
        <h2>ORDER SUMMARY</h2>
        <p>Subtotal: ₱2,199</p>
        <p>Delivery: FREE</p>
        <h2 style="color:#00ffe7;">Total: ₱2,199</h2>

        <a href="/tracking" class="btn">PLACE ORDER</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PLPASIG\Downloads\puffcart-laravel\puffcart\resources\views/cart.blade.php ENDPATH**/ ?>