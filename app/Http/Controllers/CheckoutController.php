<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class CheckoutController extends Controller
{
	public function reviewOrder() {
		$cart = session()->get('fumCart');
		$cart = (!$cart) ? [] : $cart;

		if(count($cart) <= 0) {
			return redirect('/cart');
		}

		$cart_items = DB::table('fumaco_items')
			->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
		
		$cart_arr = [];
		foreach ($cart_items as $n => $item) {
			$item_image = DB::table('fumaco_items_image_v1')
				->where('idcode', $item->f_idcode)->first();

			$cart_arr[] = [
				'item_code' => $item->f_idcode,
				'item_description' => $item->f_name_name,
				'price' => $item->f_price,
				'amount' => ($item->f_price * $cart[$item->f_idcode]['quantity']),
				'quantity' => $cart[$item->f_idcode]['quantity'],
				'stock_qty' => $item->f_qty,
				'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
			];
		}

		return view('frontend.checkout.review_order', compact('cart_arr', 'cart'));
	}

	public function billingForm() {
		return view('frontend.checkout.billing_address_form');
	}
}
