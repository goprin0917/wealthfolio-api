<?php

namespace App\Providers;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Auth::viaRequest('jwt', function (Request $request) {
            $token = $request->bearerToken();

            if (!$token) {
                return null;
            }

            try {
                $decoded = JWT::decode(
                    $token,
                    new Key(
                        config('auth.jwt_secret'),
                        'HS256'
                    )
                );

                if (isset($decoded->typ) && $decoded->typ !== 'access') {
                    return null;
                }

                if (isset($decoded->jti) && Cache::has('jwt_blocklist_' . $decoded->jti)) {
                    return null;
                }

                return User::findOrFail($decoded->sub);
            } catch (Exception $e) {
                return null;
            }
        });
    }
}
