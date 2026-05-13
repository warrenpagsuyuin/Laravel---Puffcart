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
        $request->validate([
            'confirmed' => 'required|accepted',
        ]);

        session(['age_verified' => true]);

        return redirect()->intended(route('home'));
    }

    public function showLogin()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            return $user->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        $this->prepareRegistrationCaptcha();

        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $key = Str::lower($credentials['login']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->with('error', "Too many login attempts. Try again in {$seconds} seconds.")
                ->withInput($request->only('login'));
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
                : redirect()->route('home');
        }

        RateLimiter::hit($key, 300);

        return back()
            ->with('error', 'Invalid username/email or password.')
            ->withInput($request->only('login'));
    }

    public function showRegister()
    {
        $this->prepareRegistrationCaptcha();

        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $submittedCaptchaAnswer = $this->normalizeCaptchaAnswer($request->input('captcha'));

        if (!$this->captchaIsValid($submittedCaptchaAnswer)) {
            $this->prepareRegistrationCaptcha(true);

            return back()
                ->withErrors([
                    'captcha' => 'Captcha is incorrect or expired. Please answer the new captcha.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                    'valid_id',
                    'captcha',
                ]));
        }

        $idPath = $request->file('valid_id')->store('valid-ids', 'local');

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['contact_number'],
            'address' => $data['address'],
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

        session()->forget([
            'captcha_answer_hash',
            'captcha_expires_at',
            'captcha_question',
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

    private function prepareRegistrationCaptcha(bool $force = false): void
    {
        if (!$force && $this->captchaSessionIsFresh()) {
            return;
        }

        $answer = $this->generateCaptchaAnswer();

        session([
            'captcha_answer_hash' => $this->captchaHash($answer),
            'captcha_expires_at' => now()->addMinutes(10)->timestamp,
            'captcha_question' => $answer,
        ]);
    }

    private function captchaIsValid(string $answer): bool
    {
        if ($answer === '' || !$this->captchaSessionIsFresh()) {
            return false;
        }

        return hash_equals((string) session('captcha_answer_hash'), $this->captchaHash($answer));
    }

    private function captchaSessionIsFresh(): bool
    {
        return session()->has('captcha_answer_hash')
            && session()->has('captcha_question')
            && (int) session('captcha_expires_at') >= now()->timestamp;
    }

    private function captchaHash(string $answer): string
    {
        return hash_hmac('sha256', $this->normalizeCaptchaAnswer($answer), (string) config('app.key'));
    }

    private function generateCaptchaAnswer(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $answer = '';

        for ($i = 0; $i < 6; $i++) {
            $answer .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $answer;
    }

    private function normalizeCaptchaAnswer(mixed $answer): string
    {
        return Str::upper(preg_replace('/\s+/', '', trim((string) $answer)));
    }
}
