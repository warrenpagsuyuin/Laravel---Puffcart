

<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<style>
body{background:#04050d;color:#c8f0ff;margin:0}
.auth{min-height:100vh;display:flex;align-items:center;justify-content:center}
.box{background:#0a0e1c;border:1px solid #00ffe730;padding:35px;width:350px}
h1{color:#00ffe7;text-align:center}
input{width:100%;padding:12px;margin:10px 0;background:#070a14;border:1px solid #00ffe730;color:white}
button{width:100%;padding:12px;background:#00ffe7;border:none;color:#04050d;font-weight:bold;margin-top:10px}
a{color:#00ffe7}
</style>

<div class="auth">
    <div class="box">
        <h1>PUFFCART LOGIN</h1>

        <input type="email" placeholder="Email">
        <input type="password" placeholder="Password">

        <button>SIGN IN</button>

        <p style="text-align:center;margin-top:15px;">
            <a href="/">Back Home</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PLPASIG\Downloads\puffcart-laravel\puffcart\resources\views/login.blade.php ENDPATH**/ ?>