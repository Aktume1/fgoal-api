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
        'comment' => [
            \App\Contracts\Repositories\CommentRepository::class,
            \App\Repositories\CommentRepositoryEloquent::class,
        ],
        'log' => [
            \App\Contracts\Repositories\ActivityLogRepository::class,
            \App\Repositories\ActivityLogRepositoryEloquent::class,
        ],
        'webhook' => [
            \App\Contracts\Repositories\WebhookRepository::class,
            \App\Repositories\WebhookRepositoryEloquent::class,
        ],
        'firebasetoken' => [
            \App\Contracts\Repositories\FirebaseTokenRepository::class,
            \App\Repositories\FirebaseTokenRepositoryEloquent::class,
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
