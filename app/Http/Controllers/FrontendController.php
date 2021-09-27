<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FrontendController extends Controller
{
    public function index() {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();


        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)->orderBy('fumaco_active', 'desc')->get();
        

        return view('frontend.homepage', compact('website_settings', 'item_categories', 'carousel_data'));
    }

    public function viewAboutPage() {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $about_data = DB::table('fumaco_about')->first();

        $partners = DB::table('fumaco_about_partners')->where('xstatus', 1)->orderBy('partners_sort', 'asc')->get();

        return view('frontend.about_page', compact('website_settings', 'item_categories', 'about_data', 'partners'));
    }

    public function viewProducts($category_id) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $product_category = DB::table('fumaco_categories')->where('id', $category_id)->first();

        $products = DB::table('fumaco_items')->where('f_cat_id', $category_id)
            ->where('f_status', 1)->orderBy('f_order_by', 'asc')->paginate(15);

        $products_arr = [];
        foreach ($products as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $item_name = strip_tags($product->f_name_name);
            if (strlen($item_name) > 70) {
                // truncate string
                $stringCut = substr($item_name, 0, 70);
                $endPoint = strrpos($stringCut, ' ');
                //if the string doesn't contain any space then it will cut without word basis.
                $item_name = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $item_name .= '...';
            }

            $products_arr[] = [
                'item_code' => $product->f_idcode,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
                'price' => $product->f_original_price,
                'discounted_price' => number_format(str_replace(",","",$product->f_price),2),
                'is_discounted' => $product->f_discount_trigger
            ];
        }

        return view('frontend.product_list', compact('website_settings', 'item_categories', 'product_category', 'products_arr', 'products'));
    }

    public function viewProduct($item_code) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $product_details = DB::table('fumaco_items')->where('f_idcode', $item_code)->first();

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $item_code)->get();

        $attributes = DB::table('fumaco_items_attributes')->where('idcode', $item_code)->orderBy('idx', 'asc')->get();

        return view('frontend.product_page', compact('website_settings', 'item_categories', 'product_details', 'product_images', 'attributes'));
    }
}
