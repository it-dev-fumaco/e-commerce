<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Newsletter;
use Auth;
use Webp;
use DB;
use Mail;
use Cache;
use App\Http\Traits\ProductTrait;

class ProductController extends Controller
{
    use ProductTrait;

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
    public function getItemDetails($item_code, $item_type, $uom_conversion = null) {
        try {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'message' => 'ERP API not configured.']);
            }

            $api_header = [
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ];

            $fields = '?fields=["item_name","web_long_description","item_code","description","name","custom_show_in_website","weight_per_unit","weight_uom","website_warehouse","variant_of","brand","is_stock_item","stock_uom","item_classification","item_group","package_weight","package_length","package_width","package_height","package_dimension_uom","weight_uom","product_name"]';
            $filter = '&filters=[["item_code","=","' . $item_code . '"]]';
    
            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item' . $params);

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
            
            $variant_of = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item' . $params);
            
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

            // get item prices
            $fields = '?fields=["item_code","price_list","price_list_rate","currency","uom"]';
            if ($uom_conversion) {
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["price_list","=","Website Price List"],["uom","=","' . $uom_conversion . '"]]';
            } else {
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["price_list","=","Website Price List"]]';
            }

            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item Price' . $params);

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 && isset($response['data'])) {
                $result['item_price'] = [];
            } else{
                $result['item_price'] = $response['data'];
            }

            if($item_type == 'product_bundle') {
                $result['item_price'] = count($response['data']) > 0 ? $response['data'][0]['price_list_rate'] : 0;
            }

            // get uom conversion factor
            $fields = '?fields=["parent","uom","conversion_factor"]';
            $filter = '&filters=[["parent","=","' . $item_code . '"]]&order_by=idx';
    
            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/UOM Conversion Detail' . $params);

            $item_uoms = [];
            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            $uom_conversion_response = $response['data'];
            if (count($uom_conversion_response) > 0) {
                foreach($uom_conversion_response as $uom) {
                    $uom_item_price = collect($result['item_price'])->where('uom', $uom['uom'])->first();
                    $item_uoms[] = [
                        'uom' => $uom['uom'],
                        'conversion' => '1 ' . $uom['uom'] . ' = ' . $uom['conversion_factor'] . ' ' . $result['stock_uom'],
                        'conversion_factor' => $uom['conversion_factor'],
                        'price' => ($uom_item_price) ? $uom_item_price['price_list_rate'] : 0
                    ];
                }
            }

            $result['uom_conversion'] = $item_uoms;

            // get stock quantity of selected item code
            $fields = '?fields=["item_code","warehouse","actual_qty","website_reserved_qty"]';
            $filter = '&filters=[["item_code","=","' . $item_code . '"],["warehouse","=","' .$result['warehouse'] .'"]]';
    
            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Bin' . $params);

            if ($response->failed()) {
                return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
            }

            if (count($response['data']) <= 0 && isset($response['data'])) {
                $result['stock_qty'] = 0;
            } else{
                $result['stock_qty'] = $response['data'][0]['website_reserved_qty'];
            }

            // get item attribute / specification
            $fields = '?fields=["parent","attribute","idx","attribute_value"]';
            $filter = '&filters=[["parent","=","' . $item_code . '"]]&limit_page_length=50&order_by=idx';

            $params = $fields . '' . $filter;
            
            $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item Variant Attribute' . $params);

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
                
                $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Product Bundle Item' . $params);

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
            if ($request->uom_conversion != $request->stock_uom) {
                $existing_item_uom = DB::table('fumaco_items')->where('f_idcode', 'like', $request->item_code . '%')
                    ->where('f_stock_uom', $request->uom_conversion)->exists();
                if ($existing_item_uom) {
                    return redirect()->back()->withInput($request->all())->with('error', 'Product code <b>' . $request->item_code . '</b> with <b>' . $request->uom_conversion . '</b> already exists.');
                }
            } else {
                $existing_item = DB::table('fumaco_items')->where('f_idcode', $request->item_code)->exists();
                if ($existing_item) {
                    return redirect()->back()->withInput($request->all())->with('error', 'Product code <b>' . $request->item_code . '</b> already exists.');
                }
            }

            $item = $this->getItemDetails($request->item_code, $request->item_type, $request->uom_conversion);

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
                        'uom_conversion' => 'required',
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
                        'price' => 'required|numeric',
                        'slug' => 'required'
                    ]
                );
            }

            // validate if item attributes matches the current attributes registered in database based on parent item code
            if($item['attributes'] != 0) {
                $child_items = DB::table('fumaco_items')->where('f_parent_code', $item['parent_item_code'])->pluck('f_idcode');
                $child_items = collect($child_items)->map(function($i){
                    return explode("-", $i)[0];
                })->toArray();
   
                $mismatch_attr_query = DB::table('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
                    ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
                    ->whereIn('b.idcode', $child_items)->whereNotIn('attribute_name', array_column($item['attributes'], 'attribute'))
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
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)); // Removes special chars.
                $slug = Str::slug($slug, '-');

                $existing_slug = DB::table('fumaco_items')->where('slug', $slug)->exists();
                if ($existing_slug) {
                    return redirect()->back()->with('error', 'Slug must be unique');
                }
            }

            if ($request->item_type == 'product_bundle') {
                $stock_qty = $request->stock_qty;
            } else {
                $stock_qty = ($request->is_manual) ? $request->stock_qty : $item['stock_qty'];
            }

            $item_code_suffix = '';
            if ($request->uom_conversion != $request->stock_uom) {
                $existing_same_code = DB::table('fumaco_items')
                    ->where('f_idcode', 'like', $request->item_code . '%')
                    ->pluck('f_idcode');

                $existing_same_code = collect($existing_same_code)->map(function($i){
                    return isset(explode("-", $i)[1]) ? explode("-", $i)[1] : null;
                })->toArray();

                $existing_same_code = array_filter($existing_same_code);
                $item_code_suffix = '-'.array_values(array_diff(range('A', 'Z'), $existing_same_code))[0];
            }

            // Image upload
            $featured_image_name = null;
            if($request->hasFile('featured_image')){
                $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

                $featured_image = $request->file('featured_image');

                $image_name = pathinfo($featured_image->getClientOriginalName(), PATHINFO_FILENAME);
			    $image_ext = pathinfo($featured_image->getClientOriginalName(), PATHINFO_EXTENSION);

                $image_name = Str::slug($image_name, '-');
                
                $featured_image_name = $image_name.".".$image_ext;

                if(!in_array($image_ext, $allowed_extensions)){
                    return redirect()->back()->with('error', $extension_error);
                }

                $webp = Webp::make($request->file('featured_image'));

                if(!Storage::disk('public')->exists('/item_images/'.$request->item_code.'/gallery/featured/')){
                    Storage::disk('public')->makeDirectory('/item_images/'.$request->item_code.'/gallery/featured/');
                }

                $destinationPath = storage_path('/app/public/item_images/'.$request->item_code.'/gallery/featured/');

                if ($webp->save(storage_path('/app/public/item_images/'.$request->item_code.'/gallery/featured/'.$image_name.'.webp'))) {
                    $featured_image->move($destinationPath, $featured_image_name);
                }
            } 

            $stock_uom = ($request->item_type != 'product_bundle') ? $request->uom_conversion : $item['stock_uom'];

            $id = DB::table('fumaco_items')->insertGetId([
                'f_idcode' => $item['item_code'] . $item_code_suffix,
                'f_parent_code' => $item['parent_item_code'],
                'f_parent_item_name' => $item['parent_item_name'],
                'f_name' => $item['item_code'],
                'f_name_name'	 => $request->product_name,
                'f_item_name' => $item['item_name'],
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_brand' => $item['brand'],
                'f_item_classification' => $item['item_classification'],
                'f_stock_uom' => $stock_uom,
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
                'f_featured_image' => $featured_image_name,
                'f_status' => 1,
                'f_by' => Auth::user()->username,
                'f_ip' => $request->ip(),
                'f_default_price' => $request->price,
                'keywords' => $request->keywords,
                'url_title' => $request->url_title,
                'meta_description' => $request->meta_description,
                'slug' => $slug,
                'f_new_item' => 1,
                'f_new_item_start' => Carbon::now()->toDateTimeString(),
                'f_new_item_end' => Carbon::now()->addDays(7)->toDateTimeString(),
                'image_alt' => $request->alt ? $request->alt : null,
                'f_item_type' => $request->item_type,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ]);

            $uom_conversion_factor = collect($item['uom_conversion'])->where('uom', $stock_uom)->first();
            DB::table('fumaco_item_uom_conversion')->updateOrInsert([
                    'item_code' => $item['item_code'],
                    'uom' => $stock_uom,
                ],
                [
                    'conversion_factor' => ($uom_conversion_factor['conversion_factor']) ? $uom_conversion_factor['conversion_factor'] : 1,
                    'created_by' => Auth::user()->username,
                ]
            );

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

                    $existing_item_attribute = DB::table('fumaco_items_attributes')
                        ->where('idcode', $item['item_code'])->where('attribute_name_id', $attr_name_id)->exists();

                    if (!$existing_item_attribute) {
                        $item_attr[] = [
                            'idx' => $attr['idx'],
                            'idcode' => $attr['parent'],
                            'attribute_name_id' => $attr_name_id,
                            'attribute_value' => $attribute_value,
                            'created_by' => Auth::user()->username,
                            'last_modified_by' => Auth::user()->username,
                        ];
                    }
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
                ]
            );

            $item_category = DB::table('fumaco_categories')->where('id', $request->product_category)->first();
            $item_category = ($item_category) ? $item_category->name : null;

            if($request->slug){
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)); // Removes special chars.
                $slug = Str::slug($slug, '-');

                $existing_slug = DB::table('fumaco_items')->where('slug', $slug)->where('id', '!=', $id)->exists();
                if ($existing_slug) {
                    return redirect()->back()->with('error', 'Slug must be unique');
                }
            }

            $start_date = null;
            $end_date = null;
            $is_new = 0;
            if(isset($request->is_new_item)){
                $set_as_new_date = explode(' - ', $request->new_item_duration);
                $is_new = 1;
                $start_date = Carbon::parse($set_as_new_date[0])->format('Y/m/d');
                $end_date = Carbon::parse($set_as_new_date[1])->format('Y/m/d');
            }

            // Image upload
            $featured_image_name = null;
            if(isset($request->add_featured)){
                $featured_image_name = $detail->f_featured_image;
                if($request->hasFile('featured_image')){
                    $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
                    $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

                    $featured_image = $request->file('featured_image');

                    $image_name = pathinfo($featured_image->getClientOriginalName(), PATHINFO_FILENAME);
                    $image_ext = pathinfo($featured_image->getClientOriginalName(), PATHINFO_EXTENSION);

                    $image_name = Str::slug($image_name, '-');
                    
                    $featured_image_name = $image_name.".".$image_ext;

                    if(!in_array($image_ext, $allowed_extensions)){
                        return redirect()->back()->with('error', $extension_error);
                    }

                    $webp = Webp::make($request->file('featured_image'));

                    if(!Storage::disk('public')->exists('/item_images/'.$detail->f_idcode.'/gallery/featured/')){
                        Storage::disk('public')->makeDirectory('/item_images/'.$detail->f_idcode.'/gallery/featured/');
                    }

                    $destinationPath = storage_path('/app/public/item_images/'.$detail->f_idcode.'/gallery/featured/');

                    if ($webp->save(storage_path('/app/public/item_images/'.$detail->f_idcode.'/gallery/featured/'.$image_name.'.webp'))) {
                        $featured_image->move($destinationPath, $featured_image_name);
                    }
                }
            }else if($detail->f_featured_image){
                $featured = storage_path('/app/public/item_images/'.$detail->f_idcode.'/gallery/featured/'.$detail->f_featured_image);
                $featured_webp = storage_path('/app/public/item_images/'.$detail->f_idcode.'/gallery/featured/'.explode('.', $detail->f_featured_image)[0].'.webp');
                if (file_exists($featured)) {
                    unlink($featured);
                }

                if (file_exists($featured_webp)) {
                    unlink($featured_webp);
                }
            }
            
            DB::table('fumaco_items')->where('id', $id)->update([
                'f_name_name' => $request->product_name,
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_alert_qty' => $request->alert_qty,
                'f_warehouse' => $request->warehouse,
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_featured_image' => $featured_image_name,
                'f_status' => ($request->is_disabled) ? 0 : 1,
                'f_qty' => $request->stock_qty,
                'stock_source' => ($request->is_manual) ? 0 : 1,
                'keywords' => $request->keywords,
                'url_title' => $request->url_title,
                'meta_description' => $request->meta_description,
                'slug' => $slug,
                'image_alt' => $request->alt ? Str::slug($request->alt) : null,
                'f_new_item' => $is_new,
                'f_new_item_start' => $start_date,
                'f_new_item_end' => $end_date,
                'last_modified_by' => Auth::user()->username,
            ]);

            // save cross-sell
            if($request->selected_for_cross_sell){
                $created_by = Auth::user()->username;
                $last_modified_by = null;

                $checker = DB::table('fumaco_items_cross_sell')->where('item_code', $detail->f_idcode)->first();
                if($checker){
                    $created_by = $checker->created_by;
                    $last_modified_by = Auth::user()->username;

                    DB::table('fumaco_items_cross_sell')->where('item_code', $detail->f_idcode)->delete();
                }

                foreach($request->selected_for_cross_sell as $cross_sell){
                    DB::table('fumaco_items_cross_sell')->insert([
                        'item_code' => $detail->f_idcode,
                        'item_code_cross_sell' => $cross_sell,
                        'created_by' => $created_by,
                        'last_modified_by' => $last_modified_by
                    ]);
                }
            }

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

            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if ($erp_api) {
                $item_code = $detail->f_idcode;
                $warehouse = $request->warehouse;

                $api_header = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                    'Accept-Language' => 'en'
                ];

                // get item dimension
                $fields = '?fields=["item_code","package_weight","package_width","package_length","package_height","package_dimension_uom"]';
                $filter = '&filters=[["item_code","=","' . $item_code . '"]]';
        
                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item' . $params);

                if ($response->successful()) {
                    $package_length = $package_width = $package_height = $package_weight = $package_d_uom = null;
                    if (isset($response['data'])) {
                        $package_d_uom = $response['data'][0]['package_dimension_uom'];
                        $package_length = $response['data'][0]['package_length'];
                        $package_width = $response['data'][0]['package_width'];
                        $package_height = $response['data'][0]['package_height'];
                        $package_weight = $response['data'][0]['package_weight'];
                    }

                    DB::table('fumaco_items')->where('id', $id)->update([
                        'f_package_d_uom' => $package_d_uom,
                        'f_package_length' => $package_length ? (double)$package_length : 0,
                        'f_package_width' => $package_width ? (double)$package_width : 0,
                        'f_package_height' => $package_height ? (double)$package_height : 0,
                        'f_package_weight' => $package_weight ? (double)$package_weight : 0,
                        'last_sync_date' => Carbon::now()->toDateTimeString()
                    ]);
                }

                // get stock quantity of selected item code
                $fields = '?fields=["item_code","warehouse","actual_qty","website_reserved_qty"]';
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["warehouse","=","' .$warehouse .'"]]';
        
                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Bin' . $params);

                if ($response->successful()) {
                    $qty = 0;
                    if (isset($response['data']) && count($response['data']) > 0) {
                        $qty = $response['data'][0]['actual_qty'];
                    }
                    DB::table('fumaco_items')->where('id', $id)
                        ->where('stock_source', 1)->update([
                            'f_qty' => $qty,
                            'last_sync_date' => Carbon::now()->toDateTimeString()
                        ]);
                }

                // get item price
                $fields = '?fields=["item_code","price_list","price_list_rate","currency"]';
                $filter = '&filters=[["item_code","=","' . $item_code . '"],["price_list","=","Website Price List"]]';

                $params = $fields . '' . $filter;
                
                $response = Http::withHeaders($api_header)->get($erp_api->base_url . '/api/resource/Item Price' . $params);

                if ($response->successful()) {
                    $price = 0;
                    if (isset($response['data']) && count($response['data']) > 0) {
                        $price = $response['data'][0]['price_list_rate'];
                    }

                    DB::table('fumaco_items')->where('id', $id)
                        ->update([
                            'f_default_price' => $price,
                            'last_sync_date' => Carbon::now()->toDateTimeString()
                        ]);
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
                
            // do not delete item attributes if item code exists
            $existing_item = DB::table('fumaco_items')->where('f_idcode', $item_code)->exists();
            if (!$existing_item) {
                DB::table('fumaco_items_attributes')->where('idcode', explode("-", $item_code)[0])->delete();
            }

            DB::table('fumaco_item_uom_conversion')->where('item_code', explode("-", $item_code)[0])->delete();

            DB::table('fumaco_product_bundle_item')->where('parent_item_code', explode("-", $item_code)[0])->delete();

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

    public function isNewItem($id) {
        DB::beginTransaction();
        try {
            $details = DB::table('fumaco_items')->where('id', $id)->first();

            if ($details) {
                $is_new_item = $details->f_new_item == 0 ? 1 : 0;
                DB::table('fumaco_items')->where('id', $id)->update(['f_new_item' => $is_new_item, 'last_modified_by' => Auth::user()->username]);
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
            ->when($q_string, function ($query) use ($search_str, $q_string) {
                return $query->where(function($q) use ($search_str, $q_string) {
                    foreach ($search_str as $str) {
                        $q->where('f_description', 'LIKE', "%".$str."%");
                    }

                    $q->orWhere('f_idcode', 'LIKE', "%".$q_string."%")
                        ->orWhere('f_item_classification', 'LIKE', "%".$q_string."%")->orWhere('slug', 'LIKE', "%".$q_string."%");
                });
            })
            ->orderBy('f_date', 'desc')->paginate(15);

        $brands = DB::table('fumaco_items')->select('f_brand')->orderBy('f_brand', 'asc')->groupBy('f_brand')->get();
        $categories = DB::table('fumaco_categories')->get();

        $customer_groups = DB::table('fumaco_customer_group')->get();

        $on_sale_items = $this->onSaleItems(collect($product_list->items())->pluck('f_idcode'));

        $list = [];
        foreach ($product_list as $product) {
            // check if item is on sale
            $on_sale = false;
            $discount_type = $discount_rate = $discount_display = null;
            $discounted_price = 0;
            if (array_key_exists($product->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$product->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$product->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$product->f_idcode]['discount_rate'];
                $discounted_price = $on_sale_items[$product->f_idcode]['discounted_price'];
                $discount_display = $on_sale_items[$product->f_idcode]['discount_display'];
            }

            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $image = $item_image ? $item_image->imgprimayx : null;

            $is_new_item = 0;
            if($product->f_new_item == 1){
                if($product->f_new_item_start <= Carbon::now() and $product->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $pricelist = DB::table('fumaco_product_prices as a')->join('fumaco_price_list as b', 'a.price_list_id', 'b.id')
                ->join('fumaco_customer_group as c', 'b.customer_group_id', 'c.id')
                ->where('item_code', $product->f_idcode)->select('a.id as item_price_id', 'a.*', 'b.price_list_name', 'c.customer_group_name')->get();

            $item_name = strip_tags($product->f_name_name);
            $list[] = [
                'id' => $product->id,
                'product_code' => $product->f_parent_code,
                'item_code' => $product->f_idcode,
                'product_name' => $product->f_name_name,
                'stock_uom' => $product->f_stock_uom,
                'item_name' => $item_name,
                'image' => $image,
                'price' => $product->f_default_price,
                'qty' => $product->f_qty,
                'reserved_qty' => $product->f_reserved_qty,
                'product_category' => $product->f_category,
                'brand' => $product->f_brand,
                'on_sale' => $on_sale,
                'erp_stock' => $product->stock_source,
                'status' => $product->f_status,
                'featured' => $product->f_featured,
                'is_new_item' => $is_new_item,
                'pricelist' => $pricelist,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'discounted_price' => $discounted_price,
                'discount_display' => $discount_display,
                'last_sync_date' => $product->last_sync_date
            ];
        }

        return view('backend.products.list', compact('list', 'product_list', 'brands', 'categories', 'customer_groups'));
    }

    public function viewProduct($id) {
        $item_categories = DB::table('fumaco_categories')->get();

        $details = DB::table('fumaco_items')->where('id', $id)->first();
        
        $item_image = DB::table('fumaco_items_image_v1')
            ->where('idcode', $details->f_idcode)->first();

        $item_image = ($item_image) ? $item_image->imgoriginalx : null;

        $exploded_item_code = explode("-", $details->f_idcode)[0];

        $attributes = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as b', 'a.attribute_name_id', 'b.id')
            ->where('a.idcode', $exploded_item_code)->orderBy('a.idx', 'asc')->get();

        $related_products_query = DB::table('fumaco_items as a')
            ->join('fumaco_items_relation as b', 'a.f_idcode', 'b.related_item_code')
            ->where('b.item_code', $details->f_idcode)
            ->get();

        $selected_for_cross_sell = DB::table('fumaco_items_cross_sell')->where('item_code', $details->f_idcode)->get();
        $products_for_cross_sell = DB::table('fumaco_items')->where('f_idcode', '!=', $details->f_idcode)->whereNotIn('f_idcode', collect($selected_for_cross_sell)->pluck('item_code_cross_sell'))->get();

        $bundle_items = DB::table('fumaco_product_bundle_item')->where('parent_item_code', explode("-", $details->f_idcode)[0])->orderBy('idx', 'asc')->get();
        $related_products = [];
        foreach($related_products_query as $row) {
            $image = DB::table('fumaco_items_image_v1')->where('idcode', $row->related_item_code)->first();

            $related_products[] = [
                'id' => $row->id_related,
                'item_code' => $row->related_item_code,
                'item_description' => $row->f_name_name,
                'image' => ($image) ? $image->imgprimayx : null,
                'original_price' => $row->f_default_price,
            ];
        }

        $cross_sell_arr = [];
        foreach($selected_for_cross_sell as $cross_sell){
            $cross_sell_description = DB::table('fumaco_items')->where('f_idcode', $cross_sell->item_code_cross_sell)->pluck('f_name_name')->first();
            $cross_sell_arr[] = [
                'item_code' => $cross_sell->item_code,
                'cross_sell_item_code' => $cross_sell->item_code_cross_sell, 
                'cross_sell_description' => $cross_sell_description
            ];
        }

        if($details->f_item_type == 'product_bundle') {
            return view('backend.products.view_bundle', compact('details', 'item_categories', 'attributes', 'item_image', 'related_products', 'bundle_items'));    
        }

        return view('backend.products.view', compact('details', 'item_categories', 'attributes', 'item_image', 'related_products', 'bundle_items', 'cross_sell_arr', 'products_for_cross_sell'));
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


    public function voucherList(Request $request){
        $coupon = DB::table('fumaco_voucher')
            ->whereDate('validity_date_end', '>=', Carbon::now()->endOfDay())
            ->orWhereNull('validity_date_start')
            ->where('name', 'LIKE', '%'.$request->q.'%')->orderBy('created_at', 'desc')->paginate(10);

        $invalid_coupon = DB::table('fumaco_voucher')
            ->whereRaw('total_consumed >= total_allotment')
            ->orWhereRaw('total_allotment != null')
            ->orWhereDate('validity_date_end', '<', Carbon::now()->endOfDay())
            ->where('name', 'LIKE', '%'.$request->expired_q.'%')->orderBy('created_at', 'desc')->paginate(10);

        return view('backend.marketing.list_voucher', compact('coupon', 'invalid_coupon'));
    }

    public function onSaleList(Request $request){
        $on_sale = DB::table('fumaco_on_sale')->where('sale_name', 'LIKE', '%'.$request->q.'%')->paginate(10);
        $sale_arr = [];
        foreach($on_sale as $sale){
            $child_arr = [];
            switch ($sale->apply_discount_to) {
                case 'Per Category':
                    $child_sale_arr = DB::table('fumaco_on_sale_categories as sc')->join('fumaco_categories as c', 'sc.category_id', 'c.id')
                        ->where('sc.sale_id', $sale->id)->select('c.id', 'c.name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
                    break;
                case 'Per Customer Group':
                    $child_sale_arr = DB::table('fumaco_on_sale_customer_group as sc')->join('fumaco_customer_group as c', 'sc.customer_group_id', 'c.id')
                        ->where('sc.sale_id', $sale->id)->select('c.id', 'c.customer_group_name as name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
                    break;
                case 'Per Shipping Service':
                    $child_sale_arr = DB::table('fumaco_on_sale_shipping_service')
                        ->where('sale_id', $sale->id)->select('id', 'shipping_service as name', 'discount_type', 'discount_rate', 'capped_amount', 'sale_id')->get();
                    break;
                case 'Selected Items':
                    $child_sale_arr = DB::table('fumaco_on_sale_items')
                        ->where('sale_id', $sale->id)->select('id', 'item_code as name', 'discount_type', 'discount_rate', 'capped_amount', 'sale_id')->get();
                    break;
                    
                default:
                    $child_sale_arr = [];
                    break;
            }

            if($child_sale_arr){
                foreach($child_sale_arr as $child_sale){
                    $child_arr[] = [
                        'sale_id' => $sale->id,
                        'id' => $child_sale->id,
                        'name' => $child_sale->name,
                        'discount_type' => $child_sale->discount_type,
                        'discount_rate' => $child_sale->discount_rate,
                        'capped_amount' => $child_sale->capped_amount
                    ];
                }
            }

            $sale_duration = null;

            if($sale->start_date and $sale->end_date){
                $sale_duration = Carbon::parse($sale->start_date)->format('M d, Y').' - '.Carbon::parse($sale->end_date)->format('M d, Y');
            }
            
            $sale_arr[] = [
                'id' => $sale->id,
                'name' => $sale->sale_name,
                'banner' => $sale->banner_image,
                'discount_type' => $sale->discount_type,
                'discount_rate' => $sale->discount_rate,
                'capped_amount' => $sale->capped_amount,
                'discount_for' => $sale->discount_for,
                'apply_discount_to' => $sale->apply_discount_to,
                'sale_duration' => $sale_duration,
                'notification_schedule' => $sale->notification_schedule ? Carbon::parse($sale->notification_schedule)->format('M d, Y') : null,
                'status' => $sale->status,
                'child_arr' => $child_arr
            ];
        }

        return view('backend.marketing.list_sale', compact('on_sale', 'sale_arr'));
    }

    public function addOnsaleForm(){
        $items = DB::table('fumaco_items')->where('f_status', 1)->select('f_idcode', 'f_item_name')->orderBy('f_idcode', 'asc')->get();

        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $customer_groups = DB::table('fumaco_customer_group')->get();

        $shipping_services = DB::table('fumaco_shipping_service')->where('shipping_service_name', '!=', 'Free Delivery')
            ->select('shipping_service_name')->distinct()->orderBy('shipping_service_name', 'asc')->pluck('shipping_service_name');

        $list_id = env('MAILCHIMP_LIST_ID');
        if (!isset($list_id)) {
            return redirect()->back()->with('error', 'Mailchimp not configured.');
        }

        $templates = Newsletter::getTemplatesList();
        $tags = Newsletter::getSegmentsList($list_id);

        return view('backend.marketing.add_onsale', compact('categories', 'customer_groups', 'templates', 'tags', 'shipping_services', 'items'));
    }

    public function editOnsaleForm($id){
        $on_sale = DB::table('fumaco_on_sale')->where('id', $id)->first();

        if(!$on_sale){
            return redirect()->back()->with('error', 'Sale ID not found');
        }

        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $shipping_services = DB::table('fumaco_shipping_service')->where('shipping_service_name', '!=', 'Free Delivery')->select('shipping_service_name')->distinct()->orderBy('shipping_service_name', 'asc')->pluck('shipping_service_name');

        $discounted_categories = [];
        if ($on_sale->apply_discount_to == 'Per Category') {
            $discounted_categories = DB::table('fumaco_on_sale_categories as sc')->join('fumaco_categories as c', 'sc.category_id', 'c.id')->where('sc.sale_id', $id)
                ->select('c.id', 'c.name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
        }

        $discounted_customer_group = [];
        if ($on_sale->apply_discount_to == 'Per Customer Group') {
            $discounted_customer_group = DB::table('fumaco_on_sale_customer_group as sc')->join('fumaco_customer_group as c', 'sc.customer_group_id', 'c.id')->where('sc.sale_id', $id)
                ->select('c.id', 'c.customer_group_name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
        }

        $discounted_shipping_services = [];
        if($on_sale->apply_discount_to == 'Per Shipping Service'){
            $discounted_shipping_services = DB::table('fumaco_on_sale_shipping_service as sc')
                ->where('sc.sale_id', $id)->select('sc.shipping_service', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
        }

        $items = [];
        $discounted_selected_items = [];
        if ($on_sale->apply_discount_to == 'Selected Items') {
            $discounted_selected_items_query = DB::table('fumaco_on_sale_items as sc')
                ->join('fumaco_items as i', 'sc.item_code', 'i.f_idcode')
                ->where('sc.sale_id', $id)
                ->select('sc.item_code', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id', 'i.f_name_name')->get();

            $product_list_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', collect($discounted_selected_items_query)->pluck('item_code'))
                ->select('imgprimayx', 'idcode')->get();

            $product_list_images = collect($product_list_images)->groupBy('idcode')->toArray();
    
            foreach($discounted_selected_items_query as $dsiq){
                $image = null;
                if (array_key_exists($dsiq->item_code, $product_list_images)) {
                    $image = $product_list_images[$dsiq->item_code][0]->imgprimayx;
                }

                $image = ($image) ? '/storage/item_images/'. $dsiq->item_code .'/gallery/preview/'. $image : '/storage/no-photo-available.png';

                $discounted_selected_items[] = [
                    'item_code' => $dsiq->item_code,
                    'discount_type' => $dsiq->discount_type,
                    'discount_rate' => $dsiq->discount_rate,
                    'image' => asset($image),
                    'capped_amount' => $dsiq->capped_amount,
                    'sale_id' => $dsiq->sale_id,
                    'description' => strip_tags($dsiq->f_name_name)
                ];
            }
                
            $items = DB::table('fumaco_items')->where('f_status', 1)->whereIn('f_idcode', collect($discounted_selected_items_query)->pluck('item_code'))
                ->select('f_idcode', 'f_item_name', 'f_default_price')->orderBy('f_idcode', 'asc')->get();
        }

        $customer_groups = DB::table('fumaco_customer_group')->get();

        $list_id = env('MAILCHIMP_LIST_ID');
        if (!isset($list_id)) {
            return redirect()->back()->with('error', 'Mailchimp not configured.');
        }

        $templates = Newsletter::getTemplatesList();
        $tags = Newsletter::getSegmentsList($list_id);

        $campaign = $on_sale->mailchimp_campaign_id ? Newsletter::campaignInfo($on_sale->mailchimp_campaign_id) : [];
        
        $selected_tag = $campaign ? $campaign['recipients']['segment_opts']['saved_segment_id'] : null;
        $selected_template = $campaign ? $campaign['settings']['template_id'] : null;

        return view('backend.marketing.edit_onsale', compact('on_sale', 'categories', 'discounted_categories', 'customer_groups', 'discounted_customer_group', 'templates', 'tags', 'selected_template', 'selected_tag', 'shipping_services', 'discounted_shipping_services', 'discounted_selected_items', 'items'));
    }

    public function setOnSaleStatus(Request $request){
        DB::beginTransaction();
        try {
            Cache::forget('has_clearance_sale');

            $sale_details = [];
            DB::table('fumaco_on_sale')->where('id', $request->sale_id)->update(['status' => $request->status]);
            $sale_details = DB::table('fumaco_on_sale')->where('id', $request->sale_id)->select('id', 'apply_discount_to')->first();

            if($request->status == 1 && !in_array($sale_details->apply_discount_to, ['All Items', 'Per Customer Service', 'Per Shipping Service'])){
                $subscribers = DB::table('fumaco_subscribe')->where('status', 1)->pluck('email');
                $categories = [];
                switch ($sale_details->apply_discount_to) {
                    case 'Per Category':
                        $col = 'category_id';
                        $child_tbl = 'fumaco_on_sale_categories';

                        $categories = DB::table('fumaco_categories as cat')->join('fumaco_on_sale_categories as sale', 'cat.id', 'sale.category_id')->where('sale.sale_id', $request->sale_id)->select('sale.*', 'cat.name')->get();
                        
                        break;
                    default:
                        $col = 'item_code';
                        $child_tbl = 'fumaco_on_sale_items';
                        break;
                }

                $sale_details = DB::table('fumaco_on_sale as sale')
                    ->join($child_tbl.' as child', 'child.sale_id', 'sale.id')
                    ->where('sale.id', $request->sale_id)->get();

                $sale_arr_by_item_code = collect($sale_details)->groupBy('item_code');
                $sale_child_arr = collect($sale_details)->pluck($col);

                $cart_arr = DB::table('fumaco_cart as cart')
                    ->join('fumaco_items as item', 'item.f_idcode', 'cart.item_code')
                    ->where('cart.user_type', 'member')->whereIn('cart.user_email', $subscribers)->whereIn('cart.'.$col, $sale_child_arr)
                    ->select('cart.*','item.f_default_price')->get();

                $wish_arr = DB::table('datawishlist as w')
                    ->join('fumaco_users as u', 'u.id', 'w.userid')
                    ->join('fumaco_items as i', 'i.f_idcode', 'w.'.$col)
                    ->whereIn('u.username', $subscribers)->whereIn($col, $sale_child_arr)
                    ->select('u.*', 'w.*', 'i.f_default_price', 'i.f_item_name')->get();

                $item_codes = collect($cart_arr)->pluck('item_code')->merge(collect($wish_arr)->pluck('item_code'));

                $wish_arr = collect($wish_arr)->groupBy('username');
                $cart_arr = collect($cart_arr)->groupBy('user_email');

                $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)->get();
                $item_images = collect($item_images)->groupBy('idcode');

                $customer_details = DB::table('fumaco_users')->whereIn('username', $subscribers)->get();
                $customer_details = collect($customer_details)->groupBy('username');

                foreach ($subscribers as $email) {
                    $type = 'general';
                    $items_array = $items = [];
                    if(isset($cart_arr[$email])){
                        $type = 'cart';
                        $items_array = $cart_arr[$email];
                    }else if(isset($wish_arr[$email])){
                        $type = 'wish';
                        $items_array = $wish_arr[$email];
                    }

                    foreach($items_array as $item){
                        if(isset($sale_arr_by_item_code[$item->item_code])){
                            $discount_details = $sale_arr_by_item_code[$item->item_code][0];
                            switch ($discount_details->discount_type) {
                                case 'By Percentage':
                                    $discount_amount = $item->f_default_price * ($discount_details->discount_rate / 100);
                                    break;
                                default:
                                    $discount_amount = $discount_details->discount_rate;
                                    break;
                            }

                            $discounted_price = $item->f_default_price - $discount_amount;

                            $items[] = [
                                'item_code' => $item->item_code,
                                'name' => $item->item_description,
                                'image' => isset($item_images[$item->item_code]) ? $item_images[$item->item_code][0]->imgoriginalx : null,
                                'original_price' => $item->f_default_price,
                                'discount_type' => $discount_details->discount_type == 'By Percentage' ? 'By Percentage' : 'Fixed Amount',
                                'discount_rate' => $discount_details->discount_rate,
                                'discounted_price' => $discounted_price
                            ];
                        }
                    }
                    $customer = isset($customer_details[$email]) ? $customer_details[$email][0] : [];

                    $sale_array = [
                        'user_account' => $email,
                        'customer_name' => $customer ? $customer->f_name.' '.$customer->f_lname : null,
                        'items' => $items,
                        'type' => $type
                    ];

                    $f_sale_details = collect($sale_details)->first();

                    if(in_array($type, ['cart', 'wish'])){
                        try {
                            Mail::send('emails.multiple_items_on_cart', $sale_array, function($message) use ($email){
                                $message->to(trim($email));
                                $message->subject("Hurry or you might miss out - FUMACO");
                            });
                        } catch (\Swift_TransportException  $e) {}
                    }else{
                        if($f_sale_details->apply_discount_to == 'Per Category'){
                            foreach($categories as $category){
                                $category_arr[] = [
                                    'category_name' => $category->name,
                                    'discount_type' => $category->discount_type,
                                    'discount_rate' => $category->discount_rate
                                ];
                            }

                            // Mail::send('emails.sale_per_category', ['categories' => $category_arr, 'user_account' => $subscriber, 'customer_name' => $customer->f_name.' '.$customer->f_lname], function($message) use($subscriber){
                            //     $message->to(trim($subscriber));
                            //     $message->subject("Hurry or you might miss out - FUMACO");
                            // });
                        }else{
                            // Mail::send('emails.sitewide_sale', ['discount_rate' => $sale_check->discount_rate, 'discount_type' => $sale_check->discount_type, 'user_account' => $subscriber, 'customer_name' => $customer->f_name.' '.$customer->f_lname], function($message) use($subscriber){
                            //     $message->to(trim($subscriber));
                            //     $message->subject("Hurry or you might miss out - FUMACO");
                            // });
                        }
                    }
                }
            }

            DB::commit();

            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editVoucherForm($id){
        $coupon = DB::table('fumaco_voucher')->where('id', $id)->first();
        $exclusive_vouchers = DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $id)->get();
        
        $categories_list = DB::table('fumaco_categories')->where('publish', 1)->whereNull('external_link')->get();
        $selected_categories = collect($exclusive_vouchers)->where('voucher_type', 'Per Category');

        $selected_items = collect($exclusive_vouchers)->where('voucher_type', 'Per Item');
        $item_list = DB::table('fumaco_items')->where('f_status', 1)->get();

        $selected_customer_groups = collect($exclusive_vouchers)->where('voucher_type', 'Per Customer Group');
        $customer_groups = DB::table('fumaco_customer_group')->get();

        return view('backend.marketing.edit_voucher', compact('categories_list', 'coupon', 'selected_categories', 'item_list', 'selected_items', 'customer_groups', 'selected_customer_groups'));
    }

    public function addVoucherForm(){
        $category_list = DB::table('fumaco_categories')->where('publish', 1)->whereNull('external_link')->get();
        $item_list = DB::table('fumaco_items')->where('f_status', 1)->get();

        $customer_group = DB::table('fumaco_customer_group')->get();

        return view('backend.marketing.add_voucher', compact('category_list', 'item_list', 'customer_group'));
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

            if(!strip_tags($request->coupon_description)){
                return redirect()->back()->with('error', 'Coupon description cannot be empty');
            }

            $start = null;
            $end = null;
            if(isset($request->require_validity)){
                $date = explode(' - ', $request->validity);
                $start = Carbon::parse($date[0])->format('Y/m/d');
                $end = Carbon::parse($date[1])->format('Y/m/d');
            }

            $discount_rate = null;
            $capped_amount = null;

            if($request->discount_type == 'By Percentage'){
                $discount_rate = preg_replace("/[^0-9]/", "", $request->discount_percentage);
                $capped_amount = preg_replace("/[^0-9]/", "", $request->capped_amount);
            }else if($request->discount_type == 'Fixed Amount'){
                $discount_rate = preg_replace("/[^0-9]/", "", $request->discount_amount);
            }

            $require_signin = 1;
            if($request->coupon_type == 'Promotional'){
                $require_signin = isset($request->require_signin) ? 1 : 0;
            }

            $insert = [
                'name' => $request->name,
                'code' => strtoupper(str_replace(' ', '', $request->coupon_code)),
                'total_allotment' => isset($request->unlimited_allotment) ? null : $request->allotment,
                'unlimited' => isset($request->unlimited_allotment) ? 1 : 0,
                'allowed_usage' => $request->allowed_usage,
                'minimum_spend' => preg_replace("/[^0-9]/", "", $request->minimum_spend),
                'discount_type' => $request->discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'coupon_type' => $request->coupon_type,
                'description' => $request->coupon_description,
                'require_signin' => $require_signin,
                'order_no' => $request->order_no,
                'auto_apply' => isset($request->auto_apply) ? 1 : 0,
                'validity_date_start' => $start,
                'validity_date_end' => $end,
                'remarks' => $request->remarks,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_voucher')->insert($insert);

            $voucher_id = DB::table('fumaco_voucher')->orderBy('id', 'desc')->pluck('id')->first();

            if($request->coupon_type != 'Promotional'){
                if($request->coupon_type == 'Per Category'){
                    $selected_ids = array_unique($request->selected_category);
                }else if($request->coupon_type == 'Per Item'){
                    $selected_ids = array_unique($request->selected_item);
                }else if($request->coupon_type == 'Per Customer Group'){
                    $selected_ids = array_unique($request->selected_customer_group);
                }

                foreach($selected_ids as $included_id){
                    DB::table('fumaco_voucher_exclusive_to')->insert([
                        'exclusive_to' => $included_id,
                        'allowed_usage' => $request->allowed_usage,
                        'voucher_id' => $voucher_id,
                        'voucher_type' => $request->coupon_type,
                        'created_by' => Auth::user()->username
                    ]);
                }
            }

            DB::commit();
            return redirect('/admin/marketing/voucher/list')->with('success', 'Voucher Added!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editVoucher($id, Request $request){
        DB::beginTransaction();
        try {
            $name_checker = DB::table('fumaco_voucher')->where('name', $request->name)->where('id', '!=', $id)->first();
            $code_checker = DB::table('fumaco_voucher')->where('code', $request->coupon_code)->where('id', '!=', $id)->first();

            if($name_checker or $code_checker){
				return redirect()->back()->with('error', "Voucher Name/Code must be unique.");
            }

            if(!strip_tags($request->coupon_description)){
                return redirect()->back()->with('error', 'Coupon description cannot be empty');
            }

            $start = null;
            $end = null;
            if(isset($request->require_validity)){
                $date = explode(' - ', $request->validity);
                $start = Carbon::parse($date[0])->format('Y/m/d');
                $end = Carbon::parse($date[1])->format('Y/m/d');
            }
            $discount_rate = null;
            $capped_amount = null;

            if($request->discount_type == 'By Percentage'){
                $discount_rate = preg_replace("/[^0-9]/", "", $request->discount_percentage);
                $capped_amount = preg_replace("/[^0-9]/", "", $request->capped_amount);
            }else if($request->discount_type == 'Fixed Amount'){
                $discount_rate = preg_replace("/[^0-9]/", "", $request->discount_amount);
            }

            $require_signin = 1;
            if($request->coupon_type == 'Promotional'){
                $require_signin = isset($request->require_signin) ? 1 : 0;
            }

            $update = [
                'name' => $request->name,
                'code' => strtoupper(str_replace(' ', '', $request->coupon_code)),
                'total_allotment' => isset($request->unlimited_allotment) ? null : $request->allotment,
                'unlimited' => isset($request->unlimited_allotment) ? 1 : 0,
                'allowed_usage' => $request->allowed_usage,
                'minimum_spend' => preg_replace("/[^0-9]/", "", $request->minimum_spend),
                'discount_type' => $request->discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'coupon_type' => $request->coupon_type,
                'description' => $request->coupon_description,
                'require_signin' => $require_signin,
                'order_no' => $request->order_no,
                'auto_apply' => isset($request->auto_apply) ? 1 : 0,
                'validity_date_start' => $start,
                'validity_date_end' => $end,
                'remarks' => $request->remarks,
                'last_modified_by' => Auth::user()->username
            ];

            DB::table('fumaco_voucher')->where('id', $id)->update($update);

            if($request->coupon_type != 'Promotional'){
                if($request->coupon_type == 'Per Category'){
                    $selected_ids = array_unique($request->selected_category);
                }else if($request->coupon_type == 'Per Item'){
                    $selected_ids = array_unique($request->selected_item);
                }else if($request->coupon_type == 'Per Customer Group'){
                    $selected_ids = array_unique($request->selected_customer_group);
                }

                $last_modified_by = null;
                $created_by = Auth::user()->username;

                $checker = DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $id)->first();
                if($checker){
                    $last_modified_by = Auth::user()->username;
                    $created_by = $checker->created_by;

                    DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $id)->delete();
                }

                foreach($selected_ids as $included_id){
                    DB::table('fumaco_voucher_exclusive_to')->insert([
                        'exclusive_to' => $included_id,
                        'allowed_usage' => $request->allowed_usage,
                        'voucher_id' => $id,
                        'voucher_type' => $request->coupon_type,
                        'created_by' => $created_by,
                        'last_modified_by' => $last_modified_by
                    ]);
                }
            }
            
            DB::commit();
            return redirect('/admin/marketing/voucher/list')->with('success', 'Voucher Edited!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addOnSale(Request $request){
        DB::beginTransaction();
        try {
            if($request->selected_customer_group and count($request->selected_customer_group) !== count(array_unique($request->selected_customer_group))){
				return redirect()->back()->with('error', "Cannot select the same customer group twice.");
            }else if($request->selected_category and count($request->selected_category) !== count(array_unique($request->selected_category))){
				return redirect()->back()->with('error', "Cannot select the same category twice.");
            }

            $discount_rate = $discount_type = $capped_amount = null;

            Cache::forget('has_clearance_sale');

            $notif_schedule = $request->notif_schedule ? Carbon::parse($request->notif_schedule)->format('Y-m-d') : null;

            $from = $to = null;
            if (!$request->ignore_sale_duration) {
                $sale_duration = explode(' - ', $request->sale_duration);

                $from = $request->sale_duration ? Carbon::parse($sale_duration[0])->format('Y-m-d') : null;
                $to = $request->sale_duration ? Carbon::parse($sale_duration[1])->format('Y-m-d') : null;

                 // check if date overlaps with other "On Sale"
                $date_check = DB::table('fumaco_on_sale')->where('start_date', '!=', '')->where('end_date', '!=', '')->get();

                $customer_group_date = DB::table('fumaco_customer_group as customer_group')->join('fumaco_on_sale_customer_group as on_sale', 'customer_group.id', 'on_sale.customer_group_id')->get();
                $customer_grp_check = collect($customer_group_date)->groupBy('sale_id');
    
                foreach($date_check as $date){
                    if($from >= $date->start_date and $from <= $date->end_date){
                        if($request->apply_discount_to != 'Per Customer Group'){
                            return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                        }else{ // for customer group sale date
                            if(isset($customer_grp_check[$date->id]) and in_array($customer_grp_check[$date->id][0]->customer_group_id, $request->selected_customer_group)){
                                return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                            }
                        }
                    }
    
                    if($to >= $date->start_date and $to <= $date->end_date){
                        if($request->apply_discount_to != 'Per Customer Group'){
                            return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                        }else{ // for customer group sale date
                            if(isset($customer_grp_check[$date->id]) and in_array($customer_grp_check[$date->id][0]->customer_group_id, $request->selected_customer_group)){
                                return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                            }
                        }
                    }
                }
            }

            if($request->apply_discount_to == 'All Items'){
                $discount_type = $request->discount_type;
                if($discount_type == 'Fixed Amount'){
                    $discount_rate = $request->discount_amount;
                }else if($discount_type == 'By Percentage'){
                    $discount_rate = $request->discount_percentage;
                    $capped_amount = $request->capped_amount;

                    if($discount_rate >= 100){
                        return redirect()->back()->with('error', 'Percentage discount(s) cannot be more than 100%.');
                    }
                }
            }

            $apply_discount_to = $request->sale_type == 'Clearance Sale' ? 'Selected Items' : $request->apply_discount_to;

            $insert = [
                'sale_name' => $request->sale_name,
                'start_date' => $from,
                'end_date' => $to,
                'is_clearance_sale' => $request->sale_type == 'Clearance Sale' ? 1 : 0,
                'notification_schedule' => $notif_schedule,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'discount_for' => $request->discount_for,
                'ignore_sale_duration' => $request->ignore_sale_duration,
                'apply_discount_to' => $apply_discount_to,
                'created_by' => Auth::user()->username
            ];

            // Image upload
            $rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Sorry, your file is too large.");
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $destinationPath = public_path('/assets/site-img/');

            if($request->hasFile('banner_img')){
                $banner_img = $request->file('banner_img');

                $img_name = pathinfo($banner_img->getClientOriginalName(), PATHINFO_FILENAME);
			    $img_ext = pathinfo($banner_img->getClientOriginalName(), PATHINFO_EXTENSION);

                $img_name = Str::slug($img_name, '-');

                $banner_image_name = $img_name.".".$img_ext;

                if(!in_array($img_ext, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_pr = Webp::make($banner_img);

                if($webp_pr->save(public_path('/assets/site-img/'.$img_name.'.webp'))) {
                    $banner_img->move($destinationPath, $banner_image_name);
                }

                $insert['banner_image'] = $banner_image_name;
            }

            // mailchimp
            $list_id = env('MAILCHIMP_LIST_ID');
            if (!isset($list_id)) {
                return redirect()->back()->with('error', 'Mailchimp not configured.');
            }

            $campaign = Newsletter::createCampaign(
                'FUMACO',           // from - name,
                'it@fumaco.com',    // from - email,
                $request->sale_name,// subject,
                '',                 // content - html (would be replaced by email template),
                'subscribers',
                [
                    'settings' => [
                        'title' => $request->sale_name,
                        'subject_line' => $request->sale_name,
                        'from_name' => 'FUMACO',
                        'from_email' => 'it@fumaco.com',
                        'reply_to' => 'it@fumaco.com',
                        'template_id' => (int)$request->email_template,
                    ],
                    'recipients' => [
                        'list_id' => $list_id,
                        'segment_opts' => [
                            'saved_segment_id' => (int)$request->email_tag,
                        ],
                    ],
                ],
            );

            $insert['mailchimp_campaign_id'] = $campaign['id'];

            $id = DB::table('fumaco_on_sale')->insertGetId($insert);

            if(in_array($apply_discount_to, ['Per Shipping Service', 'Per Category', 'Per Customer Group', 'Selected Items'])){
                $item_prices = [];
                if($apply_discount_to == 'Selected Items'){
                    $item_prices = DB::table('fumaco_items')->whereIn('f_idcode', $request->selected_reference)->select('f_idcode', 'f_default_price')->get();
                    $item_prices = collect($item_prices)->groupBy('f_idcode');
                }

                switch ($apply_discount_to) {
                    case 'Per Shipping Service':
                        $reference = 'shipping_service';
                        $table = 'fumaco_on_sale_shipping_service';
                        break;
                    case 'Per Category':
                        $reference = 'category_id';
                        $table = 'fumaco_on_sale_categories';
                        break;
                    case 'Per Customer Group':
                        $reference = 'customer_group_id';
                        $table = 'fumaco_on_sale_customer_group';
                        break;
                    case 'Selected Items':
                        $reference = 'item_code';
                        $table = 'fumaco_on_sale_items';
                        break;
                    default:
                        $reference = $table = null;
                        break;
                }

                $duplicate_ref = array_count_values($request->selected_reference);
                $duplicate_item  = array_filter($request->selected_reference, function ($value) use ($duplicate_ref) {
                    return $duplicate_ref[$value] > 1;
                });

                if ($duplicate_item && count($duplicate_item) > 0) {
                    $duplicate_name = $duplicate_item[0];
                    if ($reference == 'customer_group_id') {
                        $duplicate_name = DB::table('fumaco_customer_group')->where('id', $duplicate_name)->first()->customer_group_name;
                    }

                    if ($reference == 'category_id') {
                        $duplicate_name = DB::table('fumaco_categories')->where('id', $duplicate_name)->first()->name;
                    }

                    if ($reference == 'shipping_service') {
                        $duplicate_name = DB::table('fumaco_shipping_service')->where('shipping_service_id', $duplicate_name)->first()->shipping_service_name;
                    }

                    return redirect()->back()->with('error', $duplicate_name . ' has been entered multiple times.');
                }

                if ($reference == 'customer_group_id') {
                    $existing_ref = DB::table('fumaco_on_sale_customer_group as a')
                        ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                        ->join('fumaco_customer_group as c', 'a.customer_group_id', 'c.id')
                        ->where('b.status', 1)->whereIn('a.customer_group_id', $request->selected_reference)
                        ->first();

                    if ($existing_ref) {
                        return redirect()->back()->with('error', $existing_ref->customer_group_name . ' already exists in ' . $existing_ref->sale_name);
                    }
                }

                if ($reference == 'category_id') {
                    $existing_ref = DB::table('fumaco_on_sale_categories as a')
                        ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                        ->join('fumaco_categories as c', 'a.category_id', 'c.id')
                        ->where('b.status', 1)->whereIn('a.category_id', $request->selected_reference)
                        ->first();

                    if ($existing_ref) {
                        return redirect()->back()->with('error', $existing_ref->name . ' already exists in ' . $existing_ref->sale_name);
                    }
                }

                if ($reference == 'shipping_service') {
                    $existing_ref = DB::table('fumaco_on_sale_shipping_service as a')
                        ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                        ->where('b.status', 1)->whereIn('a.shipping_service', $request->selected_reference)
                        ->first();

                    if ($existing_ref) {
                        return redirect()->back()->with('error', $existing_ref->shipping_service_name . ' already exists in ' . $existing_ref->sale_name);
                    }
                }

                if ($reference == 'item_code') {
                    $existing_ref = DB::table('fumaco_on_sale_items as a')
                        ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                        ->join('fumaco_items as c', 'a.item_code', 'c.f_idcode')
                        ->where('b.status', 1)->whereIn('a.item_code', $request->selected_reference)
                        ->first();

                    if ($existing_ref) {
                        return redirect()->back()->with('error', $existing_ref->item_code . ' already exists in ' . $existing_ref->sale_name);
                    }
                }

                foreach($request->selected_reference as $key => $reference_name){
                    $discount_rate = $capped_amount = 0;

                    if($request->selected_discount_type[$key] == 'By Percentage'){
                        $discount_rate = $request->selected_discount_rate[$key];
                        $capped_amount = $request->selected_capped_amount[$key];

                        if($discount_rate >= 100){
                            return redirect()->back()->with('error', 'Percentage discount(s) cannot be more than 100%.');
                        }
                    }else if($request->selected_discount_type[$key] == 'Fixed Amount'){
                        $discount_rate = $request->selected_discount_rate[$key];

                        $item_price = isset($item_prices[$reference_name]) ? $item_prices[$reference_name][0]->f_default_price: 0;
                        if($apply_discount_to == 'Selected Items'){
                            if($discount_rate >= $item_price){
                                return redirect()->back()->with('error', 'Discount amount cannot be more than the item price.');
                            }
                        }
                    }

                    DB::table($table)->insert([
                        'sale_id' => $id,
                        $reference => $reference_name,
                        'discount_type' => $request->selected_discount_type[$key],
                        'discount_rate' => $discount_rate,
                        'capped_amount' => $capped_amount,
                        'created_by' => Auth::user()->username
                    ]);
                }
            }

            DB::commit();

            return redirect('/admin/marketing/on_sale/list')->with('success', 'On Sale Added.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editOnSale($id, Request $request){
        DB::beginTransaction();
        try {
            Cache::forget('has_clearance_sale');

            switch ($request->apply_discount_to) {
                case 'Per Shipping Service':
                    $table = 'fumaco_on_sale_shipping_service';
                    $reference = 'shipping_service';
                    $arr_key = 'shipping_service';
                    $err = 'shipping service';
                    break;
                case 'Per Category':
                    $table = 'fumaco_on_sale_categories';
                    $reference = 'category_id';
                    $arr_key = 'category';
                    $err = 'category';
                    break;
                case 'Per Customer Group':
                    $table = 'fumaco_on_sale_customer_group';
                    $reference = 'customer_group_id';
                    $arr_key = 'customer_group';
                    $err = 'customer group';
                    break;
                case 'Selected Items':
                    $table = 'fumaco_on_sale_items';
                    $reference = 'item_code';
                    $arr_key = 'item_code';
                    $err = 'item code';
                    break;
                default:
                    $err = null;
                    $table = null;
                    $reference = null;
                    $arr_key = null;
                    break;
            }

            $duplicate_ref = array_count_values($request->selected_reference[$arr_key]);
            $duplicate_item  = array_filter($request->selected_reference[$arr_key], function ($value) use ($duplicate_ref) {
                return $duplicate_ref[$value] > 1;
            });

            if ($duplicate_item && count($duplicate_item) > 0) {
                $duplicate_name = $duplicate_item[0];
                if ($reference == 'customer_group_id') {
                    $duplicate_name = DB::table('fumaco_customer_group')->where('id', $duplicate_name)->first()->customer_group_name;
                }

                if ($reference == 'category_id') {
                    $duplicate_name = DB::table('fumaco_categories')->where('id', $duplicate_name)->first()->name;
                }

                if ($reference == 'shipping_service') {
                    $duplicate_name = DB::table('fumaco_shipping_service')->where('shipping_service_id', $duplicate_name)->first()->shipping_service_name;
                }

                return redirect()->back()->with('error', $duplicate_name . ' has been entered multiple times.');
            }

            if ($reference == 'customer_group_id') {
                $existing_ref = DB::table('fumaco_on_sale_customer_group as a')
                    ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                    ->join('fumaco_customer_group as c', 'a.customer_group_id', 'c.id')
                    ->where('b.status', 1)->whereIn('a.customer_group_id', $request->selected_reference[$arr_key])
                    ->where('a.sale_id', '!=', $id)->first();

                if ($existing_ref) {
                    return redirect()->back()->with('error', $existing_ref->customer_group_name . ' already exists in ' . $existing_ref->sale_name);
                }
            }

            if ($reference == 'category_id') {
                $existing_ref = DB::table('fumaco_on_sale_categories as a')
                    ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                    ->join('fumaco_categories as c', 'a.category_id', 'c.id')
                    ->where('b.status', 1)->whereIn('a.category_id', $request->selected_reference[$arr_key])
                    ->where('a.sale_id', '!=', $id)->first();

                if ($existing_ref) {
                    return redirect()->back()->with('error', $existing_ref->name . ' already exists in ' . $existing_ref->sale_name);
                }
            }

            if ($reference == 'shipping_service') {
                $existing_ref = DB::table('fumaco_on_sale_shipping_service as a')
                    ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                    ->where('b.status', 1)->whereIn('a.shipping_service', $request->selected_reference[$arr_key])
                    ->where('a.sale_id', '!=', $id)->first();

                if ($existing_ref) {
                    return redirect()->back()->with('error', $existing_ref->shipping_service_name . ' already exists in ' . $existing_ref->sale_name);
                }
            }

            if ($reference == 'item_code') {
                $existing_ref = DB::table('fumaco_on_sale_items as a')
                    ->join('fumaco_on_sale as b', 'a.sale_id', 'b.id')
                    ->join('fumaco_items as c', 'a.item_code', 'c.f_idcode')
                    ->where('b.status', 1)->whereIn('a.item_code', $request->selected_reference[$arr_key])
                    ->where('a.sale_id', '!=', $id)->first();

                if ($existing_ref) {
                    return redirect()->back()->with('error', $existing_ref->item_code . ' already exists in ' . $existing_ref->sale_name);
                }
            }

            $selected_reference = $selected_discount_rate = $selected_discount_type = $selected_capped_amount = [];
            if($request->apply_discount_to != 'All Items'){
                if(isset($request->selected_reference[$arr_key]) and count($request->selected_reference[$arr_key]) !== count(array_unique($request->selected_reference[$arr_key]))){
                    return redirect()->back()->with('error', "Cannot select the same ".$err." more than once.");
                }

                $selected_reference = isset($request->selected_reference[$arr_key]) ? $request->selected_reference[$arr_key] : [];
                $selected_discount_rate = isset($request->selected_discount_rate[$arr_key]) ? $request->selected_discount_rate[$arr_key] : [];
                $selected_discount_type = isset($request->selected_discount_type[$arr_key]) ? $request->selected_discount_type[$arr_key] : [];
                $selected_capped_amount = isset($request->selected_capped_amount[$arr_key]) ? $request->selected_capped_amount[$arr_key] : [];
            }

            $discount_rate = $discount_type = $capped_amount = null;

            $notif_schedule = $request->notif_schedule ? date('Y-m-d', strtotime($request->notif_schedule)) : null;

            $from = $to = null;
            if (!$request->ignore_sale_duration) {
                $sale_duration = explode(' - ', $request->sale_duration);

                $from = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[0])) : null;
                $to = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[1])) : null;
                    
                // check if date overlaps with other "On Sale"
                $date_check = DB::table('fumaco_on_sale')->where('id', '!=', $id)->where('start_date', '!=', '')->where('end_date', '!=', '')->get();
                $customer_group_date = DB::table('fumaco_customer_group as customer_group')->join('fumaco_on_sale_customer_group as on_sale', 'customer_group.id', 'on_sale.customer_group_id')->get();
                $customer_grp_check = collect($customer_group_date)->groupBy('sale_id');

                foreach($date_check as $date){
                    if($from >= $date->start_date and $from <= $date->end_date){
                        if($request->apply_discount_to != 'Per Customer Group'){
                            return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                        }else{ // for customer group sale date
                            $customer_group_arr = isset($request->selected_reference['customer_group']) ? $request->selected_reference['customer_group'] : [];
                            if(isset($customer_grp_check[$date->id]) and in_array($customer_grp_check[$date->id][0]->customer_group_id, $customer_group_arr)){
                                return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                            }
                        }
                    }

                    if($to >= $date->start_date and $to <= $date->end_date){
                        if($request->apply_discount_to != 'Per Customer Group'){
                            return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                        }else{ // for customer group sale date
                            $customer_group_arr = isset($request->selected_reference['customer_group']) ? $request->selected_reference['customer_group'] : [];
                            if(isset($customer_grp_check[$date->id]) and in_array($customer_grp_check[$date->id][0]->customer_group_id, $customer_group_arr)){
                                return redirect()->back()->with('error', 'On Sale dates cannot overlap');
                            }
                        }
                    }
                }
            }

            if($request->apply_discount_to == 'All Items'){
                $discount_type = $request->discount_type;
                if($discount_type == 'Fixed Amount'){
                    $discount_rate = $request->discount_amount;
                }else if($discount_type == 'By Percentage'){
                    $discount_rate = $request->discount_percentage;
                    $capped_amount = $request->capped_amount;

                    if($discount_rate >= 100){
                        return redirect()->back()->with('error', 'Percentage discount cannot be more than or equal to 100%');
                    }
                }
            }

            if($request->child_table){
                DB::table($request->child_table)->where('sale_id', $id)->delete();
            }

            $apply_discount_to = $request->sale_type == 'Clearance Sale' ? 'Selected Items' : $request->apply_discount_to;

            $update = [
                'sale_name' => $request->sale_name,
                'start_date' => $from,
                'end_date' => $to,
                'is_clearance_sale' => $request->sale_type == 'Clearance Sale' ? 1 : 0,
                'notification_schedule' => $notif_schedule,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'discount_for' => $request->discount_for,
                'capped_amount' => $capped_amount,
                'ignore_sale_duration' => isset($request->ignore_sale_duration) ? $request->ignore_sale_duration : 0,
                'apply_discount_to' => $apply_discount_to,
                'last_modified_at' => Carbon::now()->toDateTimeString(),
                'last_modified_by' => Auth::user()->username
            ];

            // Image upload
            $rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Sorry, your file is too large.");
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $destinationPath = public_path('/assets/site-img/');

            if($request->hasFile('banner_img')){
                $banner_img = $request->file('banner_img');

                $img_name = pathinfo($banner_img->getClientOriginalName(), PATHINFO_FILENAME);
			    $img_ext = pathinfo($banner_img->getClientOriginalName(), PATHINFO_EXTENSION);

                $img_name = Str::slug($img_name, '-');

                $banner_image_name = $img_name.".".$img_ext;

                if(!in_array($img_ext, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_pr = Webp::make($banner_img);

                if($webp_pr->save(public_path('/assets/site-img/'.$img_name.'.webp'))) {
                    $banner_img->move($destinationPath, $banner_image_name);
                }

                $update['banner_image'] = $banner_image_name;
            }

            $list_id = env('MAILCHIMP_LIST_ID');
            $campaign_id = DB::table('fumaco_on_sale')->where('id', $id)->pluck('mailchimp_campaign_id')->first();

            if($campaign_id){
                Newsletter::editCampaign(
                    $campaign_id,       // Campaign ID
                    'FUMACO',           // from - name,
                    'it@fumaco.com',    // from - email,
                    $request->sale_name,// subject,
                    '',                 // content - html (would be replaced by email template),
                    'subscribers',
                    [
                        'settings' => [
                            'title' => $request->sale_name,
                            'subject_line' => $request->sale_name,
                            'from_name' => 'FUMACO',
                            'from_email' => 'it@fumaco.com',
                            'reply_to' => 'it@fumaco.com',
                            'template_id' => (int)$request->email_template,
                        ],
                        'recipients' => [
                            'list_id' => $list_id,
                            'segment_opts' => [
                                'saved_segment_id' => (int)$request->email_tag,
                            ],
                        ],
                    ],
                );
            }

            DB::table('fumaco_on_sale')->where('id', $id)->update($update);

            if(in_array($apply_discount_to, ['Per Shipping Service', 'Per Category', 'Per Customer Group', 'Selected Items'])){
                $item_prices = [];
                if($apply_discount_to == 'Selected Items'){
                    $items = isset($request->selected_reference['item_code']) ? $request->selected_reference['item_code'] : [];

                    $item_prices = DB::table('fumaco_items')->whereIn('f_idcode', $items)->select('f_idcode', 'f_default_price')->get();
                    $item_prices = collect($item_prices)->groupBy('f_idcode');
                }
                
                foreach($selected_reference as $key => $reference_name){
                    $discount_rate = 0;
                    $capped_amount = 0;

                    if($selected_discount_type[$key] == 'By Percentage'){
                        $discount_rate = $selected_discount_rate[$key];
                        $capped_amount = isset($selected_capped_amount[$key]) ? $selected_capped_amount[$key] : 0;

                        if($discount_rate >= 100){
                            return redirect()->back()->with('error', 'Percentage discount(s) cannot be more than or equal to 100%');
                        }
                    }else if($selected_discount_type[$key] == 'Fixed Amount'){
                        $discount_rate = $selected_discount_rate[$key];

                        $item_price = isset($item_prices[$reference_name]) ? $item_prices[$reference_name][0]->f_default_price : 0;

                        if($apply_discount_to == 'Selected Items'){
                            if($discount_rate >= $item_price){
                                return redirect()->back()->with('error', 'Discount amount cannot be more than the item price.');
                            }
                        }
                    }

                    DB::table($table)->insert([
                        'sale_id' => $id,
                        $reference => $reference_name,
                        'discount_type' => $selected_discount_type[$key],
                        'discount_rate' => $discount_rate,
                        'capped_amount' => $capped_amount,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ]);
                }
            }

            DB::commit();

            return redirect('/admin/marketing/on_sale/list')->with('success', 'On Sale Updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function removeOnSale($id){
        DB::beginTransaction();
        try {
            Cache::forget('has_clearance_sale');

            $image_to_delete = DB::table('fumaco_on_sale')->Where('id', $id)->first();

            if($image_to_delete->banner_image){
                $image_name = explode('.', $image_to_delete->banner_image);
                if(file_exists(public_path('/assets/site-img/'.$image_to_delete->banner_image))){
                    unlink(public_path('/assets/site-img/'.$image_to_delete->banner_image));
                }
    
                if(file_exists(public_path('/assets/site-img/'.$image_name[0].'.webp'))){
                    unlink(public_path('/assets/site-img/'.$image_name[0].'.webp'));
                }
            }            

            DB::table('fumaco_on_sale')->where('id', $id)->delete();
            DB::table('fumaco_on_sale_categories')->where('sale_id', $id)->delete();
            DB::table('fumaco_on_sale_customer_group')->where('sale_id', $id)->delete();
            DB::table('fumaco_on_sale_items')->where('sale_id', $id)->delete();

            DB::commit();
            
            return redirect()->back()->with('success', 'On Sale Deleted.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function removeVoucher($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_voucher')->where('id', $id)->delete();
            DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Coupon Deleted.');
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
        
        $image_query = DB::table('fumaco_items_image_v1')->where('idcode', $details->f_idcode)->get();
        
        $item_image = collect($image_query)->where('promotion_img', 0);

        $img_arr = [];
        foreach($item_image as $img){
            $img_zoom = ($img->imgoriginalx) ? $img->imgoriginalx : null;
            $img_primary = ($img->imgprimayx) ? $img->imgprimayx : null;

            $img_arr[] = [
                'img_id' => $img->id,
                // 'item_code' => $img->idcode,
                'item_name' => $details->f_name_name,
                'primary' => $img_primary,
                'zoom' => $img_zoom 
            ];
        }

        $promo_image = collect($image_query)->where('promotion_img', 1);

        $promo_arr = [];
        if($promo_image){
            foreach($promo_image as $promo){
                $promo_img = $promo->imgoriginalx ? $promo->imgoriginalx : null;
                $promo_arr[] = [
                    'img_id' => $promo->id,
                    // 'item_code' => $promo->idcode,
                    'item_name' => $details->f_name_name,
                    'zoom' => $promo_img 
                ];
            }
        }

        return view('backend.products.images', compact('img_arr', 'promo_arr', 'details'));
    }

    public function deleteProductImage($id, $social = null){
        DB::beginTransaction();
		try{
            $img = DB::table('fumaco_items_image_v1')->where('id', $id)->first();

            $primary_img = explode(".", $img->imgprimayx)[0];
            $original_img = explode(".", $img->imgoriginalx)[0];

            if($social){
                $original = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$img->imgoriginalx);
                $original_webp = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$original_img.'.webp');    
            }else{
                $primary = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/preview/'.$img->imgprimayx);
                $original = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$img->imgoriginalx);

                $primary_webp = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/preview/'.$primary_img.'.webp');
                $original_webp = storage_path('/app/public/item_images/'.$img->idcode.'/gallery/original/'.$original_img.'.webp');

                if (file_exists($primary)) {
                    unlink($primary);
                }

                if (file_exists($primary_webp)) {
                    unlink($primary_webp);
                }
            }

            if (file_exists($original)) {
                unlink($original);
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
			$checker = DB::table('fumaco_items_image_v1')->where('idcode', $request->item_code)->get();

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

            if(!Storage::disk('public')->exists('/item_images/'.$item_code.'/gallery/original/')){
                Storage::disk('public')->makeDirectory('/item_images/'.$item_code.'/gallery/original/');
            }

            if(!Storage::disk('public')->exists('/item_images/'.$item_code.'/gallery/preview/')){
                Storage::disk('public')->makeDirectory('/item_images/'.$item_code.'/gallery/preview/');
            }

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

            if($request->hasFile('promotion_image')){
                $social_image = $request->file('promotion_image');

                $image_name = pathinfo($social_image->getClientOriginalName(), PATHINFO_FILENAME);
			    $image_ext = pathinfo($social_image->getClientOriginalName(), PATHINFO_EXTENSION);

                $image_name = Str::slug($image_name, '-');
                
                $social_image_name = $image_name.".".$image_ext;

                $checker = collect($checker)->where('promotion_img', 1);

                foreach($checker as $c){
                    if($c->imgoriginalx == $social_image_name){
                        $image_error = "Sorry, file already exists.";
                        return redirect()->back()->with('image_error', $image_error);
                    }
                }

                if(!in_array($image_ext, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp = Webp::make($request->file('promotion_image'));

                if(!Storage::disk('public')->exists('/item_images/'.$item_code.'/gallery/social/')){
                    Storage::disk('public')->makeDirectory('/item_images/'.$item_code.'/gallery/social/');
                }

                $destinationPath = storage_path('/app/public/item_images/'.$item_code.'/gallery/social/');

                if ($webp->save(storage_path('/app/public/item_images/'.$item_code.'/gallery/social/'.$image_name.'.webp'))) {
                    $social_image->move($destinationPath, $social_image_name);
                }

                $insert = [
                    'idcode' => $item_code,
                    'img_name' => $social_image_name,
                    'imgoriginalx' => $social_image_name,
                    'promotion_img' => 1,
                    'img_status' => 1,
                    'created_by' => Auth::user()->username
                ];
                
                DB::table('fumaco_items_image_v1')->insert($insert);
            }

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
                ->where('f_idcode', 'not like', $request->parent .'%')
                ->orderBy('f_order_by', 'asc')->get();

            $list = [];
            foreach($query as $row) {
                $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $row->f_idcode)->first();

                $list[] = [
                    'item_code' => $row->f_idcode,
                    'item_description' => $row->f_name_name,
                    'image' => ($item_image) ? $item_image->imgprimayx : null,
                    'original_price' => $row->f_default_price,
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

    public function viewProductsToCompare(Request $request){
        $product_comparison_id = DB::table('product_comparison_attribute')->select('product_comparison_id', 'category_id', 'status')->groupBy('product_comparison_id', 'category_id', 'status')->paginate(10);

        if($request->search){
            $category = DB::table('fumaco_categories')->where('name', 'LIKE', '%'.$request->search.'%')->first();
            $product_comparison_id = DB::table('product_comparison_attribute')->where('category_id', $category->id)->select('product_comparison_id', 'category_id', 'status')->groupBy('product_comparison_id', 'category_id', 'status')->paginate(10);
        }

        $comparison_arr = [];
        foreach($product_comparison_id as $compare){
            $item_codes = DB::table('product_comparison_attribute')->where('product_comparison_id', $compare->product_comparison_id)->select('item_code')->groupBy('item_code')->get();
            $category = DB::table('fumaco_categories')->where('id', $compare->category_id)->first();
            $comparison_arr[] = [
                'comparison_id' => $compare->product_comparison_id,
                'category_name' => $category->name,
                'item_codes' => $item_codes,
                'status' => $compare->status
            ];
        }

        return view('backend.products.list_compare', compact('comparison_arr', 'product_comparison_id'));
    }

    public function statusProductsToCompare(Request $request){
        DB::beginTransaction();
        try {
            DB::table('product_comparison_attribute')->where('product_comparison_id', $request->compare_id)->update([
                'status' => $request->status,
                'last_modified_by' => Auth::user()->username
            ]);
            DB::commit();
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editProductsToCompare($compare_id, Request $request){
        $product_comparison = DB::table('product_comparison_attribute')->where('product_comparison_id', $compare_id)->get();

        $category_id = collect($product_comparison)->pluck('category_id')->first();
        $category = DB::table('fumaco_categories')->where('id', $category_id)->first();

        if($request->selected_items and count($request->selected_items) < 2){
            return redirect()->back()->with('error', 'Please select at least 2 items');
        }

        if($request->selected_items){
            foreach($request->selected_items as $selected_item_code){
                $checker = DB::table('product_comparison_attribute')->where('category_id', $category_id)->where('item_code', $selected_item_code)->where('product_comparison_id', '!=', $compare_id)->exists();
                if($checker == 1){
                    return redirect()->back()->with('error', 'Item Code '.$selected_item_code.' already exists');
                }
            }
        }

        $items = DB::table('fumaco_items')->where('f_category', $category->name)->get();
        $item_codes = $request->selected_items ? $request->selected_items : collect($product_comparison)->unique('item_code')->pluck('item_code');

        $selected_attribute_id = collect($product_comparison)->unique('attribute_name_id')->pluck('attribute_name_id');

        $attribute_query = DB::table('fumaco_attributes_per_category as cat_attrib')->join('fumaco_items_attributes as item_attrib', 'cat_attrib.id', 'item_attrib.attribute_name_id')->where('cat_attrib.category_id', $category->id);

        $attributes_clone = Clone $attribute_query; // list of selected attributes for product comparison
        $attributes = $attributes_clone->whereIn('item_attrib.attribute_name_id', $selected_attribute_id)->groupBy('cat_attrib.id', 'cat_attrib.attribute_name')->select('cat_attrib.id', 'cat_attrib.attribute_name')->get();

        $attribute_names_clone = Clone $attribute_query; // list of attributes based on selected item codes
        $attribute_names = $attribute_names_clone->whereIn('item_attrib.idcode', $item_codes)->whereNotIn('cat_attrib.id', collect($attributes)->pluck('id'))->groupBy('cat_attrib.id', 'cat_attrib.attribute_name')->select('cat_attrib.id', 'cat_attrib.attribute_name')->get();

        return view('backend.products.edit_compare', compact('product_comparison', 'category', 'category_id', 'items', 'item_codes', 'attribute_names', 'attributes'));
    }

    public function addProductsToCompare(Request $request){
        $categories = DB::table('fumaco_categories')->where('publish', 1)->get();

        $items = null;
        $attribute_names = null;
        $selected_category = $request->selected_category;
        if($selected_category){
            $items = DB::table('fumaco_items')->where('f_category', $selected_category)->get();
        }

        if($request->selected_items and count($request->selected_items) < 2){
            return redirect()->back()->with('error', 'Please select at least 2 items');
        }

        if($request->selected_items){
            $category = DB::table('fumaco_categories')->where('name', $selected_category)->first();

            foreach($request->selected_items as $selected_item_code){
                $checker = DB::table('product_comparison_attribute')->where('category_id', $category->id)->where('item_code', $selected_item_code)->exists();
                if($checker == 1){
                    return redirect()->back()->with('error', 'Item Code '.$selected_item_code.' already exists');
                }
            }

            $attribute_names = DB::table('fumaco_attributes_per_category as cat_attrib')->join('fumaco_items_attributes as item_attrib', 'cat_attrib.id', 'item_attrib.attribute_name_id')->where('cat_attrib.category_id', $category->id)/*->whereIn('item_attrib.idcode', $request->selected_items)*/->groupBy('cat_attrib.id', 'cat_attrib.attribute_name')->select('cat_attrib.id', 'cat_attrib.attribute_name')->get();
        }

        return view('backend.products.add_comparison', compact('categories', 'items', 'selected_category', 'attribute_names'));
    }

    public function deleteProductsToCompare($compare_id){
        DB::beginTransaction();
        try {
            DB::table('product_comparison_attribute')->where('product_comparison_id', $compare_id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Product Comparison Deleted.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function saveProductsToCompare(Request $request){
        DB::beginTransaction();
        try {
            if($request->selected_items and count($request->selected_items) < 2){
                return redirect()->back()->with('error', 'Please select at least 2 items');
            }

            $category = DB::table('fumaco_categories')->where('name', $request->selected_category)->first();

            if(isset($request->compare_edit)){ // If editing product comparison
                $save_created_by = DB::table('product_comparison_attribute')->where('product_comparison_id', $request->product_comparison_id)->first();
                DB::table('product_comparison_attribute')->where('product_comparison_id', $request->product_comparison_id)->delete();
                $product_comparison_id = $request->product_comparison_id;
                $created_by = $save_created_by->created_by;
                $status = $save_created_by->status;
                $last_modified_by = Auth::user()->username;
            }else{ // if adding product comparison
                $product_comparison = DB::table('product_comparison_attribute')->orderBy('product_comparison_id', 'desc')->first();
                $product_comparison_id = $product_comparison ? $product_comparison->product_comparison_id + 1 : 1;
                $created_by = Auth::user()->username;
                $last_modified_by = null;
                $status = 0;
            }

            foreach($request->selected_items as $item){
                foreach($request->attribute_names as $attrib){
                    $attrib_value = DB::table('fumaco_items_attributes')->where('attribute_name_id', $attrib)->where('idcode', $item)->first();
                    DB::table('product_comparison_attribute')->insert([
                        'product_comparison_id' => $product_comparison_id,
                        'category_id' => $category ? $category->id : $request->selected_category,
                        'item_code' => $item,
                        'attribute_name_id' => $attrib,
                        'attribute_value' => $attrib_value ? $attrib_value->attribute_value : 'N/A',
                        'status' => $status,
                        'created_by' => $created_by,
                        'last_modified_by' => $last_modified_by
                    ]);
                }

            }
            DB::commit();
            if(isset($request->compare_edit)){
                return redirect('/admin/products/compare/list')->with('success', 'Product Comparison Edited.');
            }else{
                return redirect('/admin/products/compare/add')->with('success', 'Product Comparison Added.');
            }
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    // function to get items from ERP via API
    public function searchWarehouse(Request $request) {
        if($request->ajax()) {
            $erp_api = DB::table('api_setup')->where('type', 'erp_api')->first();
            if (!$erp_api) {
                return response()->json(['status' => 0, 'ERP API not configured.']);
            }

            $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"],["is_group","=","0"],["disabled","=","0"],["stock_warehouse","=","1"],["parent_warehouse","!=","P2 Consignment Warehouse - FI"]]';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'token '. $erp_api->api_key. ':' . $erp_api->api_secret_key . '',
                'Accept-Language' => 'en'
            ])->get($erp_api->base_url . '/api/resource/Warehouse' . ($params));


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

    public function searchWebItems(Request $request) {
        if($request->ajax()) {
            $search_str = explode(' ', $request->q);
            $items = DB::table('fumaco_items')->where('f_status', 1)
                ->when($request->q, function ($query) use ($request, $search_str){
                    return $query->where(function($q) use ($search_str, $request) {
                        foreach ($search_str as $str) {
                            $q->where('f_item_name', 'LIKE', "%".$str."%");
                        }

                        $q->orWhere('f_idcode', 'LIKE', "%".$request->q."%");
                    });
                })
                ->select('f_idcode', 'f_name_name', 'f_default_price')->orderBy('f_idcode', 'asc')
                ->limit(8)->get();

            $product_list_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', collect($items)->pluck('f_idcode'))
                ->select('imgprimayx', 'idcode')->get();

            $product_list_images = collect($product_list_images)->groupBy('idcode')->toArray();
    
            $result = [];
            foreach($items as $item){
                $image = null;
                if (array_key_exists($item->f_idcode, $product_list_images)) {
                    $image = $product_list_images[$item->f_idcode][0]->imgprimayx;
                }

                $image = ($image) ? '/storage/item_images/'. $item->f_idcode .'/gallery/preview/'. $image : '/storage/no-photo-available.png';

                $result[] = [
                    'id' => $item->f_idcode,
                    'text' => $item->f_idcode.' - '.strip_tags($item->f_name_name),
                    'description' => strip_tags($item->f_name_name),
                    'image' => asset($image),
                    'default_price' => $item->f_default_price
                ];
            }
    
            return response()->json(['items' => $result]);
        }

        return response()->json(['items' => $result]);
    }
}