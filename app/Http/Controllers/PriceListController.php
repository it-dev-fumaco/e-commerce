<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;
use DB;

class PriceListController extends Controller
{
    public function viewPriceList(Request $request) {
        $price_list = DB::table('fumaco_price_list as a')
            ->join('fumaco_customer_group as b', 'a.customer_group_id', 'b.id')
            ->when($request->q, function ($query) use ($request) {
                return $query->where('price_list_name', 'LIKE', "%".$request->q."%");
            })
            ->select('a.*', 'b.customer_group_name')
            ->paginate(10);

        $customer_groups = DB::table('fumaco_customer_group')->get();

        return view('backend.pricelist', compact('price_list', 'customer_groups'));
    }

    public function getErpPriceList(Request $request) {
        $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
        if (!$erp_api) {
            return response()->json(['status' => 0, 'ERP API not configured.']);
        }

        $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"],["selling","=","1"],["enabled","=","1"],["name","!=","Website Price List"]]';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
            'Accept-Language' => 'en'
        ])->get($erp_api->base_url . '/api/resource/Price List' . ($params));

        if ($response->failed()) {
            return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
        }

        $erp_pricelist = [];
        foreach ($response['data'] as $row) {
            $erp_pricelist[] = [
                'id' => $row['name'],
                'text' => $row['name'],
            ];
        }

        return response()->json($erp_pricelist);
    }

    public function savePriceList(Request $request) {
        DB::beginTransaction();
        try {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'ERP API not configured.']);
            }

            $this->validate($request, [
                'pricelist' => 'required',
            ]);

            $existing = DB::table('fumaco_price_list')->where('customer_group_id', $request->customer_group)
                ->where('price_list_name', $request->pricelist)->exists();

            if ($existing) {
                return redirect()->back()->with('error', 'Record already exists.');
            }

            $id = DB::table('fumaco_price_list')->insertGetId([
                'price_list_name' => $request->pricelist,
                'customer_group_id' => $request->customer_group,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ]);

            $data = [];
            // get website items
            $items = DB::table('fumaco_items')->pluck('f_idcode');
            foreach ($items as $item) {
                $fields = '?fields=["price_list_rate","price_list","item_code"]';
                $filter = '&filters=[["price_list","=","' . $request->pricelist . '"],["item_code","=","'. $item .'"]]';
                $params = $fields . '' . $filter;

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Item Price' . ($params));

                if ($response->successful()) {
                    if (count($response['data']) > 0) {
                        $data[] = [
                            'item_code' => $item,
                            'price' => $response['data'][0]['price_list_rate'],
                            'price_list_id' => $id
                        ];
                    }
                }
            }

            DB::table('fumaco_product_prices')->insert($data);

            DB::commit();

            return redirect()->back()->with('success', 'Price list has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deletePriceList($id) {
        DB::table('fumaco_product_prices')->where('price_list_id', $id)->delete();

        DB::table('fumaco_price_list')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Price list has been deleted.');
    }

    public function viewItemPrices($pricelist_id, Request $request) {
        $details = DB::table('fumaco_price_list')->where('id', $pricelist_id)->first();
        if (!$details) {
            return redirect()->back()->with('error', 'Price list not found.');
        }

        $list = DB::table('fumaco_product_prices as a')->join('fumaco_items as b', 'a.item_code', 'b.f_idcode')
            ->where('a.price_list_id', $pricelist_id)
            ->when($request->q, function ($query) use ($request) {
                return $query->where('a.item_code', 'LIKE', "%".$request->q."%")->orWhere('f_name_name', 'like', "%".$request->q."%");
            })
            ->orderBy('a.last_modified_at', 'asc')->paginate(20);

        return view('backend.item_pricelist', compact('details', 'list'));
    }

    public function syncItemPrices(Request $request) {
        if ($request->ajax()) {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if ($erp_api) {
                // get registered price list in website database
                $pricelists = DB::table('fumaco_price_list')->pluck('price_list_name', 'id');
                $items = DB::table('fumaco_items')->select('id', 'f_idcode', 'f_stock_uom')
                    ->orderBy('created_at', 'desc')->get();
    
                foreach ($pricelists as $id => $pricelist) {
                    foreach ($items as $item) {
                        $item_code = explode("-", $item->f_idcode)[0];
                        $fields = '?fields=["price_list_rate","price_list","item_code","uom"]';
                        $filter = '&filters=[["price_list","=","' . $pricelist . '"],["item_code","=","'. $item_code .'"],["uom","=","'. $item->f_stock_uom .'"]]';
                        $params = $fields . $filter;
        
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                            'Accept-Language' => 'en'
                        ])->get($erp_api->base_url . '/api/resource/Item Price' . ($params));
    
                        if ($response->successful()) {
                            if (count($response['data']) > 0) {
                                $existing_item_price = DB::table('fumaco_product_prices')
                                    ->where(['item_code' => $item_code, 'price_list_id' => $id])
                                    ->where(function($q) use ($item) {
                                        $q->orWhere('uom', null)
                                            ->orWhere('uom', $item->f_stock_uom);
                                    })->first();
    
                                if ($existing_item_price) {
                                    DB::table('fumaco_product_prices')->where('id', $existing_item_price->id)
                                        ->update(['price' => $response['data'][0]['price_list_rate'], 'uom' => $response['data'][0]['uom']]);
                                } else {
                                    DB::table('fumaco_product_prices')->insert([
                                        'item_code' => $item_code,
                                        'price' => $response['data'][0]['price_list_rate'],
                                        'price_list_id' => $id,
                                        'uom' => $response['data'][0]['uom']
                                    ]);
                                }
                            }
                        }
                    }
                }
    
                foreach ($items as $item) {
                    $item_code = explode("-", $item->f_idcode)[0];
                    $fields = '?fields=["price_list_rate","price_list","item_code"]';
                    $filter = '&filters=[["price_list","=","Website Price List"],["item_code","=","'. $item_code .'"],["uom","=","'. $item->f_stock_uom .'"]]';
                    $params = $fields . $filter;
    
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                        'Accept-Language' => 'en'
                    ])->get($erp_api->base_url . '/api/resource/Item Price' . ($params));
    
                    if ($response->successful()) {
                        if (count($response['data']) > 0) {
                            DB::table('fumaco_items')->where('id', $item->id)
                                ->update(['f_default_price' => $response['data'][0]['price_list_rate']]);
                        }
                    }
                }
            }
    
            return response()->json(['status' => 1, 'message' => 'Item prices updated.']);
        }
    }
}