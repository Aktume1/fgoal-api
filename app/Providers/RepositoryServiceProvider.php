<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected static $repositories = [
        'user' => [
            \App\Contracts\Repositories\UserRepository::class,
            \App\Repositories\UserRepositoryEloquent::class,
        ],
        'group' => [
            \App\Contracts\Repositories\GroupRepository::class,
            \App\Repositories\GroupRepositoryEloquent::class,
        ],
        'objective' => [
            \App\Contracts\Repositories\ObjectiveRepository::class,
            \App\Repositories\ObjectiveRepositoryEloquent::class,
        ],
        'unit' => [
            \App\Contracts\Repositories\UnitRepository::class,
            \App\Repositories\UnitRepositoryEloquent::class,
        ],
        'quarter' => [
            \App\Contracts\Repositories\QuarterRepository::class,
            \App\Repositories\QuarterRepositoryEloquent::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (static::$repositories as $repository) {
            $this->app->singleton(
                $repository[0],
                $repository[1]
            );
        }
    }
}
