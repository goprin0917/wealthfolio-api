<?php

namespace App\Repositories\V1;

use App\Interfaces\V1\AuthRepositoryInterface;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthRepository implements AuthRepositoryInterface
{
    public function authUser(): User
    {
        return Auth::user();
    }

    public function login(array $credentials): array
    {
        $user = User::where('username', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => [
                    'The provided credentials are incorrect.'
                ]
            ]);
        }

        return $this->generateTokenPair($user);
    }

    public function logout(string $token): void
    {
        try {
            $decoded = JWT::decode(
                $token,
                new Key(
                    config('auth.jwt_secret'),
                    'HS256'
                )
            );

            $expiresIn = $decoded->exp - time();

            if ($expiresIn > 0) {
                Cache::put(
                    'jwt_blocklist_' . $decoded->jti,
                    true,
                    $expiresIn
                );
            }
        } catch (Exception $e) {
            Log::error('Logout failed.' . $e->getMessage());
        }
    }

    public function refresh(string $refreshToken): array
    {
        try {
            $decoded = JWT::decode(
                $refreshToken,
                new Key(
                    config('auth.jwt_secret'),
                    'HS256'
                )
            );

            if (!isset($decoded->typ) || $decoded->typ !== 'refresh') {
                throw new Exception('Invalid token type.');
            }

            if (isset($decoded->jti) && Cache::has('jwt_blocklist_' . $decoded->jti)) {
                throw new Exception('Token has been revoked.');
            }

            $user = User::findOrFail($decoded->sub);
            if (!$user) {
                throw new Exception('User not found.');
            }

            $expiresIn = $decoded->exp - time();
            if ($expiresIn > 0) {
                Cache::put(
                    'jwt_blocklist_' . $decoded->jti,
                    true,
                    $expiresIn
                );
            }

            return $this->generateTokenPair($user);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'refresh_token' => [
                    'The provided refresh token is invalid or expired.'
                ]
            ]);
        }
    }

    private function generateTokenPair(User $user): array
    {
        return [
            'access_token' => $this->createToken($user, 3600, 'access'),
            'refresh_token' => $this->createToken($user, 86400 * 7, 'refresh')
        ];
    }

    private function createToken(User $user, int $ttl, string $type): string
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'jti' => (string) Str::uuid(),
            'iat' => time(),
            'exp' => time() + $ttl,
            'typ' => $type
        ];

        return JWT::encode(
            $payload,
            config('auth.jwt_secret'),
            'HS256'
        );
    }
}
