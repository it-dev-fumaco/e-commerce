<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use Webp;
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

            if($request->item_type == 'product_bundle') {
                $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"]]';
    
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Product Bundle' . ($params));
            } else {
                $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"],["custom_show_in_website","=","1"],["has_variants","=","0"]]';
    
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Item' . ($params));
            }
    
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
    public function getItemDetails($item_code, $item_type) {
        try {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'message' => 'ERP API not configured.']);
            }
            
            $fields = '?fields=["item_name","website_warehouse","web_long_description","item_code","description","name","custom_show_in_website","weight_per_unit","weight_uom","website_warehouse","variant_of","brand","is_stock_item","stock_uom","item_classification","item_group","package_weight","package_length","package_width","package_height","package_dimension_uom","weight_uom","product_name"]';
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

            // get parent item code
            $fields = '?fields=["item_name"]';
            $filter = '&filters=[["item_code","=","' . $response['data'][0]['variant_of'] . '"]]';

            $params = $fields . '' . $filter;
            
            $variant_of = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Item' . $params);
            
            $parent_item_name = (isset($variant_of['data'][0])) ? $variant_of['data'][0]['item_name'] : null;

            $result = [
                'parent_item_name' => $parent_item_name,
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
                'package_length' => floatval($response['data'][0]['package_length']),
                'package_width' => floatval($response['data'][0]['package_width']),
                'package_height' => floatval($response['data'][0]['package_height']),
                'package_weight' => floatval($response['data'][0]['package_weight']),
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
            $filter = '&filters=[["parent","=","' . $item_code . '"]]&limit_page_length=50&order_by=idx';

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

            $product_bundle_items = [];
            if($item_type == 'product_bundle') {
                 // get product bundle items
                $fields = '?fields=["parent","item_code","idx","qty","description","uom"]';
                $filter = '&filters=[["parent","=","' . $item_code . '"]]&limit_page_length=100&order_by=idx';

                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ])->get($erp_api->base_url . '/api/resource/Product Bundle Item' . $params);

                if ($response->failed()) {
                    return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
                }

                if (count($response['data']) <= 0 && isset($response['data'])) {
                    $result['bundle_items'] = 0;
                } else{
                    $result['bundle_items'] = $response['data'];
                }
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

            $item = $this->getItemDetails($request->item_code, $request->item_type);

            if($request->item_type == 'product_bundle') {
                $request->validate(
                    [
                        'item_code' => 'required',
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
                        'slug' => 'required'
                    ]
                );
            } else {
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
                        'slug' => 'required'
                    ]
                );
            }

            // validate if item attributes matches the current attributes registered in database based on parent item code
            if($item['attributes'] != 0) {
                $mismatch_attr_query = DB::table('fumaco_items as a')
                    ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
                    ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
                    ->where('a.f_parent_code', $item['parent_item_code'])
                    ->whereNotIn('attribute_name', array_column($item['attributes'], 'attribute'))
                    ->distinct()->count();

                if ($mismatch_attr_query > 0) {
                    return redirect()->back()->withInput($request->all())->with('error', 'Some of the attributes of this item did not exists in the parent attributes <b>' . $item['parent_item_code'] . '</b>.');
                }
            }
       
            $item_category = DB::table('fumaco_categories')->where('id', $request->product_category)->first(); 
            $item_category = ($item_category) ? $item_category->name : null;
            if(!$item_category) {
                return redirect()->back()->withInput($request->all())->with('error', 'Please select product category.');
            }
            if($request->slug){
                $rules = array(
                    'slug' => 'required|unique:fumaco_items,slug'
                );

                $validation = Validator::make($request->all(), $rules);
                if($validation->fails()){
                    return redirect()->back()->with('error', 'Slug must be unique');
                }
            }

            if ($request->item_type == 'product_bundle') {
                $stock_qty = $request->stock_qty;
            } else {
                $stock_qty = ($request->is_manual) ? $request->stock_qty : $item['stock_qty'];
            }

            $id = DB::table('fumaco_items')->insertGetId([
                'f_idcode' => $item['item_code'],
                'f_parent_code' => $item['parent_item_code'],
                'f_parent_item_name' => $item['parent_item_name'],
                'f_name' => $item['item_code'],
                'f_name_name'	 => $request->product_name,
                'f_item_name' => $item['item_name'],
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_brand' => $item['brand'],
                'f_item_classification' => $item['item_classification'],
                'f_stock_uom' => $item['stock_uom'],
                'f_weight_per_unit' => $item['weight_per_unit'],
                'f_weight_uom' => (!$item['weight_uom']) ? $request->weight_uom : $item['weight_uom'],
                'f_package_d_uom' => $item['package_dimension_uom'],
                'f_package_length' => $item['package_length'],
                'f_package_width'	 => $item['package_width'],
                'f_package_height' => $item['package_height'],
                'f_package_weight' => $item['package_weight'],
                'f_warehouse' => $item['warehouse'],
                'f_qty' => $stock_qty,
                'stock_source' => ($request->is_manual) ? 0 : 1,
                'f_alert_qty' => $request->alert_qty,
                'f_description' => $item['item_description'],
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_status' => 1,
                'f_by' => Auth::user()->username,
                'f_ip' => $request->ip(),
                'f_original_price' => $item['item_price'],
                'keywords' => $request->keywords,
                'url_title' => $request->url_title,
                'meta_description' => $request->meta_description,
                'slug' => strtolower($request->slug),
                'f_item_type' => $request->item_type,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ]);

            if($item['attributes'] != 0) {
                // insert item attributes
                $item_attr = [];
                foreach($item['attributes'] as $attr) {
                    $existing_attribute = DB::table('fumaco_attributes_per_category')
                        ->where('category_id', $request->product_category)
                        ->where('attribute_name', $attr['attribute'])->first();

                    if (!$existing_attribute) {
                        // insert attribute names
                        $attr_id = DB::table('fumaco_attributes_per_category')->insertGetId([
                            'category_id' => $request->product_category,
                            'attribute_name' => $attr['attribute'],
                            'slug' => Str::slug($attr['attribute'], '-'),
                            'created_by' => Auth::user()->username
                        ]);
                    }
                    // get attribute name id
                    $attr_name_id = ($existing_attribute) ? $existing_attribute->id : $attr_id;

                    $attribute_value = $attr['attribute_value'];
                    if (strtoupper($attribute_value) == 'n/a') {
                        $attribute_value = strtoupper($attribute_value);
                    }

                    $item_attr[] = [
                        'idx' => $attr['idx'],
                        'idcode' => $attr['parent'],
                        'attribute_name_id' => $attr_name_id,
                        'attribute_value' => $attribute_value,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }

                DB::table('fumaco_items_attributes')->insert($item_attr);
            }

            if ($request->item_type == 'product_bundle') {
                // insert product bundle items
                $bundle_items = [];
                foreach($item['bundle_items'] as $bundle) {
                    $bundle_items[] = [
                        'idx' => $bundle['idx'],
                        'parent_item_code' => $bundle['parent'],
                        'item_code' => $bundle['item_code'],
                        'item_description' => $bundle['description'],
                        'qty' => $bundle['qty'],
                        'uom' => $bundle['uom'],
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }

                DB::table('fumaco_product_bundle_item')->insert($bundle_items);
            }
           
            // insert brand
            $existing_brand = DB::table('fumaco_brands')->where('brandname', $item['brand'])->exists();
            if(!$existing_brand) {
                DB::table('fumaco_brands')->insert(['brandname' => $item['brand'], 'slug' => Str::slug($item['brand'], '-')]);
            }
            
            DB::commit();

            $redirect_to = ($request->item_type == 'product_bundle') ? '/admin/product/' . $id . '/edit_bundle' : '/admin/product/' . $id . '/edit';

            return redirect($redirect_to)->with('success', 'Product has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updateItem($id, Request $request) {
        DB::beginTransaction();
        try {
            $detail = DB::table('fumaco_items')->where('id', $id)->first();
            if(!$detail) {
                return redirect()->back()->with('error', 'Item not found.');
            }

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
            if($request->slug){
                $slug_check = DB::table('fumaco_items')->where('id', '!=', $id)->where('slug', $request->slug)->count();
                if($slug_check > 0){
                    return redirect()->back()->with('error', 'Slug must be unique.');
                }
            }
            DB::table('fumaco_items')->where('id', $id)->update([
                'f_name_name' => $request->product_name,
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_alert_qty' => $request->alert_qty,
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_status' => ($request->is_disabled) ? 0 : 1,
                'f_qty' => $request->stock_qty,
                'stock_source' => ($request->is_manual) ? 0 : 1,
                'keywords' => $request->keywords,
                'url_title' => $request->url_title,
                'meta_description' => $request->meta_description,
                'slug' => strtolower($request->slug),
                'last_modified_by' => Auth::user()->username,
            ]);

            if($detail->f_cat_id != $request->product_category) {
                // update attributes per category
                $attributes = DB::table('fumaco_items_attributes as a')
                    ->join('fumaco_attributes_per_category as b', 'a.attribute_name_id', 'b.id')
                    ->select('a.id', 'b.attribute_name')->where('a.idcode', $detail->f_idcode)->get();

                foreach($attributes as $attr) {
                    $existing_attribute = DB::table('fumaco_attributes_per_category')
                        ->where('category_id', $request->product_category)
                        ->where('attribute_name', $attr->attribute_name)->first();

                    if (!$existing_attribute) {
                        // insert attribute names
                        $attr_id = DB::table('fumaco_attributes_per_category')->insertGetId([
                            'category_id' => $request->product_category,
                            'attribute_name' => $attr->attribute_name,
                            'slug' => Str::slug($attr->attribute_name, '-'),
                            'created_by' => Auth::user()->username,
                            'last_modified_by' => Auth::user()->username,
                        ]);
                    }
                    // get attribute name id
                    $attr_name_id = ($existing_attribute) ? $existing_attribute->id : $attr_id;
                    DB::table('fumaco_items_attributes')->where('id', $attr->id)->update(
                        [
                            'attribute_name_id' => $attr_name_id,
                            'last_modified_by' => Auth::user()->username,
                        ]
                    );
                }
            }

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

            DB::table('fumaco_product_bundle_item')->where('parent_item_code', $item_code)->delete();

            DB::table('fumaco_items_image_v1')->where('idcode', $item_code)->delete();

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
            DB::table('fumaco_items')->where('f_idcode', $item_code)->update(['f_status' => 0, 'last_modified_by' => Auth::user()->username]);

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
            DB::table('fumaco_items')->where('f_idcode', $item_code)->update(['f_status' => 1, 'last_modified_by' => Auth::user()->username]);

            DB::commit();

            return redirect()->back()->with('success', 'Product code <b>' . $item_code . '</b> has been enabled.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function featureItem($id) {
        DB::beginTransaction();
        try {

            $details = DB::table('fumaco_items')->where('id', $id)->first();
            if ($details) {
                $featured = ($details->f_featured) ? 0 : 1;
                DB::table('fumaco_items')->where('id', $id)->update(['f_featured' => $featured, 'last_modified_by' => Auth::user()->username]);
            }

            DB::commit();

            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

	public function viewAddForm($type) {
        $item_categories = DB::table('fumaco_categories')->get();

        if ($type == 'product_bundle') {
            return view('backend.products.add_bundle', compact('item_categories'));
        }

        if ($type == 'simple_product') {
            return view('backend.products.add', compact('item_categories'));
        }

        return redirect('/admin/product/list');
	}

    public function viewList(Request $request) {
        $q_string = $request->q;
        $search_str = explode(' ', $q_string);
        $product_list = DB::table('fumaco_items')->where('f_brand', 'LIKE', "%".$request->brands."%")
            ->when($request->parent_code, function ($query) use ($request) {
                return $query->where('f_parent_code', 'LIKE', "%".$request->parent_code."%");
            })
            ->where('f_cat_id', 'LIKE', "%".$request->category."%")
            ->when($request->is_featured, function($c) use ($request) {
                $c->where('f_featured', $request->is_featured);
            })
            ->when($request->on_sale, function($c) use ($request) {
                $c->where('f_onsale', $request->on_sale);
            })
            ->when($q_string, function ($query) use ($search_str, $q_string) {
                return $query->where(function($q) use ($search_str, $q_string) {
                    foreach ($search_str as $str) {
                        $q->where('f_description', 'LIKE', "%".$str."%");
                    }

                    $q->orWhere('f_idcode', 'LIKE', "%".$q_string."%")
                        ->orWhere('f_item_classification', 'LIKE', "%".$q_string."%");
                });
            })
            ->orderBy('f_date', 'desc')->paginate(15);

        $brands = DB::table('fumaco_items')->select('f_brand')->orderBy('f_brand', 'asc')->groupBy('f_brand')->get();
        $categories = DB::table('fumaco_categories')->get();

        $list = [];
        foreach ($product_list as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->where('onsale_img', 0)->first();

            $image = $item_image ? $item_image->imgprimayx : null;

            $image_sale = [];

            $sale_image = null;

            if($product->f_onsale == 1){
                $sale_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->where('onsale_img', 1)->first();
                $on_sale_imgs = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->where('onsale_img', 1)->get();

                $sale_image = $sale_image ? $sale_image->imgprimayx : null;

                foreach($on_sale_imgs as $sale_img){
                    $image_sale[] = [
                        'id' => $sale_img->id,
                        'orig' => $sale_img->imgoriginalx,
                        'primary' => $sale_img->imgprimayx
                    ];
                }
            }

            $item_name = strip_tags($product->f_name_name);
            $list[] = [
                'id' => $product->id,
                'product_code' => $product->f_parent_code,
                'item_code' => $product->f_idcode,
                'product_name' => $product->f_name_name,
                'item_name' => $item_name,
                'image' => $sale_image ? $sale_image : $image,
                'on_sale_image' => $image_sale,
                'price' => $product->f_original_price,
                'new_price' => $product->f_price,
                'discount_percentage' => $product->f_discount_percent,
                'qty' => $product->f_qty,
                'reserved_qty' => $product->f_reserved_qty,
                'product_category' => $product->f_category,
                'brand' => $product->f_brand,
                'on_sale' => $product->f_onsale,
                'erp_stock' => $product->stock_source,
                'status' => $product->f_status,
                'featured' => $product->f_featured
            ];
        }

        return view('backend.products.list', compact('list', 'product_list', 'brands', 'categories'));
    }

    public function viewProduct($id) {
        $item_categories = DB::table('fumaco_categories')->get();

        $details = DB::table('fumaco_items')->where('id', $id)->first();
        
        $item_image = DB::table('fumaco_items_image_v1')
            ->where('idcode', $details->f_idcode)->first();

        $item_image = ($item_image) ? $item_image->imgoriginalx : null;

        $attributes = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as b', 'a.attribute_name_id', 'b.id')
            ->where('a.idcode', $details->f_idcode)->orderBy('a.idx', 'asc')->get();

        $related_products_query = DB::table('fumaco_items as a')
            ->join('fumaco_items_relation as b', 'a.f_idcode', 'b.related_item_code')
            ->where('b.item_code', $details->f_idcode)
            ->get();

        $bundle_items = DB::table('fumaco_product_bundle_item')->where('parent_item_code', $details->f_idcode)->orderBy('idx', 'asc')->get();
        $related_products = [];
        foreach($related_products_query as $row) {
            $image = DB::table('fumaco_items_image_v1')->where('idcode', $row->related_item_code)->first();

            $related_products[] = [
                'id' => $row->id_related,
                'item_code' => $row->related_item_code,
                'item_description' => $row->f_name_name,
                'image' => ($image) ? $image->imgprimayx : null,
                'original_price' => $row->f_original_price,
            ];
        }

        if($details->f_item_type == 'product_bundle') {
            return view('backend.products.view_bundle', compact('details', 'item_categories', 'attributes', 'item_image', 'related_products', 'bundle_items'));    
        }

        return view('backend.products.view', compact('details', 'item_categories', 'attributes', 'item_image', 'related_products', 'bundle_items'));
    }

    public function viewCategoryAttr(Request $request) {
        $list = DB::table('fumaco_categories')->paginate(10);

        $attributes = [];
        $cat_id = $request->cat_id;
        if(isset($cat_id)) {
            $attributes = DB::table('fumaco_attributes_per_category')->where('category_id', $cat_id)->get();
        }

        return view('backend.products.category_attribute_settings', compact('list', 'attributes'));
    }

    public function voucherList(){
        $coupon = DB::table('fumaco_voucher')->paginate(10);
        return view('backend.marketing.list_voucher', compact('coupon'));
    }

    public function onSaleList(){
        $on_sale = DB::table('fumaco_on_sale')->paginate(10);

        $sale_arr = [];
        foreach($on_sale as $sale){
            $coupon = DB::table('fumaco_voucher')->where('id', $sale->coupon)->first();
            $categories_arr = [];
            if($sale->discount_for == 'Per Category'){
                $cat_ids = explode(',', $sale->apply_discount_to);
                foreach($cat_ids as $cat_id){
                    $cat_name = DB::table('fumaco_categories')->where('id', $cat_id)->first();
                    $categories_arr[] = [
                        'id' => $cat_id,
                        'name' => $cat_name->name
                    ];
                }
            }
            
            $sale_arr[] = [
                'id' => $sale->id,
                'name' => $sale->sale_name,
                'discount_type' => $sale->discount_type,
                'discount_rate' => $sale->discount_rate,
                'capped_amount' => $sale->capped_amount,
                'coupon' => $coupon ? $coupon->code : '',
                'discount_for' => $sale->discount_for,
                'categories' => $categories_arr,
                'sale_duration' => Carbon::parse($sale->start_date)->format('M d, Y').' - '.Carbon::parse($sale->end_date)->format('M d, Y'),
                'status' => $sale->status
            ];
        }

        // return $sale_arr;
        return view('backend.marketing.list_sale', compact('on_sale', 'sale_arr'));
    }

    public function addOnsaleForm(){
        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $vouchers = DB::table('fumaco_voucher')->get();

        return view('backend.marketing.add_onsale', compact('categories', 'vouchers'));
    }

    public function editOnsaleForm($id){
        $on_sale = DB::table('fumaco_on_sale')->where('id', $id)->first();

        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $vouchers = DB::table('fumaco_voucher')->get();

        return view('backend.marketing.edit_onsale', compact('on_sale', 'categories', 'vouchers'));
    }

    public function addVoucherForm(){
        return view('backend.marketing.add_voucher');
    }

    public function addVoucher(Request $request){
        DB::beginTransaction();
        try {
            $rules = array(
				'name' => 'required|unique:fumaco_voucher,name',
                'coupon_code' => 'required|unique:fumaco_voucher,code'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Voucher Name/Code must be unique.");
			}

            $insert = [
                'name' => $request->name,
                'code' => $request->coupon_code,
                'total_allotment' => $request->allotment,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_voucher')->insert($insert);
            DB::commit();
            return redirect('/admin/marketing/voucher/list')->with('success', 'Voucher Added!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    // public function onSaleItemList(Request $request){
    //     $items = DB::table('fumaco_items')->paginate(15);
    //     return view('backend.marketing.item_list', compact('items'));
    // }

    public function addOnSale(Request $request){
        DB::beginTransaction();
        try {
            $from = '';
            $to = '';
            $discount_rate = null;

            if(isset($request->set_duration)){
                $sale_duration = explode(' - ', $request->sale_duration);

                $from = $sale_duration[0];
                $to = $sale_duration[1];
            }            

            if($request->discount_type == 'Fixed Amount'){
                $discount_rate = $request->discount_amount;
            }else if($request->discount_type == 'By Percentage'){
                $discount_rate = $request->discount_percentage;
            }

            $insert = [
                'sale_name' => $request->sale_name,
                'start_date' => $from,
                'end_date' => $to,
                'discount_type' => $request->discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $request->capped_amount,
                'discount_for' => $request->discount_for,
                'apply_discount_to' => $request->discount_for == 'All Items' ? $request->discount_for : $request->selected_categories,
                'coupon' => $request->coupon,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_on_sale')->insert($insert);

            DB::commit();
            return redirect('/admin/marketing/on_sale/list')->with('success', 'On Sale Added.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    // update parent variant attribute status
    public function updateCategoryAttr($cat_id, Request $request) {
        DB::beginTransaction();
        try {
            $attr_names = $request->attribute_name;
            $status = $request->show_in_website;
            foreach($attr_names as $i => $attr_name) {
                DB::table('fumaco_attributes_per_category')
                    ->where('category_id', $cat_id)->where('attribute_name', $attr_name)
                    ->update(['status' => $status[$i], 'last_modified_by' => Auth::user()->username]);
            }

            DB::commit();

            return redirect()->back()->with('attr_success', 'Product Attribute has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('attr_error', 'An error occured. Please try again.');
        }
    }

    public function uploadImagesForm($id){
        $details = DB::table('fumaco_items')->where('id', $id)->first();
        
        $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $details->f_idcode)->where('onsale_img', 0)->get();

        $img_arr = [];
        foreach($item_image as $img){
            $img_zoom = ($img->imgoriginalx) ? $img->imgoriginalx : null;
            $img_primary = ($img->imgprimayx) ? $img->imgprimayx : null;

            $img_arr[] = [
                'img_id' => $img->id,
                'item_code' => $img->idcode,
                'item_name' => $details->f_name_name,
                'primary' => $img_primary,
                'zoom' => $img_zoom 
            ];
        }

        return view('backend.products.images', compact('img_arr', 'details'));
    }

    public function deleteProductImage($id){
        DB::beginTransaction();
		try{
            $img = DB::table('fumaco_items_image_v1')->where('id', $id)->first();

            $primary_img = explode(".", $img->imgprimayx)[0];
            $original_img = explode(".", $img->imgoriginalx)[0];

            $primary = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/preview/'.$img->imgprimayx);
            $original = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$img->imgoriginalx);

            $primary_webp = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/preview/'.$primary_img.'.webp');
            $original_webp = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$original_img.'.webp');

            if (file_exists($primary)) {
                unlink($primary);
            }

            if (file_exists($original)) {
                unlink($original);
            }

            if (file_exists($primary_webp)) {
                unlink($primary_webp);
            }

            if (file_exists($original_webp)) {
                unlink($original_webp);
            }
            
            DB::table('fumaco_items_image_v1')->where('id', $id)->delete();

            DB::commit();
			return redirect()->back()->with('success', 'Media Successfully Deleted');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('image_error', 'Error');
		}
    }

    public function uploadImages(Request $request){
        DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_items_image_v1')->where('idcode', $request->item_code)->where('onsale_img', 0)->get();

            $image_error = '';

            $item_code = $request->item_code;

            $rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				$error = "Sorry, your file is too large.";
				return redirect()->back()->with('error', $error);
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $origImgPath = storage_path('/app/public/item_images/'.$item_code.'/gallery/original/'); // on sale
            $prevImgPath = storage_path('/app/public/item_images/'.$item_code.'/gallery/preview/'); // on sale

            if($request->hasFile('img_zoom')){
                if(!$request->hasFile('img_primary')){
                    return redirect()->back()->with('error', 'Zoom/Preview Image cannot be empty');
                }
                $img_primary = $request->file('img_primary'); //400
                $img_zoom = $request->file('img_zoom');//1024
    
                $p_filename = pathinfo($img_primary->getClientOriginalName(), PATHINFO_FILENAME);//400
                $z_filename = pathinfo($img_zoom->getClientOriginalName(), PATHINFO_FILENAME);//1024
                $p_extension = pathinfo($img_primary->getClientOriginalName(), PATHINFO_EXTENSION);//400
                $z_extension = pathinfo($img_zoom->getClientOriginalName(), PATHINFO_EXTENSION);//1024
    
                $p_filename = Str::slug($p_filename, '-');
                $z_filename = Str::slug($z_filename, '-');
    
                $p_name = $p_filename.".".$p_extension;//400
                $z_name = $z_filename.".".$z_extension;//1024
    
                if(!in_array($p_extension, $allowed_extensions) or !in_array($z_extension, $allowed_extensions)){
                    $image_error = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                    return redirect()->back()->with('image_error', $image_error);
                }
    
                foreach($checker as $c){
                    if($c->imgprimayx == $p_name){
                        $image_error = "Sorry, file already exists.";
                        return redirect()->back()->with('image_error', $image_error);
                    }
                    if($c->imgoriginalx == $z_name){
                        $image_error = "Sorry, file already exists.";
                        return redirect()->back()->with('image_error', $image_error);
                    }
                }
    
                $folder_name = 'public/item_images/'.$item_code.'/gallery/';
                $img_primary->storeAs($folder_name . 'preview', $p_name); // 400px
                $img_zoom->storeAs($folder_name . 'original', $z_name); // 1024px
    
                $webp_primary = Webp::make($request->file('img_primary'));
                $webp_zoom = Webp::make($request->file('img_zoom'));
    
                $webp_primary->save(storage_path('/app/' .$folder_name . 'preview/') . $p_filename  .'.webp');
                $webp_zoom->save(storage_path('/app/' .$folder_name . 'original/') . $z_filename .'.webp');
            
                $images_arr = [
                    'idcode' => $item_code,
                    'img_name' => $p_filename,
                    'imgprimayx' => $p_name,
                    'imgoriginalx' => $z_name,
                    'img_status' => 1,
                    'created_by' => Auth::user()->username,
                ];
    
                DB::table('fumaco_items_image_v1')->insert($images_arr);
            }

            // On sale image

            DB::commit();

			return redirect()->back()->with('success', 'Media Successfully Added');
		}catch(Exception $e){
			DB::rollback();

			return redirect()->back()->with('image_error', 'Error');
		}
    }

    // ajax get products based on item category
    public function selectProductsRelated($category_id, Request $request) {
        if ($request->ajax()) {
            $existing_related_products = DB::table('fumaco_items_relation')
                ->where('item_code', $request->parent)->distinct()->pluck('related_item_code');

            $query = DB::table('fumaco_items')->where('f_cat_id', $category_id)
                ->whereNotIn('f_idcode', $existing_related_products)
                ->orderBy('f_order_by', 'asc')->get();

            $list = [];
            foreach($query as $row) {
                $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $row->f_idcode)->first();

                $list[] = [
                    'item_code' => $row->f_idcode,
                    'item_description' => $row->f_name_name,
                    'image' => ($item_image) ? $item_image->imgprimayx : null,
                    'original_price' => $row->f_original_price,
                ];
            }

            return view('backend.products.select_related_products', compact('list'));
        }
    }

    public function saveRelatedProducts($parent_code, Request $request) {
        DB::beginTransaction();
        try {
            $items = $request->selected_products;
            $values = [];
            foreach ($items as $item) {
                $existing = DB::table('fumaco_items_relation')
                    ->where('item_code', $parent_code)->where('related_item_code', $item)
                    ->exists();

                if (!$existing) {
                    $values[] = [
                        'item_code' => $parent_code,
                        'related_item_code' => $item,
                        'created_by' => Auth::user()->username,
                    ];
                }
            }

            DB::table('fumaco_items_relation')->insert($values);

            DB::commit();

            return redirect()->back()->with('success', 'Related Products has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function removeRelatedProduct($id) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_items_relation')->where('id_related', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Product has been removed from related products.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function setProductOnSale($item_code, Request $request) {
        DB::beginTransaction();
        try {
            $success_msg = 'Image Uploaded';
            if(!isset($request->on_sale_enabled)){ // If adding images, ignore if Product is already "On Sale"
                $discount_percentage = $request->discount_percentage;
                if (!$discount_percentage && $discount_percentage <= 0) {
                    return redirect()->back()->with('error', 'Discount percentage cannot be less than or equal to zero.');
                }
                
                $item = DB::table('fumaco_items')->where('f_idcode', $item_code)->first();
                if (!$item) {
                    return redirect()->back()->with('error', 'Product not found.');
                }
    
                $discounted_price = $item->f_original_price - ($item->f_original_price * $discount_percentage / 100);
    
                DB::table('fumaco_items')->where('f_idcode', $item_code)->update([
                    'f_price' => $discounted_price,
                    'f_onsale' => 1,
                    'f_discount_percent' => $discount_percentage,
                    'f_discount_trigger' => 1,
                    'last_modified_by' => Auth::user()->username,
                ]);

                $success_msg = 'Product has been set "On Sale".';
            }

            // Adding On sale images

            $rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				$error = "Sorry, your file is too large.";
				return redirect()->back()->with('error', $error);
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $origImgPath = storage_path('/app/public/item_images/'.$item_code.'/gallery/original/'); // on sale
            $prevImgPath = storage_path('/app/public/item_images/'.$item_code.'/gallery/preview/'); // on sale

            if($request->hasFile('on_sale_img_zoom')){
                $img_sale = $request->file('on_sale_img_zoom');
                $img_sale_prev = $request->file('on_sale_img_primary');

                $onsale_primary_name = pathinfo($img_sale->getClientOriginalName(), PATHINFO_FILENAME);
			    $onsale_primary_ext = pathinfo($img_sale->getClientOriginalName(), PATHINFO_EXTENSION);

                $onsale_zoom_name = pathinfo($img_sale_prev->getClientOriginalName(), PATHINFO_FILENAME);
			    $onsale_zoom_ext = pathinfo($img_sale_prev->getClientOriginalName(), PATHINFO_EXTENSION);

                $onsale_primary_name = Str::slug($onsale_primary_name, '-');
                $onsale_zoom_name = Str::slug($onsale_zoom_name, '-');

                $sale_image_name_small = $onsale_primary_name.".".$onsale_primary_ext;
                $sale_image_name_big = $onsale_zoom_name.".".$onsale_zoom_ext;

                $zoom_checker = DB::table('fumaco_items_image_v1')->where('imgoriginalx', $sale_image_name_big)->first();
                $primary_checker = DB::table('fumaco_items_image_v1')->where('imgprimayx', $sale_image_name_small)->first();

                if($zoom_checker or $primary_checker){
                    return redirect()->back()->with('error', 'Image already exists');
                }

                if(!in_array($onsale_primary_ext, $allowed_extensions) or !in_array($onsale_zoom_ext, $allowed_extensions)){
                    return redirect()->back()->with('error', $extension_error);
                }

                $webp_orig = Webp::make($img_sale);
                $webp_prev = Webp::make($img_sale_prev);

                if ($webp_orig->save(storage_path('/app/public/item_images/'.$item_code.'/gallery/original/'.$onsale_primary_name.'.webp'))) {
                    $img_sale->move($origImgPath, $sale_image_name_small);
                }

                if ($webp_prev->save(storage_path('/app/public/item_images/'.$item_code.'/gallery/preview/'.$onsale_primary_name.'.webp'))) {
                    $img_sale_prev->move($prevImgPath, $sale_image_name_big);
                }

                $insert = [
                    'idcode' => $item_code,
                    'img_name' => $onsale_primary_name,
                    'imgoriginalx' => $sale_image_name_big,
                    'imgprimayx' => $sale_image_name_small,
                    'onsale_img' => 1,
                    'img_status' => 1,
                    'created_by' => Auth::user()->username
                ];

                DB::table('fumaco_items_image_v1')->insert($insert);
            }

            DB::commit();

            return redirect()->back()->with('success', $success_msg);
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addOnSaleImages($item_code, $img_sale = null, $img_sale_prev = null){
        
    }

    public function disableProductOnSale($item_code) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_items')->where('f_idcode', $item_code)->update(['f_onsale' => 0, 'f_discount_trigger' => 0, 'last_modified_by' => Auth::user()->username]);

            DB::commit();

            return redirect()->back()->with('success', 'Product code <b>' . $item_code . '</b> has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}