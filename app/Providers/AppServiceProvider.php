<?php

namespace App\Providers;

use App\Models\Point;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
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

        Gate::define('send-request', function (User $user, Point $point) {
            if ($user->id === $point->user_id)
                return false;

            return $point->shouldShowToUser($user);
        });

        Gate::define('decide-request', function (User $user, Request $request) {
            return $user->id === $request->point()->get()->first()->user_id;
        });
    }
}
