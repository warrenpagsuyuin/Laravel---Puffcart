<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $query = User::where('email', $credentials['login']);

        if (Schema::hasColumn('users', 'username')) {
            $query->orWhere('username', $credentials['login']);
        }

        $user = $query->first();

        if (
            !$user ||
            !Hash::check($credentials['password'], $user->password) ||
            $user->role !== 'admin' ||
            (Schema::hasColumn('users', 'is_active') && !$user->is_active)
        ) {
            if ($user && Schema::hasColumn('users', 'failed_login_attempts')) {
                $user->increment('failed_login_attempts');
                $user->forceFill(['last_failed_login_at' => now()])->save();
            }

            return back()
                ->withErrors(['login' => 'Invalid admin credentials.'])
                ->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if (Schema::hasColumn('users', 'failed_login_attempts')) {
            $user->forceFill([
                'failed_login_attempts' => 0,
                'last_failed_login_at' => null,
            ])->save();
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
