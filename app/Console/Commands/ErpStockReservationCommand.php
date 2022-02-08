<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;

class ErpStockReservationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock_reservation:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create stock reservation in ERP';

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
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ];

            // for product bundles
            // get webiste orders which does not exists in erp stock reservation
            $product_bundle_orders = DB::table('fumaco_order_items as a')
                ->join('fumaco_items as b', 'a.item_code', 'b.f_idcode')
                ->join('fumaco_order as c', 'a.order_number', 'c.order_number')
                ->join('fumaco_product_bundle_item as d', 'b.f_idcode', 'd.parent_item_code')
                ->whereNotIn('c.order_status', ['Cancelled', 'Delivered', 'Delivered Order'])
                ->where('c.stock_reserve_status', 0)->where('a.item_type', 'product_bundle')
                ->select('d.item_code', 'c.id', 'd.item_description', 'b.f_warehouse', 'a.item_qty', 'c.order_number', 'd.qty', 'd.uom')
                ->get();

            // for simple products
            // get webiste orders which does not exists in erp stock reservation
            $simple_product_orders = DB::table('fumaco_order_items as a')
                ->join('fumaco_items as b', 'a.item_code', 'b.f_idcode')
                ->join('fumaco_order as c', 'a.order_number', 'c.order_number')
                ->whereNotIn('c.order_status', ['Cancelled', 'Delivered', 'Delivered Order'])
                ->where('c.stock_reserve_status', 0)->where('a.item_type', '!=', 'product_bundle')
                ->select('a.item_code', 'c.id', 'a.item_name', 'b.f_warehouse', 'a.item_qty', 'c.order_number', 'b.f_stock_uom')
                ->get();

            $simple_product_orders_item_codes = array_column($simple_product_orders->toArray(), 'item_code');
            $simple_product_orders_item_codes = collect($simple_product_orders_item_codes)->map(function($i){
                return isset(explode("-", $i)[0]) ? explode("-", $i)[0] : null;
            })->toArray();

            $item_uom_conversion = DB::table('fumaco_item_uom_conversion')
                ->whereIn('item_code', $simple_product_orders_item_codes)
                ->pluck('conversion_factor', DB::raw('CONCAT(item_code, "-", uom)'))
                ->toArray();

            $item_default_uom = DB::table('fumaco_items')->whereIn('f_idcode', $simple_product_orders_item_codes)
                ->pluck('f_stock_uom', 'f_idcode')->toArray();

            if (count($product_bundle_orders) > 0) {
                foreach ($product_bundle_orders as $order) {
                    $new_stock_reservations = [
                        'type' => 'Website Stocks',
                        'item_code' => $order->item_code,
                        'description' => $order->item_description,
                        'warehouse' => $order->f_warehouse,
                        'reserve_qty' => ($order->item_qty * $order->qty),
                        'reference_no' => $order->order_number,
                        'stock_uom' => $order->uom
                    ];

                    // reserve stocks in erp
                    $insert_response = Http::withHeaders($headers)
                        ->post($erp_api->base_url . '/api/resource/Stock Reservation', ($new_stock_reservations));

                    if ($insert_response->successful()) {
                        DB::table('fumaco_order')->where('id', $order->id)->update(['stock_reserve_status' => 1]);
                        info('success (product bundle)');
                    }
                }
            }

            if (count($simple_product_orders) > 0) {
                foreach ($simple_product_orders as $order) {
                    $default_stock_uom = $item_default_uom[explode("-", $order->item_code)[0]];

                    $conversion_factor = 1;
                    if (array_key_exists(explode("-", $order->item_code)[0] . '-' . $order->f_stock_uom, $item_uom_conversion)) {
                        $conversion_factor = $item_uom_conversion[explode("-", $order->item_code)[0] . '-' . $order->f_stock_uom];
                    }
                    
                    $new_stock_reservations = [
                        'type' => 'Website Stocks',
                        'item_code' => explode("-", $order->item_code)[0],
                        'description' => $order->item_name,
                        'warehouse' => $order->f_warehouse,
                        'reserve_qty' => ($order->item_qty * $conversion_factor),
                        'reference_no' => $order->order_number,
                        'stock_uom' => $default_stock_uom
                    ];

                    //  reserve stocks in erp
                    $insert_response = Http::withHeaders($headers)
                        ->post($erp_api->base_url . '/api/resource/Stock Reservation', ($new_stock_reservations));

                    if ($insert_response->successful()) {
                        DB::table('fumaco_order')->where('id', $order->id)->update(['stock_reserve_status' => 1]);
                        info('success (simple product)');
                        info($new_stock_reservations);
                    }
                }
            }

            // get cancelled orders
            $cancelled_orders = DB::table('fumaco_order')->where('stock_reserve_status', 1)
                ->where('order_status', 'Cancelled')->pluck('order_number')->toArray();
            // delete stock reservation in erp
            $fields = '?fields=["name","item_code","warehouse","reserve_qty", "reference_no"]';
            foreach($cancelled_orders as $order_number) {
                $filter = '&filters=[["reference_no","=", "'. $order_number .'"],["status","!=","Issued"]]';
                $params = $fields . '' . $filter;
                $response = Http::withHeaders($headers)->get($erp_api->base_url . '/api/resource/Stock Reservation' . $params);
                if ($response->successful() && isset($response['data']) && count($response['data']) > 0) {
                    $delete_response = Http::withHeaders($headers)->delete($erp_api->base_url . '/api/resource/Stock Reservation/' . $response['data'][0]['name']);
                    if ($delete_response->successful()) {
                        info('delete success');
                    }
                }
            }
        }

        return 0;
    }
}
