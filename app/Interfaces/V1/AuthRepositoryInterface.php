<?php

namespace App\Interfaces\V1;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function authUser(): User;

    public function login(array $credentials): array;

    public function logout(string $token): void;

    public function refresh(string $refreshToken): array;
}
