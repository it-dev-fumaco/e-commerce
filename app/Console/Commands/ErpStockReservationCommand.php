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

            // get webiste orders which does not exists in erp stock reservation
            $orders = DB::table('fumaco_order_items as a')
                ->join('fumaco_items as b', 'a.item_code', 'b.f_idcode')
                ->join('fumaco_order as c', 'a.order_number', 'c.order_number')
                ->whereNotIn('c.order_status', ['Cancelled', 'Delivered', 'Delivered Order'])
                ->where('c.erp_stock_reserved', 0)
                ->select('a.item_code', 'c.id', 'a.item_name', 'b.f_warehouse', 'a.item_qty', 'c.order_number')
                ->get();

            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $new_stock_reservations = [
                        'type' => 'Website Stocks',
                        'item_code' => $order->item_code,
                        'description' => $order->item_name,
                        'warehouse' => $order->f_warehouse,
                        'reserve_qty' => $order->item_qty,
                        'reference_no' => $order->order_number
                    ];
                    // reserve stocks in erp
                    $insert_response = Http::withHeaders($headers)
                        ->post($erp_api->base_url . '/api/resource/Stock Reservation', ($new_stock_reservations));

                    if ($insert_response->successful()) {
                        DB::table('fumaco_order')->where('id', $order->id)->update(['erp_stock_reserved' => 1]);
                        info('success');
                    }
                }
            }

            // get cancelled orders
            $cancelled_orders = DB::table('fumaco_order')->where('erp_stock_reserved', 1)
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
