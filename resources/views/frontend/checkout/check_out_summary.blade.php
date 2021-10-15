@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
	'activePage' => 'checkout_customer_form'
])

@section('content')
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
						<div class="card-body he1x" style="padding-bottom: 0px !important;">
							<strong>Your Order ID : # {{ $summary_arr[0]['address'][0]['order_tracker_code'] }}</strong>
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">
							Your Customer Name :  {{ $summary_arr[0]['address'][0]['xfname']." ".$summary_arr[0]['address'][0]['xlname'] }}
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Your Customer Email Address :  {{ $summary_arr[0]['address'][0]['xemail'] }}
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
														<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $shipping_fname. " " .$shipping_lname }}</div>

														<div class="card-body he1x" style="padding-bottom: 0px !important;">Address Type :  {{ $shipping_address_type }}</div>

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
															<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $summary_arr[0]['address'][0]['xcontact_person'] }}</div>

															<div class="card-body he1x" style="padding-bottom: 0px !important;">Address Type :  {{ $summary_arr[0]['address'][0]['xaddresstype'] }}</div>

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
							<tr style="text-align: center">
								<th class="col-md-2"></th>
								<th>Product</th>
								<th>Qty</th>
								<th class="col-md-2">Total</th>
							</tr>
							</thead>
							<tbody>
								@foreach ($cart_arr as $cart)
									<tr>
										<td class="col-md-2" style="padding-top: 20px;padding-bottom: 20px;">
											<center>
												<img src="{{ asset('/storage/item/images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="" width="55" height="55">
											</center>
										</td>
										<td style="font-size: 12px; padding-top: 20px;padding-bottom: 20px;">{{ $cart['item_description'] }}</td>
										<td style="text-align: center; padding-top: 20px;padding-bottom: 20px;">{{ $cart['quantity'] }}</td>
										<td class="col-md-2" style="text-align: center;padding-top: 20px;padding-bottom: 20px;"><span class="amount">{{ $cart['subtotal'] }}</span></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						
						<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
							<div class="d-flex justify-content-between align-items-center">
								Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">P {{ number_format(collect($cart_arr)->sum('subtotal'), 2, '.', ',') }}</small>
							</div>
							<hr>
						</div>
						<div class="card-body he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">Shipping
							<div class="form-check">
								<label class="form-check-label" for="shipradio">&nbsp;</label>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="shipping_fee" id="Free Shipping" value="0" data-name="Free Delivery" required checked>
									<label class="form-check-label" for="Free Shipping">Free Shipping</label>
								</div>
								<small class="text-muted stylecap he1x">P 0.00</small>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="shipping_fee" id="Standard" value="500" data-name="Standard Delivery" required>
									<label class="form-check-label" for="Standard">Standard</label>
								</div>
								<small class="text-muted stylecap he1x">P 500.00</small>
							</div>
							<hr>
						</div>
						<div class="card-body he1x">
							<div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Total <small class="text-muted stylecap he1x" style="color:#FF9D00 !important;" id="grand-total">0.00</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-8 mx-auto">
					<a href="javascript:history.back()" class="btn btn-lg btn-outline-primary col-md-4" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BACK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
				<div class="col-md-4 mx-auto">
					<div class="card" style="margin-left: 5%;">
						@php
						$password = 'q5m12345';
						$serviceid = 'Q5M';
						$merchantapprovalurl = $summary_arr[0]['base_url']."/cart-success";
						$merchantunapprovalurl = $summary_arr[0]['base_url']."/checkout_summary";

						$string = $password . $serviceid .  $summary_arr[0]['address'][0]['order_tracker_code'] . $summary_arr[0]['base_url']."/cart" . $merchantapprovalurl . $merchantunapprovalurl . $summary_arr[0]['grand_total'] . "PHP" . $summary_arr[0]['address'][0]['order_ip'] . "600";

						$hash = hash('sha256', $string);	
					@endphp
					<center>
						<form action="/checkout/place_order" method="post" name="adminForm" enctype="multipart/form-data">
							@csrf
							<div style="display: none">
								<input name="TransactionType" value="SALE">
								<input name="PymtMethod" value="ANY">
								<input name="ServiceID" value="Q5M">
								<input name="PaymentID" value="{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="OrderNumber" value="{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="PaymentDesc" value="Fumaco Online Sale / Tracker Code : {{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="MerchantReturnURL" value="{{ $summary_arr[0]['base_url']."/cart" }}">
								<input name="MerchantApprovalURL" value="{{ $merchantapprovalurl }}">
								<input name="MerchantUnApprovalURL" value="{{ $merchantunapprovalurl }}">
								<input name="Amount" value="{{ $summary_arr[0]['grand_total'] }}">
								<input name="CurrencyCode" value="PHP">
								<input name="CustIP" value="{{ $summary_arr[0]['address'][0]['order_ip'] }}">
								<input name="CustName" value="{{ $summary_arr[0]['address'][0]['xfname']. " " .$summary_arr[0]['address'][0]['xlname'] }}">
								<input name="CustEmail" value="{{ $summary_arr[0]['address'][0]['xemail'] }}">
								<input name="CustPhone" value="{{ $summary_arr[0]['ship_mobile'] }}">
								<input name="PageTimeout" value="600">
								<input name="HashValue" value="{{ $hash }}">
								<input name="order_id" value="FUMAC02020">
								<input name="orderpayment_id" value="FUMAC02020">
								<input name="orderpayment_type" value="payment_eghl">
								<input name="task" value="confirmPayment">
								
								{{-- place order --}}
								<input type="text" name="order_no" value="{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}" id="order_no">
								<input id="total_amount" name="total_amount" value=""/>
								<input type="text" id="shipping_id" name="shipping_id" value="">
								<input type="text" id="shipping_fee" name="shipping_fee" value="">
								{{-- place order --}}

							</div>

							<input id="checkout-btn" class="btn btn-lg btn-outline-primary col-md-12" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROCEED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" style="float: right;" type="submit">
						</form>
					</div>
				</div>
			</div>
		</div>
		<br>&nbsp;
	</main>

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

	<script>
		$(document).ready(function() {
			updateTotal();
			function updateTotal() {
				var subtotal = 0;
				$('#cart-items tbody tr').each(function(){
					var amount = $(this).find('.amount').eq(0).text();
					subtotal += parseFloat(amount);
				});
					
				var shipping_fee = $("input[name='shipping_fee']:checked").val();
				var total = parseFloat(shipping_fee) + subtotal;

				total = (isNaN(total)) ? 0 : total;

				$('#grand-total').text('P ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
				
				$("#total_amount").val($('#grand-total').text());
				$('#shipping_id').val($("input[name='shipping_fee']:checked").data('name'));
				$('#shipping_fee').val($("input[name='shipping_fee']:checked").val());
			}

			// $('#checkout-btn').click(function(e){
			// 	e.preventDefault();
			// 	var data = {
			// 		'shipping_fee': $("input[name='shipping_fee']:checked").val(),
			// 		'shipping_name': $("input[name='shipping_fee']:checked").data('name'),
			// 		'grand_total' : $("#total_amount").val(),
			// 		'order_no' : $("#order_no").val(),
			// 		'_token': "{{ csrf_token() }}",
			// 	}

			// 	$.ajax({
			// 		type:'POST',
			// 		url:'/checkout/place_order',
			// 		data: data,
			// 		success: function (response) {
			// 			window.location.href = "https://pay.e-ghl.com/IPGSG/Payment.aspx";
			// 			// window.location.href = "/checkout/review_order";
			// 		},
			// 		error: function () {
			// 			alert('An error occured.');
			// 		}
			// 	});
			// });

			$(document).on('change', 'input[name="shipping_fee"]', function(){
				updateTotal();
			});
		});

	</script>
@endsection