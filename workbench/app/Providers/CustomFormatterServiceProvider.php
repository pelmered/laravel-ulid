<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomFormatterServiceProvider extends ServiceProvider
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
        //
        //Ulid::setFormatter(new \Workbench\UlidFormatter());
    }
}
