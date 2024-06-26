@extends('frontend.layout', [
	'namePage' => 'Order Summary',
	'activePage' => 'checkout_success'
])

{{-- for bank deposit payment method only --}}
@section('content')
	<style>
		.products-head {
			margin-top: 10px !important;
			padding-left: 40px !important;
			padding-right: 40px !important;
		}
		.he1 {
			font-weight: 300 !important;
			font-size: 12px !important;
		}
		.he2, .he2x2x {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.he1x {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.he2x {
			font-weight: 200 !important;
			font-size: 12px !important;
		}
		.he2x2 {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.he3x1x {
			font-weight: 100 !important;
			font-size: 14px !important;
		}
		.btmp {
			margin-bottom: 15px !important;
		}
		.tbls {
			padding-bottom: 25px !important;
			padding-top: 25px !important;
		}
	</style>

	<main style="background-color:#0062A5;">
		<br><br><br>
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-xl-8 mx-auto">
					<div class="col-lg-12 p-0 custom-padding">
						<center>
							<br><br><br>
							<h1 style="color:#186EA9 !important; letter-spacing: 2px !important;">THANK YOU FOR SHOPPING!</h1>
							<br>
                  		</center>
						<div style="color:#58595A !important;">
							<h6 class="font-weight-bold mt-2">Order no.: <b>{{ $order_details->order_number }}</b></h6>
							<p class="mt-3 mb-5">Your order has been placed, a confirmation will be sent to your email <b>{{ $loggedin }}</b> with the details of your order.</p>
							@if ($order_details->order_shipping == 'Store Pickup')
							<h6 class="font-weight-bold mt-2"><b>STORE PICKUP:</b></h6>
							<span class="d-inline-block" style="width: 100px;"><strong>Store: </strong></span>
							{{ $order_details->store_location }}
							<br>
							<span class="d-inline-block" style="width: 100px;"><strong>Address: </strong></span>
							{!! $store_address !!}
							<br>
							<br>
							<p><b>Pickup by:</b> {{ \Carbon\Carbon::parse($order_details->pickup_date)->format('D, F d, Y') }}</p>
							@else
							<h6 class="font-weight-bold mt-2"><b>SHIPPING TO:</b></h6>
							<span class="d-inline-block" style="width: 100px;"><strong>Customer: </strong></span>
							{{ $order_details->order_name .' ' . $order_details->order_lastname }}
							<br>
							<span class="d-inline-block" style="width: 100px;"><strong>Address: </strong></span>
							{!! $order_details->order_ship_address1 . ' ' . $order_details->order_ship_address2 . ', ' . ucwords(strtolower($order_details->order_ship_brgy)) . ', ' . ucwords(strtolower($order_details->order_ship_city)) . ', ' . ucwords(strtolower($order_details->order_ship_prov)) . ', ' . $order_details->order_ship_country . ' ' . $order_details->order_ship_postal !!}
							<br>
							<br>
							<p><b>Est. Delivery Date:</b> {{ $order_details->estimated_delivery_date }}</p>
							@endif
						</div>
						<table class="table">
							@php
								$sum_discount = collect($items)->sum('discount');
								$colspan = ($sum_discount > 0) ? 5 : 4;
								$gt_discount = $order_details->discount_amount;
							@endphp
							<thead>
								<tr style="font-size: 0.9rem;">
									<th class="text-left" colspan="2">Item Description</th>
									<th class="text-center d-none d-sm-table-cell">Qty</th>
									@if ($sum_discount > 0)
									<th class="text-center d-none d-sm-table-cell">Discount</th>
									@endif
									<th class="text-center d-none d-sm-table-cell">Price</th>
									<th class="text-center d-none d-sm-table-cell">Amount</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($items as $item)
								@php
								$src = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
								@endphp
								<tr style="font-size: 0.8rem;">
									<td class="text-center">
										<img src="{{ $src }}" class="img-responsive" alt="" width="55" height="55">
									</td>
									<td>
										<!-- for desktop -->
										<div class="d-none d-md-block">
											{{ $item['item_name'] }}
										</div>
										<!-- for desktop -->
										<!-- for mobile -->
										<div class="d-md-none" style="text-align: left !important;">
											<span><b>Qty:</b> {{ $item['qty'] }}</span><br>
											@if ($item['discount'])
											<span><b>Discount:</b> 
												@switch($item['discount_type'])
													@case('Fixed Amount')
														₱ {{ number_format(str_replace(",","",$item['discount']), 2) }}
														@break
													@case('By Percentage')
													@case('percentage')
													@case(null)
														{{ $item['discount'] ? $item['discount'] . '%' : '-' }}
														@break
													@default
													-											
												@endswitch
											</span>
											<br>
											@endif
											<span style="white-space: nowrap !important"><b>Price:</b> ₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</span><br>
											<span style="white-space: nowrap !important"><b>Amount:</b> ₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</span>
										</div>
										<!-- for mobile -->
									</td>
									<td class="text-center d-none d-sm-table-cell">{{ $item['qty'] }}</td>
									@if ($sum_discount > 0)
									<td class="text-center d-none d-sm-table-cell">
										@switch($item['discount_type'])
											@case('Fixed Amount')
												₱ {{ number_format(str_replace(",","",$item['discount']), 2) }}
												@break
											@case('By Percentage')
											@case('percentage')
											@case(null)
												{{ $item['discount'] ? $item['discount'] . '%' : '-' }}
												@break
											@default
											-											
										@endswitch
									</td>
									@endif
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
								 </tr>
								 <tr class="d-md-none" style="font-size: 0.8rem;">
									<td colspan=6 class="text-justify">
										{{ $item['item_name'] }}
									</td>
								 </tr>
								@empty
								<tr>
									<td colspan="6" class="text-center text-muted">No items found.</td>
								</tr>
								@endforelse
							</tbody>
							<tfoot>
								<tr style="font-size: 0.8rem; text-align: right;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">Subtotal</td>
									<td class="pb-1 pt-1 d-md-none">Subtotal</td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">₱ {{ number_format(str_replace(",","", collect($items)->sum('amount')), 2) }}</td>
								</tr>
								@if ($shipping_discount)
								@php
									switch ($shipping_discount->discount_type) {
										case 'Fixed Amount':
											$shipping_discount_amount = $shipping_discount->discount_rate;
											break;
										case 'By Percentage':
											$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $order_details->order_subtotal;
											$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
											break;
										default:
											$shipping_discount_amount = 0;
											break;
									}
								@endphp
									@if ($shipping_discount_amount < $order_details->order_subtotal)
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">{{ $shipping_discount->sale_name }}
											</td>
											<td class="pb-1 pt-1 d-md-none">{{ $shipping_discount->sale_name }}
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format(str_replace(",","",$shipping_discount_amount), 2) }}</td>
										</tr>
									@endif
								@endif
								@if ($order_details->voucher_code)
									@if ($order_details->voucher_code)
										@php
											switch ($voucher_details->discount_type) {
												case 'By Percentage':
													$voucher_discount_amount = ($voucher_details->discount_rate / 100) * $order_details->order_subtotal;
													if($voucher_details->capped_amount){
														$voucher_discount_amount = $voucher_discount_amount < $voucher_details->capped_amount ? $voucher_discount_amount : $voucher_details->capped_amount;
													}
													break;
												default:
													$voucher_discount_amount = $order_details->order_subtotal > $voucher_details->discount_rate ? $voucher_details->discount_rate : 0;
													break;
											}
										@endphp
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
											</td>
											<td class="pb-1 pt-1 d-md-none">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format($voucher_discount_amount, 2) }}</td>
										</tr>
									@endif
								@endif
								@isset($price_rule['Any'])
									@php
										$rule = $price_rule['Any'];
										switch ($rule['discount_type']) {
											case 'Percentage':
												$discount_amount = collect($items)->sum('amount') * ($rule['discount_rate'] / 100);
												break;
											default:
												$discount_amount = collect($items)->sum('amount') > $rule['discount_rate'] ? $rule['discount_rate'] : 0;
												break;
										}

										$gt_discount = $order_details->discount_amount > $discount_amount ? $order_details->discount_amount - $discount_amount : $order_details->discount_amount;
									@endphp
									@if ($discount_amount)
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $rule['discount_name'] }}</span>
											</td>
											<td class="pb-1 pt-1 d-md-none">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $rule['discount_name'] }}</span>
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format($discount_amount, 2) }}</td>
										</tr>
									@endif
								@endisset
								<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">{{ $order_details->order_shipping }}</td>
									<td class="pb-1 pt-1 d-md-none">{{ $order_details->order_shipping }}</td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">
										@if ($order_details->order_shipping_amount > 0)
										₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}
										@else
										FREE		
										@endif
									</td>
								</tr>
								@php
									$grand_total = $order_details->grand_total;
									if(!$order_details->grand_total){
										$grand_total = $order_details->order_shipping_amount + ($order_details->order_subtotal - $gt_discount);
									}
								@endphp
								<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}"><b>Grand Total</b></td>
									<td class="pb-1 pt-1 d-md-none"><b>Grand Total</b></td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;"><b>₱ {{ number_format(str_replace(",","", $grand_total), 2) }}</b></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="col-lg-12 text-center mx-auto"><br/><br/>
					<a href="/" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #313131 !important; border-color: #313131 !important;">RETURN TO HOMEPAGE</a>
					<br>
				</div>
				<div class="col-lg-12">&nbsp;&nbsp;
					<br><br><br>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#0062A5;"></main>

@endsection
