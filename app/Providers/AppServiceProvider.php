<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Log semua query SQL ke laravel.log
        DB::listen(function ($query) {
            Log::info('SQL Query:', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time . ' ms'
            ]);
        });
    }
}

