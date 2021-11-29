@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
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

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container marketing">
		  	<br>
			<div class="row">
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				@if(session()->has('success'))
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								{!! session()->get('success') !!}
							</div>
						</div>
					</div>
				@endif
				@if(session()->has('error'))
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								{!! session()->get('error') !!}
							</div>
						</div>
					</div>
				@endif
				<div class="col-md-12 col-lg-8 mx-auto">
					<div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
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
								$checkbox = "d-none";
								$col = "6";
								$ship_text = '';
							}
						@endphp
						<div class="card-body he1x" style="padding-bottom: 0px !important; font-size:1rem !important;" >
							<strong>Your Order No : <span id="order-no">{{ $order_no }}</span></strong>
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">
							Customer Name :  {{ (Auth::check()) ? Auth::user()->f_name . " " . Auth::user()->f_lname : $shipping_fname." ".$shipping_lname }}
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Email Address :  {{ (Auth::check()) ? Auth::user()->username : $shipping_email }}
							<div class="card-body he1x" style="padding-bottom: 0px !important;">
								<div class="accordion" id="accordionExample">
									<div class="row">
										<div class="col-md-12 col-lg-{{ $col }} d-flex align-items-stretch">
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
																	<a href="#" style="font-size: 14px; width:100%; text-decoration: none;" role="button" data-toggle="modal" data-target="#selectShippingModal">UPDATE YOUR ADDRESS</a>
																@endif
															</div>
														</div>
													</h2>
												</div>

												<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
													<div class="card-body" style="padding: 10px !important">
                                                        <div class="card-body he1x" style="padding-bottom: 0px !important;"><b>{{ $shipping_address_type }}</b></div>
														@if($shipping_details['address_type'] == 'Business Address' )
															<div class="card-body he1x" style="padding-bottom: 0px !important;">
																Business Name : {{ $shipping_business_name }}<br/>
																TIN : {{ $shipping_tin }}
															</div>
														@endif
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
										@if ($shipping_details['same_as_billing'] == 0)
											<div class="col-md-12 col-lg-6 d-flex align-items-stretch">
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
																		<a href="#" style="font-size: 14px; width:100%; text-decoration: none;" role="button"  data-toggle="modal" data-target="#selectBillingModal">UPDATE YOUR ADDRESS</a>
																	@endif
																</div>
															</div>
														</h2>
													</div>

													<div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1" data-parent="#accordionExample">
														<div class="card-body">
															<div class="card-body he1x" style="padding-bottom: 0px !important;"><b>{{ $billing_details['address_type'] }}</b></div>
															@if($billing_details['address_type'] == 'Business Address' )
																<div class="card-body he1x" style="padding-bottom: 0px !important;">
																	Business Name: {{ $billing_details['business_name'] }}<br/>
																	{{ $billing_details['tin'] }}
																</div>
															@endif
															<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $billing_details['fname'] . ' ' . $billing_details['lname'] }}</div>
															<div class="card-body he1x" style="padding-bottom: 0px !important;">
																{{ $billing_details['address_line1']." ".$billing_details['address_line2'].", ".$billing_details['brgy'].", ".$billing_details['city'].", ".$billing_details['province'].", ".$billing_details['country']." ".$billing_details['postal_code'] }}
															</div>

															<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Number :  {{ $billing_details['mobile_no'] }}<br/>&nbsp;</div>
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
				<div class="col-md-12 col-lg-4 mx-auto">
					<div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Order Summary<hr></div>
						
						  <div class="m-2">
							<div id="item-preloader" style="display: none;">
								<div class="spinner-border" role="status">
									<span class="sr-only">Loading...</span>
								  </div>
							  </div>
						<table class="table mb-0" id="cart-items">
							<thead>
							<tr style="font-size: 0.8rem !important;">
								<th class="text-center" colspan="2" style="width: 70%">Product Description</th>
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
											<span class="d-block">{{ $cart['item_description'] }}</span>
											<span id="{{ $cart['item_code'] }}v" class="d-inline-block text-white d-none" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">Voucher Applied</span>
										</td>
										<td style="text-align: center;">{{ $cart['quantity'] }}</td>
										<td class="col-md-2" style="text-align: right;">
											<span class="d-block" id="{{ $cart['item_code'] }}o">₱ {{ number_format($cart['subtotal'], 2, '.', ',') }}</span>
											<p class="d-block d-none text-success">₱ <span id="{{ $cart['item_code'] }}">{{ $cart['subtotal'] }}</span></p>
											<span class="amount d-none" id="{{ $cart['item_code'] }}a">{{ $cart['subtotal'] }}</span>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

						<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
							<div class="d-flex justify-content-between align-items-center">
								Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">₱ {{ number_format(collect($cart_arr)->sum('subtotal'), 2, '.', ',') }}</small>
							</div>
							{{-- <div class="d-flex justify-content-between align-items-center">
								Discount <small class="text-danger stylecap he1x">- ₱ <span id="discount-total">0.00</span></small>
							</div>
							<hr class="mt-2 mb-2">
							<div class="d-flex justify-content-between align-items-center">
								Subtotal <small class="text-muted stylecap he1x" id="subtotal">₱ 0.00</small>
							</div> --}}
							<hr class="mt-2">
						</div>
						<div class="card-body he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">Select Shipping Method
							<div class="alert alert-warning alert-dismissible fade show text-center m-1 p-3 d-none" role="alert" id="alert-box"></div>
							<div class="form-check">
								<label class="form-check-label" for="shipradio">&nbsp;</label>
							</div>
							@forelse ($shipping_rates as $l => $srate)
							<div class="d-flex justify-content-between align-items-center">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="shipping_fee" id="{{ 'sr' . $l }}" value="{{ $srate['shipping_cost'] }}" data-sname="{{ $srate['shipping_service_name'] }}" data-est="{{ $srate['expected_delivery_date'] }}" data-pickup="{{ $srate['pickup'] }}" required {{ $loop->first ? 'checked' : '' }} data-lead="{{ $srate['max_lead_time'] }}">
									<label class="form-check-label" for="{{ 'sr' . $l }}">{{ $srate['shipping_service_name'] }} <br class="d-xl-none"/>
										@if (count($srate['stores']) <= 0)<small class="fst-italic">({{ $srate['min_lead_time'] . " - ". $srate['max_lead_time'] . " Days" }})</small>@endif</label>
								</div>
								<small class="text-muted stylecap he1x" style="white-space: nowrap !important">₱ {{ number_format($srate['shipping_cost'], 2, '.', ',') }}</small>
							</div>
							@if (count($srate['stores']) > 0)
							<div class="row d-none" id="for-store-pickup">
								<div class="col-md-6 offset-md-3">
									<div class="form-group">
										<label for="store-selection">Select Store</label>
										<select id="store-selection" class="form-control formslabelfnt" style="text-align: center;">
											<option value="">Select Store</option>
											@foreach ($srate['stores'] as $store)
											<option value="{{ $store->store_name }}" data-address="{{ $store->address }}" data-available-time="Available Time: <br>{{ date("h:i A", strtotime($store->available_from)) . ' - ' . date("h:i A", strtotime($store->available_to)) }}">{{ $store->store_name }}</option>
											@endforeach
										</select>
										<div class="m-1 text-center" id="store-address"></div>
										<div class="m-1 text-center" id="available-time"></div>
									</div>
								</div>
								<div class="col-md-6 offset-md-3 bootstrap-timepicker">
									<div class="form-group">
										<label for="pickup-time">Pickup by</label>
										<input type="text" class="form-control" id="pickup-time" style="text-align: center;">
									</div>
								</div>
							</div>
							@endif
							@empty
								<h6>No available shipping methods.</h6>
							@endforelse
							<hr>
							<p class="d-none" id="est-div" style="font-size: 0.8rem; font-style: italic;">Estimated Delivery Date: <b><span id="estimated-delivery-date"></span></b></p>
						</div>
						<div class="card-body he1x pt-0">
							<div class="alert alert-danger fade show mb-0 d-none" id="coupon-alert" role="alert" style="border: 1px solid;"></div>
							<label for="coupon-code" class="m-2 mb-0">Coupon</label>
							<div class="d-flex flex-row">
								<div class="p-2 col-md-10">
									<input type="text" id="coupon-code" class="form-control" placeholder="Enter Coupon Code" aria-label="Enter Coupon Code" aria-describedby="basic-addon2">
								</div>
								<div class="p-2 col-md-2">
									<button class="btn w-100 btn-outline-success" type="button" id="apply-coupon-btn">Apply</button></div>
							  </div>
							  <hr>
							<div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Grand Total <small class="stylecap he1x" id="grand-total">0.00</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="row mb-4">
				<div class="col-md-8 mx-auto">
					<div class="col-md-4 d-none d-md-block d-xl-block">
						<a href="javascript:history.back()" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 94%;">BACK</a>
					</div>
				</div>
				<div class="col-md-4 mx-auto">
					<div class="card">
							<div id="payment-form" class="d-none"></div>
							<button class="btn btn-lg btn-outline-primary" id="checkout-btn" style="float: right;" {{ (count($shipping_rates) <= 0) ? 'disabled' : '' }}>PROCEED</button>
					</div>
				</div><br/>&nbsp;
				<div class="col-md-4 d-md-none d-lg-none d-xl-none">
					<a href="javascript:history.back()" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 100%;">BACK</a>
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
									{{-- @if($shipping_address->xdefault)
									<div id="shipping-del{{ $shipping_address->id }}" class="modal fade" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Warning!</h4>
												</div>
												<div class="modal-body">
													<p class="text-center">Cannot delete default shipping address.</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									@else --}}
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
									{{-- @endif --}}
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
															<input type="text" name="province" id="province1_1_{{ $key }}" class="form-control caption_1" required>
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
															<input type="text" name="city" id="City_Municipality1_1_{{ $key }}" class="form-control caption_1" required>
														</div>
														<div class="col-md-4">
															<label class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
															<input type="text" name="brgy" id="Barangay1_1_{{ $key }}" class="form-control caption_1" required>
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
																<option value="" selected>Choose...</option>
																<option value="Business Address">Business Address</option>
																<option value="Home Address">Home Address</option>
															</select>
														</div>
													</div>
													<br/>
													<div class="row" class="ship_for_business" id="ship_for_business_{{ $key }}" style="display: none">
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
														<input type="text" name="province" id="bill_province1_1_{{ $key }}" class="form-control caption_1" required>
													</div>
													<div class="col">
														<label class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
														<input type="text" name="city" id="bill_City_Municipality1_1_{{ $key }}" class="form-control caption_1"  required>
													</div>
													<div class="col">
														<label class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
														<input type="text" name="brgy" id="bill_Barangay1_1_{{ $key }}" class="form-control caption_1" required>
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
															<option value="">Choose...</option>
															<option value="Business Address">Business Address</option>
															<option value="Home Address">Home Address</option>
														</select>
													</div>
												</div>
												<br/>
												<div class="row" id="bill_for_business_{{ $key }}" style="display: none">
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
								{{-- @if ($billing_address->xdefault)
								<div id="myDelete{{ $billing_address->id }}" class="modal fade" role="dialog">
									<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Warning!</h4>
												</div>
												<div class="modal-body">
													<p class="text-center">Cannot delete fefault billing address.</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
									</div>
								</div>
								@else --}}
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
		var perfEntries = performance.getEntriesByType("navigation");
		if (perfEntries[0].type === "back_forward") {
			location.reload(true);
		}

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
						if (response.status == 0) {
							$('#coupon-alert').removeClass('d-none').text(response.message);
						} else {
							$.each(response, function(i, d) {
								price = (isNaN(d.discounted_price)) ? 0 : d.discounted_price;
								$(d.id).text(price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
								$(d.id + 'a').text(price);
								if (d.is_discounted) {
									$(d.id).parent().removeClass('d-none');
									$(d.id + 'o').addClass('text-muted').html('<s>' + $(d.id + 'o').text() + '</s>');
									$(d.id + 'v').removeClass('d-none').text($('#coupon-code').val());
								}
							});

							updateTotal();

							$('#coupon-code').removeClass('is-invalid');
							$('#coupon-alert').addClass('d-none');
						}
						
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
			console.log($(this).val());
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
				// window.location.href = '/';
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
			var storeloc = $("#store-selection").val();
			
			var data = {
				estimated_del, s_name, s_amount, _token: '{{ csrf_token() }}', storeloc, picktime
			}

			if(ispick && (!storeloc || !picktime)) {
				$('#alert-box').removeClass('d-none').text('Please select store and pickup date');
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

				var l = $("input[name='shipping_fee']:checked").data('lead');

				$("#pickup-time").datepicker({
					showInputs: false,
					startDate: '+'+ l +'d',
					format: 'D, M. dd, yyyy',
					autoclose: true,
					daysOfWeekDisabled: [0]
				});
			}else{
				$('#for-store-pickup').addClass('d-none');
				$('#store-selection').removeAttr('required');
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

			$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
				$.each(obj.results, function(e, i) {
					provinces.push({
						id: i.text,
						code: i.provCode,
						text: i.text
					});
				});

				$('#province1_1_'+ship_key).select2({
					placeholder: 'Select Province',
					data: provinces
				});

				$('#City_Municipality1_1_'+ship_key).select2({
					placeholder: 'Select City',
				});

				$('#Barangay1_1_'+ship_key).select2({
					placeholder: 'Select Barangay',
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
			console.log(bill_key);
			$('.bill_type').change(function(){
				if($(this).val() == "Business Address"){
					$("#bill_for_business_"+bill_key).slideDown();
					$("#bill_business_name_"+bill_key).prop('required',true); 	
				}else{
					$("#bill_for_business_"+bill_key).slideUp();
					$("#bill_business_name_"+bill_key).prop('required',false);
				}
			});

			var provinces_bill = [];
			$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
				$.each(obj.results, function(e, i) {
					provinces_bill.push({
						id: i.text,
						code: i.provCode,
						text: i.text
					});
				});

				$('#bill_province1_1_'+bill_key).select2({
					placeholder: 'Select Province',
					data: provinces_bill
				});

				$('#bill_City_Municipality1_1_'+bill_key).select2({
					placeholder: 'Select City',
				});

				$('#bill_Barangay1_1_'+bill_key).select2({
					placeholder: 'Select Barangay',
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
