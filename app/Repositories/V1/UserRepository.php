<?php

namespace App\Repositories\V1;

use App\Interfaces\V1\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function fetchAll(): Collection
    {
        $users = User::get();

        return $users;
    }

    public function fetchById(string $id): User
    {
        $user = User::findOrFail($id);

        return $user;
    }

    public function create(array $data): User
    {
        $user = User::create($data);

        return $user;
    }

    public function update(string $id, array $data): User
    {
        $user = User::findOrFail($id);
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return $user;
    }

    public function delete(string $id): User
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $user;
    }
}
