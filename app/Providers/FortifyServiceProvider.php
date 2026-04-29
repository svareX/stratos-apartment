<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $username = Str::lower($request->input(Fortify::username()));
            $ip = $request->ip();

            $attemptKey = 'login:attempts:'.$username;
            $ipKey = 'login:attempts:ip:'.$ip;
            $lockKey = 'login:lock:'.$username;

            // If user/account is locked, deny immediately and let Fortify handle the failure
            $locked = Redis::get($lockKey);
            if ($locked) {
                return null;
            }

            $user = User::where('email', $username)->first();

            // Avoid user enumeration: increment IP counter for unknown users
            if (! $user) {
                $ipAttempts = Redis::incr($ipKey);
                if ($ipAttempts === 1) {
                    Redis::expire($ipKey, config('rate_limit.auth.attempt_ttl_seconds', 3600));
                }

                return null;
            }

            if (Hash::check($request->password, $user->password)) {
                // Successful login: reset counters
                Redis::del($attemptKey);
                Redis::del($ipKey);

                return $user;
            }

            // Failed login: increment and possibly lock
            $attempts = Redis::incr($attemptKey);
            if ($attempts === 1) {
                Redis::expire($attemptKey, config('rate_limit.auth.attempt_ttl_seconds', 3600));
            }

            $threshold = config('rate_limit.auth.lockout_threshold', 10);
            if ($attempts >= $threshold) {
                // Exponential backoff: clamp to configured max_lock_minutes
                $overflow = $attempts - $threshold;
                $minutes = min(config('rate_limit.auth.max_lock_minutes', 60), (int) pow(2, max(0, $overflow)) );
                Redis::setex($lockKey, $minutes * 60, 1);
            }

            return null;
        });
    }
}
