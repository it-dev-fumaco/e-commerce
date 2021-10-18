@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
	'activePage' => 'checkout_customer_form'
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
			font-weight: 500 !important;
			font-size: 15px !important;
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
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.btmp {
			margin-bottom: 15px !important;
		}
		.tbls {
			padding-bottom: 25px !important;
			padding-top: 25px !important;
		}
		.flex-1 {
			flex: 1 !important;
			display: flex !important;
		}
	</style>
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{asset('/assets/site-img/header3-sm.png')}}"alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
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
				<li class="breadcrumb-item"><a href="/cart" style="color: #000000 !important; text-decoration: none;">Shopping Cart</a></li>
				<li class="breadcrumb-item"><a href="{{ url()->previous() }}" style="color: #000000 !important; text-decoration: none;">Billing & Shipping Address</a></li>
				<li class="breadcrumb-item active"><a href="#" style="color: #000000 !important; text-decoration: underline;">Place Order</a></li>
			</ol>
		</nav>
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container marketing">
		  	<br>
			<div class="row">
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 mx-auto">
					<div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
						@php
							$shipping_fname = $summary_arr[0]['address'][0]['xfname'];
							$shipping_lname = $summary_arr[0]['address'][0]['xlname'];
							$shipping_address1 = $summary_arr[0]['address'][0]['xshippadd1'];
							$shipping_address2 = $summary_arr[0]['address'][0]['xshippadd2'];
							$shipping_province = $summary_arr[0]['address'][0]['xshiprov'];
							$shipping_city = $summary_arr[0]['address'][0]['xshipcity'];
							$shipping_brgy = $summary_arr[0]['address'][0]['xshipbrgy'];
							$shipping_postal = $summary_arr[0]['address'][0]['xshippostalcode'];
							$shipping_country = $summary_arr[0]['address'][0]['xshipcountry'];
							$shipping_address_type = $summary_arr[0]['address'][0]['xshiptype'];
							$shipping_mobile = $summary_arr[0]['ship_mobile'];

							if($summary_arr[0]['same_address'] == 1){
								$checkbox = 'd-block';
								$col = "12";
								$ship_text = " & Billing";
							}else{
								$checkbox = "d-none";
								$col = "6";
								$ship_text = '';
							}
						@endphp
						<div class="card-body he1x" style="padding-bottom: 0px !important; font-size:1rem !important;" >
							<strong>Your Order No : <span id="order-no">{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}</span></strong>
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">
							Customer Name :  {{ $summary_arr[0]['address'][0]['xfname']." ".$summary_arr[0]['address'][0]['xlname'] }}
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Email Address :  {{ $summary_arr[0]['address'][0]['xemail'] }}
							<div class="card-body he1x" style="padding-bottom: 0px !important;">
								<div class="accordion" id="accordionExample">
									<div class="row">
										<div class="col-md-{{ $col }} d-flex align-items-stretch">
											<div class="card" style="width: 100%">
												<div class="card-header" id="headingOne">
													<h2 class="mb-0">
														<div class="row">
															<div class="col-md-6">
																<button class="btn btn-link he1x" type="button" data-toggle="collapse" data-target="" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; color:#2c2c2d;">
																	<b>Shipping{{ $ship_text }} Address</b>
																</button>
															</div>
															<div class="col-md-6" style="text-align: right;">
																@if (Auth::check())
																	<a href="/myprofile/address" style="font-size: 14px; width:100%; text-decoration: none;">UPDATE YOUR ADDRESS</a>
																@endif
															</div>
														</div>
													</h2>
												</div>

												<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
													<div class="card-body">
                                                        <div class="card-body he1x" style="padding-bottom: 0px !important;"><b>{{ $shipping_address_type }}</b></div>
														<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $shipping_fname. " " .$shipping_lname }}</div>
														<div class="card-body he1x" style="padding-bottom: 0px !important;">
															{{ $shipping_address1." ".$shipping_address2.", ".$shipping_brgy.", ".$shipping_city.", ".$shipping_province.", ".$shipping_country." ".$shipping_postal }}
														</div>
														<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Number :  {{ $shipping_mobile }}<br/>&nbsp;</div>

														<div class="form-check {{ $checkbox }} ">
															<input class="form-check-input" type="checkbox"  checked disabled>
															<label class="form-check-label" class="formslabelfnt">Billing address is the same as above</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										@if ($summary_arr[0]['same_address'] == 0)
											<div class="col-md-6 d-flex align-items-stretch">
												<div class="card" style="width: 100%">
													<div class="card-header" id="headingOne1">
														<h2 class="mb-0">
															<div class="row">
																<div class="col-md-6">
																	<button class="btn btn-link he1x" type="button" data-toggle="collapse" data-target="" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; color:#2c2c2d;">
																		<b>Billing Address</b>
																	</button>
																</div>
																<div class="col-md-6" style="text-align: right;">
																	@if (Auth::check())
																		<a href="/myprofile/address" style="font-size: 14px; width:100%; text-decoration: none;">UPDATE YOUR ADDRESS</a>
																	@endif
																</div>
															</div>
														</h2>
													</div>

													<div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1" data-parent="#accordionExample">
														<div class="card-body">
                                                            <div class="card-body he1x" style="padding-bottom: 0px !important;"><b>{{ $summary_arr[0]['address'][0]['xaddresstype'] }}</b></div>
															<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $summary_arr[0]['address'][0]['xcontact_person'] }}</div>
															<div class="card-body he1x" style="padding-bottom: 0px !important;">
																{{ $summary_arr[0]['address'][0]['xadd1']." ".$summary_arr[0]['address'][0]['xadd2'].", ".$summary_arr[0]['address'][0]['xbrgy'].", ".$summary_arr[0]['address'][0]['xcity'].", ".$summary_arr[0]['address'][0]['xprov'].", ".$summary_arr[0]['address'][0]['xcountry']." ".$summary_arr[0]['address'][0]['xpostal'] }}
															</div>

															<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Number :  {{ $summary_arr[0]['bill_mobile'] }}<br/>&nbsp;</div>
														</div>
													</div>
												</div>
											</div>
										@endif
									</div>
									<br/>
								</div>
							</div>
						</div>

					</div><br/>
				</div>

				<div class="col-md-4 mx-auto">
					<div class="card" style="margin-left: 5%; background-color: #f4f4f4 !important; border-radius: 0rem !important;">
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Cart Total<hr></div>
						<table class="table" id="cart-items">
							<thead>
							<tr style="font-size: 0.8rem !important;">
								<th class="text-center" colspan="2">Product Description</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Amount</th>
							</tr>
							</thead>
							<tbody style="font-size: 0.8rem !important;">
								@foreach ($cart_arr as $cart)
									<tr>
										<td class="col-md-2">
											<center>
												<img src="{{ asset('/storage/item/images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="" width="55" height="55">
											</center>
										</td>
										<td>{{ $cart['item_description'] }}</td>
										<td style="text-align: center;">{{ $cart['quantity'] }}</td>
										<td class="col-md-2" style="text-align: right;">
                                 @if ($cart['discount'])
                                     <small class="text-muted"><s>₱ {{ number_format($cart['original_price'], 2, '.', ',') }}</s></small><br>
                                     ₱ {{ number_format($cart['price'], 2, '.', ',') }}
                                 @else
                                 ₱ {{ number_format($cart['price'], 2, '.', ',') }}
                                 @endif
                                 <span class="amount d-none">{{ $cart['subtotal'] }}</span>
                              </td>
									</tr>
								@endforeach
							</tbody>
						</table>

						<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
							<div class="d-flex justify-content-between align-items-center">
								Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">₱ {{ number_format(collect($cart_arr)->sum('subtotal'), 2, '.', ',') }}</small>
							</div>
							<hr>
						</div>
						<div class="card-body he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">Select Shipping Method
							<div class="form-check">
								<label class="form-check-label" for="shipradio">&nbsp;</label>
							</div>
							@forelse ($shipping_rates as $l => $srate)
							<div class="d-flex justify-content-between align-items-center">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="shipping_fee" id="{{ 'sr' . $l }}" value="{{ $srate['shipping_cost'] }}" data-sname="{{ $srate['shipping_service_name'] }}" data-est="{{ $srate['expected_delivery_date'] }}" required checked>
									<label class="form-check-label" for="{{ 'sr' . $l }}">{{ $srate['shipping_service_name'] }}</label>
								</div>
								<small class="text-muted stylecap he1x">₱ {{ number_format($srate['shipping_cost'], 2, '.', ',') }}</small>
							</div>
							@empty
								<h6>No available shipping methods.</h6>
							@endforelse
							<hr>
							<p class="d-none" id="est-div" style="font-size: 0.8rem; font-style: italic;">Estimated Delivery Date: <b><span id="estimated-delivery-date"></span></b></p>
						</div>
						<div class="card-body he1x">
							<div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Grand Total <small class="text-muted stylecap he1x" style="color:#FF9D00 !important;" id="grand-total">0.00</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="row mb-4">
				<div class="col-md-8 mx-auto">
					<a href="javascript:history.back()" class="btn btn-lg btn-outline-primary col-md-4" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left;">BACK</a>
				</div>
				<div class="col-md-4 mx-auto">
					<div class="card" style="margin-left: 5%;">
							<div id="payment-form" class="d-none"></div>
							<button class="btn btn-lg btn-outline-primary" id="checkout-btn" style="float: right;">PROCEED</button>
					</div>
				</div>
			</div>
		</div>
	</main>

	<div id="custom-overlay" style="display: none;">
		<div class="custom-spinner"></div>
		<br/>
		Loading...
	</div>
	  
	<style>
		#custom-overlay {
			background: #ffffff;
			color: #666666;
			position: fixed;
			height: 100%;
			width: 100%;
			z-index: 5000;
			top: 0;
			left: 0;
			float: left;
			text-align: center;
			padding-top: 25%;
			opacity: .80;
		}
		.custom-spinner {
			margin: 0 auto;
			height: 64px;
			width: 64px;
			animation: rotate 0.8s infinite linear;
			border: 5px solid firebrick;
			border-right-color: transparent;
			border-radius: 50%;
		}
		@keyframes rotate {
			0% {
				transform: rotate(0deg);
			}
			100% {
				transform: rotate(360deg);
			}
		}
	</style>
