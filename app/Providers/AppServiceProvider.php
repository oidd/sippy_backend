<?php

namespace App\Providers;

use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Grammar::macro('typeGEOMETRY_POINT', function () {
            return 'GEOMETRY(POINT, 4326)';
        });

        Grammar::macro('typeGEOMETRY_POLYGON', function () {
            return 'GEOMETRY(POLYGON, 4326)';
        });
    }
}
