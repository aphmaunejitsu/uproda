<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ImageRepository;
use App\Repositories\ImageRepositoryInterface;

class ImageRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ImageRepositoryInterface::class,
            ImageRepository::class
        );
        //
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
