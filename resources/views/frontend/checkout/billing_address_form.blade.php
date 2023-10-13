@extends('frontend.layout', [
	'namePage' => 'Billing and Shipping Form',
	'activePage' => 'checkout_customer_form'
])

@section('content')
@php
	$page_title = 'SHOPPING CART';
@endphp
@include('frontend.header')

	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; white-space: nowrap !important">
				<li class="breadcrumb-item">
					<a href="/cart" style="color: #000000 !important; text-decoration: none;">Shopping Cart</a>
				</li>
				<li class="breadcrumb-item">
					<a href="#" style="color: #000000 !important; text-decoration: underline;">Billing & Shipping Address</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="#" style="color: #928d8d !important; text-decoration: none;">Place Order</a>
				</li>
			</ol>
		</nav>
	</main>
	
	<main style="background-color:#ffffff; min-height: 700px;" class="products-head">
		<div class="container-fluid">
			@php
				$form_action = '/setdetails';
				if (!$has_shipping_address) {
					$form_action = '/checkout/set_address';
				}
			@endphp
			<form action="{{ $form_action }}" method="post" id="checkout-form">
				@csrf
				<div class="row">
					<div class="col-md-12 col-xl-8 mx-auto">
						<div class="alert alert-warning alert-dismissible fade show text-center d-none" id="alert-message" role="alert"></div>
						<div class="row mb-3">
							<div class="col-12 col-md-6 head-text">
								<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important; font-weight: 600 !important;">Shipping Information</p>
							</div>
							@if(!Auth::check())
								<div class="col-7 offset-5 offset-md-2 col-md-4 col-lg-3 offset-lg-3 col-xl-3 offset-xl-3">
									<div class="effect">
										<div class="buttons" style="display: flex; justify-content: center; align-items: center; font-size: 10pt;">
											<small class="text-muted">
												<span class="open-modal" data-target="#loginModal" style="color: #0D6EFD; text-decoration: underline; cursor: pointer">Sign in</span> with
											</small>
											{{-- <a href="#" class="fb fb-signin" title="Sign in with Facebook" style="height: 25px !important; width: 25px !important; border-radius: 8px;">
												<i class="fa fa-facebook" aria-hidden="true" style="font-size: 10pt;"></i>
											</a> --}}
											<a href="{{ route('google.login').'?checkout=1' }}" class="g-plus" title="Sign in with Google" style="height: 25px !important; width: 25px !important; border-radius: 8px;">
												<img src="{{ asset('assets/google.svg') }}" width="15">
											</a>
											<a href="{{ route('linkedin.login').'?checkout=1' }}" class="in" title="Sign in with Linked In" style="height: 25px !important; width: 25px !important; border-radius: 8px;">
												<i class="fa fa-linkedin" aria-hidden="true" style="font-size: 10pt;"></i>
											</a>
										</div>
									</div>
								</div>
							@endif	
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="fname" class="formslabelfnt">First Name : <span class="text-danger">*</span></label>
								<input type="hidden" class="form-control formslabelfnt" id="logtype" name="logtype" value="1" required>
								<input type="text" class="form-control formslabelfnt" id="fname" name="fname" required value="{{ (old('fname')) ? old('fname') : (Auth::check() ? Auth::user()->f_name : '') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-6">
								<label for="lname" class="formslabelfnt">Last Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="lname" name="lname" required value="{{ (old('lname')) ? old('lname') : (Auth::check() ? Auth::user()->f_lname : '') }}"><br class="d-lg-none d-xl-none"/>
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
								<div class="form-group" id="prov-parent">
									<label for="ship_province1_1" class="formslabelfnt">Province : <span class="text-danger">*</span></label>
									<select class="form-control" id="ship_province1_1" name="ship_province1_1">
										@foreach ($provinces as $province)
											<option value="{{ $province['text'] }}" data-code="{{ $province['provCode'] }}">{{ $province['text'] }}</option>	
										@endforeach
									</select>
									<br class="d-lg-none d-xl-none"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group" id="city-parent">
									<label for="ship_City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
									<select class="form-control formslabelfnt" id="ship_City_Municipality1_1" name="ship_City_Municipality1_1"></select>
									<br class="d-lg-none d-xl-none"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="ship_Barangay1_1" class="formslabelfnt">Barangay : <span class="text-danger">*</span></label>
									<select class="form-control formslabelfnt" id="ship_Barangay1_1" name="ship_Barangay1_1"></select>
								</div>
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
									<option value="Business Address" {{ old('ship_Address_type1_1') == 'Business Address' ? 'selected' : '' }}>Business Address</option>
									<option value="Home Address" {{ old('ship_Address_type1_1') == 'Home Address' ? 'selected' : '' }}>Home Address</option><br class="d-lg-none d-xl-none"/>
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
							<br class="d-none d-md-block">&nbsp;
						</div>
						<div class="row">
							<div class="col-md-4">
								<label for="email1_1" class="formslabelfnt">Email Address : <span class="text-danger">*</span></label>
								<input type="email" class="form-control formslabelfnt" id="ship_email" name="ship_email" required value="{{ (old('ship_email')) ? old('ship_email') : (Auth::check() ? Auth::user()->username : '') }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="contactnumber1_1" class="formslabelfnt">Mobile Number : <span class="text-danger">*</span></label>
								<div class="row">
									<div class="col-1 col-md-2 col-xl-1" style="display: flex; align-items: center">
										+63
									</div>
									<div class="col-11 col-md-10 col-xl-11">
										<input type="text" class="form-control formslabelfnt d-inline" id="ship_mobilenumber1_1" name="ship_mobilenumber1_1" required value="{{ old('ship_mobilenumber1_1') }}"><br class="d-lg-none d-xl-none"/>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<label for="contactnumber1_1" class="formslabelfnt">Contact Number : </label>
								<div class="row">
									<div class="col-1 col-md-2 col-xl-1" style="display: flex; align-items: center">
										+63
									</div>
									<div class="col-11 col-md-10 col-xl-11">
										<input type="text" class="form-control formslabelfnt d-inline" id="contactnumber1_1" name="contactnumber1_1" value="{{ old('contactnumber1_1') }}"><br class="d-lg-none d-xl-none"/>
									</div>
								</div>
							</div>
						</div>
						<small style="font-style: italic; font-size: 0.75rem; margin-top: 20px; display: block;">Note: * Required information</small>
						<br>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="myCheck" name="same_as_billing" value="1" checked>
							<label class="form-check-label" for="flexCheckChecked" class="formslabelfnt" style="font-size: 14px;">Billing address is the same as above</label>
						</div>
						<br/>
						
						<div id="billAddress" style="display: none;">{{-- BILLING FORM --}}
							<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Billing Information</p>
							<div class="row">
								<div class="col-md-6">
									<label for="fname" class="formslabelfnt">First Name : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt required-field" id="bill_fname" name="bill_fname" value="{{ (old('bill_fname')) ? old('bill_fname') : (Auth::check() ? Auth::user()->f_name : '') }}">
								</div>
								<div class="col-md-6">
									<label for="lname" class="formslabelfnt">Last Name : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt required-field" id="bill_lname" name="bill_lname" value="{{ (old('bill_lname')) ? old('bill_lname') : (Auth::check() ? Auth::user()->f_lname : '') }}">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-6">
									<label for="Address1_1" class="formslabelfnt">Address Line 1 : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt required-field" id="Address1_1" name="Address1_1" value="{{ old('Address1_1') }}">
								</div>
								<div class="col-md-6">
									<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
									<input type="text" class="form-control formslabelfnt" id="Address2_1" name="Address2_1" value="{{ old('Address2_1') }}">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="province1_1" class="formslabelfnt">Province : <span class="text-danger">*</span></label>
										<select class="form-control required-field" id="province1_1" name="province1_1" value="{{ old('province1_1') }}">
											@foreach ($provinces as $province)
												<option value="{{ $province['text'] }}" data-code="{{ $province['provCode'] }}">{{ $province['text'] }}</option>	
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
										<select class="form-control formslabelfnt required-field" id="City_Municipality1_1" name="City_Municipality1_1" value="{{ old('City_Municipality1_1') }}"></select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="Barangay1_1" class="formslabelfnt">Barangay : <span class="text-danger">*</span></label>
										<select class="form-control formslabelfnt required-field" id="Barangay1_1" name="Barangay1_1" value="{{ old('Barangay1_1') }}"></select>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-4">
									<label for="postal1_1" class="formslabelfnt">Postal Code : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt required-field" id="postal1_1" name="postal1_1" value="{{ old('postal1_1') }}">
								</div>
								<div class="col-md-4">{{-- Country Select --}}
									<label for="country_region1_1" class="formslabelfnt">Country / Region : <span class="text-danger">*</span></label>
									<select class="form-control formslabelfnt required-field" id="country_region1_1" name="country_region1_1">
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
									<select class="form-control formslabelfnt required-field" id="Address_type1_1" name="Address_type1_1">
										<option value="">Choose...</option>
										<option value="Business Address" {{ old('ship_Address_type1_1') == 'Business Address' ? 'selected' : '' }}>Business Address</option>
										<option value="Home Address" {{ old('ship_Address_type1_1') == 'Home Address' ? 'selected' : '' }}>Home Address</option>
									</select>
								</div>
							</div>
							<br>
							<div class="row" id="bill_for_business" style="display: none">
								<div class="col-md-6">
									<label for="bill_business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="bill_business_name" name="bill_business_name"><br class="d-lg-none d-xl-none"/>
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
									<input type="email" class="form-control formslabelfnt required-field" id="email" name="email" value="{{ (old('email')) ? old('email') : (Auth::check() ? Auth::user()->username : '') }}">
								</div>
								<div class="col-md-6">
									<label for="mobilenumber1_1" class="formslabelfnt required-field">Mobile Number : <span class="text-danger">*</span></label>
									<div class="row">
										<div class="col-1 col-md-2 col-xl-1" style="display: flex; align-items: center">
											+63
										</div>
										<div class="col-11 col-md-10 col-xl-11">
											<input type="text" class="form-control formslabelfnt required-field d-inline" id="mobilenumber1_1" name="mobilenumber1_1" required value="{{ old('mobilenumber1_1') }}"><br class="d-lg-none d-xl-none"/>
										</div>
									</div>
								</div>
							</div>
							
							<small style="font-style: italic; font-size: 0.75rem; margin-top: 20px; display: block;">Note: * Required information</small>
							<br/>&nbsp;
						</div>
					</div>
				</div>
				<div class="row mb-4">
					<div class="col-12 col-xl-8 row mx-auto">
						<div class="order-last order-md-first col-12 col-md-5 col-xl-4">
							<a href="/cart" class="btn btn-lg btn-outline-primary w-100" role="button" style="background-color: #777575 !important; border-color: #777575 !important;">BACK</a>
						</div>
						<div class="order-first mb-2 order-md-last col-12 col-md-5 offset-md-2 col-xl-4 offset-xl-4">
							<button type="submit" id="form-submit" class="btn btn-lg btn-outline-primary" style="width: 100% !important">PROCEED</button>
						</div>
					</div>
				</div>
			</form>

			<!-- Login Modal -->
			<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<form action="/login" method="post">
						@csrf
						<input type="checkbox" name="checkout" class="d-none" readonly>
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="loginModalLabel">Login</h5>
								<a type="button" class="close close-modal text-dark" data-target="#loginModal" aria-label="Close">
									<span aria-hidden="true"><i class="fas fa-times"></i></span>
								</a>
							</div>
							<div class="modal-body" style="min-height: 450px;">
								<div class="row justify-content-center">
									<div class="col-md-10">
										<div class="form-group">
											<label for="username" class="formslabelfnt">Email Address <span class="text-danger">*</span></label>
											<input type="text" class="form-control formslabelfnt" name="username" required>
										</div>
										<div class="form-group mt-3">
											<label for="password" class="formslabelfnt">Password <span class="text-danger">*</span></label>
											<input type="password" class="form-control formslabelfnt" name="password" required>
										</div>
										<div class="form-group">
											<input type="submit" class="btn btn-primary mt-3" style="display: block; width: 100%;" value="LOGIN">
										</div>
										<br/>
										<a href="/password/reset" style="font-size: 13px; color: #404040; font-weight: 500;">Forgot Password?</a>
										<p style=" font-size: 1rem !important; margin-top: 12px;">
											<span style="display: inline-block; color:  #616a6b ">New member? </span> <a href="/signup" class="forgot-1" style="display: inline-block; font-size: 1rem !important; color: #404040;">Create new account.</a>
										</p>
										<hr>
										<small class="text-muted"> or sign in with</small>
										<div class="effect">
											<div class="buttons">
											  <a href="#" class="fb" title="Sign in with Facebook" onclick="triggerLogin();"><i class="fa fa-facebook" aria-hidden="true"></i></a>
											  <a href="{{ route('google.login').'?checkout=1' }}" class="g-plus" title="Sign in with Google">
												<img src="{{ asset('assets/google.svg') }}" width="25">
											  </a>
											  <a href="{{ route('linkedin.login').'?checkout=1' }}" class="in" title="Sign in with Linked In"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
											  {{-- <a href="#" class="tw" title="Sign in with Apple"><i class="fab fa-apple" aria-hidden="true"></i></a> --}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<br/>&nbsp;
		</div>
	</main>

	<div id="custom-overlay" style="display: none;">
		<div class="custom-spinner"></div>
		<br/>
		Loading...
	</div>
@endsection
@section('style')
<style>
		.effect {
			width: 100%;
		}
		.effect .buttons {
			display: flex;
			justify-content: center;
		}
		.effect a {
			text-align: center;
			margin: 3px 8px;
			text-decoration: none !important;
			color: white !important;
			width: 50px;
			height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 10px;
			font-size: 20px;
			overflow: hidden;
			position: relative;
			box-shadow: 0 0 7px 0 #404040;
		}
		.effect a i {
			position: relative;
			z-index: 3;
		}
		.effect a.fb {
			background-color: #3b5998;
		}
		.effect a.tw {
			background-color: #aeb5c5;
		}
		.effect a.g-plus {
			background-color: #fff;
		}
		.effect a.in {
			background-color: #007bb6;
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
	.formslabelfnt {
		font-weight: 400 !important;
		font-size: 14px !important;
	}
	.btmp {
		margin-bottom: 15px !important;
	}
	.tbls {
		padding-bottom: 25px !important;
		padding-top: 25px !important;
	}
	.is-invalid .select2-container--default .select2-selection--single {
		border-color: #dc3545;
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
	.head-text{
		text-align: left;
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
		.products-head{
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
		.head-text{
			text-align: center !important;
		}
	}

	@media (max-width: 767.98px) {
		.breadcrumb{
			font-size: 8pt !important;
			font-weight: 500;
		}
		.head-text{
			text-align: center !important;
		}
	}
	/* select2 white block space issue */
	#ship_province1_1,
	#ship_City_Municipality1_1,
	#ship_Barangay1_1,
	#province1_1,
	#City_Municipality1_1,
	#Barangay1_1 {
		box-sizing: border-box !important;
		display: inline-block !important;
		margin: 0 !important;
		position: relative !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('script')
<!-- Select2 -->
<script src="{{ asset('/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$('input[type="checkbox"]').prop("checked", true);
		var str = "{{ implode(',', $shipping_zones) }}";
		var res = str.split(",");
		var provinces = [];

		$('#ship_City_Municipality1_1').select2({
			placeholder: 'Select City',
		});
		
		$('#ship_Barangay1_1').select2({
			placeholder: 'Select Barangay',
		});

		$('#City_Municipality1_1').select2({
				placeholder: 'Select City',
		});

		$('#Barangay1_1').select2({
			placeholder: 'Select Barangay',
		});

		$('#ship_province1_1').select2().val("METRO MANILA").trigger('change.select2');
		$('#province1_1').select2().val("METRO MANILA").trigger('change.select2');
		loadCities(1339, '#ship_City_Municipality1_1');
		loadCities(1339, '#City_Municipality1_1');

		$(document).on('select2:select', '#ship_province1_1', function(e){
			var data = e.params.data;
			var id = $('#ship_province1_1 option:selected').data('code');

			$(this).parent('.form-group').removeClass('is-invalid')

			$('#ship_City_Municipality1_1').empty().trigger('change');
			$('#ship_Barangay1_1').empty().trigger('change');
			loadCities(id, '#ship_City_Municipality1_1');
		});

		function loadCities(id, select) {
			$(select).select2({
				placeholder: 'Select City',
				ajax: {
					url: '/getJson?list=cities.json&provCode=' + id,
					method: 'GET',
					dataType: 'json'
				}
			});
		}
		$(document).on('select2:select', '#ship_City_Municipality1_1', function(e){
			var data = e.params.data;
			var id = data.code;

			$(this).parent('.form-group').removeClass('is-invalid')

			$('#ship_Barangay1_1').empty().trigger('change');
			$('#ship_Barangay1_1').select2({
				placeholder: 'Select Barangay',
				ajax: {
					url: '/getJson?list=brgy.json&citymunCode=' + id,
					method: 'GET',
					dataType: 'json'
				}
			});
		});

		$(document).on('select2:select', '#province1_1', function(e){
			var data = e.params.data;
			var id = $('#province1_1 option:selected').data('code');

			$(this).parent('.form-group').removeClass('is-invalid')

			$('#City_Municipality1_1').empty().trigger('change');
			$('#Barangay1_1').empty().trigger('change');
			loadCities(id, '#City_Municipality1_1');
		});

		$(document).on('select2:select', '#City_Municipality1_1', function(e){
			var data = e.params.data;
			var id = data.code;

			$(this).parent('.form-group').removeClass('is-invalid')

			$('#Barangay1_1').empty().trigger('change');
			$('#Barangay1_1').select2({
				placeholder: 'Select Barangay',
				ajax: {
					url: '/getJson?list=brgy.json&citymunCode=' + id,
					method: 'GET',
					dataType: 'json'
				}
			});
		});
		
		$(document).on('click', '#form-submit', function (e){
			e.preventDefault();
			
			var form = $('#checkout-form');
			var reportValidity = form[0].reportValidity();

			var s1 = $('#ship_province1_1');
			var s2 = $('#ship_City_Municipality1_1');
			var s3 = $('#ship_Barangay1_1');

			s1.parent('.form-group').removeClass('is-invalid');
			if (!s1.val()) {
				s1.parents('.form-group').addClass('is-invalid');
				return false;
			}
			s2.parent('.form-group').removeClass('is-invalid');
			if (!s2.val()) {
				s2.parents('.form-group').addClass('is-invalid');
				return false;
			}
			s3.parent('.form-group').removeClass('is-invalid');
			if (!s3.val()) {
				s3.parents('.form-group').addClass('is-invalid');
				return false;
			}

			if ($('input[type="checkbox"]').prop("checked") == false) {
				var s4 = $('#province1_1');
				var s5 = $('#City_Municipality1_1');
				var s6 = $('#Barangay1_1');

				s4.parent('.form-group').removeClass('is-invalid');
				if (!s4.val()) {
					s4.parents('.form-group').addClass('is-invalid');
					return false;
				}
				s5.parent('.form-group').removeClass('is-invalid');
				if (!s5.val()) {
					s5.parents('.form-group').addClass('is-invalid');
					return false;
				}
				s6.parent('.form-group').removeClass('is-invalid');
				if (!s6.val()) {
					s6.parents('.form-group').addClass('is-invalid');
					return false;
				}
			}

			if(reportValidity){
				$('#checkout-form').submit();
			}
		});

		same_billing_and_shipping_address()

		$('#ship_City_Municipality1_1').val(null).trigger('change');
		$('#ship_Barangay1_1').val(null).trigger('change');

		function same_billing_and_shipping_address(){
			if($('input[type="checkbox"]').prop("checked") == false) {
				$('#billAddress').slideDown();
				$('#billAddress .required-field').prop('required', true)
			}else{
				$('#billAddress').slideUp();
				$('#billAddress .required-field').prop('required', false)
			}

			toggleBillBusinessName($('#Address_type1_1').val());
		}

		$('input[type="checkbox"]').click(function() {
			same_billing_and_shipping_address()
		});

		$('#Address_type1_1').change(function(){
			toggleBillBusinessName($(this).val());
		});

		if ($('input[type="checkbox"]').prop("checked") == false) {
			toggleBillBusinessName($('#Address_type1_1').val());
		}

		toggleShipBusinessName($('#ship_Address_type1_1').val());
		toggleBillBusinessName($('#Address_type1_1').val());
		function toggleShipBusinessName (address_type) {
			if(address_type == "Business Address"){
				$('#ship_for_business').slideDown();
				$("#ship_business_name").prop('required',true);
			}else{
				$('#ship_for_business').slideUp();
				$("#ship_business_name").prop('required',false);
			}
		}

		function toggleBillBusinessName (address_type) {
			if(address_type == "Business Address"){
				$('#bill_for_business').slideDown();
				$("#bill_business_name").prop('required',true);
			}else{
				$('#bill_for_business').slideUp();
				$("#bill_business_name").prop('required',false);
			}
		}

		$('#ship_Address_type1_1').change(function(){
			toggleShipBusinessName($(this).val());
		});

		$('#checkout-form').submit(function(e){
			e.preventDefault();

			$('#custom-overlay').fadeIn();
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success:(response) => {
					if (response.status == 'error'){
						$('#alert-message').removeClass('d-none').html(response.message);
						$('#custom-overlay').fadeOut();
						window.scrollTo(0, 0);
					} else {
						window.location.href = response.message;
					}
				}
      		});
		});
	});

</script>
@endsection

