<?php

namespace App\Providers;

use App\Models\User;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', ['request' => $request]));

        Fortify::authenticateUsing(function (Request $request) {
            $email = Str::lower(trim((string) $request->string('email')));
            $password = (string) $request->input('password');

            if ($email === '' || $password === '') {
                return null;
            }

            $user = User::query()
                ->with('role')
                ->where('email', $email)
                ->where('enabled', true)
                ->first();

            if (! $user) {
                return null;
            }

            $storedHash = (string) $user->password;
            $passwordMatches = false;

            if ($storedHash !== '' && preg_match('/^\$2[aby]\$|^\$argon2/i', $storedHash) === 1) {
                $passwordMatches = Hash::check($password, $storedHash);
            } elseif (strlen($storedHash) === 32 && ctype_xdigit($storedHash)) {
                $passwordMatches = hash_equals(strtolower($storedHash), md5($password));

                if ($passwordMatches) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            }

            return $passwordMatches ? $user : null;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
