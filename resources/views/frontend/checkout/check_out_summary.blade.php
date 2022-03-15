@extends('frontend.layout', [
	'namePage' => 'Checkout - Fumaco',
	'activePage' => 'checkout_customer_form'
])

@section('content')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('/datepicker/datepicker3.css') }}">
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{asset('/assets/site-img/header3-sm.png')}}"alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
					<div class="container">
						<div class="carousel-caption text-start mx-auto" style="bottom: 1rem !important;">
						<center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; white-space: nowrap !important">
				<li class="breadcrumb-item"><a href="/cart" style="color: #000000 !important; text-decoration: none;">Shopping Cart</a></li>
				<li class="breadcrumb-item"><a href="{{ url()->previous() }}" style="color: #000000 !important; text-decoration: none;">Billing & Shipping Address</a></li>
				<li class="breadcrumb-item active"><a href="#" style="color: #000000 !important; text-decoration: underline;">Place Order</a></li>
			</ol>
		</nav>
	</main>

	@php
		$shipping_fname = $shipping_details['fname'];
		$shipping_lname = $shipping_details['lname'];
		$shipping_address1 = $shipping_details['address_line1'];
		$shipping_address2 = $shipping_details['address_line2'];
		$shipping_province = $shipping_details['province'];
		$shipping_city = $shipping_details['city'];
		$shipping_brgy = $shipping_details['brgy'];
		$shipping_postal = $shipping_details['postal_code'];
		$shipping_country = $shipping_details['country'];
		$shipping_address_type = $shipping_details['address_type'];
		$shipping_mobile = $shipping_details['mobile_no'];
		$shipping_email = $shipping_details['email_address'];
		$shipping_business_name = $shipping_details['business_name'];
		$shipping_tin = $shipping_details['tin'];

		if($shipping_details['same_as_billing'] == 1){
			$checkbox = 'd-block';
			$col = "12";
			$ship_text = " & Billing";
		}else{
			$checkbox = "d-none1";
			$col = "6";
			$ship_text = '';
		}
	@endphp
	<main style="background-color:#ffffff;" class="products-head">
		<div class="container-fluid p-0 mb-5">
			<div class="row">
				<div class="col-md-4">
					<div class="card he1x" style="background-color: #f4f4f4 !important; padding-bottom: 11px; min-height: 600px;">
						<p style="margin: 15px 0 0 20px;"><strong>Your Order No.: <span id="order-no">{{ $order_no }}</span></strong></p>
						<p style="margin: 15px 0 0 20px;">Customer Name: {{ (Auth::check()) ? Auth::user()->f_name . " " . Auth::user()->f_lname : $shipping_fname." ".$shipping_lname }}</p>
						<p style="margin: 15px 0 0 20px;">Email Address: {{ (Auth::check()) ? Auth::user()->username : $shipping_email }}</p>
						<br>
						<div class="card m-2">
							<div class="card-header">
								<div class="d-flex justify-content-between">
									<div class="p-0"><b>Shipping{{ $ship_text }} Address</b></div>
									<div class="p-0">
										@if (Auth::check())
										<a href="#" style="font-size: 14px; width:100%; text-decoration: none;" role="button" data-toggle="modal" data-target="#selectShippingModal">Edit Address</a>
										@endif
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="he1x" style="margin-bottom: 10px !important;"><b>{{ $shipping_address_type }}</b></div>
								@if($shipping_details['address_type'] == 'Business Address' )
									<div class="he1x" style="margin-bottom: 10px !important;">
										Business Name: {{ $shipping_business_name }}<br/>
										TIN: {{ $shipping_tin }}
									</div>
								@endif
								<div class="he1x" style="margin-bottom: 10px !important;">Contact Person :  {{ $shipping_fname. " " .$shipping_lname }}</div>
								<div class="he1x" style="margin-bottom: 10px !important;">
									{{ $shipping_address1." ".$shipping_address2.", ".$shipping_brgy.", ".$shipping_city.", ".$shipping_province.", ".$shipping_country." ".$shipping_postal }}
								</div>
								<div class="he1x" style="margin-bottom: 5px !important;">Contact Number :  {{ $shipping_mobile }}</div>
								<br>
								<div class="form-check {{ $checkbox }}">
									<input class="form-check-input" type="checkbox" {{ $shipping_details['same_as_billing'] ? 'checked' : '' }} id="same-as-billing-cb">
									<label class="form-check-label" class="formslabelfnt" for="same-as-billing-cb">Billing address is the same as above</label>
								</div>
							</div>
						</div>

						@if ($shipping_details['same_as_billing'] == 0)
						<div class="card m-2">
							<div class="card-header">
								<div class="d-flex justify-content-between">
									<div class="p-0">	<b>Billing Address</b></div>
									<div class="p-0">
										@if (Auth::check())
										<a href="#" style="font-size: 14px; width:100%; text-decoration: none;" role="button"  data-toggle="modal" data-target="#selectBillingModal">Edit Address</a>
										@endif
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="he1x" style="margin-bottom: 10px !important;"><b>{{ $shipping_address_type }}</b></div>
								@if($billing_details['address_type'] == 'Business Address' )
									<div class="he1x" style="margin-bottom: 10px !important;">
										Business Name: {{ $billing_details['business_name'] }}<br/>
										TIN: {{ $billing_details['tin'] }}
									</div>
								@endif
								<div class="he1x" style="margin-bottom: 10px !important;">Contact Person :  {{ $billing_details['fname'] . ' ' . $billing_details['lname'] }}</div>
								<div class="he1x" style="margin-bottom: 10px !important;">
									{{ $billing_details['address_line1']." ".$billing_details['address_line2'].", ".$billing_details['brgy'].", ".$billing_details['city'].", ".$billing_details['province'].", ".$billing_details['country']." ".$billing_details['postal_code'] }}
								</div>
								<div class="he1x" style="margin-bottom: 5px !important;">Contact Number :  {{ $billing_details['mobile_no'] }}</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="card he1x" style="background-color: #f4f4f4 !important; padding-bottom: 11px; min-height: 600px;">
						<p style="padding: 15px 0 0 20px;">Order Summary</p>
						<hr style="margin: -10px 20px 0 20px;">
						<div class="card-body" style="padding-top: 5px; margin-left: 5px; margin-right: 5px;">
							<div class="m-0">
								<div id="item-preloader" style="display: none;">
									<div class="spinner-border" role="status">
										<span class="sr-only">Loading...</span>
									</div>
								</div>
								<table class="table" id="cart-items" style="border-color: #8c8c8cd0 !important;">
									<thead style="border-bottom: 1px solid #8c8c8cd0 !important;">
										<tr style="font-size: 0.8rem !important;">
											<th class="text-left" colspan="2" style="width: 70%">Product Description</th>
											<th class="text-center" style="width: 13%">Qty</th>
											<th class="text-center" style="width: 17%">Amount</th>
										</tr>
									</thead>
									<tbody style="font-size: 0.8rem !important;">
										@foreach ($cart_arr as $cart)
										<tr>
											<td class="col-md-2" style="width: 10%">
												<center>
													<img src="{{ asset('/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="{{ Str::slug(explode(".", $cart['item_image'])[0], '-') }}" width="55" height="55">
												</center>
											</td>
											<td>
												<span class="d-block">{!! $cart['item_description'] !!}</span>
												<span id="voucherApp{{ $cart['item_code'] }}" class="text-white d-none voucher-item-code" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">Discount Applied</span>
											</td>
											<td style="text-align: center;">{{ $cart['quantity'] }}</td>
											<td class="col-md-2" style="text-align: right;">
												<span class="d-block" id="{{ $cart['item_code'] }}" style="white-space: nowrap !important">₱ {{ number_format($cart['subtotal'], 2, '.', ',') }}</span>
												<span class="amount d-none">{{ $cart['subtotal'] }}</span>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="he1x">
								<div class="d-flex justify-content-between align-items-center" style="padding: 5px 0 5px 5px; border-bottom: 1px solid #8c8c8cd0;">
									Total Amount <small class="text-muted stylecap he1x">₱ {{ number_format(collect($cart_arr)->sum('subtotal'), 2, '.', ',') }}</small>
								</div>
								<div class="d-flex justify-content-between align-items-center d-none" id="discount-div">
									<p class="m-0">Discount <span id="voucher-code" class="text-white d-none" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">Voucher Applied</span></p>
									<small class="text-danger stylecap he1x">- ₱ <span id="discount-amount">0.00</span></small>
								</div>
								<div class="d-flex justify-content-between align-items-center" style="padding: 5px 0 5px 5px; border-bottom: 1px solid #8c8c8cd0;">
									Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">₱ {{ number_format(collect($cart_arr)->sum('subtotal'), 2, '.', ',') }}</small>
								</div>
							</div>
							<div class="he1x pt-0">
								<div class="alert alert-danger fade show mb-0 d-none" id="coupon-alert" role="alert" style="border: 1px solid;"></div>
								<label for="coupon-code" class="m-2 mb-0">Coupon</label>
								<div class="d-flex flex-row">
									<div class="p-2 col-md-10 col-lg-7 col-xl-10">
										<input type="text" id="coupon-code" class="form-control" placeholder="Enter Coupon Code" aria-label="Enter Coupon Code" aria-describedby="basic-addon2">
									</div>
									<div class="p-2 col-md-2 col-lg-5 col-xl-2">
										<button class="btn w-100 btn-outline-success" type="button" id="apply-coupon-btn">Apply</button></div>
									  </div>
								  <hr>
								<div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Grand Total <small class="stylecap he1x" id="grand-total">0.00</small>
								</div>
							</div>
						</div>						
					</div>
				</div>

				<div class="col-md-4">
					<div class="card he1x" style="background-color: #f4f4f4 !important; padding-bottom: 11px;">
						<p style="padding: 15px 0 0 20px;">Select Shipping Method</p>
						<hr style="margin: -10px 20px 0 20px;">
						<div class="card-body">
							<div class="he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">
								<div class="alert alert-warning alert-dismissible fade show text-center m-1 p-3 d-none" role="alert" id="alert-box"></div>
								<div id="voucher-free" class="d-none"></div>
								@php
									$sd_exists = false;
									if (in_array('Standard Delivery', array_column($shipping_rates, 'shipping_service_name'))) {
										$sd_exists = true;
									}
								@endphp
								@forelse ($shipping_rates as $l => $srate)
								@php
									if ($sd_exists) {
										$defaul_selected = ($srate['shipping_service_name'] == 'Standard Delivery') ? 'checked' : '';
									} else {
										$defaul_selected = ($loop->first) ? 'checked' : '';
									}
								@endphp
								<div class="d-flex justify-content-between" style="padding: 0 15px 0 15px;">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="shipping_fee" id="{{ 'sr' . $l }}" value="{{ $srate['shipping_cost'] }}" data-sname="{{ $srate['shipping_service_name'] }}" data-est="{{ $srate['expected_delivery_date'] }}" data-pickup="{{ $srate['pickup'] }}" required {{ $defaul_selected }} data-lead="{{ $srate['max_lead_time'] }}">
										<label class="form-check-label" for="{{ 'sr' . $l }}">{{ $srate['shipping_service_name'] }} <br class="d-xl-none"/>
											@if (count($srate['stores']) <= 0)<small class="fst-italic">({{ $srate['min_lead_time'] . " - ". $srate['max_lead_time'] . " Days" }})</small>@endif</label>
									</div>
									<small class="text-muted stylecap he1x" style="white-space: nowrap !important">₱ {{ number_format($srate['shipping_cost'], 2, '.', ',') }}</small>
								</div>
								@if (count($srate['stores']) > 0)
								<div class="row d-none" id="for-store-pickup">
									<div class="col-md-6 offset-md-3">
										<div class="form-group">
											<label for="store-selection">Select Store *</label>
											<select id="store-selection" class="form-control no-click-outline formslabelfnt" style="text-align: center;">
												<option value="">Select Store</option>
												@foreach ($srate['stores'] as $store)
												<option value="{{ $store->store_name }}" data-address="{{ $store->address }}" data-available-time="Available Time: <br>{{ date("h:i A", strtotime($store->available_from)) . ' - ' . date("h:i A", strtotime($store->available_to)) }}" data-start="{{ $store->available_from }}" data-end="{{ $store->available_to }}" data-leadtime="{{ $store->allowance_in_hours }}">{{ $store->store_name }}</option>
												@endforeach
											</select>
											<div class="m-1 text-left" id="store-address"></div>
											<div class="m-1 text-left" id="available-time"></div>
										</div>
									</div>
									<div class="col-md-6 offset-md-3 bootstrap-timepicker">
										<div class="form-group">
											<label for="pickup-time">Pickup by</label>
											<input type="text" class="form-control no-click-outline" id="pickup-time" style="text-align: center;" placeholder="Choose a date">
										</div>
									</div>
									<div class="col-md-6 offset-md-3">
										<div class="form-group">
											<label for="pickup-timeslot">Pickup Time</label>
											<select class="form-control no-click-outline" style="text-align: center;" id="pickup-timeslot">
												<option value="">Please select store</option>
											</select>
										</div>
									</div>
								</div>
								@endif
								@empty
									<h6 class="text-center">No available shipping methods.</h6>
								@endforelse
								<hr style="margin-right: 5px; margin-left: 5px;">
								<p class="d-none" id="est-div" style="font-size: 0.8rem; font-style: italic;">Estimated Delivery Date: <b><span id="estimated-delivery-date"></span></b></p>
							</div>
						</div>
						<p style="padding: 0 0 0 20px;">Select Payment Method</p>
						<hr style="margin: -10px 20px 0 20px;">
						<div class="card-body">
							<div class="he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">
								<div class="d-flex justify-content-between" style="padding: 0 15px 0 15px;">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="payment_method" value="CC" id="credit-card" data-ib="">
										<label class="form-check-label" for="credit-card">Visa / Master Card</label>
									</div>
								</div>
								<div class="d-flex justify-content-between" style="padding: 0 15px 0 15px;">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="payment_method" value="WA" id="gcash" data-ib="GCash3">
										<label class="form-check-label" for="gcash">GCash</label>
									</div>
								</div>
								<div class="d-flex justify-content-between" style="padding: 0 15px 0 15px;">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="payment_method" value="WA" id="grabpay" data-ib="GrabPayPH">
										<label class="form-check-label" for="grabpay">GrabPay</label>
									</div>
								</div>
								<hr style="margin-right: 5px; margin-left: 5px;">
								<p class="d-none" id="est-div" style="font-size: 0.8rem; font-style: italic;">Estimated Delivery Date: <b><span id="estimated-delivery-date"></span></b></p>
							</div>
						</div>		
					</div>

					<div class="row m-4">
						<div class="col-md-10 mx-auto">
							<div class="card">
									<div id="payment-form" class="d-none"></div>
									<button class="btn btn-lg btn-outline-primary" id="checkout-btn" style="border: 0;" {{ (count($shipping_rates) <= 0) ? 'disabled' : '' }}>PAY NOW</button>
							</div>
						</div>
						{{-- <div class="col-md-10 mx-auto mt-4">
								<a href="javascript:history.back()" class="btn btn-lg btn-outline-secondary text-white" id="checkout-btn" style="background-color: #777575 !important; border-color: #777575 !important; width: 100%; border-radius: 0;">BACK</a>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</main>

	{{-- Select Default Address --}}
	<div class="modal fade" id="selectShippingModal" tabindex="-1" role="dialog" aria-labelledby="selectShippingModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Shipping Address</h5>
				</div>
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th class="addressModal">Address</th>
								<th class="addressModal">Contacts</th>
								<th class="addressModal">Type</th>
								<th class="addressModal">Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($shipping_add as $key => $shipping_address)
							<tr>
								<td>
									<a href="/myprofile/address/{{ $shipping_address->id }}/shipping/change_default">
									@if($shipping_address->xdefault)
									<i class="fas fa-check-circle" style="font-size: 24px;"></i>
									@else
									<i class="far fa-check-circle" style="font-size: 24px; color:#ada8a8;"></i>
									@endif
									</a>
								</td>
								<td class="addressModal">{{ $shipping_address->xadd1 .' '. $shipping_address->xadd2 .' '. $shipping_address->xprov }}</td>
								<td class="addressModal">{{ $shipping_address->xcontactlastname1 .', '.$shipping_address->xcontactname1 }}</td>
								<td class="addressModal">{{ $shipping_address->add_type }}</td>
								<td class="addressModal">
									<button type="button" class="btn btn-success btn-xs shipping {{ $key }}" data-toggle="modal" data-target="#shipping-view{{ $shipping_address->id }}">
										<i class="fas fa-eye"></i>
									</button>
									<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#shipping-del{{ $shipping_address->id }}">
										<i class="fas fa-trash-alt"></i>
									</button>
									<div id="shipping-del{{ $shipping_address->id }}" class="modal fade" role="dialog">
										<form action="/myprofile/address/{{ $shipping_address->id }}/shipping/delete" method="POST">
											@csrf
											@method('delete')
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title">Confirmation</h4>
													</div>
													<div class="modal-body">
														<p class="text-center">Delete shipping address?</p>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary btn-xs">Confirm</button>
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id="shipping-view{{ $shipping_address->id }}" class="modal fade" role="dialog">
										<div class="modal-dialog" style="max-width: 80% !important;">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Shipping Information</h4>
													<a type="button" class="close clear-btn" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</a>
												</div>
												<form action="/myprofile/address/{{ $shipping_address->id }}/shipping/update" method="post">
													@csrf
												<div class="modal-body">
													<div class="row">
														<div class="col">
															<label for="x" class="myprofile-font-form"><strong>Contact Person</strong></label>
															<br>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<label for="Address1_1" class="myprofile-font-form">First Name : <span class="text-danger">*</span></label>
															<input type="text" name="first_name" class="form-control caption_1" value="{{ $shipping_address->xcontactname1 }}" required>
														</div>
														<div class="col-md-6">
															<label for="Address2_1" class="myprofile-font-form">Last Name : <span class="text-danger">*</span></label>
															<input type="text" name="last_name" class="form-control caption_1" value="{{ $shipping_address->xcontactlastname1 }}" required>
														</div>
													</div>
													<div class="row"><br></div>
													<div class="row">
														<div class="col-md-4">
															<label class="myprofile-font-form">Contact Number : </label>
															<input type="text" name="contact" class="form-control caption_1" value="{{ $shipping_address->xcontactnumber1 }}">
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">Mobile Number : <span class="text-danger">*</span></label>
															<input type="text" name="mobile" class="form-control caption_1" value="{{ $shipping_address->xmobile_number }}" required>
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">Contact Email : <span class="text-danger">*</span></label>
															<input type="text" name="email" class="form-control caption_1" value="{{ $shipping_address->xcontactemail1 }}" required>
														</div>
													</div>
													<div class="row">
														<div class="col">
															<br>
															<label for="x" class="myprofile-font-form"><strong>Address</strong></label>
															<br>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<label for="Address1_1" class="myprofile-font-form">Address Line 1 : <span class="text-danger">*</span></label>
															<input type="text" name="address1" class="form-control caption_1" value="{{ $shipping_address->xadd1 }}" required>
														</div>
														<div class="col-md-6">
															<label for="Address2_1" class="myprofile-font-form">Address Line 2 : </label>
															<input type="text" name="address2" class="form-control caption_1" value="{{ $shipping_address->xadd2 }}">
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-md-4">
															<label class="myprofile-font-form">Province : <span class="text-danger">*</span></label>
															<input type="text" name="province" id="province1_1_{{ $key }}" class="form-control caption_1" required value="{{ $shipping_address->xprov }}">
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
															<input type="text" name="city" id="City_Municipality1_1_{{ $key }}" class="form-control caption_1" required value="{{ $shipping_address->xcity }}">
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
															<input type="text" name="brgy" id="Barangay1_1_{{ $key }}" class="form-control caption_1" required value="{{ $shipping_address->xbrgy }}">
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-md-4">
															<label for="postal1_1" class="myprofile-font-form">Postal Code : <span class="text-danger">*</span></label>
															<input type="text" name="postal" class="form-control caption_1" value="{{ $shipping_address->xpostal }}" required>
														</div>
														<div class="col-md-4">
															<label for="country_region1_1" class="myprofile-font-form">Country / Region : <span class="text-danger">*</span></label>
															<input type="text" name="country" class="form-control caption_1" value="{{ $shipping_address->xcountry }}" required>
														</div>
														<div class="col-md-4">
															<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
															<select class="form-control formslabelfnt ship_type" name="Address_type1_1">
																<option value="Business Address" {{ $shipping_address->add_type == 'Business Address' ? 'selected' : '' }}>Business Address</option>
																<option value="Home Address" {{ $shipping_address->add_type == 'Home Address' ? 'selected' : '' }}>Home Address</option>
															</select>
														</div>
													</div>
													<br/>
													<div class="row" class="ship_for_business" id="ship_for_business_{{ $key }}" {!! $shipping_address->add_type != 'Business Address' ? 'style="display: none"' : '' !!}>
														<div class="col-md-6">
															<label for="business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
															<input type="text" class="form-control formslabelfnt" id="ship_business_name_{{ $key }}" name="business_name" value="{{ $shipping_address->xbusiness_name }}"><br class="d-lg-none d-xl-none"/>
														</div>
														<div class="col-md-6">
															<label for="tin" class="formslabelfnt">TIN Number :</label>
														<input type="checkbox" name="checkout" checked hidden />
														<input type="text" class="form-control formslabelfnt" id="tin" name="tin" value="{{ $shipping_address->xtin_no }}"><br class="d-lg-none d-xl-none"/>
														</div>
														<br>&nbsp;
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Save Changes</button>
												</div>
												</form>
											</div>
										</div>
									</div>
								</td>
							</tr>
							@empty
							<tr>
								<td colspan="5" class="text-center">No shipping address found.</td>
							</tr>
							@endforelse
						</tbody>
					</table>

					<a href="#" class="btn btn-primary" data-target="#addShippingModal" data-toggle="modal">Add New Shipping Address</a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="selectBillingModal" tabindex="-1" role="dialog" aria-labelledby="selectBillingModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Billing Address</h5>
				</div>
				<div class="modal-body">
					<table class="table">
					<thead>
						<tr>
							<th></th>
							<th class="addressModal">Address</th>
							<th class="addressModal">Contacts</th>
							<th class="addressModal">Type</th>
							<th class="addressModal">Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($billing_add as $key => $billing_address)
						<tr>
							<td class="addressModal">
								<a href="/myprofile/address/{{ $billing_address->id }}/billing/change_default/">
								@if ($billing_address->xdefault)
									<i class="fas fa-check-circle" style="font-size: 24px;"></i>
								@else
									<i class="far fa-check-circle" style="font-size: 24px; color:#ada8a8;"></i>
								@endif
								</a>
							</td>
							<td class="addressModal">{{ $billing_address->xadd1 . ' ' . $billing_address->xadd2 .' '. $billing_address->xprov}}</td>
							<td class="addressModal">{{ $billing_address->xcontactlastname1 . ', ' . $billing_address->xcontactname1 }}</td>
							<td class="addressModal">{{ $billing_address->add_type }}</td>
							<td class="addressModal">
								<button type="button" class="btn btn-success btn-xs billing {{ $key }}" data-toggle="modal" data-target="#myadd{{ $billing_address->id }}">
									<i class="fas fa-eye"></i>
								</button>
								
								<div id="myadd{{ $billing_address->id }}" class="modal fade" role="dialog">
									<div class="modal-dialog" style="max-width: 80% !important;">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Billing Information</h4>
												<a type="button" class="close clear-btn" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</a>
											</div>
											<form action="/myprofile/address/{{ $billing_address->id }}/billing/update" method="post">
												@csrf
											<div class="modal-body">
												<div class="row">
													<div class="col">
														<label for="x" class="myprofile-font-form"><strong>Contact Person</strong></label>
														<br>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">First Name : <span class="text-danger">*</span></label>
														<input type="text" name="first_name" class="form-control caption_1" value="{{ $billing_address->xcontactname1 }}" required>
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Last Name : <span class="text-danger">*</span></label>
														<input type="text" name="last_name" class="form-control caption_1" value="{{ $billing_address->xcontactlastname1 }}" required>
													</div>
												</div>
												<div class="row"><br></div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">Contact Number : </label>
														<input type="text" name="contact" class="form-control caption_1" value="{{ $billing_address->xcontactnumber1 == 0 ? '' : $billing_address->xcontactnumber1 }}">
													</div>
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">Mobile Number : <span class="text-danger">*</span></label>
														<input type="text" name="mobile" class="form-control caption_1" value="{{ $billing_address->xmobile_number }}" required>
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Contact Email : <span class="text-danger">*</span></label>
														<input type="text" name="email" class="form-control caption_1" value="{{ $billing_address->xcontactemail1 }}" required>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<br>
														<label for="x" class="myprofile-font-form"><strong>Address</strong></label>
														<br>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">Address Line 1 : <span class="text-danger">*</span></label>
														<input type="text" name="address1" class="form-control caption_1" value="{{ $billing_address->xadd1 }}" required>
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Address Line 2 : </label>
														<input type="text" name="address2" class="form-control caption_1" value="{{ $billing_address->xadd2 }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label class="myprofile-font-form">Province : <span class="text-danger">*</span></label>
														<input type="text" name="province" id="bill_province1_1_{{ $key }}" class="form-control caption_1" required value="{{ $billing_address->xprov }}">
													</div>
													<div class="col">
														<label class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
														<input type="text" name="city" id="bill_City_Municipality1_1_{{ $key }}" class="form-control caption_1" required value="{{ $billing_address->xcity }}">
													</div>
													<div class="col">
														<label class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
														<input type="text" name="brgy" id="bill_Barangay1_1_{{ $key }}" class="form-control caption_1" required value="{{ $billing_address->xbrgy }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label for="postal1_1" class="myprofile-font-form">Postal Code : <span class="text-danger">*</span></label>
														<input type="text" name="postal" class="form-control caption_1" value="{{ $billing_address->xpostal }}" required>
													</div>
													<div class="col">
														<label for="country_region1_1" class="myprofile-font-form">Country / Region : <span class="text-danger">*</span></label>
														<input type="text" name="country" class="form-control caption_1" value="{{ $billing_address->xcountry }}" required>
													</div>
													<div class="col-md-4">
														<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
														<select class="form-control formslabelfnt bill_type" id="bill_Address_type1_1" name="Address_type1_1" required>
															<option value="Business Address" {{ $billing_address->add_type == 'Business Address' ? 'selected' : '' }}>Business Address</option>
															<option value="Home Address" {{ $billing_address->add_type == 'Home Address' ? 'selected' : '' }}>Home Address</option>
														</select>
													</div>
												</div>
												<br/>
												<div class="row" id="bill_for_business_{{ $key }}" {!! $billing_address->add_type != 'Business Address' ? 'style="display: none"' : '' !!}>
													<div class="col-md-6">
														<label for="business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
														<input type="text" class="form-control formslabelfnt" id="bill_business_name_{{ $key }}" name="business_name" value="{{ $billing_address->xbusiness_name }}"><br class="d-lg-none d-xl-none"/>
													</div>
													<div class="col-md-6">
														<input type="checkbox" name="checkout" checked hidden />
														<label for="tin" class="formslabelfnt">TIN Number :</label>
														<input type="text" class="form-control formslabelfnt" id="tin" name="tin" value="{{ $billing_address->xtin_no }}"><br class="d-lg-none d-xl-none"/>
													</div>
													<br>&nbsp;
												</div>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">Save Changes</button>
											</div>
											</form>
										</div>
									</div>
								</div>

								<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myDelete{{ $billing_address->id }}">
									<i class="fas fa-trash-alt"></i>
								</button>

								<div id="myDelete{{ $billing_address->id }}" class="modal fade" role="dialog">
									<form action="/myprofile/address/{{ $billing_address->id }}/billing/delete" method="POST">
										@csrf
										@method('delete')
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Confirmation</h4>
												</div>
												<div class="modal-body">
													<p class="text-center">Delete billing address?</p>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary btn-xs">Confirm</button>
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								{{-- @endif --}}
							</td>
						</tr> 
						@empty
						<tr>
							<td colspan="5" class="text-center">No billing address found.</td>
						</tr>
						@endforelse
					</tbody>
				</table>

					<a href="#" class="btn btn-primary" data-target="#addBillingModal" data-toggle="modal">Add New Billing Address</a>
				</div>
			</div>
		</div>
	</div>
	{{-- Update Address Modal --}}

	{{-- Add Address Modal --}}

	<div class="modal fade" id="addShippingModal" tabindex="-1" role="dialog" aria-labelledby="addShippingModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">New Shipping Address</h5>
				</div>
				<form action="/checkout/update_shipping" method="POST">
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<label for="fname" class="formslabelfnt">First Name : <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control formslabelfnt" id="logtype" name="logtype" value="1" required>
								<input type="text" class="form-control formslabelfnt" id="fname" name="fname" required value="{{ old('fname') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-6">
								<label for="lname" class="formslabelfnt">Last Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="lname" name="lname" required value="{{ old('lname') }}"><br class="d-lg-none d-xl-none"/>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="Address1_1" class="formslabelfnt">Address Line 1 : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_Address1_1" name="ship_Address1_1" required value="{{ old('ship_Address1_1') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-6">
								<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
								<input type="text" class="form-control formslabelfnt" id="Address2_1" name="ship_Address2_1" value="{{ old('ship_Address2_1') }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<label for="ship_province1_1" class="formslabelfnt">Province : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_province1_1" name="ship_province1_1" required value="{{ old('ship_province1_1') }}">
								<br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="ship_City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_City_Municipality1_1" name="ship_City_Municipality1_1" required><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="ship_Barangay1_1" class="formslabelfnt">Barangay : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_Barangay1_1" name="ship_Barangay1_1" required value="{{ old('ship_Barangay1_1') }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<label for="postal1_1" class="formslabelfnt">Postal Code : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_postal1_1" min="0" name="ship_postal1_1" required value="{{ old('ship_postal1_1') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">{{-- Country Select --}}
								@php
									$countries = ["Philippines"];
								@endphp
								<label for="country_region1_1" class="formslabelfnt">Country / Region : <span class="text-danger">*</span></label>
								<select class="form-control formslabelfnt" id="ship_country_region1_1" name="ship_country_region1_1" required>
									<option disabled value="">Choose...</option>
									@foreach ($countries as $country)
									@php
										if (old('ship_country_region1_1')) {
											$s1 = old('ship_country_region1_1') == $country ? 'selected' : '';
										} else {
											$s1 = $country == 'Philippines' ? 'selected' : '';
										}
									@endphp
									<option value="{{ $country }}" {{ $s1 }}>{{ $country }}</option>
									@endforeach
								</select><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
								<select class="form-control formslabelfnt" id="ship_Address_type1_1" name="ship_Address_type1_1" required>
									<option selected disabled value="">Choose...</option>
									<option value="Business Address">Business Address</option>
									<option value="Home Address">Home Address</option><br class="d-lg-none d-xl-none"/>
								</select>
							</div>
						</div>
						<br/>
						<div class="row" id="ship_for_business" style="display: none">
							<div class="col-md-6">
								<label for="ship_business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_business_name" name="ship_business_name" required><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-6">
								<label for="ship_tin" class="formslabelfnt">TIN Number :</label>
								<input type="text" class="form-control formslabelfnt" id="ship_tin" name="ship_tin"><br class="d-lg-none d-xl-none"/>
							</div>
							<br>&nbsp;
						</div>
						<div class="row">
							<div class="col-md-4">
								<label for="email1_1" class="formslabelfnt">Email Address : <span class="text-danger">*</span></label>
								<input type="email" class="form-control formslabelfnt" id="ship_email" name="ship_email" required value="{{ old('ship_email') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="contactnumber1_1" class="formslabelfnt">Mobile Number : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_mobilenumber1_1" name="ship_mobilenumber1_1" required value="{{ old('ship_mobilenumber1_1') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="contactnumber1_1" class="formslabelfnt">Contact Number : </label>
								<input type="text" class="form-control formslabelfnt" id="contactnumber1_1" name="contactnumber1_1" value="{{ old('contactnumber1_1') }}"><br class="d-lg-none d-xl-none"/>
							</div>
						</div>
						<small style="font-style: italic; font-size: 0.75rem; margin-top: 20px; display: block;">Note: * Required information</small>
						<br>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addBillingModal" tabindex="-1" role="dialog" aria-labelledby="addBillingModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">New Billing Address</h5>
				</div>
				<form action="/checkout/update_billing" method="POST">
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<label for="fname" class="formslabelfnt">First Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="bill_fname" name="bill_fname" value="{{ old('bill_fname') }}" required>
							</div>
							<div class="col-md-6">
								<label for="lname" class="formslabelfnt">Last Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="bill_lname" name="bill_lname" value="{{ old('bill_lname') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="Address1_1" class="formslabelfnt">Address Line 1 : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="Address1_1" name="Address1_1" value="{{ old('Address1_1') }}" required>
							</div>
							<div class="col-md-6">
								<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
								<input type="text" class="form-control formslabelfnt" id="Address2_1" name="Address2_1" value="{{ old('Address2_1') }}">
							</div>
						</div>
						<br>
						<div class="row" id="bill_add">
							<div class="col-md-4">
								<label for="province1_1" class="formslabelfnt">Province : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="province1_1" name="province1_1" required>
							</div>
							<div class="col-md-4">
								<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="City_Municipality1_1" name="City_Municipality1_1" required>
							</div>
							<div class="col-md-4">
								<label for="Barangay1_1" class="formslabelfnt">Barangay : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="Barangay1_1" name="Barangay1_1" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<label for="postal1_1" class="formslabelfnt">Postal Code : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="postal1_1" name="postal1_1" value="{{ old('postal1_1') }}" required>
							</div>
							<div class="col-md-4">{{-- Country Select --}}
								<label for="country_region1_1" class="formslabelfnt">Country / Region : <span class="text-danger">*</span></label>
								<select class="form-control formslabelfnt" id="country_region1_1" name="country_region1_1" required>
									<option disabled value="">Choose...</option>
									@foreach ($countries as $country)
									@php
										if (old('country_region1_1')) {
											$s2 = old('country_region1_1') == $country ? 'selected' : '';
										} else {
											$s2 = $country == 'Philippines' ? 'selected' : '';
										}
									@endphp
									<option value="{{ $country }}" {{ $s2 }}>{{ $country }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-4">
								<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
								<select class="form-control formslabelfnt" id="Address_type1_1" name="Address_type1_1" required>
									<option disabled selected value="">Choose...</option>
									<option value="Business Address">Business Address</option>
									<option value="Home Address">Home Address</option>
								</select>
							</div>
						</div>
						<br/>
						<div class="row" id="bill_for_business" style="display: none">
							<div class="col-md-6">
								<label for="bill_business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="bill_business_name" name="bill_business_name" required><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-6">
								<label for="bill_tin" class="formslabelfnt">TIN Number :</label>
								<input type="text" class="form-control formslabelfnt" id="bill_tin" name="bill_tin"><br class="d-lg-none d-xl-none"/>
							</div>
							<br>&nbsp;
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="email1_1" class="formslabelfnt">Email Address : <span class="text-danger">*</span></label>
								<input type="email" class="form-control formslabelfnt" id="email" name="email" value="{{ old('email') }}" required>
							</div>
							<div class="col-md-6">
								<label for="mobilenumber1_1" class="formslabelfnt">Mobile Number : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="mobilenumber1_1" name="mobilenumber1_1" value="{{ old('mobilenumber1_1') }}" required>
							</div>
						</div>
						<small style="font-style: italic; font-size: 0.75rem; margin-top: 20px; display: block;">Note: * Required information</small>
						<br>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="custom-overlay" style="display: none;">
		<div class="custom-spinner"></div>
		<br/>
		Loading...
	</div>

@endsection

@section('style')
<style>
	#item-preloader {
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		z-index: 999999;
		opacity: 0.5;
		background: rgba( 235, 237, 239 );
		transition: opacity 200ms ease-in-out;
		border-radius: 4px;
		width: 100%;
		height: 100%;
	}
	#available-time {
		font-weight: normal;
	}
	#store-address {
		font-weight: normal;
	}
	.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {	
		background: none !important;
		color: #999 !important;
		cursor: default !important;
	}
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
	.select2-selection__rendered {
		line-height: 34px !important;
	}
	.select2-container .select2-selection--single {
		height: 37px !important;
	}
	.select2-selection__arrow {
		height: 35px !important;
	}
	.addressModal, .formslabelfnt{
		font-size: 15px !important;
	}

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
	.clear-btn{
		border: none !important;
		background-color: rgba(0,0,0,0);
		color: #000;
		font-size: 24px;
		text-decoration: none !important;
		text-transform: none !important;
	}
	.clear-btn:hover{
		color: #000;
	}
	@keyframes rotate {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
	@media (max-width: 575.98px) {
		.breadcrumb{
			font-size: 8pt !important;
			font-weight: 500;
		}
	}

	@media (max-width: 767.98px) {
		.breadcrumb{
			font-size: 8pt !important;
			font-weight: 500;
		}
	}
</style>
@endsection

@section('script')
<!-- Select2 -->
<script src="{{ asset('/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- bootstrap datepicker -->
<script src="{{ asset('/datepicker/bootstrap-datepicker.js') }}"></script>

<script>
	$(document).ready(function() {
		$(window).bind("pageshow", function(event) {
			if (event.originalEvent.persisted) {
				window.location.reload(); 
			}
		});

		var sizeTheOverlays = function() {
			$("#item-preloader").resize().each(function() {
				var h = $(this).parent().outerHeight();
				var w = $(this).parent().outerWidth();
				$(this).css("height", h);
				$(this).css("width", w);
			});
		};

		sizeTheOverlays();

		$('#coupon-code').val('');
		$("#coupon-code").keyup(function () {  
            $(this).val($(this).val().toUpperCase());  
        });

		$('#apply-coupon-btn').click(function(e){
			e.preventDefault();

			if ($('#coupon-code').val() === '') {
				$('#coupon-code').addClass('is-invalid');
			} else {
				$("#item-preloader").fadeIn();
				$.ajax({
					url: '/checkout/apply_voucher/' + $('#coupon-code').val(),
					type:"GET",
					success: function (response) {
						$('#free-shipping-voucher').remove();
						if (response.status == 0) {
							$('#coupon-alert').removeClass('d-none').text(response.message);
							$('.voucher-item-code').addClass('d-none');
							$('#voucher-code').addClass('d-none').text('');
							$('#discount-div').addClass('d-none');
							$('#discount-amount').text('0.00');
							$('#voucher-free').empty();

							$("input:radio[name='shipping_fee']:first").attr('checked', true);
						} else {
							var discount = (isNaN(response.discount)) ? 0 : response.discount;
							$('#voucher-code').removeClass('d-none').text(response.voucher_code);
							$('#discount-div').removeClass('d-none');
							$('#discount-amount').text(discount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
							$('#coupon-code').removeClass('is-invalid');
							$('#coupon-alert').addClass('d-none');

							if(response.item_applied_discount.length > 0) {
								$.each(response.item_applied_discount, function(d, a){
									$('#voucherApp' + a).removeClass('d-none');
								});
							} else {
								$('.voucher-item-code').addClass('d-none');
							}
							var existing_fd = $("input:radio[name='shipping_fee'][data-sname='Free Delivery']").length;
							if (existing_fd <= 0) {
								if(response.shipping && response.shipping.length != 0) {
									var el = '<div class="d-flex justify-content-between align-items-center" id="free-shipping-voucher">' +
										'<div class="form-check">' +
										'<input class="form-check-input" type="radio" name="shipping_fee" id="0l" value="'+ response.shipping.shipping_cost+'" data-sname="'+ response.shipping.shipping_service_name+'" data-est="'+ response.shipping.expected_delivery_date+'" data-pickup="false" required data-lead="'+ response.shipping.max_lead_time+'">' +
										'<label class="form-check-label" for="0l">'+ response.shipping.shipping_service_name+' <br class="d-xl-none"/>' +
										'<small class="fst-italic">('+ response.shipping.min_lead_time+' - '+ response.shipping.max_lead_time+' Days)</small></label>' +
										'</div>' +
										'<small class="text-muted stylecap he1x" style="white-space: nowrap !important">₱ 0.00</small>' +
										'</div>';
								
									$('#voucher-free').html(el).removeClass('d-none');
								}
							}
						}

						updateTotal();
						
						$("#item-preloader").fadeOut();
					},
					error : function(data) {
						$('#coupon-alert').removeClass('d-none').text('An error occured. Please try again.');
					}
				});
			}
		});
		updateTotal();

		// Add Address
		$('#ship_province1_1').select2({
			dropdownParent: $('#addShippingModal')
		});

		$('#ship_City_Municipality1_1').select2({
			dropdownParent: $('#addShippingModal')
		});

		$('#ship_Barangay1_1').select2({
			dropdownParent: $('#addShippingModal')
		});

		$('#province1_1').select2({
			dropdownParent: $('#addBillingModal')
		});

		$('#City_Municipality1_1').select2({
			dropdownParent: $('#addBillingModal')
		});

		$('#Barangay1_1').select2({
			dropdownParent: $('#addBillingModal')
		});
		// Add Address

		$('#ship_Address_type1_1').change(function(){
			if($(this).val() == "Business Address"){
				$('#ship_for_business').slideDown();
				$("#ship_business_name").prop('required',true);
			}else{
				$('#ship_for_business').slideUp();
				$("#ship_business_name").prop('required',false);
			}
		});

		$('#Address_type1_1').change(function(){
			if($(this).val() == "Business Address"){
				$('#bill_for_business').slideDown();
				$("#bill_business_name").prop('required',true);
			}else{
				$('#bill_for_business').slideUp();
				$("#bill_business_name").prop('required',false);
			}
		});

		$(document).on('change', 'input[name="shipping_fee"]', function(){
			updateTotal();
		});

		$('#checkout-btn').click(function(e){
			e.preventDefault();
			saveOrder();
		});

		function callback(data) {
			if(data.status == 2) {
				alert(data.message);
			} else if (data.status == 1) {
				$('#payment-form').empty();
				$.ajax({
					url: '/eghlform/' + data.id,
					type:"GET",
					success:function(data){
						$('#payment-form').html(data);
						checkForm();
					},
					error : function(data) {
						console.log(data);
					}
				});
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

		function saveOrder() {
			var s_name = $("input[name='shipping_fee']:checked").data('sname');
			var s_amount = $("input[name='shipping_fee']:checked").val();
			var estimated_del = $("input[name='shipping_fee']:checked").data('est');

			var ispick = $("input[name='shipping_fee']:checked").data('pickup');
			var picktime = $('#pickup-time').val();
			var timeslot = $('#pickup-timeslot').val();
			var storeloc = $("#store-selection").val();

			var pay_name = $("input[name='payment_method']:checked").val();
			var ib = $("input[name='payment_method']:checked").data('ib');
			
			var data = {
				estimated_del, s_name, s_amount, _token: '{{ csrf_token() }}', storeloc, picktime, timeslot, pay_name, ib
			}

			if (!s_name) {
				$('#alert-box').removeClass('d-none').text('Please select shipping method.');
			} else if (!pay_name) {
				$('#alert-box').removeClass('d-none').text('Please select payment method.');
			} else if(ispick && (!storeloc || !picktime || !timeslot)) {
				$('#alert-box').removeClass('d-none').text('Please select store and pickup date & time');
			} else {
				$('#custom-overlay').fadeIn();

				$.ajax({
					url: '/order/save',
					type:"POST",
					data: data,
					success: callback,
					error : function(data) {
						$('#alert-box').removeClass('d-none').text('An error occured. Please try again.');
					}
				});
			}
		}

		function updateTotal() {
			var subtotal = 0;
			$('#cart-items tbody tr').each(function(){
				var amount = $(this).find('.amount').eq(0).text();
				subtotal += parseFloat(amount);
			});

			subtotal = (isNaN(subtotal)) ? 0 : subtotal;

			var voucher_discount = parseFloat($('#discount-amount').text().replace(/,/g , ''));
			voucher_discount = (isNaN(voucher_discount)) ? 0 : voucher_discount;

			subtotal = subtotal - voucher_discount;

			$('#cart-subtotal').text('₱ ' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));

			var shipping_fee = $("input[name='shipping_fee']:checked").val();
			var total = parseFloat(shipping_fee) + subtotal;

			var estimated_del = $("input[name='shipping_fee']:checked").data('est');
			if (estimated_del) {
				$('#est-div').removeClass('d-none');
				$('#estimated-delivery-date').text(estimated_del);
			} else {
				$('#est-div').addClass('d-none');
				$('#estimated-delivery-date').text('');
			}

			if($("input[name='shipping_fee']:checked").data('pickup')) {
				$('#for-store-pickup').removeClass('d-none');
				$('#store-selection').val('');
				$('#store-selection').attr('required', true);
				$('#pickup-time').attr({
					'required': true,
					'placeholder': 'Please select store',
					'disabled': true
				});
				$('#pickup-timeslot').attr({
					'required': true,
					'disabled': true
				});
			}else{
				$('#for-store-pickup').addClass('d-none');
				$('#store-selection').removeAttr('required');
				$('#pickup-time').removeAttr('required');
				$('#pickup-timeslot').removeAttr('required');
				$('#available-time').text('');
				$('#store-address').text('');
			}

			total = (isNaN(total)) ? 0 : total;

			$('#grand-total').text('₱ ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));

			$("#total_amount").val($('#grand-total').text());
		}

		var provinces_edit = [];
		// Edit Shipping Address
		$('.shipping').click(function(){ 
			var ship_key = $(this).attr('class').split(' ').pop();

			$('.ship_type').change(function(){
				if($(this).val() == "Business Address"){
					$("#ship_for_business_"+ship_key).slideDown();
					$("#ship_business_name_"+ship_key).prop('required',true); 	
				}else{
					$("#ship_for_business_"+ship_key).slideUp();
					$("#ship_business_name_"+ship_key).prop('required',false);
				}
			});

			var provinces_shipping = [];
			$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
				$.each(obj.results, function(e, i) {
					provinces_shipping.push({
						id: i.text,
						code: i.provCode,
						text: i.text
					});
				});

				var province = $('#province1_1_'+ship_key).val();
				var city = $('#City_Municipality1_1_'+ship_key).val();

				$('#province1_1_'+ship_key).select2({
					placeholder: 'Select Province',
					data: provinces_shipping
				});

				var provCodeShipping = provinces_shipping.filter(function(obj) {
					return (obj.id === province);
				});

				var cities_shipping = [];
				$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
					var filtered_cities = $.grep(obj.results, function(v) {
						return v.provCode === provCodeShipping[0].code;
					});

					$.each(filtered_cities, function(e, i) {
						cities_shipping.push({
							id: i.text,
							code: i.citymunCode,
							text: i.text,
							
						});
					});

					$('#City_Municipality1_1_'+ship_key).select2({
						placeholder: 'Select City',
						data: cities_shipping
					});

					var cityCodeShipping = cities_shipping.filter(function(obj) {
						return (obj.id === city);
					});

					var brgy_shipping = [];
					$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
						var filtered = $.grep(obj.results, function(v) {
							return v.citymunCode === cityCodeShipping[0].code;
						});

						$.each(filtered, function(e, i) {
							brgy_shipping.push({
								id: i.brgyDesc,
								text: i.brgyDesc
							});
						});

						$('#Barangay1_1_'+ship_key).select2({
							placeholder: 'Select Barangay',
							data: brgy_shipping
						});
					});
				});
			});

			$(document).on('select2:select', '#province1_1_'+ship_key, function(e){
				var data = e.params.data;
				var select_el = $('#City_Municipality1_1_'+ship_key);
				var cities = [];

				select_el.empty();
				$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
					var filtered_cities = $.grep(obj.results, function(v) {
						return v.provCode === data.code;
					});

					$.each(filtered_cities, function(e, i) {
						cities.push({
							id: i.text,
							code: i.citymunCode,
							text: i.text,
						});
					});

					select_el.select2({
						placeholder: 'Select City',
						data: cities
					});
				});
			});

			$(document).on('select2:select', '#City_Municipality1_1_'+ship_key, function(e){
				var data = e.params.data;
				var select_el = $('#Barangay1_1_'+ship_key);
				var brgy = [];

				select_el.empty();
				$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
					var filtered = $.grep(obj.results, function(v) {
						return v.citymunCode === data.code;
					});

					$.each(filtered, function(e, i) {
						brgy.push({
							id: i.brgyDesc,
							text: i.brgyDesc
						});
					});

					select_el.select2({
						placeholder: 'Select Barangay',
						data: brgy
					});
				});
			});
		});
		// Edit Shipping Address
		
		// Edit Billing Address
		$('.billing').click(function(){
			var bill_key = $(this).attr('class').split(' ').pop();
			$('.bill_type').change(function(){
				if($(this).val() == "Business Address"){
					$("#bill_for_business_"+bill_key).slideDown();
					$("#bill_business_name_"+bill_key).prop('required',true); 	
				}else{
					$("#bill_for_business_"+bill_key).slideUp();
					$("#bill_business_name_"+bill_key).prop('required',false);
				}
			});

			var provinces_billing = [];
			$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
				$.each(obj.results, function(e, i) {
					provinces_billing.push({
						id: i.text,
						code: i.provCode,
						text: i.text
					});
				});

				var province = $('#bill_province1_1_'+bill_key).val();
				var city = $('#bill_City_Municipality1_1_'+bill_key).val();

				$('#bill_province1_1_'+bill_key).select2({
					placeholder: 'Select Province',
					data: provinces_billing
				});

				var provCode = provinces_billing.filter(function(obj) {
					return (obj.id === province);
				});

				var cityCode = [];
				var cities_billing = [];
				$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
					var filtered_cities = $.grep(obj.results, function(v) {
						return v.provCode === provCode[0].code;
					});

					$.each(filtered_cities, function(e, i) {
						cities_billing.push({
							id: i.text,
							code: i.citymunCode,
							text: i.text,
							
						});
					});

					$('#bill_City_Municipality1_1_'+bill_key).select2({
						placeholder: 'Select City',
						data: cities_billing
					});

					var cityCode = cities_billing.filter(function(obj) {
						return (obj.id === city);
					});

					var brgy_billing = [];
					$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
						var filtered = $.grep(obj.results, function(v) {
							return v.citymunCode === cityCode[0].code;
						});

						$.each(filtered, function(e, i) {
							brgy_billing.push({
								id: i.brgyDesc,
								text: i.brgyDesc
							});
						});

						$('#bill_Barangay1_1_'+bill_key).select2({
							placeholder: 'Select Barangay',
							data: brgy_billing
						});
					});
				});
			});

			$(document).on('select2:select', '#bill_province1_1_'+bill_key, function(e){
				var data = e.params.data;
				var select_el = $('#bill_City_Municipality1_1_'+bill_key);
				var cities_bill = [];

				select_el.empty();
				$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
					var filtered_cities = $.grep(obj.results, function(v) {
						return v.provCode === data.code;
					});

					$.each(filtered_cities, function(e, i) {
						cities_bill.push({
							id: i.text,
							code: i.citymunCode,
							text: i.text,
							
						});
					});

					select_el.select2({
						placeholder: 'Select City',
						data: cities_bill
					});
				});
			});

			$(document).on('select2:select', '#bill_City_Municipality1_1_'+bill_key, function(e){
				var data = e.params.data;
				var select_el = $('#bill_Barangay1_1_'+bill_key);
				var brgy_bill = [];

				select_el.empty();
				$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
					var filtered = $.grep(obj.results, function(v) {
						return v.citymunCode === data.code;
					});

					$.each(filtered, function(e, i) {
						brgy_bill.push({
							id: i.brgyDesc,
							text: i.brgyDesc
						});
					});

					select_el.select2({
						placeholder: 'Select Barangay',
						data: brgy_bill
					});
				});
			});
		});
		// Edit Billing Address

		// Add Address
		var str = "{{ implode(',', $shipping_zones) }}";
		var res = str.split(",");
		var provinces = [];
		$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
			var filtered_province = $.grep(obj.results, function(v) {
				return $.inArray(v.text, res) > -1;
			});

			$.each(filtered_province, function(e, i) {
				provinces.push({
					id: i.text,
					code: i.provCode,
					text: i.text
				});
			});

			$('#ship_province1_1').select2({
				placeholder: 'Select Province',
				data: provinces
			});

			$('#ship_City_Municipality1_1').select2({
				placeholder: 'Select City',
			});

			$('#ship_Barangay1_1').select2({
				placeholder: 'Select Barangay',
			});
		});

		$(document).on('select2:select', '#ship_province1_1', function(e){
			var data = e.params.data;
			var select_el = $('#ship_City_Municipality1_1');
			var cities = [];

			select_el.empty();
			$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
				var filtered_cities = $.grep(obj.results, function(v) {
					return v.provCode === data.code;
				});

				$.each(filtered_cities, function(e, i) {
					cities.push({
						id: i.text,
						code: i.citymunCode,
						text: i.text,
					});
				});

				select_el.select2({
					placeholder: 'Select City',
					data: cities
				});
			});
		});

		$(document).on('select2:select', '#ship_City_Municipality1_1', function(e){
			var data = e.params.data;
			var select_el = $('#ship_Barangay1_1');
			var brgy = [];

			select_el.empty();
			$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
				var filtered = $.grep(obj.results, function(v) {
					return v.citymunCode === data.code;
				});

				$.each(filtered, function(e, i) {
					brgy.push({
						id: i.brgyDesc,
						text: i.brgyDesc
					});
				});

				select_el.select2({
					placeholder: 'Select Barangay',
					data: brgy
				});
			});
		});

		var provinces_bill = [];
		$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
			var filtered_province_bill = $.grep(obj.results, function(v) {
				return $.inArray(v.text, res) > -1;
			});

			$.each(filtered_province_bill, function(e, i) {
				provinces.push({
					id: i.text,
					code: i.provCode,
					text: i.text
				});
			});
			
			$.each(filtered_province_bill, function(e, i) {
				provinces_bill.push({
					id: i.text,
					code: i.provCode,
					text: i.text
				});
			});

			$('#province1_1').select2({
				placeholder: 'Select Province',
				data: provinces_bill
			});

			$('#City_Municipality1_1').select2({
				placeholder: 'Select City',
			});

			$('#Barangay1_1').select2({
				placeholder: 'Select Barangay',
			});
		});

		$(document).on('select2:select', '#province1_1', function(e){
			var data = e.params.data;
			var select_el = $('#City_Municipality1_1');
			var cities_bill = [];

			select_el.empty();
			$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
				var filtered_cities = $.grep(obj.results, function(v) {
					return v.provCode === data.code;
				});

				$.each(filtered_cities, function(e, i) {
					cities_bill.push({
						id: i.text,
						code: i.citymunCode,
						text: i.text,
						
					});
				});

				select_el.select2({
					placeholder: 'Select City',
					data: cities_bill
				});
			});
		});

		$(document).on('select2:select', '#City_Municipality1_1', function(e){
			var data = e.params.data;
			var select_el = $('#Barangay1_1');
			var brgy_bill = [];

			select_el.empty();
			$.getJSON("{{ asset('/json/brgy.json') }}", function(obj){
				var filtered = $.grep(obj.results, function(v) {
					return v.citymunCode === data.code;
				});

				$.each(filtered, function(e, i) {
					brgy_bill.push({
						id: i.brgyDesc,
						text: i.brgyDesc
					});
				});

				select_el.select2({
					placeholder: 'Select Barangay',
					data: brgy_bill
				});
			});
		});

		$('#store-selection').on('change', function(e){
			$('#pickup-timeslot').empty();
			$('#pickup-time').attr({
					'required': true,
					'placeholder': 'Choose a date',
					'disabled': false
				});
				$('#pickup-timeslot').attr({
					'required': true,
					'disabled': false
				});

			var leadtime = $('#store-selection option:selected').data('leadtime');

			$('#pickup-time').datepicker('update', '');
			$('#pickup-time').datepicker('remove', 'startDate');

			$("#pickup-time").datepicker({
				showInputs: false,
				startDate: '+'+ (leadtime/24) +'d',
				format: 'D, M. dd, yyyy',
				autoclose: true,
				daysOfWeekDisabled: [0],
			}).on('changeDate', function(e) {
				var s1 = $('#store-selection option:selected').data('start');
				var s2 = $('#store-selection option:selected').data('end');

				var start = new Date(new Date(e.date).toLocaleDateString() + ' ' + s1);
				var end = new Date(new Date(e.date).toLocaleDateString() + ' ' + s2);

				var startHour = start.getHours();
				var endHour = end.getHours();

				var am = [];
				var pm = [];
				var p = 1;
				for (let i = (startHour + 1); i <= endHour; i++) {
					if (i <= 12) {
						am.push(i);
					} else {
						pm.push(p);
						p++;
					}
				}

				var am_min = Math.min.apply(Math, am) + ':00 AM';
				var am_max = Math.max.apply(Math, am) + ((Math.max.apply(Math, am) == 12) ? ':00 NN' : ':00 AM');
				var pm_min = Math.min.apply(Math, pm) + ':00 PM';
				var pm_max = Math.max.apply(Math, pm) + ':00 PM';
				
				var timeslots = [am_min + ' - ' + am_max, pm_min + ' - ' + pm_max];
				var opt = '<option value="">Select timeslot</option>';
				$.each(timeslots, function(i, d){
					opt += '<option value="' + d + '">' + d + '</option>';
				});

				$('#pickup-timeslot').empty();
				$('#pickup-timeslot').append(opt);
			});

			if ($(this).val()) {
				var available_time = $(this).find(':selected').data('available-time');
				$('#available-time').html(available_time);
				$('#store-address').text('Address: ' + $(this).find(':selected').data('address'));
			} else {
				$('#available-time').text('');
				$('#store-address').text('');
			}
		});
	});

</script>
@endsection
