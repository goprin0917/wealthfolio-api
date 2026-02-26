<?php

namespace App\Repositories\V1;

use App\Interfaces\V1\HoldingRepositoryInterface;
use App\Models\Holding;
use Illuminate\Database\Eloquent\Collection;

class HoldingRepository implements HoldingRepositoryInterface
{
    public function fetchAll(): Collection
    {
        $holdings = Holding::get();
        $holdings->load([
            'user'
        ]);

        return $holdings;
    }

    public function fetchById(string $id): Holding
    {
        $holding = Holding::findOrFail($id);
        $holding->load([
            'user'
        ]);

        return $holding;
    }

    public function create(array $data): Holding
    {
        $holding = Holding::create($data);

        return $holding;
    }

    public function update(string $id, array $data): Holding
    {
        $holding = Holding::findOrFail($id);
        foreach ($data as $key => $value) {
            $holding->$key = $value;
        }
        $holding->save();

        return $holding;
    }

    public function delete(string $id): Holding
    {
        $holding = Holding::findOrFail($id);
        $holding->delete();

        return $holding;
    }
}
