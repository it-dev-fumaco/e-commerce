<?php

namespace App\Http\Traits;

use Auth;
use DB;
use Carbon\Carbon;

trait ProductTrait {
    public function getItemPriceAndDiscount($product, $all_items_sale, $sale_per_category, $is_on_clearance_sale) {
        $item_price = $product['default_price'];
        $discount_rate = $product['discount_rate'];
        // get item default price and discount
        if (in_array($product['discount_type'], ['percentage', 'By Percentage'])) {
            $discount_display = ($discount_rate . '% OFF');
            $discount = ($item_price * ($discount_rate/100));
        } else {
            $discount_display = '₱' . number_format($discount_rate, 2, '.', ',') . ' OFF';
            $discount = $discount_rate;
        }

        $sale = $all_items_sale;
        $item_on_sale = $is_on_clearance_sale ? $is_on_clearance_sale : $product['on_sale'];
        if (!$is_on_clearance_sale) {
            if (!$item_on_sale) {
                if (!$sale) {
                    if(array_key_exists($product['category_id'], $sale_per_category)) {
                        $sale = $sale_per_category[$product['category_id']][0];
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
                    ->where('item_code', explode("-", $product['item_code'])[0])->where('uom', $product['stock_uom'])
                    ->select('price', 'on_sale', 'discount_rate', 'discount_type')->first();
                $item_price = ($exclusive_pl) ? $exclusive_pl->price : $item_price;
                if ($exclusive_pl) {
                    $item_on_sale = $exclusive_pl->on_sale;
                    $discount_rate += $exclusive_pl->discount_rate;

                    $discount = ($exclusive_pl->discount_type == 'percentage') ? ($exclusive_pl->price * ($exclusive_pl->discount_rate/100)) : $exclusive_pl->discount_rate;
                }
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

    public function getSalePerShippingService($shipping_service){
        return DB::table('fumaco_on_sale as p')
            ->join('fumaco_on_sale_shipping_service as c', 'p.id', 'c.sale_id')->where('c.shipping_service', $shipping_service)
            ->where('status', 1)->where('p.apply_discount_to', 'Per Shipping Service')->whereDate('p.start_date', '<=', Carbon::now())->whereDate('p.end_date', '>=', Carbon::now())
            ->first();
    }

    public function isIncludedInClearanceSale($collection) {
        $query = DB::table('fumaco_on_sale as os')
            ->join('fumaco_on_sale_items as osi', 'os.id', 'osi.sale_id')
            ->join('fumaco_items as i', 'i.f_idcode', 'osi.item_code')
            ->when(count($collection) > 0, function($c) use ($collection) {
                $c->whereIn('i.f_idcode', $collection);
            })
            ->where('os.is_clearance_sale', 1)->where('os.status', 1)
            ->whereDate('os.start_date', '<=', Carbon::now()->startOfDay())->whereDate('os.end_date', '>=', Carbon::now()->endOfDay())
            ->select('i.f_idcode', 'i.f_default_price', 'i.f_cat_id', 'osi.discount_type', 'osi.discount_rate')
            ->get();

        return collect($query)->groupBy('f_idcode')->toArray();
    }
}