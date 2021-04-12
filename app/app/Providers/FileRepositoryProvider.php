<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\FileRepository;
use App\Repositories\FileRepositoryInterface;

class FileRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class
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
