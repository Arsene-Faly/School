<?php

namespace App\Providers;

use App\Models\SiteView;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share('totalViews', function () {
            return SiteView::first()->views ?? 0;
        });
    }
}
