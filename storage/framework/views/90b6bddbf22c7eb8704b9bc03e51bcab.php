

<?php $__env->startSection('title', 'Product'); ?>

<?php $__env->startSection('content'); ?>
<style>
body{background:#04050d;color:#c8f0ff;margin:0}
.nav{display:flex;justify-content:space-between;padding:15px 40px;background:#070a14;border-bottom:1px solid #00ffe730}
.logo{color:#00ffe7;letter-spacing:3px;font-weight:bold}
.nav a{color:#5a8fa8;margin-left:20px;text-decoration:none}
.wrap{display:grid;grid-template-columns:1fr 1fr;gap:40px;padding:70px}
.box{background:#0a0e1c;border:1px solid #00ffe730;padding:40px}
h1{color:#00ffe7;text-shadow:0 0 15px #00ffe7}
.price{color:#00ffe7;font-size:30px}
.btn{display:inline-block;margin-top:20px;padding:12px 20px;border:1px solid #00ffe7;color:#00ffe7;text-decoration:none}
.btn:hover{background:#00ffe7;color:#04050d}
</style>

<div class="nav">
    <div class="logo">PUFFCART</div>
    <div>
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
        <a href="/cart">Cart</a>
        <a href="/login">Login</a>
    </div>
</div>

<div class="wrap">
    <div class="box">
        <h1>DEVICE PREVIEW</h1>
        <p style="font-size:120px;text-align:center;">💨</p>
    </div>

    <div class="box">
        <h1>XROS 4 MINI</h1>
        <p>Pod System · Vaporesso</p>
        <div class="price">₱1,299</div>
        <p>Compact design, long battery life, and smooth vapor experience.</p>

        <a href="/cart" class="btn">ADD TO CART</a>
        <a href="/shop" class="btn">BACK TO SHOP</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PLPASIG\Downloads\puffcart-laravel\puffcart\resources\views/product.blade.php ENDPATH**/ ?>