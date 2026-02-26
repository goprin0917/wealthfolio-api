<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\HoldingRequest;
use App\Http\Resources\V1\HoldingResource;
use App\Interfaces\V1\HoldingRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class HoldingController extends Controller
{
    public function __construct(
        private HoldingRepositoryInterface $holdingRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $holdings = $this->holdingRepository->fetchAll();

        return HoldingResource::collection($holdings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HoldingRequest $request): HoldingResource
    {
        $validated = $request->only([
            'user_id',
            'symbol',
            'name',
            'type',
            'quantity',
            'average_price'
        ]);

        $holding = $this->holdingRepository->create($validated);

        return new HoldingResource($holding);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): HoldingResource
    {
        $holding = $this->holdingRepository->fetchById($id);

        return new HoldingResource($holding);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HoldingRequest $request, string $id): HoldingResource
    {
        $validated = $request->only([
            'user_id',
            'symbol',
            'name',
            'type',
            'quantity',
            'average_price'
        ]);

        $holding = $this->holdingRepository->update($id, $validated);

        return new HoldingResource($holding);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): HoldingResource
    {
        $holding = $this->holdingRepository->delete($id);

        return new HoldingResource($holding);
    }
}
