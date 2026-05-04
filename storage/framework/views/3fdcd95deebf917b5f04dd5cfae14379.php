

<?php $__env->startSection('title', 'Shop'); ?>

<?php $__env->startSection('content'); ?>

<style>
body {
    background:#04050d;
    color:#c8f0ff;
    margin:0;
}

.nav {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 40px;
    border-bottom:1px solid #00ffe730;
    background:#070a14;
}

.logo {
    color:#00ffe7;
    font-size:18px;
    letter-spacing:3px;
    font-weight:bold;
}

.nav a {
    color:#5a8fa8;
    margin-left:20px;
    text-decoration:none;
}

.nav a:hover {
    color:#00ffe7;
}

.shop-wrap {
    display:grid;
    grid-template-columns:220px 1fr;
    min-height:100vh;
}

.sidebar {
    background:#070a14;
    border-right:1px solid #00ffe730;
    padding:25px;
}

.sidebar h3 {
    color:#00ffe7;
    font-size:14px;
    letter-spacing:2px;
}

.sidebar label {
    display:block;
    margin:12px 0;
    color:#5a8fa8;
}

.products {
    padding:40px;
}

.products h1 {
    color:#00ffe7;
    letter-spacing:3px;
    text-shadow:0 0 15px #00ffe7;
}

.grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;
}

.card {
    background:#0a0e1c;
    border:1px solid #00ffe730;
    padding:20px;
}

.card:hover {
    border-color:#00ffe7;
}

.card h3 {
    color:#c8f0ff;
}

.card p {
    color:#5a8fa8;
}

.price {
    color:#00ffe7;
    font-size:22px;
    margin-top:10px;
}

.btn {
    display:inline-block;
    margin-top:15px;
    padding:10px 15px;
    border:1px solid #00ffe7;
    color:#00ffe7;
    text-decoration:none;
}

.btn:hover {
    background:#00ffe7;
    color:#04050d;
}
</style>

<div class="nav">
    <div class="logo">PUFFCART</div>
    <div>
        <a href="<?php echo e(url('/')); ?>">Home</a>
        <a href="<?php echo e(url('/shop')); ?>">Shop</a>
        <a href="<?php echo e(url('/cart')); ?>">Cart</a>
        <a href="<?php echo e(url('/login')); ?>">Login</a>
        <a href="<?php echo e(url('/tracking')); ?>">Tracking</a>
    </div>
</div>

<div class="shop-wrap">

    <div class="sidebar">
        <h3>// FILTERS</h3>

        <label><input type="checkbox" checked> Devices</label>
        <label><input type="checkbox"> E-Liquids</label>
        <label><input type="checkbox"> Coils & Pods</label>
        <label><input type="checkbox"> Accessories</label>
    </div>

    <div class="products">
        <h1>SHOP ALL PRODUCTS</h1>

        <div class="grid">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card">
                    <h3><?php echo e($product->name); ?></h3>
                    <p><?php echo e($product->category); ?> · <?php echo e($product->brand); ?></p>
                    <div class="price">₱<?php echo e(number_format($product->price, 2)); ?></div>
                    <a href="<?php echo e(url('/product')); ?>" class="btn">VIEW PRODUCT</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PLPASIG\Downloads\puffcart-laravel\puffcart\resources\views/shop.blade.php ENDPATH**/ ?>