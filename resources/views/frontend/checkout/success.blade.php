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
		.he2 {
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
		.he2x2x {
			font-weight: 200 !important;
			font-size: 10px !important;
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
				<div class="col-lg-12" style="padding-left: 15% !important; padding-right: 15% !important;">
					<div class="col-lg-12">
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
									<td>{{ $item['item_name'] }}
									{{-- for mobile --}}
									<div class="d-md-none d-xl-none">
										<br/>
										<p><b>Qty:</b> {{ $item['qty'] }}</p>
										@if ($sum_discount > 0)
										<p><b>Discount (%):</b> {{ $item['discount'] . '%' }}</p>
										@endif
										<p style="white-space: nowrap !important"><b>Price:</b> ₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</p>
										<p style="white-space: nowrap !important"><b>Amount:</b> ₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</p>
									</div>
									{{-- for mobile --}}
									</td>
									<td class="text-center d-none d-sm-table-cell">{{ $item['qty'] }}</td>
									@if ($sum_discount > 0)
									<td class="text-center d-none d-sm-table-cell">{{ $item['discount'] . '%' }}</td>
									@endif
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
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
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">₱ {{ number_format(str_replace(",","",$order_details->order_subtotal), 2) }}</td>
								</tr>
								@if ($order_details->voucher_code)
								<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">Discount
										<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
									</td>
									<td class="pb-1 pt-1 d-md-none">Discount 
										<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
									</td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format(str_replace(",","",$order_details->discount_amount), 2) }}</td>
								</tr>
								@endif
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
								<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}"><b>Grand Total</b></td>
									<td class="pb-1 pt-1 d-md-none"><b>Grand Total</b></td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;"><b>₱ {{ number_format(str_replace(",","",($order_details->order_shipping_amount + ($order_details->order_subtotal - $order_details->discount_amount))), 2) }}</b></td>
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
