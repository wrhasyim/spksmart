<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AppSetting;

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
        // Membagikan data AppSetting secara global ke semua view Blade
        try {
            $appSetting = AppSetting::first();
            View::share('appSetting', $appSetting);
        } catch (\Exception $e) {
            // Mengabaikan error jika tabel belum dimigrasi saat setup awal
            View::share('appSetting', null);
        }
    }
}