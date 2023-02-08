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
            $query = DB::table('fumaco_on_sale as os')
                ->join('fumaco_on_sale_items as osi', 'os.id', 'osi.sale_id')
                ->join('fumaco_items as i', 'i.f_idcode', 'osi.item_code')
                ->where('os.is_clearance_sale', 1)->where('os.status', 1)->where('i.f_status', 1)
                ->first();
            
            if($query){
                if($query->ignore_sale_duration || $query->start_date <= Carbon::now()->startOfDay() && $query->end_date >= Carbon::now()->endOfDay()){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });
    }
}
