<?php

namespace App\Providers;

use App\Services\GeneratorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('App\Services\GeneratorService', function ($app) {
            return new GeneratorService();
        });
    }
}
