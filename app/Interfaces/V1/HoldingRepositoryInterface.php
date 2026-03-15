<?php

namespace App\Interfaces\V1;

use App\Models\Holding;
use Illuminate\Pagination\LengthAwarePaginator;

interface HoldingRepositoryInterface
{
    public function search(array $filters = []): LengthAwarePaginator;

    public function fetchById(string $id): Holding;

    public function create(array $data, string|int $userId): Holding;

    public function update(string $id, array $data): Holding;

    public function delete(string $id): Holding;
}
