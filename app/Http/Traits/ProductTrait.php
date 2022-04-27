<?php

namespace App\Http\Traits;

use Auth;
use DB;
use Carbon\Carbon;

trait ProductTrait {
    public function getItemPriceAndDiscount($item_on_sale, $category, $sale, $item_price, $item_code, $discount_type, $discount_rate, $uom, $sale_per_category) {
        // set discounted price based on sale details
        $discount_display = ($discount_type == 'percentage') ? ($discount_rate . '% OFF') : '₱' . number_format($discount_rate, 2, '.', ',') . ' OFF';
        $discount = ($discount_type == 'percentage') ? ($item_price * ($discount_rate/100)) : $discount_rate;
        if (!$item_on_sale) {
            if (!$sale) {
                if(array_key_exists($category, $sale_per_category)) {
                    $sale = $sale_per_category[$category][0];
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
            $exclusive_pl = DB::table('fumaco_product_prices')->where('price_list_id', Auth::user()->pricelist_id)
                ->where('item_code', explode("-", $item_code)[0])->where('uom', $uom)
                ->select('price', 'on_sale', 'discount_rate', 'discount_type')->first();
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

    public function getProductRating($item_collection) {
        // get product reviews
        if (count($item_collection) > 0) {
            $product_reviews_query = DB::table('fumaco_product_review')
                ->where('status', '!=', 'pending')->whereIn('item_code', $item_collection)
                ->select('item_code', 'rating')->get();

            $product_reviews = collect($product_reviews_query)->groupBy('item_code');
            return collect($product_reviews)->map(function($values, $index){
                $total_reviews = count($values);
                $total_rating = collect($values)->sum('rating');
                $overall_rating = ($total_reviews > 0) ? ($total_rating / $total_reviews) : 0;

                return [
                    'total_reviews' => $total_reviews,
                    'overall_rating' => $overall_rating,
                    'product_reviews' => $values,
                ];
            })->toArray();
        } else {
            return [
                'total_reviews' => 0,
                'overall_rating' => 0,
                'product_reviews' => [],
            ];
        }
    }

    public function getSalePerItemCategory($category_collection) {
        $sale_per_category = DB::table('fumaco_on_sale as sale')
            ->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')
            ->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())
            ->where('status', 1)->whereIn('cat_sale.category_id', $category_collection)
            ->select('cat_sale.discount_type', 'cat_sale.discount_rate', 'cat_sale.category_id')->get();
        
        return collect($sale_per_category)->groupBy('category_id')->toArray();
    }

    public function getSalePerCustomerGroup($customer_group_id) {
        return DB::table('fumaco_on_sale')
            ->join('fumaco_on_sale_customer_group', 'fumaco_on_sale.id', 'fumaco_on_sale_customer_group.sale_id')
            ->join('fumaco_customer_group', 'fumaco_customer_group.id', 'fumaco_on_sale_customer_group.customer_group_id')
            ->whereDate('fumaco_on_sale.start_date', '<=', Carbon::now())->whereDate('fumaco_on_sale.end_date', '>=', Carbon::now())
            ->where('fumaco_on_sale.status', 1)->where('fumaco_customer_group.id', $customer_group_id)
            ->select('fumaco_on_sale_customer_group.discount_type', 'fumaco_on_sale_customer_group.discount_rate')
            ->first();
    }
}