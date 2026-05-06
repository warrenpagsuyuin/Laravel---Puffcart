<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\MFAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminAuthController extends Controller
{
    protected AuthenticationService $authService;
    protected MFAService $mfaService;

    public function __construct(AuthenticationService $authService, MFAService $mfaService)
    {
        $this->authService = $authService;
        $this->mfaService = $mfaService;
    }

    public function showLogin()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Find user by email or username
        $query = User::where('email', $credentials['login']);

        if (Schema::hasColumn('users', 'username')) {
            $query->orWhere('username', $credentials['login']);
        }

        $user = $query->first();

        // Check if account exists
        if (!$user || $user->role !== 'admin') {
            AuditLog::log('admin_login_failed', 'Admin user not found', null, $ipAddress, $userAgent);

            return back()
                ->withErrors(['login' => 'Invalid admin credentials.'])
                ->onlyInput('login');
        }

        // Check if account is locked
        $lockStatus = $this->authService->isAccountLocked($user);
        if ($lockStatus['locked']) {
            return back()
                ->withErrors(['login' => $lockStatus['message']])
                ->onlyInput('login');
        }

        // Check if account is active
        if (Schema::hasColumn('users', 'is_active') && !$user->is_active) {
            AuditLog::log('admin_login_failed', 'Admin account inactive', $user->id, $ipAddress, $userAgent);

            return back()
                ->withErrors(['login' => 'Your admin account is inactive.'])
                ->onlyInput('login');
        }

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            $failureInfo = $this->authService->recordFailedAttempt($user, $ipAddress, $userAgent);

            return back()
                ->withErrors(['login' => $failureInfo['message']])
                ->onlyInput('login');
        }

        // Password correct - reset failed attempts
        $this->authService->resetFailedAttempts($user);

        // Check if MFA is enabled for this admin
        if ($user->mfa_enabled) {
            // Generate and send MFA code
            $this->mfaService->generateAndSendCode($user);

            // Store user in session for MFA verification
            session(['mfa_user_id' => $user->id, 'mfa_ip' => $ipAddress, 'mfa_user_agent' => $userAgent]);

            return redirect()->route('admin.mfa.show');
        }

        // No MFA - log in directly
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        AuditLog::log('admin_login_success', 'Admin login successful', $user->id, $ipAddress, $userAgent);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function showMFA()
    {
        if (!session('mfa_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Invalid MFA session.');
        }

        return view('admin.mfa');
    }

    public function verifyMFA(Request $request)
    {
        $request->validate([
            'mfa_code' => 'required|string|digits:6',
        ]);

        $userId = session('mfa_user_id');
        if (!$userId) {
            return redirect()->route('admin.login')->with('error', 'Invalid MFA session.');
        }

        $user = User::findOrFail($userId);
        $ipAddress = session('mfa_ip') ?? $request->ip();
        $userAgent = session('mfa_user_agent') ?? $request->userAgent();

        // Verify MFA code
        if (!$this->mfaService->verifyCode($user, $request->mfa_code)) {
            AuditLog::log('admin_mfa_failed', 'MFA code verification failed', $user->id, $ipAddress, $userAgent);

            return back()->withErrors(['mfa_code' => 'Invalid or expired MFA code.']);
        }

        // Clear MFA session
        session()->forget(['mfa_user_id', 'mfa_ip', 'mfa_user_agent']);

        // Log in user
        Auth::login($user, false);
        $request->session()->regenerate();

        AuditLog::log('admin_login_success', 'Admin login successful with MFA', $user->id, $ipAddress, $userAgent);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        AuditLog::log('admin_logout', 'Admin logout', $user?->id, $ipAddress, $userAgent);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}

