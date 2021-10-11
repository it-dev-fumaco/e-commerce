<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
    
            $params = '?filters=[["name","LIKE","%25' . $request->q . '%25"],["custom_show_in_website","=","1"],["has_variants","=","0"]]';
    
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
            if(!$item_category) {
                return redirect()->back()->withInput($request->all())->with('error', 'Please select product category.');
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
                        'slug' => Str::slug($attr['attribute'], '-')
                    ]);
                }
                // get attribute name id
                $attr_name_id = ($existing_attribute) ? $existing_attribute->id : $attr_id;

                $item_attr[] = [
                    'idx' => $attr['idx'],
                    'idcode' => $attr['parent'],
                    'attribute_name_id' => $attr_name_id,
                    'attribute_value' => $attr['attribute_value'],
                ];
            }

            // insert brand
            $existing_brand = DB::table('fumaco_brands')->where('brandname', $item['brand'])->exists();
            if(!$existing_brand) {
                DB::table('fumaco_brands')->insert(['brandname' => $item['brand'], 'slug' => Str::slug($item['brand'], '-')]);
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

            DB::table('fumaco_items')->where('id', $id)->update([
                'f_name_name' => $request->product_name,
                'f_cat_id' => $request->product_category,
                'f_category' => $item_category,
                'f_alert_qty' => $request->alert_qty,
                'f_caption' => $request->website_caption,
                'f_full_description' => $request->full_detail,
                'f_status' => ($request->is_disabled) ? 0 : 1
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
                            'slug' => Str::slug($attr->attribute_name, '-')
                        ]);
                    }
                    // get attribute name id
                    $attr_name_id = ($existing_attribute) ? $existing_attribute->id : $attr_id;
                    DB::table('fumaco_items_attributes')->where('id', $attr->id)->update(
                        [
                            'attribute_name_id' => $attr_name_id,
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
        $product_list = DB::table('fumaco_items')
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

        $list = [];
        foreach ($product_list as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $item_name = strip_tags($product->f_name_name);
            $list[] = [
                'id' => $product->id,
                'product_code' => $product->f_parent_code,
                'item_code' => $product->f_idcode,
                'product_name' => $product->f_name_name,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
                'price' => $product->f_original_price,
                'qty' => $product->f_qty,
                'reserved_qty' => $product->f_reserved_qty,
                'product_category' => $product->f_category,
                'brand' => $product->f_brand,
                'on_sale' => $product->f_onsale,
                'status' => $product->f_status,
            ];
        }

        return view('backend.products.list', compact('list', 'product_list'));
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
        
        return view('backend.products.view', compact('details', 'item_categories', 'attributes', 'item_image'));
    }

    public function viewParentCodeList(Request $request) {
        $list = DB::table('fumaco_items')->whereNotNull('f_parent_code')
            ->select('f_parent_code', 'f_parent_item_name')->groupBy('f_parent_code', 'f_parent_item_name')->paginate(10);

        $attributes = [];
        $parent = $request->parent;
        if(isset($parent)) {
            $variant_codes = DB::table('fumaco_items')
                ->where('f_parent_code', $parent)->pluck('f_idcode');

            $attributes = DB::table('fumaco_items_attributes')
                ->whereIn('idcode', $variant_codes)->select('attribute_name', 'attribute_status')
                ->orderBy('idx', 'asc')->groupBy('attribute_name', 'attribute_status')->get();
        }

        return view('backend.products.parent_code_list', compact('list', 'attributes'));
    }

    // update parent variant attribute status
    public function updateProductAttribute($parent, Request $request) {
        DB::beginTransaction();
        try {
            $attr_names = $request->attribute_name;
            $status = $request->show_in_website;
            foreach($attr_names as $i => $attr_name) {
                $variant_codes = DB::table('fumaco_items')
                    ->where('f_parent_code', $parent)->pluck('f_idcode');

                $attributes = DB::table('fumaco_items_attributes')
                    ->whereIn('idcode', $variant_codes)->where('attribute_name', $attr_name)
                    ->update(['attribute_status' => $status[$i]]);
            }

            DB::commit();

            return redirect()->back()->with('attr_success', 'Product Attribute has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('attr_error', 'An error occured. Please try again.');
        }
        return $request->all();
    }

    public function uploadImagesForm($id){
        $details = DB::table('fumaco_items')->where('id', $id)->first();
        
        $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $details->f_idcode)->get();

        $img_arr = [];
        foreach($item_image as $img){
            $img_zoom = ($img->imgoriginalx) ? $img->imgoriginalx : 'test.jpg';
            $img_primary = ($img->imgprimayx) ? $img->imgprimayx : 'test.jpg';

            $img_arr[] = [
                'img_id' => $img->id,
                'item_code' => $img->idcode,
                'item_name' => $details->f_name_name,
                'primary' => $img_primary,
                'zoom' => $img_zoom 
            ];
        }

        return view('backend.products.images', compact('img_arr'));
    }

    public function deleteProductImage(Request $request){
        DB::beginTransaction();
		try{
            DB::table('fumaco_items_image_v1')->where('id', $request->img_id)->delete();
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

			$img_primary = $request->file('img_primary'); //400
			$img_zoom = $request->file('img_zoom');//1024

			$p_filename = pathinfo($img_primary->getClientOriginalName(), PATHINFO_FILENAME);//400
			$z_filename = pathinfo($img_zoom->getClientOriginalName(), PATHINFO_FILENAME);//1024
			$p_extension = pathinfo($img_primary->getClientOriginalName(), PATHINFO_EXTENSION);//400
			$z_extension = pathinfo($img_zoom->getClientOriginalName(), PATHINFO_EXTENSION);//1024

			$request->file('img_zoom')->store('/item/images/'.$request->item_code.'/gallery/original');//1024
			$request->file('img_primary')->store('/item/images/'.$request->item_code.'/gallery/preview');//400

			$p_name = "(400p) " .$p_filename.".".$p_extension;//400
			$z_name = "(1024p) " .$z_filename.".".$z_extension;//1024

            // dd($p_name. " : ".$z_name);

			$image_error = '';
			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

			if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

			if($p_extension != "jpg" and $p_extension != "png" and $p_extension != "jpeg" and $p_extension != "gif"){
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

			$z_destinationPath = public_path('/item/images/'.$request->item_code.'/gallery/original');
			$p_destinationPath = public_path('/item/images/'.$request->item_code.'/gallery/preview');
			$img_primary->move($p_destinationPath, $p_name);
			// $img_primary->move($z_destinationPath, $z_name);
			$img_zoom->move($z_destinationPath, $z_name);

			$images_arr[] = [
				'idcode' => $request->item_code,
                'imgnum' => " ",
                'img_name' => " ",
                'imgprimayx' => $p_name,
                'imgoriginalx' => $z_name,
                'img_status' => 1
			];

            DB::table('fumaco_items_image_v1')->insert($images_arr);
            DB::commit();
			return redirect()->back()->with('success', 'Media Successfully Added');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('image_error', 'Error');
		}

    }
}