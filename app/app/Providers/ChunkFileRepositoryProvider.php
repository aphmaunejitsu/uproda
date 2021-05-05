<?php

namespace App\Providers;

use App\Repositories\ChunkFileRepository;
use App\Repositories\ChunkFileRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ChunkFileRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            ChunkFileRepositoryInterface::class,
            ChunkFileRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
