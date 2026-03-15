<?php

namespace App\Repositories\V1;

use App\Interfaces\V1\HoldingRepositoryInterface;
use App\Models\Holding;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class HoldingRepository implements HoldingRepositoryInterface
{
    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = Holding::query()
            ->where('user_id', Auth::id());

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('symbol', 'like', $searchTerm)
                    ->orWhere('name', 'like', $searchTerm);
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $allowedSorts = [
            'symbol',
            'name',
            'type',
            'quantity',
            'buy_price',
            'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy(
                $sortBy,
                $sortDirection === 'asc' ? 'asc' : 'desc'
            );
        }

        $perPage = (int) ($filters['per_page'] ?? 10);

        return $query->paginate($perPage);
    }

    public function fetchById(string $id): Holding
    {
        $holding = Holding::findOrFail($id);
        $holding->load([
            'user'
        ]);

        return $holding;
    }

    public function create(array $data, string|int $userId): Holding
    {
        $data["user_id"] = $userId;

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
