<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Sử dụng Bootstrap 5 cho pagination
        Paginator::useBootstrapFive();

        // Bắt buộc Laravel generate tất cả URL với https trên môi trường production
        // Tránh tình trạng route()/asset() trả về http khi đứng sau proxy (Railway)
        if ($this->app->environment('production')) {
            // Cố định root URL nếu đã cấu hình APP_URL
            if (config('app.url')) {
                URL::forceRootUrl(config('app.url'));
            }
            URL::forceScheme('https');
        }
    }
}
