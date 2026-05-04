<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Models\Product;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/shop', function () {
    $products = Product::all();
    return view('shop', compact('products'));
});

Route::get('/product', function () {
    return view('product');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/tracking', function () {
    return view('tracking');
});

/*
|--------------------------------------------------------------------------
| Register with 18+ Verification, ID Upload, Consent, Captcha
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
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'date_of_birth' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'password' => [
            'required',
            'confirmed',
            Password::min(10)->mixedCase()->numbers()->symbols(),
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
        'age_confirmed' => true,
        'privacy_consent' => true,
        'verification_status' => 'pending',
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('success', 'Account created. Your ID is pending verification.');
});

/*
|--------------------------------------------------------------------------
| Login with Account Lockout
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/profile');
    }

    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $key = Str::lower($request->email) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);

        return back()->with('error', "Too many login attempts. Try again in {$seconds} seconds.");
    }

    if (Auth::attempt($request->only('email', 'password'))) {
        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect('/profile');
    }

    RateLimiter::hit($key, 300);

    return back()->with('error', 'Invalid email or password.');
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
});