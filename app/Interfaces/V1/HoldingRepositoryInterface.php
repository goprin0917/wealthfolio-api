<?php

namespace App\Interfaces\V1;

use App\Models\Holding;
use Illuminate\Database\Eloquent\Collection;

interface HoldingRepositoryInterface
{
    public function fetchAll(): Collection;

    public function fetchById(string $id): Holding;

    public function create(array $data): Holding;

    public function update(string $id, array $data): Holding;

    public function delete(string $id): Holding;
}
