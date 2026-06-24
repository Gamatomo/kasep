<?php

namespace App\Providers;

use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Setting;
use App\Observers\PenjualanObserver;
use App\Observers\PengeluaranObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('layouts.master', function ($view) {
            $view->with('setting', Setting::first());
        });
        view()->composer('layouts.auth', function ($view) {
            $view->with('setting', Setting::first());
        });
        view()->composer('auth.login', function ($view) {
            $view->with('setting', Setting::first());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Penjualan::observe(PenjualanObserver::class);
        Pengeluaran::observe(PengeluaranObserver::class);
    }
}
