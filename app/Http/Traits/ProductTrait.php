<?php

namespace App\Http\Traits;

use Auth;
use DB;
use Carbon\Carbon;

trait ProductTrait {
    public function getItemPriceAndDiscount($item_on_sale, $category, $sale, $item_price, $item_code, $discount_type, $discount_rate) {
        // set discounted price based on sale details
        $discount_display = ($discount_type == 'percentage') ? ($discount_rate . '% OFF') : '₱' . number_format($discount_rate, 2, '.', ',') . ' OFF';
        $discount = ($discount_type == 'percentage') ? ($item_price * ($discount_rate/100)) : $discount_rate;
        if (!$item_on_sale) {
            if (!$sale) {
          
                $sale = DB::table('fumaco_on_sale as sale')
                    ->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')
                    ->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())
                    ->where('status', 1)->where('cat_sale.category_id', $category)->first();
            }

            if (Auth::check()) {
                $customer_group_sale = DB::table('fumaco_on_sale')
                    ->join('fumaco_on_sale_customer_group', 'fumaco_on_sale.id', 'fumaco_on_sale_customer_group.sale_id')
                    ->join('fumaco_customer_group', 'fumaco_customer_group.id', 'fumaco_on_sale_customer_group.customer_group_id')
                    ->whereDate('fumaco_on_sale.start_date', '<=', Carbon::now())->whereDate('fumaco_on_sale.end_date', '>=', Carbon::now())
                    ->where('fumaco_on_sale.status', 1)->where('fumaco_customer_group.customer_group_name', Auth::user()->customer_group)
                    ->first();

                if ($customer_group_sale) {
                    $sale = $customer_group_sale;
                }
            }

            if ($sale) {
                $item_on_sale = 1;
                if($sale->discount_type == 'By Percentage'){
                    $discount = ($item_price * ($sale->discount_rate/100));

                    $discount_display = $sale->discount_rate . '% OFF';
                    $discount_rate = $sale->discount_rate;
                }else if($sale->discount_type == 'Fixed Amount'){
                    if($item_price > $sale->discount_rate){
                        $discount = $sale->discount_rate;
                        $discount_display = '₱' . number_format($sale->discount_rate, 2, '.', ',') . ' OFF';
                        $discount_rate = $sale->discount_rate;
                    }
                }
            }
        }
        // get prices based on price list assigned for logged in user
        if (Auth::check()) {
            $exclusive_pl = DB::table('fumaco_product_prices')->where('price_list_id', Auth::user()->pricelist_id)->where('item_code', $item_code)->first();

            $item_price = ($exclusive_pl) ? $exclusive_pl->price : $item_price;
            if ($exclusive_pl) {
                $item_on_sale = $exclusive_pl->on_sale;
                $discount_rate += $exclusive_pl->discount_rate;

                $discount = ($exclusive_pl->discount_type == 'percentage') ? ($exclusive_pl->price * ($exclusive_pl->discount_rate/100)) : $exclusive_pl->discount_rate;
            }
        }

        $discounted_price = $item_price - $discount;

        return [
            'item_price' => $item_price,
            'discounted_price' => $discounted_price,
            'is_on_sale' => $item_on_sale,
            'discount_display' => $discount_display,
            'discount_rate' => $discount_rate,
        ];
    }

    public function getProductRating($item_code) {
        // get product reviews
        $product_reviews = DB::table('fumaco_product_review')
            ->where('status', '!=', 'pending')->where('item_code', $item_code)->get();

        $total_reviews = collect($product_reviews)->count();
        $total_rating = collect($product_reviews)->sum('rating');
        $overall_rating = ($total_reviews > 0) ? ($total_rating / $total_reviews) : 0;

        return [
            'total_reviews' => $total_reviews,
            'overall_rating' => $overall_rating,
            'product_reviews' => $product_reviews,
        ];
    }
}