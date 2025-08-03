<?php

namespace App\Providers;

use App\Contracts\HomeownerDriver;
use App\Services\HomeownerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HomeownerDriver::class, HomeownerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
