<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
| Authentication - Register
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (Request $request) {

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect('/login')->with('success', 'Account created successfully. Please login.');
});

/*
|--------------------------------------------------------------------------
| Authentication - Login
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/profile');
    }

    return back()->with('error', 'Invalid email or password.');
});

/*
|--------------------------------------------------------------------------
| Profile (Protected)
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