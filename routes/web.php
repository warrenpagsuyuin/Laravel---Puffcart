<?php

use Illuminate\Support\Facades\Route;
use App\Events\ChatbotMessage;
use App\Events\TestNotification;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminWalkInController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Public Customer Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/shop', [CustomerProductController::class, 'index'])->name('shop');
Route::get('/product/{product}', [CustomerProductController::class, 'show'])->name('product.show');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerOrderController::class, 'placeOrder'])->middleware('throttle:checkout')->name('checkout.place');

    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking', [CustomerOrderController::class, 'track'])->name('orders.track');
    Route::get('/tracking', [CustomerOrderController::class, 'trackingIndex'])->name('tracking');
});

/*
|--------------------------------------------------------------------------
| WebSocket / Chatbot Routes
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/test-websocket', function () {
        broadcast(new TestNotification('WebSocket is working on PuffCart!'));

        return 'WebSocket event sent!';
    })->middleware('throttle:mail-tests')->name('test.websocket');
}

Route::post('/chatbot/send', function (Request $request) {
    $request->validate([
        'message' => 'required|string|max:500',
    ]);

    $message = strtolower($request->message);
    $reply = "I'm sorry, I don't understand yet. You can ask about delivery, payment, products, tracking, age verification, returns, or support.";

    if (str_contains($message, 'hello') || str_contains($message, 'hi')) {
        $reply = 'Hello! Welcome to PuffCart. How can I help you today?';
    } elseif (str_contains($message, 'delivery') || str_contains($message, 'shipping')) {
        $reply = 'We offer same-day delivery in Metro Manila. Shipping time may vary depending on your location.';
    } elseif (str_contains($message, 'payment') || str_contains($message, 'pay')) {
        $reply = 'We accept secure PayMongo payments via GCash, Maya, credit cards, and bank transfers.';
    } elseif (str_contains($message, 'product') || str_contains($message, 'vape') || str_contains($message, 'device')) {
        $reply = 'We offer vape devices, e-liquids, coils, pods, and accessories from trusted brands.';
    } elseif (str_contains($message, 'tracking') || str_contains($message, 'track')) {
        $reply = 'You can track your order by clicking the Tracking link in the navigation menu.';
    } elseif (str_contains($message, 'age') || str_contains($message, '18') || str_contains($message, 'verify')) {
        $reply = 'PuffCart requires 18+ age verification before account approval and purchasing.';
    } elseif (str_contains($message, 'return') || str_contains($message, 'refund')) {
        $reply = 'Returns and refunds are reviewed based on product condition and order details. Please contact support for assistance.';
    } elseif (str_contains($message, 'support') || str_contains($message, 'contact')) {
        $reply = 'You can contact PuffCart support through the Support or Login page.';
    } elseif (str_contains($message, 'cart')) {
        $reply = 'You can view your selected products by clicking Cart in the navigation menu.';
    } elseif (str_contains($message, 'shop')) {
        $reply = 'Click Shop in the navigation menu to browse all available PuffCart products.';
    }

    broadcast(new ChatbotMessage($reply, 'bot'));

    return response()->json([
        'status' => 'sent',
        'reply' => $reply,
    ]);
})->middleware('throttle:chatbot')->name('chatbot.send');

/*
|--------------------------------------------------------------------------
| Customer Registration
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:registration');

/*
|--------------------------------------------------------------------------
| Customer Login / Logout
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:password-reset')->name('password.send-reset-link');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset-form');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('throttle:password-reset')->name('password.update');

/*
|--------------------------------------------------------------------------
| Payment Routes (PayMongo)
|--------------------------------------------------------------------------
*/

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('{order}', [PaymentController::class, 'show'])
        ->middleware('auth')
        ->name('show');

    Route::post('checkout/{order}', [PaymentController::class, 'initiateCheckout'])
        ->middleware(['auth', 'throttle:checkout'])
        ->name('checkout');

    Route::get('success/{order}', [PaymentController::class, 'paymentSuccess'])
        ->middleware('auth')
        ->name('success');

    Route::get('failed/{order}', [PaymentController::class, 'paymentFailed'])
        ->middleware('auth')
        ->name('failed');

    Route::get('cancel/{order}', [PaymentController::class, 'paymentCancel'])
        ->middleware('auth')
        ->name('cancel');

    Route::post('webhook', [PaymentController::class, 'webhook'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name('webhook');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('index');

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:admin-login')->name('login.submit');

    // MFA Routes
    Route::get('/mfa', [AdminAuthController::class, 'showMFA'])->name('mfa.show');
    Route::post('/mfa/verify', [AdminAuthController::class, 'verifyMFA'])->middleware('throttle:admin-login')->name('mfa.verify');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::patch('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/walk-in', [AdminWalkInController::class, 'index'])->name('walk-in.index');
        Route::post('/walk-in/checkout', [AdminWalkInController::class, 'checkout'])->name('walk-in.checkout');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
        Route::post('/users/{user}/unlock', [AdminUserController::class, 'unlock'])->name('users.unlock');

        Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{user}', [AdminVerificationController::class, 'show'])->name('verifications.show');
        Route::get('/verifications/{user}/document', [AdminVerificationController::class, 'document'])->name('verifications.document');
        Route::post('/verifications/{user}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{user}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/{auditLog}', [AdminAuditLogController::class, 'show'])->name('audit-logs.show');

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });
});

/*
|--------------------------------------------------------------------------
| Test Email Route (Development Only)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/test-email', function () {
        $user = \App\Models\User::first();

        if (!$user) {
            return 'No users found in database';
        }

        try {
            Mail::send(new PasswordResetMail($user, 'test-token-12345'));

            return 'Email sent successfully to ' . $user->email;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    })->middleware('throttle:mail-tests');
}
