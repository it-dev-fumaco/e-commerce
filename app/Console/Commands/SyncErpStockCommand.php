<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;

class SyncErpStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:erpstock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize items stocks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
        if ($erp_api) {
            // get active products
            $products = DB::table('fumaco_items')->where('f_status', 1)
                ->pluck('f_warehouse', 'f_idcode');

            foreach ($products as $item_code => $warehouse) {
                 // // get stock quantity of selected item code
                $fields = '?fields=["item_code","warehouse","actual_qty","website_reserved_qty"]';
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["warehouse","=","' .$warehouse .'"]]';
        
                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Bin' . $params);

                if ($response->successful()) {
                    if (count($response['data']) > 0 && isset($response['data'])) {
                        DB::table('fumaco_items')->where('f_idcode', $item_code)->where('f_warehouse', $warehouse)
                            ->update(['f_qty' => $response['data'][0]['website_reserved_qty']]);
                    }                   
                }

                 // get item price
                $fields = '?fields=["item_code","price_list","price_list_rate","currency"]';
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["price_list","=","Website Price List"]]';

                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Item Price' . $params);

                if ($response->successful()) {
                    if (count($response['data']) > 0 && isset($response['data'])) {
                        DB::table('fumaco_items')->where('f_idcode', $item_code)->where('f_warehouse', $warehouse)
                            ->where('is_manual_stock', 0)->update(['f_original_price' => $response['data'][0]['price_list_rate']]);
                    }
                }
            }

            DB::table('api_setup')->where('type', 'erp_api')->update(['last_sync_date' => Carbon::now()]);
            
            info('stocks updated');
        }

        return 0;
    }
}
