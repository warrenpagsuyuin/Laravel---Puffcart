<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function verifyAge()
    {
        return view('age-verify');
    }

    public function verifyAgePost(Request $request)
    {
        $request->validate(['confirmed' => 'required|accepted']);
        session(['age_verified' => true]);
        return redirect()->intended(route('home'));
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return auth()->user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('profile');
        }

        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $key = Str::lower($credentials['login']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->with('error', "Too many login attempts. Try again in {$seconds} seconds.");
        }

        $query = User::where('email', $credentials['login']);

        if (Schema::hasColumn('users', 'username')) {
            $query->orWhere('username', $credentials['login']);
        }

        $user = $query->first();

        if (
            $user &&
            Hash::check($credentials['password'], $user->password) &&
            (!Schema::hasColumn('users', 'is_active') || $user->is_active)
        ) {
            Auth::login($user, $request->boolean('remember'));
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
    }

    public function showRegister()
    {
        $captchaA = rand(1, 9);
        $captchaB = rand(1, 9);

        session([
            'captcha_answer' => $captchaA + $captchaB,
            'captcha_question' => "{$captchaA} + {$captchaB}",
        ]);

        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        if ((int) $data['captcha'] !== (int) session('captcha_answer')) {
            return back()
                ->withErrors(['captcha' => 'Captcha answer is incorrect.'])
                ->withInput();
        }

        $idPath = $request->file('valid_id')->store('valid-ids', 'public');

        User::create([
            'name' => $data['name'],
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'date_of_birth' => $data['date_of_birth'],
            'valid_id_path' => $idPath,
            'age_verified' => false,
            'age_confirmed' => true,
            'privacy_consent' => true,
            'verification_status' => 'pending',
            'role' => 'customer',
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Account created. Your ID is pending verification.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
