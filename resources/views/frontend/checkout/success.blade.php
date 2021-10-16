@extends('frontend.layout', [
	'namePage' => 'Order Summary',
	'activePage' => 'checkout_success'
])

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
							<h6 class="font-weight-bold mt-2">Your order no.: <b>{{ $order_details->order_number }}</b></h6>
							<p class="mt-3 mb-5">Confirmation will be sent to your email with the details of your order.</p>
							<h6 class="font-weight-bold mt-2"><b>SHIPPING TO:</b></h6>
							<span class="d-inline-block" style="width: 100px;"><strong>Customer: </strong></span>
							{{ $order_details->order_name .' ' . $order_details->order_lastname }}
							<br>
							<span class="d-inline-block" style="width: 100px;"><strong>Address: </strong></span>
							{!! $order_details->order_ship_address1 . ' ' . $order_details->order_ship_address2 . ', ' . $order_details->order_ship_brgy . ', ' . $order_details->order_ship_city . ', ' . $order_details->order_ship_prov . ', ' . $order_details->order_ship_postal . ', ' . $order_details->order_ship_country !!}
						</div>
						<br><br>
						<table class="table">
							<thead>
								<tr style="font-size: 0.9rem;">
									<th class="text-left" colspan="2">Item Description</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Price</th>
									<th class="text-center">Amount</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($items as $item)
								@php
								$src = ($item['image']) ? '/storage/item/images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
								@endphp
								<tr style="font-size: 0.8rem;">
									<td class="text-center">
										<img src="{{ $src }}" class="img-responsive" alt="" width="55" height="55">
									</td>
									<td>{{ $item['item_name'] }}</td>
									<td class="text-center">{{ $item['qty'] }}</td>
									<td class="text-right" style="text-align: right;">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
									<td class="text-right" style="text-align: right;">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
								 </tr>
								@empty
								<tr>
									<td colspan="5" class="text-center text-muted">No items found.</td>
								</tr>
								@endforelse
							</tbody>
							<tfoot>
								<tr style="font-size: 0.8rem; text-align: right;">
									<td class="pb-1 pt-1" colspan="4">Subtotal</td>
									<td class="pb-1 pt-1">₱ {{ number_format(str_replace(",","",$order_details->order_subtotal), 2) }}</td>
								</tr>
								<tr style="font-size: 0.8rem; text-align: right;">
									<td class="pb-1 pt-1" colspan="4">{{ $order_details->order_shipping }}</td>
									<td class="pb-1 pt-1">₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}</td>
								</tr>
								<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
									<td class="pb-1 pt-1" colspan="4"><b>Grand Total</b></td>
									<td class="pb-1 pt-1"><b>₱ {{ number_format(str_replace(",","",($order_details->order_shipping_amount + $order_details->order_subtotal)), 2) }}</b></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="col-lg-12 text-center p-5">
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