<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\SalesReportController;

/*
|--------------------------------------------------------------------------
| Age Verification
|--------------------------------------------------------------------------
*/
Route::get('/age-verify', fn() => view('age-verify'))->name('age.verify');
Route::post('/age-verify', [AuthController::class, 'verifyAge'])->name('age.verify.post');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('age.verified')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Customer / Public Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['age.verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [ProductController::class, 'index'])->name('shop');
    Route::get('/shop/{product:slug}', [ProductController::class, 'show'])->name('product.show');

    Route::middleware(['auth'])->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

        // Checkout & Orders
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('order.place');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/track/{order:order_number}', [OrderController::class, 'track'])->name('orders.track');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', ProductManagementController::class);

        // Orders
        Route::get('orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status');

        // Customers
        Route::resource('customers', CustomerManagementController::class)->only(['index', 'show', 'destroy']);

        // Inventory
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::patch('inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');

        // Payments
        Route::get('payments', [PaymentManagementController::class, 'index'])->name('payments.index');
        Route::patch('payments/{payment}/status', [PaymentManagementController::class, 'updateStatus'])->name('payments.status');

        // Sales Reports
        Route::get('reports', [SalesReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/pdf', [SalesReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('reports/export/csv', [SalesReportController::class, 'exportCsv'])->name('reports.csv');
    });
