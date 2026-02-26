<?php

namespace App\Providers;

use App\Interfaces\V1\AuthRepositoryInterface;
use App\Interfaces\V1\HoldingRepositoryInterface;
use App\Interfaces\V1\UserRepositoryInterface;
use App\Repositories\V1\AuthRepository;
use App\Repositories\V1\HoldingRepository;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = [
            AuthRepositoryInterface::class => AuthRepository::class,
            HoldingRepositoryInterface::class => HoldingRepository::class,
            UserRepositoryInterface::class => UserRepository::class
        ];

        foreach ($bindings as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
