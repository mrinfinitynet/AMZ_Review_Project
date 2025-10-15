<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\ClientComposer;

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

        // Share active clients with all views
        View::composer('admin.partials.master', ClientComposer::class);

        // Share frontend settings (logo, favicon, site title) with all views
        View::composer('*', function ($view) {
            $settings = \App\Models\FrontendSetting::all()->pluck('value', 'key');
            $view->with('settings', $settings);
        });
    }
}
