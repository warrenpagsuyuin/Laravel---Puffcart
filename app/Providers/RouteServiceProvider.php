<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            $login = (string) $request->input('login', '');

            return Limit::perMinute(5)->by(mb_strtolower($login) . '|' . $request->ip());
        });

        RateLimiter::for('admin-login', function (Request $request) {
            $login = (string) $request->input('login', '');

            return Limit::perMinute(5)->by('admin|' . mb_strtolower($login) . '|' . $request->ip());
        });

        RateLimiter::for('registration', function (Request $request) {
            return Limit::perMinutes(10, 5)->by('register|' . $request->ip());
        });

        RateLimiter::for('password-reset', function (Request $request) {
            $email = (string) $request->input('email', '');

            return Limit::perMinutes(10, 5)->by('password-reset|' . mb_strtolower($email) . '|' . $request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(20)->by('checkout|' . ($request->user()?->id ?: $request->ip()));
        });

        RateLimiter::for('mail-tests', function (Request $request) {
            return Limit::perMinute(2)->by('mail-tests|' . $request->ip());
        });

        RateLimiter::for('chatbot', function (Request $request) {
            return Limit::perMinute(30)->by('chatbot|' . $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