@endsection

@section('script')
<script>
	$(document).ready(function() {
		const id = $('#order-no').text();

		updateTotal();
		updateShipping();

		$(document).on('change', 'input[name="shipping_fee"]', function(){
			updateTotal();
			updateShipping();
		});
		
		$('#checkout-btn').click(function(e){
			e.preventDefault();
			updateShipping(1);
		});

		function callback(data) {
			if(data.status == 2) {
				window.location.href = '/';
			} else if (data.status == 1) {
				$('#payment-form').empty();
				if(data.s) {
					$('#custom-overlay').fadeIn();
					$.ajax({
						url: '/eghlform/' + id,
						type:"GET",
						success:function(data){
							$('#payment-form').html(data);
							checkForm();
						},
						error : function(data) {
							console.log(data);
						}
					});
				}
			} else {
				alert(d.message);
			}
		}

		function checkForm() {
			if ($("#payment-form").find('form').length) {
				$('#payment-form form').delay(500).submit();
			} else {
				$('#custom-overlay').fadeOut();
			}
		}

		function updateShipping(submit) {
			var s_name = $("input[name='shipping_fee']:checked").data('sname');
			var s_amount = $("input[name='shipping_fee']:checked").val();
			var data = {
				s_name, s_amount, _token: '{{ csrf_token() }}', submit
			}

			$.ajax({
				url: '/checkout/updateshipping/' + id,
				type:"POST",
				data: data,
				success: callback,
				error : function(data) {
					console.log('error updating shipping');
				}
			});
		}

		function updateTotal() {
			var subtotal = 0;
			$('#cart-items tbody tr').each(function(){
				var amount = $(this).find('.amount').eq(0).text();
				subtotal += parseFloat(amount);
			});

			var shipping_fee = $("input[name='shipping_fee']:checked").val();
			var total = parseFloat(shipping_fee) + subtotal;

			var estimated_del = $("input[name='shipping_fee']:checked").data('est');
			if (estimated_del) {
				$('#est-div').removeClass('d-none');
				$('#estimated-delivery-date').text(estimated_del);
			}

			total = (isNaN(total)) ? 0 : total;

			$('#grand-total').text('₱ ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));

			$("#total_amount").val($('#grand-total').text());
		}
	});

</script>
@endsection
