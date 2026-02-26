<?php

namespace App\Interfaces\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function fetchAll(): Collection;

    public function fetchById(string $id): User;

    public function create(array $data): User;

    public function update(string $id, array $data): User;

    public function delete(string $id): User;
}
