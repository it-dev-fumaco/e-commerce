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
            // ->when($request->on_sale, function($c) use ($request) {
            //     $on_sale_items = DB::table('fumaco_product_prices')->where('on_sale', 1)->pluck('item_code');
            //     $c->whereIn('f_idcode', $on_sale_items);
            // })
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

        $list = [];
        foreach ($product_list as $product) {
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
                'on_sale' => $product->f_onsale,
                'erp_stock' => $product->stock_source,
                'status' => $product->f_status,
                'featured' => $product->f_featured,
                'is_new_item' => $is_new_item,
                'pricelist' => $pricelist,
                'discount_type' => $product->f_discount_type,
                'discount_rate' => $product->f_discount_rate,
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
            $categories_arr = [];
            if($sale->apply_discount_to == 'Per Category'){
                $sale_categories = DB::table('fumaco_on_sale_categories as sc')->join('fumaco_categories as c', 'sc.category_id', 'c.id')->where('sc.sale_id', $sale->id)
                    ->select('c.id', 'c.name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
                
                foreach($sale_categories as $category){
                    $categories_arr[] = [
                        'sale_id' => $category->sale_id,
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'discount_type' => $category->discount_type,
                        'discount_rate' => $category->discount_rate,
                        'capped_amount' => $category->capped_amount
                    ];
                }
            }

            $customer_group_arr = [];
            if($sale->apply_discount_to == 'Per Customer Group'){
                $sale_customer_group = DB::table('fumaco_on_sale_customer_group as sc')->join('fumaco_customer_group as c', 'sc.customer_group_id', 'c.id')->where('sc.sale_id', $sale->id)
                    ->select('c.id', 'c.customer_group_name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
                
                foreach($sale_customer_group as $cg){
                    $customer_group_arr[] = [
                        'sale_id' => $cg->sale_id,
                        'customer_group_id' => $cg->id,
                        'customer_group_name' => $cg->customer_group_name,
                        'discount_type' => $cg->discount_type,
                        'discount_rate' => $cg->discount_rate,
                        'capped_amount' => $cg->capped_amount
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
                'categories' => $categories_arr,
                'sale_duration' => $sale_duration,
                'notification_schedule' => $sale->notification_schedule ? Carbon::parse($sale->notification_schedule)->format('M d, Y') : null,
                'status' => $sale->status,
                'customer_group' => $customer_group_arr
            ];
        }

        return view('backend.marketing.list_sale', compact('on_sale', 'sale_arr'));
    }

    public function addOnsaleForm(){
        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $customer_groups = DB::table('fumaco_customer_group')->get();

        $list_id = env('MAILCHIMP_LIST_ID');

        $templates = Newsletter::getTemplatesList();
        $tags = Newsletter::getSegmentsList($list_id);

        return view('backend.marketing.add_onsale', compact('categories', 'customer_groups', 'templates', 'tags'));
    }

    public function editOnsaleForm($id){
        $on_sale = DB::table('fumaco_on_sale')->where('id', $id)->first();

        $categories = DB::table('fumaco_categories')->where('publish', 1)->where('external_link', null)->get();

        $discounted_categories = [];
        if ($on_sale && $on_sale->apply_discount_to == 'Per Category') {
            $discounted_categories = DB::table('fumaco_on_sale_categories as sc')->join('fumaco_categories as c', 'sc.category_id', 'c.id')->where('sc.sale_id', $id)
                ->select('c.id', 'c.name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
        }

        $discounted_customer_group = [];
        if ($on_sale && $on_sale->apply_discount_to == 'Per Customer Group') {
            $discounted_customer_group = DB::table('fumaco_on_sale_customer_group as sc')->join('fumaco_customer_group as c', 'sc.customer_group_id', 'c.id')->where('sc.sale_id', $id)
                ->select('c.id', 'c.customer_group_name', 'sc.discount_type', 'sc.discount_rate', 'sc.capped_amount', 'sc.sale_id')->get();
        }

        $customer_groups = DB::table('fumaco_customer_group')->get();

        $list_id = env('MAILCHIMP_LIST_ID');

        $templates = Newsletter::getTemplatesList();
        $tags = Newsletter::getSegmentsList($list_id);

        $campaign = $on_sale->mailchimp_campaign_id ? Newsletter::campaignInfo($on_sale->mailchimp_campaign_id) : [];
        
        $selected_tag = $campaign ? $campaign['recipients']['segment_opts']['saved_segment_id'] : null;
        $selected_template = $campaign ? $campaign['settings']['template_id'] : null;

        return view('backend.marketing.edit_onsale', compact('on_sale', 'categories', 'discounted_categories', 'customer_groups', 'discounted_customer_group', 'templates', 'tags', 'selected_template', 'selected_tag'));
    }

    public function setOnSaleStatus(Request $request){
        DB::beginTransaction();
        try {
            $sale_details = [];
            DB::table('fumaco_on_sale')->where('id', $request->sale_id)->update(['status' => $request->status]);
            if($request->status == 1){
                $sale_check = DB::table('fumaco_on_sale')->where('id', $request->sale_id)->first();

                $subscribers = DB::table('fumaco_subscribe')->where('status', 1)->select('email')->pluck('email');
                $categories = DB::table('fumaco_categories as cat')->join('fumaco_on_sale_categories as sale', 'cat.id', 'sale.category_id')->where('sale.sale_id', $request->sale_id)->select('sale.*', 'cat.name')->get();

                $items_on_sale = DB::table('fumaco_items')->whereIn('f_cat_id', collect($categories)->pluck('category_id'))->pluck('f_idcode');

                foreach($subscribers as $subscriber){
                    $items = [];
                    $cart_items = [];
                    $wish_items = [];
                    $category_arr = [];
                    $customer = DB::table('fumaco_users')->where('username', $subscriber)->select('id', 'f_name', 'f_lname')->first();

                    $discount_rate = null;
                    $discount_type = null;
                    $type = null;
                    
                    if($customer){
                        if($categories){
                            $cart_check = DB::table('fumaco_cart as cart')->join('fumaco_items as items', 'items.f_idcode', 'cart.item_code')->where('items.f_onsale', 0)->whereIn('cart.category_id', collect($categories)->pluck('category_id'))->where('cart.user_email', $subscriber)->exists();
                            $wish_check = DB::table('datawishlist as wish')->join('fumaco_items as items', 'items.f_idcode', 'wish.item_code')->where('items.f_onsale', 0)->whereIn('wish.category_id', collect($categories)->pluck('category_id'))->where('userid', $customer->id)->exists();
                        }else{
                            $cart_check = DB::table('fumaco_cart as cart')->join('fumaco_items as items', 'items.f_idcode', 'cart.item_code')->where('f_onsale', 0)->where('cart.user_email', $subscriber)->exists();
                            $wish_check = DB::table('datawishlist as wish')->join('fumaco_items as items', 'items.f_idcode', 'wish.item_code')->where('items.f_onsale', 0)->where('wish.userid', $customer->id)->exists();
                        }
    
                        if($cart_check){
                            $type = 'cart';
                            if($sale_check->apply_discount_to == 'Per Category'){
                                $cart_items = DB::table('fumaco_cart as cart')->join('fumaco_items as items', 'cart.item_code', 'items.f_idcode')->where('cart.user_email', $subscriber)->where('items.f_onsale', 0)->whereIn('items.f_cat_id', collect($categories)->pluck('category_id'))->select('cart.*', 'items.f_default_price', 'items.f_name_name')->get();
        
                                foreach($cart_items as $item){
                                    $price = $item->f_default_price;
                                    
                                    $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->item_code)->pluck('imgprimayx')->first();
                                    $cat_id = DB::table('fumaco_items')->where('f_idcode', $item->item_code)->pluck('f_cat_id')->first();
    
                                    $discount_type = collect($categories)->where('category_id', $cat_id)->pluck('discount_type')->first();
                                    $discount_rate = collect($categories)->where('category_id', $cat_id)->pluck('discount_rate')->first();
    
                                    if($discount_type == 'By Percentage'){
                                        $price = $item->f_default_price - ($item->f_default_price * ($discount_rate/100));
                                    }else if ($discount_type == 'Fixed Amount'){
                                        if($discount_rate < $price){
                                            $price = $item->f_default_price - $discount_rate;
                                        }else{
                                            $type = 'general';
                                        }
                                    }
            
                                    $items[] = [
                                        'item_code' => $item->item_code,
                                        'name' => $item->f_name_name,
                                        'image' => $image,
                                        'original_price' => $item->f_default_price,
                                        'discount_type' => $discount_type,
                                        'discount_rate' => $discount_rate,
                                        'discounted_price' => $price
                                    ];
                                }
                            }else{
                                $cart_items = DB::table('fumaco_cart as cart')->join('fumaco_items as items', 'cart.item_code', 'items.f_idcode')->where('cart.user_email', $subscriber)->where('items.f_onsale', 0)->select('cart.*', 'items.f_default_price')->get();
        
                                foreach($cart_items as $item){
                                    $price = $item->f_default_price;
                                    $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->item_code)->pluck('imgprimayx')->first();
        
                                    $discount_type = $sale_check->discount_type;
                                    $discount_rate = $sale_check->discount_rate;
    
                                    if($discount_type == 'By Percentage'){
                                        $price = $item->f_default_price - ($item->f_default_price * ($discount_rate/100));
                                    }else if ($sale_check->discount_type == 'Fixed Amount'){
                                        if($discount_rate < $price){
                                            $price = $item->f_default_price - $discount_rate;
                                        }else{
                                            $type = 'general';
                                        }
                                    }
            
                                    $items[] = [
                                        'item_code' => $item->item_code,
                                        'name' => $item->item_description,
                                        'image' => $image,
                                        'original_price' => $item->f_default_price,
                                        'discount_type' => $discount_type,
                                        'discount_rate' => $discount_rate,
                                        'discounted_price' => $price
                                    ];
                                }
                            }
                        }else if($wish_check){
                            $type = 'wishlist';
                            if($sale_check->apply_discount_to == 'Per Category'){
                                $wish_items = DB::table('datawishlist as wish')->join('fumaco_items as items', 'wish.item_code', 'items.f_idcode')->where('wish.userid', $customer->id)->where('items.f_onsale', 0)->select('wish.*', 'items.f_name_name', 'items.f_default_price')->get();
    
                                foreach($wish_items as $item){
                                    $price = $item->f_default_price;
                                    $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->item_code)->pluck('imgprimayx')->first();
                                    $cat_id = DB::table('fumaco_items')->where('f_idcode', $item->item_code)->pluck('f_cat_id')->first();
        
                                    $discount_type = collect($categories)->where('category_id', $cat_id)->pluck('discount_type')->first();
                                    $discount_rate = collect($categories)->where('category_id', $cat_id)->pluck('discount_rate')->first();
                                    if($discount_type == 'By Percentage'){
                                        $price = $item->f_default_price - ($item->f_default_price * ($discount_rate/100));
                                    }else if ($discount_type == 'Fixed Amount'){
                                        if($discount_rate < $price){
                                            $price = $item->f_default_price - $discount_rate;
                                        }else{
                                            $type = 'general';
                                        }
                                    }
            
                                    $items[] = [
                                        'item_code' => $item->item_code,
                                        'name' => $item->f_name_name,
                                        'image' => $image,
                                        'original_price' => $item->f_default_price,
                                        'discount_type' => $discount_type,
                                        'discount_rate' => $discount_rate,
                                        'discounted_price' => $price
                                    ];
                                }
                            }else{
                                $wish_items = DB::table('datawishlist as wish')->join('fumaco_items as items', 'wish.item_code', 'items.f_idcode')->where('wish.userid', $customer->id)->where('items.f_onsale', 0)->select('wish.*', 'items.f_name_name', 'items.f_default_price')->get();
    
                                foreach($wish_items as $item){
                                    $price = $item->f_default_price;
                                    $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->item_code)->pluck('imgprimayx')->first();
        
                                    $discount_type = $sale_check->discount_type;
                                    $discount_rate = $sale_check->discount_rate;
    
                                    if($discount_type == 'By Percentage'){
                                        $price = $item->f_default_price - ($item->f_default_price * ($discount_rate/100));
                                    }else if ($sale_check->discount_type == 'Fixed Amount'){
                                        if($discount_rate < $price){
                                            $price = $item->f_default_price - $discount_rate;
                                        }else{
                                            $type = 'general';
                                        }
                                    }
            
                                    $items[] = [
                                        'item_code' => $item->item_code,
                                        'name' => $item->f_name_name,
                                        'image' => $image,
                                        'original_price' => $item->f_default_price,
                                        'discount_type' => $discount_type,
                                        'discount_rate' => $discount_rate,
                                        'discounted_price' => $price
                                    ];
                                }
                            }
                        }else{ // Subscriber has no items listed on cart and wishlist
                            $type = 'general';
                        }

                        $sale_details = [
                            'user_account' => $subscriber,
                            'customer_name' => $customer->f_name.' '.$customer->f_lname,
                            'items' => $items,
                            'type' => $type
                        ];
    
                        if($type == 'cart' or $type == 'wishlist'){
                            Mail::send('emails.multiple_items_on_cart', $sale_details, function($message) use($subscriber){
                                $message->to(trim($subscriber));
                                $message->subject("Hurry or you might miss out - FUMACO");
                            });
                        }else if($type == 'general'){
                            if($sale_check->apply_discount_to == 'Per Category'){
                                
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
                $capped_amount = $request->capped_amount;
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
                'minimum_spend' => $request->minimum_spend,
                'discount_type' => $request->discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'coupon_type' => $request->coupon_type,
                'description' => $request->coupon_description,
                'require_signin' => $require_signin,
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
                $capped_amount = $request->capped_amount;
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
                'minimum_spend' => $request->minimum_spend,
                'discount_type' => $request->discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'coupon_type' => $request->coupon_type,
                'description' => $request->coupon_description,
                'require_signin' => $require_signin,
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

            $discount_rate = null;
            $discount_type = null;
            $capped_amount = null;

            $sale_duration = explode(' - ', $request->sale_duration);

            $from = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[0])) : null;
            $to = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[1])) : null;
            $notif_schedule = $request->notif_schedule ? date('Y-m-d', strtotime($request->notif_schedule)) : null;

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

            if($request->apply_discount_to == 'All Items'){
                $discount_type = $request->discount_type;
                if($discount_type == 'Fixed Amount'){
                    $discount_rate = $request->discount_amount;
                }else if($discount_type == 'By Percentage'){
                    $discount_rate = $request->discount_percentage;
                    $capped_amount = $request->capped_amount;
                }
            }

            $insert = [
                'sale_name' => $request->sale_name,
                'start_date' => $from,
                'end_date' => $to,
                'notification_schedule' => $notif_schedule,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'capped_amount' => $capped_amount,
                'discount_for' => $request->discount_for,
                'apply_discount_to' => $request->apply_discount_to,
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
            $campaign = Newsletter::createCampaign(
                'FUMACO', // from - name,
                'it@fumaco.com', // from - email,
                $request->sale_name, // subject,
                '', // content - html (would be replaced by email template),
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

            DB::table('fumaco_on_sale')->insert($insert);

            if($request->apply_discount_to == 'Per Category'){
                $sale_id = DB::table('fumaco_on_sale')->orderBy('id', 'desc')->first();
                foreach($request->selected_category as $key => $category){
                    $category_discount_rate = 0;
                    $category_capped_amount = 0;

                    if($request->selected_discount_type[$key] == 'By Percentage'){
                        $category_discount_rate = $request->category_discount_rate[$key];
                        $category_capped_amount = $request->category_capped_amount[$key];
                    }else if($request->selected_discount_type[$key] == 'Fixed Amount'){
                        $category_discount_rate = $request->category_discount_rate[$key];
                    }

                    DB::table('fumaco_on_sale_categories')->insert([
                        'sale_id' => $sale_id->id,
                        'category_id' => $category,
                        'discount_type' => $request->selected_discount_type[$key],
                        'discount_rate' => $category_discount_rate,
                        'capped_amount' => $category_capped_amount,
                        'created_by' => Auth::user()->username
                    ]);
                }
            }

            if($request->apply_discount_to == 'Per Customer Group'){
                $sale_id = DB::table('fumaco_on_sale')->orderBy('id', 'desc')->first();
                foreach($request->selected_customer_group as $key => $customer_group){
                    $customer_group_discount_rate = 0;
                    $customer_group_capped_amount = 0;

                    if($request->selected_discount_type[$key] == 'By Percentage'){
                        $customer_group_discount_rate = $request->customer_group_discount_rate[$key];
                        $customer_group_capped_amount = $request->customer_group_capped_amount[$key];
                    }else if($request->selected_discount_type[$key] == 'Fixed Amount'){
                        $customer_group_discount_rate = $request->customer_group_discount_rate[$key];
                    }

                    DB::table('fumaco_on_sale_customer_group')->insert([
                        'sale_id' => $sale_id->id,
                        'customer_group_id' => $customer_group,
                        'discount_type' => $request->selected_discount_type[$key],
                        'discount_rate' => $customer_group_discount_rate,
                        'capped_amount' => $customer_group_capped_amount,
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
            if($request->selected_customer_group and count($request->selected_customer_group) !== count(array_unique($request->selected_customer_group))){
				return redirect()->back()->with('error', "Cannot select the same customer group twice.");
            }else if($request->selected_category and count($request->selected_category) !== count(array_unique($request->selected_category))){
				return redirect()->back()->with('error', "Cannot select the same category twice.");
            }

            $discount_rate = null;
            $discount_type = null;
            $capped_amount = null;

            $sale_duration = explode(' - ', $request->sale_duration);

            $from = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[0])) : null;
            $to = $request->sale_duration ? date('Y-m-d', strtotime($sale_duration[1])) : null;
            $notif_schedule = $request->notif_schedule ? date('Y-m-d', strtotime($request->notif_schedule)) : null;

            // check if date overlaps with other "On Sale"
            $date_check = DB::table('fumaco_on_sale')->where('id', '!=', $id)->where('start_date', '!=', '')->where('end_date', '!=', '')->get();
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

            if($request->apply_discount_to == 'All Items'){
                $discount_type = $request->discount_type;
                if($discount_type == 'Fixed Amount'){
                    $discount_rate = $request->discount_amount;
                }else if($discount_type == 'By Percentage'){
                    $discount_rate = $request->discount_percentage;
                    $capped_amount = $request->capped_amount;
                }
            }

            if($request->apply_discount_to != 'Per Customer Group'){ // if sale is not per customer group
                DB::table('fumaco_on_sale_customer_group')->where('sale_id', $id)->delete();
            }
            
            if($request->apply_discount_to != 'Per Category'){ // if sale is not per category
                DB::table('fumaco_on_sale_categories')->where('sale_id', $id)->delete();
            }

            $update = [
                'sale_name' => $request->sale_name,
                'start_date' => $from,
                'end_date' => $to,
                'notification_schedule' => $notif_schedule,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'discount_for' => $request->discount_for,
                'capped_amount' => $capped_amount,
                'apply_discount_to' => $request->apply_discount_to,
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
                    $campaign_id, // Campaign ID
                    'FUMACO', // from - name,
                    'it@fumaco.com', // from - email,
                    $request->sale_name, // subject,
                    '', // content - html (would be replaced by email template),
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

            if($request->apply_discount_to == 'Per Category'){
                $last_modified_by = null;
                $checker = DB::table('fumaco_on_sale_categories')->where('sale_id', $id)->count();

                if($checker > 0){
                    $last_modified_by = Auth::user()->username;
                }
                DB::table('fumaco_on_sale_categories')->where('sale_id', $id)->delete();
                foreach($request->selected_category as $key => $category){
                    $category_discount_rate = 0;
                    $category_capped_amount = 0;

                    if($request->selected_discount_type[$key] == 'By Percentage'){
                        $category_discount_rate = $request->category_discount_rate[$key];
                        $category_capped_amount = $request->category_capped_amount[$key];
                    }else if($request->selected_discount_type[$key] == 'Fixed Amount'){
                        $category_discount_rate = $request->category_discount_rate[$key];
                    }

                    DB::table('fumaco_on_sale_categories')->insert([
                        'sale_id' => $id,
                        'category_id' => $category,
                        'discount_type' => $request->selected_discount_type[$key],
                        'discount_rate' => $category_discount_rate,
                        'capped_amount' => $category_capped_amount,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => $last_modified_by
                    ]);
                }
            }

            if($request->apply_discount_to == 'Per Customer Group'){
                $last_modified_by = null;
                $checker = DB::table('fumaco_on_sale_customer_group')->where('sale_id', $id)->count();

                if($checker > 0){
                    $last_modified_by = Auth::user()->username;
                }
                DB::table('fumaco_on_sale_customer_group')->where('sale_id', $id)->delete();
                foreach($request->selected_customer_group as $key => $customer_group){
                    $customer_group_discount_rate = 0;
                    $customer_group_capped_amount = 0;

                    if($request->selected_discount_type[$key] == 'By Percentage'){
                        $customer_group_discount_rate = $request->customer_group_discount_rate[$key];
                        $customer_group_capped_amount = $request->customer_group_capped_amount[$key];
                    }else if($request->selected_discount_type[$key] == 'Fixed Amount'){
                        $customer_group_discount_rate = $request->customer_group_discount_rate[$key];
                    }

                    DB::table('fumaco_on_sale_customer_group')->insert([
                        'sale_id' => $id,
                        'customer_group_id' => $customer_group,
                        'discount_type' => $request->selected_discount_type[$key],
                        'discount_rate' => $customer_group_discount_rate,
                        'capped_amount' => $customer_group_capped_amount,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => $last_modified_by
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

    public function removeOnSale($id){
        DB::beginTransaction();
        try {
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

    public function setProductOnSale($item_code, Request $request) {
        DB::beginTransaction();
        try {
            $item = DB::table('fumaco_items')->where('f_idcode', $item_code)->first();
            if (!$item) {
                return redirect()->back()->with('error', 'Product not found.');
            }

            $discount_rate = $request->discount_rate;
            if (!$discount_rate && $discount_rate <= 0) {
                return redirect()->back()->with('error', 'Discount rate cannot be less than or equal to zero.');
            }
            
            $customer_group = DB::table('fumaco_customer_group')->where('id', $request->customer_group)->first()->customer_group_name;
            if ($customer_group == 'Individual') {
                DB::table('fumaco_items')->where('f_idcode', $item_code)->update([
                    'f_discount_type' => $request->discount_type,
                    'f_onsale' => 1,
                    'f_discount_rate' => $discount_rate,
                    'last_modified_by' => Auth::user()->username,
                ]);
            } else {
                DB::table('fumaco_product_prices')->where('id', $request->price_list_id)->update([
                    'discount_type' => $request->discount_type,
                    'on_sale' => 1,
                    'discount_rate' => $discount_rate,
                    'last_modified_by' => Auth::user()->username,
                ]);
            }
            
            // $success_msg = 'Product has been set "On Sale".';

            // $subscribers = DB::table('fumaco_subscribe')->where('status', 1)->select('email')->pluck('email');

            // foreach($subscribers as $subscriber){
            //     $customer = DB::table('fumaco_users')->where('username', $subscriber)->select('id', 'f_name', 'f_lname')->first();
            //     $image = DB::table('fumaco_items_image_v1')->where('idcode', $item_code)->pluck('imgprimayx')->first();

            //     $cart_check = DB::table('fumaco_cart')->where('user_email', $subscriber)->where('item_code', $item_code)->first();
            //     $wish_check = DB::table('datawishlist')->where('userid', $customer->id)->where('item_code', $item_code)->first();

            //     $name = $customer->f_name.' '.$customer->f_lname;

            //     $sale_details = [
            //         'item_code' => $item_code,
            //         'image' => $image,
            //         'percentage' => $request->discount_percentage,
            //         'customer_name' => $name,
            //         'item_details' => $item->f_name_name,
            //         'original_price' => $item->f_original_price,
            //         'discounted_price' => $discounted_price,
            //         'email' => $subscriber,
            //         'type' => $cart_check ? 'cart' : 'wishlist',
            //         'multiple_items' => 0
            //     ];

            //     if($cart_check or $wish_check){
            //         Mail::send('emails.items_on_cart_sale', $sale_details, function($message) use($subscriber){
            //             $message->to(trim($subscriber));
            //             $message->subject("Hurry or you might miss out - FUMACO");
            //         });
            //     }else{ // Subscriber does not have items listed on cart and wishlist
            //         Mail::send('emails.sale_per_item', $sale_details, function($message) use($subscriber){
            //             $message->to(trim($subscriber));
            //             $message->subject("Hurry or you might miss out - FUMACO");
            //         });
            //     }
            // }

            DB::commit();

            return redirect()->back()->with('success', 'Product has been set "On Sale".');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function disableProductOnSale($item_code, Request $request) {
        DB::beginTransaction();
        try {
            $selected_pricelists = $request->price_list;
            if (in_array('Website Price List', $selected_pricelists)) {
                DB::table('fumaco_items')->where('f_idcode', $item_code)->update([
                    'f_onsale' => 0,
                    'f_discount_rate' => 0,
                    'f_discount_type' => null,
                    'last_modified_by' => Auth::user()->username
                ]);
            }

            if (($key = array_search("Website Price List", $selected_pricelists)) !== false) {
                unset($selected_pricelists[$key]);
            }
    
            foreach ($selected_pricelists as $price_list_id) {
                DB::table('fumaco_product_prices')->where('id', $price_list_id)->update([
                    'on_sale' => 0,
                    'discount_rate' => 0,
                    'discount_type' => null,
                    'last_modified_by' => Auth::user()->username
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Product code <b>' . $item_code . '</b> has been updated.');
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
}