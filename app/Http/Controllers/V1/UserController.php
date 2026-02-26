<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\V1\UserResource;
use App\Interfaces\V1\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $users = $this->userRepository->fetchAll();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): UserResource
    {
        $validated = $request->only([
            'name',
            'username',
            'password',
            'password_confirmation'
        ]);

        $user = $this->userRepository->create($validated);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): UserResource
    {
        $user = $this->userRepository->fetchById($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->only([
            'name',
            'username',
        ]);

        $user = $this->userRepository->update($id, $validated);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): UserResource
    {
        $user = $this->userRepository->delete($id);

        return new UserResource($user);
    }
}
