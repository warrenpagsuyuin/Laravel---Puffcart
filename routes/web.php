<?php

use App\Events\ChatbotMessage;
use App\Events\TestNotification;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMLInsightController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\PaymentController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/*
|--------------------------------------------------------------------------
| Public Customer Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/shop', function () {
    $products = Product::query()
        ->when(\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active'), fn ($query) => $query->where('is_active', true))
        ->paginate(12);

    return view('shop', compact('products'));
})->name('shop');

Route::get('/product/{product}', function (Product $product) {
    return view('product', compact('product'));
})->name('product.show');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/tracking', function () {
    return view('tracking');
})->name('tracking');

/*
|--------------------------------------------------------------------------
| WebSocket / Chatbot Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-websocket', function () {
    broadcast(new TestNotification('WebSocket is working on Puffcart!'));

    return 'WebSocket event sent!';
})->name('test.websocket');

Route::post('/chatbot/send', function (Request $request) {
    $request->validate([
        'message' => 'required|string|max:500',
    ]);

    $message = strtolower($request->message);
    $reply = "I'm sorry, I don't understand yet. You can ask about delivery, payment, products, tracking, age verification, returns, or support.";

    if (str_contains($message, 'hello') || str_contains($message, 'hi')) {
        $reply = 'Hello! Welcome to Puffcart. How can I help you today?';
    } elseif (str_contains($message, 'delivery') || str_contains($message, 'shipping')) {
        $reply = 'We offer same-day delivery in Metro Manila. Shipping time may vary depending on your location.';
    } elseif (str_contains($message, 'payment') || str_contains($message, 'pay')) {
        $reply = 'We accept secure PayMongo payments via GCash, Maya, credit cards, and bank transfers.';
    } elseif (str_contains($message, 'product') || str_contains($message, 'vape') || str_contains($message, 'device')) {
        $reply = 'We offer vape devices, e-liquids, coils, pods, and accessories from trusted brands.';
    } elseif (str_contains($message, 'tracking') || str_contains($message, 'track')) {
        $reply = 'You can track your order by clicking the Tracking link in the navigation menu.';
    } elseif (str_contains($message, 'age') || str_contains($message, '18') || str_contains($message, 'verify')) {
        $reply = 'Puffcart requires 18+ age verification before account approval and purchasing.';
    } elseif (str_contains($message, 'return') || str_contains($message, 'refund')) {
        $reply = 'Returns and refunds are reviewed based on product condition and order details. Please contact support for assistance.';
    } elseif (str_contains($message, 'support') || str_contains($message, 'contact')) {
        $reply = 'You can contact Puffcart support through the Support or Login page.';
    } elseif (str_contains($message, 'cart')) {
        $reply = 'You can view your selected products by clicking Cart in the navigation menu.';
    } elseif (str_contains($message, 'shop')) {
        $reply = 'Click Shop in the navigation menu to browse all available Puffcart products.';
    }

    broadcast(new ChatbotMessage($reply, 'bot'));

    return response()->json([
        'status' => 'sent',
        'reply' => $reply,
    ]);
})->name('chatbot.send');

/*
|--------------------------------------------------------------------------
| Customer Registration
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    $captchaA = rand(1, 9);
    $captchaB = rand(1, 9);

    session([
        'captcha_answer' => $captchaA + $captchaB,
        'captcha_question' => "$captchaA + $captchaB",
    ]);

    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'date_of_birth' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'password' => [
            'required',
            'confirmed',
            Password::min(8)->mixedCase()->numbers()->symbols(),
        ],
        'age_confirmed' => 'accepted',
        'privacy_consent' => 'accepted',
        'captcha' => 'required|numeric',
    ]);

    if ((int) $request->captcha !== (int) session('captcha_answer')) {
        return back()
            ->withErrors(['captcha' => 'Captcha answer is incorrect.'])
            ->withInput();
    }

    $idPath = $request->file('valid_id')->store('valid-ids', 'public');

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'date_of_birth' => $request->date_of_birth,
        'valid_id_path' => $idPath,
        'age_verified' => false,
        'age_confirmed' => true,
        'privacy_consent' => true,
        'verification_status' => 'pending',
        'role' => 'customer',
        'is_active' => true,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Account created. Your ID is pending verification.');
});

/*
|--------------------------------------------------------------------------
| Customer Login / Logout
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    if (Auth::check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('profile');
    }

    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'login' => 'required|string|max:255',
        'password' => 'required',
    ]);

    $key = Str::lower($request->login) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);

        return back()->with('error', "Too many login attempts. Try again in {$seconds} seconds.");
    }

    $query = User::where('email', $request->login);

    if (Schema::hasColumn('users', 'username')) {
        $query->orWhere('username', $request->login);
    }

    $user = $query->first();

    if (
        $user &&
        Hash::check($request->password, $user->password) &&
        (!Schema::hasColumn('users', 'is_active') || $user->is_active)
    ) {
        Auth::login($user);
        RateLimiter::clear($key);
        $request->session()->regenerate();

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('profile');
    }

    RateLimiter::hit($key, 300);

    return back()
        ->with('error', 'Invalid username/email or password.')
        ->onlyInput('login');
});

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Payment Routes (PayMongo)
|--------------------------------------------------------------------------
*/

Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('checkout/{order}', [PaymentController::class, 'initiateCheckout'])
        ->middleware('auth')
        ->name('checkout');
    Route::get('success/{order}', [PaymentController::class, 'paymentSuccess'])
        ->middleware('auth')
        ->name('success');
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
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // MFA Routes
    Route::get('/mfa', [AdminAuthController::class, 'showMFA'])->name('mfa.show');
    Route::post('/mfa/verify', [AdminAuthController::class, 'verifyMFA'])->name('mfa.verify');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
        Route::post('/users/{user}/unlock', [AdminUserController::class, 'unlock'])->name('users.unlock');

        Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
        Route::post('/verifications/{user}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{user}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/{auditLog}', [AdminAuditLogController::class, 'show'])->name('audit-logs.show');

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/ml-insights', [AdminMLInsightController::class, 'index'])->name('ml-insights.index');
    });
});

