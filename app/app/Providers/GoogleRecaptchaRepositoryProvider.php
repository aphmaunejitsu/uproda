<?php

namespace App\Providers;

use App\Repositories\GoogleRecaptchaRepository;
use App\Repositories\GoogleRecaptchaRepositoryInterface;
use App\Services\GoogleRecaptchaService;
use Illuminate\Support\ServiceProvider;

class GoogleRecaptchaRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            GoogleRecaptchaRepositoryInterface::class,
            GoogleRecaptchaRepository::class
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
