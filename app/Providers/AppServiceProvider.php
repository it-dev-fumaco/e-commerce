<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use DB;
use Cache;
use Carbon\Carbon;

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

            Config::set('app.url', $config->set_value);
            Config::set('app.asset_url', $config->set_value);
        }

        // check if clearance sale exists
        Cache::remember('has_clearance_sale', 3600, function () {
            return DB::table('fumaco_on_sale')->where('is_clearance_sale', 1)->where('status', 1)
                ->whereDate('start_date', '<=', Carbon::now()->startOfDay())->whereDate('end_date', '>=', Carbon::now()->endOfDay())
                ->exists();
        });
    }
}
