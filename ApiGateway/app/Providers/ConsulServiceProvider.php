<?php

namespace App\Providers;

use App\Services\ConsulService;
use Illuminate\Support\ServiceProvider;

class ConsulServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        (new ConsulService())->registerService();
    }
}
