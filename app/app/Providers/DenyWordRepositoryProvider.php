<?php

namespace App\Providers;

use App\Repositories\DenyWordRepository;
use App\Repositories\DenyWordRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DenyWordRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            DenyWordRepositoryInterface::class,
            DenyWordRepository::class
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
