<?php

namespace App\Providers;

use App\Repositories\ImageHashRepository;
use App\Repositories\ImageHashRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ImageHashRepositoryProvider extends ServiceProvider
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
            ImageHashRepositoryInterface,
            ImageHashRepository
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
