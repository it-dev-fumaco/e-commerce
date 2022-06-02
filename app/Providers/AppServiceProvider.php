<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use DB;
use Cache;

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
        if (\Schema::hasTable('fumaco_settings')) {
            $config = Cache::remember('custom_app_config', 3600, function () {
                return DB::table('fumaco_settings')->first();
            });

            // Config::set('app.url', $config->set_value);
            Config::set('app.asset_url', $config->set_value);
        }
    }
}
