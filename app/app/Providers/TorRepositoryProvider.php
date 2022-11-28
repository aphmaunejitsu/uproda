<?php

namespace App\Providers;

use App\Repositories\TorRepository;
use App\Repositories\TorRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class TorRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            TorRepositoryInterface::class,
            TorRepository::class
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
