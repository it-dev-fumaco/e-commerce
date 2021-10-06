<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use DB;

class ProductController extends Controller
{
    // function to get items from ERP via API
    public function searchItem(Request $request) {
        if($request->ajax()) {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'ERP API not configured.']);
            }
    
            $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"],["show_in_website","=","1"],["has_variants","=","0"]]';
    
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Item' . ($params));
    
            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }
    
            $result = [];
            foreach ($response['data'] as $row) {
                $result[] = [
                    'id' => $row['name'],
                    'text' => $row['name'],
                ];
            }
    
            return $result;
        }
    }

     // function to get item details from ERP via API
    public function getItemDetails($item_code) {
        try {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'message' => 'ERP API not configured.']);
            }
            
            $fields = '?fields=["item_name","website_warehouse","web_long_description","item_code","description","name","show_in_website","weight_per_unit","weight_uom","website_warehouse","variant_of","brand","is_stock_item","stock_uom","item_classification","item_group","package_weight","package_length","package_width","package_height","package_dimension_uom","weight_uom","product_name"]';
            $filter = '&filters=[["item_code","=","' . $item_code . '"]]';
    
            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Item' . $params);

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 || !isset($response['data'])) {
                return response()->json(['status' => 0, 'message' => 'Product ' . $item_code . ' not found.']);
            }

            $result = [
                'parent_item_code' => $response['data'][0]['variant_of'],
                'item_code' => $response['data'][0]['name'],
                'item_name' => $response['data'][0]['item_name'],
                'product_name' => $response['data'][0]['product_name'],
                'item_classification' => $response['data'][0]['item_classification'],
                'item_description' => $response['data'][0]['description'],
                'web_long_description' => $response['data'][0]['web_long_description'],
                'brand' => $response['data'][0]['brand'],
                'stock_uom' => $response['data'][0]['stock_uom'],
                'warehouse' => $response['data'][0]['website_warehouse'],
                'package_dimension_uom' => $response['data'][0]['package_dimension_uom'],
                'weight_uom' => $response['data'][0]['weight_uom'],
                'weight_per_unit' => $response['data'][0]['weight_per_unit'],
                'package_length' => $response['data'][0]['package_length'],
                'package_width' => $response['data'][0]['package_width'],
                'package_height' => $response['data'][0]['package_height'],
                'package_weight' => $response['data'][0]['package_weight'],
            ];

            // get stock quantity of selected item code
            $fields = '?fields=["item_code","warehouse","actual_qty","website_reserved_qty"]';
            $filter = '&filters=[["item_code","=","' . $item_code . '"],["warehouse","=","' .$result['warehouse'] .'"]]';
    
            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Bin' . $params);

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 && isset($response['data'])) {
                $result['stock_qty'] = 0;
            } else{
                $result['stock_qty'] = $response['data'][0]['website_reserved_qty'];
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

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 && isset($response['data'])) {
                $result['item_price'] = 0;
            } else{
                $result['item_price'] = $response['data'][0]['price_list_rate'];
            }

            // get item attribute / specification
            $fields = '?fields=["parent","attribute","idx","attribute_value"]';
            $filter = '&filters=[["parent","=","' . $item_code . '"]]&order_by=idx';

            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Item Variant Attribute' . $params);

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 && isset($response['data'])) {
                $result['attributes'] = 0;
            } else{
                $result['attributes'] = $response['data'];
            }

            return $result;
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
        }
    }

    public function saveItem(Request $request) {
        DB::beginTransaction();
        try {
            $existing_item = DB::table('fumaco_items')->where('f_idcode', $request->item_code)->exists();
            if ($existing_item) {
                return redirect()->back()->withInput($request->all())->with('error', 'Product code <b>' . $request->item_code . '</b> already exists.');
            }

            $item = $this->getItemDetails($request->item_code);

            $request->validate(
                [
                    'item_code' => 'required',
                    'parent_item_code' => 'required',
                    'item_code' => 'required',
                    'product_name' => 'required',
                    'item_name' => 'required',
                    'product_category' => 'required',
                    'brand' => 'required',
                    'item_classification' => 'required',
                    'stock_uom' => 'required',
                    'weight_per_unit' => 'required',
                    'weight_uom' => 'required',
                    'package_dimension_uom' => 'required',
                    'package_length' => 'required',
                    'package_width' => 'required',
                    'package_height' => 'required',
                    'package_weight' => 'required',
                    'warehouse' => 'required',
                    'stock_qty' => 'required|integer',
                    'alert_qty' => 'required|integer',
                    'item_description' => 'required',
                    'website_caption' => 'required',
                    'full_detail' => 'required',
                    'price' => 'required|numeric',
                ]
            );
    
            $item_category = DB::table('fumaco_categories')->where('id', $request->product_category)->first();
            $item_category = ($item_category) ? $item_category->name : null;
    
            $id = DB::table('fumaco_items')->insertGetId([
                'f_idcode' => $item['item_code'],
                'f_parent_code' => $item['parent_item_code'],
                'f_name' => $item['item_code'],
                'f_name_name'	 => $item['product_name'],
                'f_item_name' => $item['item_name'],
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_brand' => $item['brand'],
                'f_item_classification' => $item['item_classification'],
                'f_stock_uom' => $item['stock_uom'],
                'f_weight_per_unit' => $item['weight_per_unit'],
                'f_weight_uom' => $item['weight_uom'],
                'f_package_d_uom' => $item['package_dimension_uom'],
                'f_package_length' => $item['package_length'],
                'f_package_width'	 => $item['package_width'],
                'f_package_height' => $item['package_height'],
                'f_package_weight' => $item['package_weight'],
                'f_warehouse' => $item['warehouse'],
                'f_qty' => $item['stock_qty'],
                'f_alert_qty' => $request->alert_qty,
                'f_description' => $item['item_description'],
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_status' => 1,
                'f_by' => Auth::user()->username,
                'f_ip' => $request->ip(),
                'f_original_price' => $item['item_price'],
            ]);

            $item_attr = [];
            foreach($item['attributes'] as $attr) {
                $item_attr[] = [
                    'idx' => $attr['idx'],
                    'idcode' => $attr['parent'],
                    'attribute_name' => $attr['attribute'],
                    'attribute_value' => $attr['attribute_value'],
                    'attribute_status' => 1
                ];
            }

            DB::table('fumaco_items_attributes')->insert($item_attr);

            DB::commit();

            return redirect('/admin/product/' . $id . '/edit')->with('success', 'Product has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updateItem($id, Request $request) {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'product_name' => 'required',
                    'product_category' => 'required',
                    'alert_qty' => 'required|integer',
                    'website_caption' => 'required',
                    'full_detail' => 'required',
                ]
            );

            $item_category = DB::table('fumaco_categories')->where('id', $request->product_category)->first();
            $item_category = ($item_category) ? $item_category->name : null;

            DB::table('fumaco_items')->where('id', $id)->update([
                'f_name_name' => $request->product_name,
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_alert_qty' => $request->alert_qty,
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_status' => ($request->is_disabled) ? 0 : 1
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Product has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteItem($item_code) {
        DB::beginTransaction();
        try {
            $has_existing_transaction = DB::table('fumaco_order_items')->where('item_code', $item_code)->exists();
            if ($has_existing_transaction) {
                return redirect()->back()->with('error', 'Cannot delete product with transactions.');
            }

            DB::table('fumaco_items')->where('f_idcode', $item_code)->delete();

            DB::table('fumaco_items_attributes')->where('idcode', $item_code)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Product <b>' . $item_code . '</b> has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function disableItem($item_code) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_items')->where('f_idcode', $item_code)->update(['f_status' => 0]);

            DB::commit();

            return redirect()->back()->with('success', 'Product code <b>' . $item_code . '</b> has been disabled.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function enableItem($item_code) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_items')->where('f_idcode', $item_code)->update(['f_status' => 1]);

            DB::commit();

            return redirect()->back()->with('success', 'Product code <b>' . $item_code . '</b> has been enabled.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

	public function viewAddForm() {
        $item_categories = DB::table('fumaco_categories')->get();

		return view('backend.products.add', compact('item_categories'));
	}

    public function viewList(Request $request) {
        $q_string = $request->q;
        $search_str = explode(' ', $q_string);
        $list = DB::table('fumaco_items')
            ->when($q_string, function ($query) use ($search_str, $q_string) {
                return $query->where(function($q) use ($search_str, $q_string) {
                    foreach ($search_str as $str) {
                        $q->where('f_description', 'LIKE', "%".$str."%");
                    }

                    $q->orWhere('f_idcode', 'LIKE', "%".$q_string."%")
                        ->orWhere('f_item_classification', 'LIKE', "%".$q_string."%");
                });
            })
            ->orderBy('f_date', 'desc')->paginate(10);

        return view('backend.products.list', compact('list'));
    }

    public function viewProduct($id) {
        $item_categories = DB::table('fumaco_categories')->get();

        $details = DB::table('fumaco_items')->where('id', $id)->first();
        
        $item_image = DB::table('fumaco_items_image_v1')
            ->where('idcode', $details->f_idcode)->first();

        $item_image = ($item_image) ? $item_image->imgoriginalx : 'test.jpg';

        $attributes = DB::table('fumaco_items_attributes')
            ->where('idcode', $details->f_idcode)->orderBy('idx', 'asc')->get();
        
        return view('backend.products.view', compact('details', 'item_categories', 'attributes', 'item_image'));
    }
}