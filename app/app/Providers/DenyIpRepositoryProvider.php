<?php

namespace App\Providers;

use App\Repositories\DenyIpRepository;
use App\Repositories\DenyIpRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DenyIpRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            DenyIpRepositoryInterface::class,
            DenyIpRepository::class
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
