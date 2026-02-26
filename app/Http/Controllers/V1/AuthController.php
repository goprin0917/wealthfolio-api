<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Interfaces\V1\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required'
            ]
        ]);

        $tokens = $this->authRepository->login($credentials);

        return response()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'token_type' => 'Bearer'
        ]);
    }

    public function me(): UserResource
    {
        $user = $this->authRepository->authUser();

        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            $this->authRepository->logout($token);
        }

        $cookie = Cookie::forget('refresh_token');

        return response()->json([
            'message' => 'Successfully logged out'
        ])->withCookie($cookie);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Refresh token missing.'
            ], 401);
        }

        $tokens = $this->authRepository->refresh($refreshToken);

        return $this->respondWithTokens(
            $tokens['access_token'],
            $tokens['refresh_token']
        );
    }

    private function respondWithTokens(string $accessToken, string $refreshToken): JsonResponse
    {
        $cookie = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 7,
            '/',
            null,
            config('app.env') !== 'local',
            true,
            false,
            'Strict',
        );

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer'
        ])->withCookie($cookie);
    }
}
