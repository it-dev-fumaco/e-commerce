@extends('frontend.layout', [
	'namePage' => 'Checkout - Review Order',
	'activePage' => 'checkout_review_order'
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
			font-weight: 600 !important;
			font-size: 14px !important;
		}
		.he2x {
			font-weight: 200 !important;
			font-size: 12px !important;
		}
		.he2x2 {
			font-weight: 500 !important;
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
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 14px !important;">
				<li class="breadcrumb-item">
					<a href="#" style="color: #000000 !important; text-decoration: underline;">Review Your Orders</a>
				</li>
				<li class="breadcrumb-item">
					<a href="#" style="color: #c1bdbd !important; text-decoration: none;">Billing & Shipping Address</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="#" style="color: #c1bdbd !important; text-decoration: none;">Place Order</a>
				</li>
			</ol>
		</nav>
	</main>
	
	<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
		<div class="container">
			<div class="row">
				<!--products-->
				<div class="col-lg-8">
					<table class="table">
						<col style="width: 10%;">
						<col style="width: 45%;">
						<col style="width: 15%;">
						<col style="width: 15%;">
						<col style="width: 15%;">
						<thead>
							<tr>
								<th class="he1x">Product</th>
								<th class="he1x"></th>
								<th class="he1x text-center">Price</th>
								<th class="he1x text-center">Quantity</th>
								<th class="he1x text-center">Total</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($cart_arr as $item)
							<tr class="he2x2">
								<td class="text-center align-middle">
									<img src="{{ asset('/item/images/'.$item['item_code'].'/gallery/preview/'.$item['item_image']) }}" class="img-responsive" alt="" width="55" height="55">
								</td>
								<td class="tbls">{{ $item['item_description'] }}</td>
								<td class="tbls text-center">P {{ number_format($item['price'], 2, '.', ',') }}</td>
								<td class="tbls text-center">{{ $item['quantity'] }}</td>
								<td class="tbls" style="text-align: right;">P {{ number_format($item['amount'], 2, '.', ',') }}</td>
							</tr>
							@empty
							<tr>
								<td style="border-bottom: 0; padding: 10px 0;" colspan="5">
									<div class="alert alert-danger alert-dismissible fade show" role="alert">Your cart is empty!</div>
								</td>
							</tr>
							@endforelse
							@php
								$subtotal = collect($cart_arr)->sum('amount');
								$shipping_fee = $cart['shipping']['shipping_fee'];
								$grand_total = $subtotal + $shipping_fee;
							@endphp
							<tr style="text-align: right;">
								<td colspan="4"><small>Subtotal<br>Shipping Fee</small></td>
								<td><small>P {{ number_format($subtotal, 2, '.', ',') }}<br>P {{ number_format($shipping_fee, 2, '.', ',') }}</small></td>
							</tr>
							<tr style="text-align: right;">
								<td colspan="4"><small>Grand Total</small></td>
								<td><small><b>P {{ number_format($grand_total, 2, '.', ',') }}</b></small></td>
							</tr>
						</tbody>
					</table>
				</div>
				<!--products-->
				
				<!--sidebar-->
				<div class="col-lg-4">
					<div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
						<div class="card-body he1x" style="padding-bottom: 0px !important;">CHECKOUT USING<hr></div>
						<br>
						<div style="padding-left:5%; padding-right:5%;">
							@if (!Auth::check())
							<a href="/checkout/form" class="btn btn-lg btn-outline-primary" style="width:100%; font-size: 14px;">CHECKOUT AS GUEST</a>
							<br><br>
							<a href="/login" class="btn btn-lg btn-outline-primary" style="width:100%; font-size: 14px;">MEMBER LOGIN</a>
							<br><br>
							@else
							<a href="/checkout/set_address" class="btn btn-lg btn-outline-primary" style="font-size: 14px; width:100%">CHECKOUT</a>
							<br><br><br>
							<a href="#" class="btn btn-lg btn-outline-primary" style="font-size: 14px; width:100%">UPDATE YOUR ADDRESS</a>
							<br><br>
							@endif
						</div>
						<br>
					</div>
					<br>
					<a href="/" class="btn btn-outline-primary" role="button" style="width:100% !important;">CONTINUE SHOPPING</a>
				</div>
				<br>
			</div>
			<!--sidebar-->
		</div>
	</div>
</main>
@endsection