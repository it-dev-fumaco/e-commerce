<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use DB;
use Cache;

class GoogleApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (\Schema::hasTable('api_setup')) {
            $config = Cache::remember('google_api_config', 3600, function () {
                return DB::table('api_setup')->whereIn('type', ['google_maps_api', 'google_analytics_api'])
                    ->pluck('api_key', 'type');
            });

            $google_maps_api = (array_key_exists('google_maps_api', $config)) ? $config['google_maps_api'] : '';

            Config::set('googlemaps.key', $google_maps_api);
            Config::set('google_api', $config);
        }
    }
}
