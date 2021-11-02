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
	</style>
	
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;">
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
			@if(session()->has('error'))
				<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
					{!! session()->get('error') !!}
				</div>
			@endif
			@if(count($errors->all()) > 0)
              <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                @foreach ($errors->all() as $error)
                  <span class="d-block">{!! $error !!}</span>
                @endforeach
              </div>
            @endif
			@php
				$form_action = '/setdetails';
				if (!$has_shipping_address) {
					$form_action = '/checkout/set_address';
				}
			@endphp
			<form action="{{ $form_action }}" method="post">
				@csrf
				<div class="row">
					<div class="col-md-8 mx-auto">
						<table class="table">
							<tr>
								<td class='col-md-9'>
									<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Shipping Information</p>
								</td>
								@if(!Auth::check())
									<td style="text-align: right; font-size: 10pt;">
										Already a member? <a href="#" data-toggle="modal" data-target="#loginModal">Log in</a>
									</td>
								@endif
							</tr>
						</table>
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
								{{-- <select class="form-control formslabelfnt" id="ship_province1_1" name="ship_province1_1" required>
									<option value="">Select Province</option>
									@foreach ($provinces as $province)
									<option value="{{ $province['text'] }}" data-id="{{ $province['provCode'] }}">{{ $province['text'] }}</option>
									@endforeach
								</select> --}}
								<input type="text" class="form-control formslabelfnt" id="ship_province1_1" name="ship_province1_1" required value="{{ old('ship_province1_1') }}">
								<br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col-md-4">
								<label for="ship_City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
								<input type="text" class="form-control formslabelfnt" id="ship_City_Municipality1_1" name="ship_City_Municipality1_1" required value="{{ old('ship_City_Municipality1_1') }}"><br class="d-lg-none d-xl-none"/>
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
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="myCheck" name="same_as_billing"  checked>
							<label class="form-check-label" for="flexCheckChecked" class="formslabelfnt" style="font-size: 14px;">Billing address is the same as above</label>
						</div>
						<br/>
						
						<div id="billAddress" style="display: none;">{{-- BILLING FORM --}}
							<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Billing Information</p>
							<div class="row">
								<div class="col-md-6">
									<label for="fname" class="formslabelfnt">First Name : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="bill_fname" name="bill_fname" value="{{ old('bill_fname') }}">
								</div>
								<div class="col-md-6">
									<label for="lname" class="formslabelfnt">Last Name : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="bill_lname" name="bill_lname" value="{{ old('bill_lname') }}">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-6">
									<label for="Address1_1" class="formslabelfnt">Address Line 1 : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="Address1_1" name="Address1_1" value="{{ old('Address1_1') }}">
								</div>
								<div class="col-md-6">
									<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
									<input type="text" class="form-control formslabelfnt" id="Address2_1" name="Address2_1" value="{{ old('Address2_1') }}">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-4">
									<label for="province1_1" class="formslabelfnt">Province : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="province1_1" name="province1_1" value="{{ old('province1_1') }}">
								</div>
								<div class="col-md-4">
									<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="City_Municipality1_1" name="City_Municipality1_1" value="{{ old('City_Municipality1_1') }}">
								</div>
								<div class="col-md-4">
									<label for="Barangay1_1" class="formslabelfnt">Barangay : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="Barangay1_1" name="Barangay1_1" value="{{ old('Barangay1_1') }}">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-4">
									<label for="postal1_1" class="formslabelfnt">Postal Code : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="postal1_1" name="postal1_1" value="{{ old('postal1_1') }}">
								</div>
								<div class="col-md-4">{{-- Country Select --}}
									<label for="country_region1_1" class="formslabelfnt">Country / Region : <span class="text-danger">*</span></label>
									<select class="form-control formslabelfnt" id="country_region1_1" name="country_region1_1">
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
									<select class="form-control formslabelfnt" id="Address_type1_1" name="Address_type1_1">
										<option disabled value="">Choose...</option>
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
									<input type="email" class="form-control formslabelfnt" id="email" name="email" value="{{ old('email') }}">
								</div>
								<div class="col-md-6">
									<label for="mobilenumber1_1" class="formslabelfnt">Mobile Number : <span class="text-danger">*</span></label>
									<input type="text" class="form-control formslabelfnt" id="mobilenumber1_1" name="mobilenumber1_1" value="{{ old('mobilenumber1_1') }}">
								</div>
							</div>
							
							<small style="font-style: italic; font-size: 0.75rem; margin-top: 20px; display: block;">Note: * Required information</small>
							<br/>&nbsp;
						</div>
					</div>
				</div>
				{{-- <div class="row">
					<br/>&nbsp;
					<div class="col-md-8 mx-auto">
						<a href="/cart" class="btn btn-lg btn-outline-primary col-md-5 mx-auto" role="button" style="background-color: #777575 !important; border-color: #777575 !important;">BACK</a>
						<input type="submit" class="btn btn-lg btn-outline-primary col-md-5 mx-auto" role="button" style="float: right;" value="PROCEED">
					</div>
				</div> --}}
				<div class="row mb-4">
					<div class="col-md-8 mx-auto">
						<div class="col-md-4 d-none d-md-block d-lg-block d-xl-block">
							<a href="/cart" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 94%;">BACK</a>
						</div>
						<div class="col-md-4 d-none d-md-block d-lg-block d-xl-block" style="float: right !important">
							<button type="submit" class="btn btn-lg btn-outline-primary" style="width: 100% !important">PROCEED</button>
						</div>
						<div class="col-md-4 d-md-none d-xl-none">
							<button type="submit" class="btn btn-lg btn-outline-primary" style="width: 100% !important">PROCEED</button>
						</div>
						<br/>
						<div class="col-md-4 d-md-none d-xl-none">
							<a href="/cart" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 100%;">BACK</a>
						</div>
					</div>
					
				</div>
			</form>

			<!-- Login Modal -->
			<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<form action="/login" method="post">
						@csrf
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="loginModalLabel">Login</h5>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col">
										<label for="username" class="formslabelfnt">Username</label>
										<input type="text" class="form-control formslabelfnt" name="username" required>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label for="password" class="formslabelfnt">Password</label>
										<input type="password" class="form-control formslabelfnt" name="password" required>
									</div>
								</div>
								<br/>
								<a href="/password/reset">Forgot Password?</a>
								<p style=" font-size: 1rem !important; margin-top: 12px;">
									<span style="display: inline-block; color:  #616a6b ">New member? </span> <a href="/signup" class="forgot-1" style="display: inline-block; font-size: 1rem !important;">Create new account.</a>
								</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<input type="checkbox" name="summary" readonly checked hidden>
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<br/>&nbsp;
		</div>
	</main>


	<style>
		.select2-selection__rendered {
				line-height: 34px !important;
			}
			.select2-container .select2-selection--single {
				height: 37px !important;
			}
			.select2-selection__arrow {
				height: 35px !important;
			}
	</style>
@endsection

@section('script')
<!-- Select2 -->
<script src="{{ asset('/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
	$(document).ready(function() {
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

		$('input[type="checkbox"]').click(function() {
			if($(this).prop("checked") == false) {
				$('#billAddress').slideDown();
				$("#Address1_1").prop('required',true);
				$("#email").prop('required',true);
				$("#province1_1").prop('required',true);
				$("#City_Municipality1_1").prop('required',true);
				$("#Barangay1_1").prop('required',true);
				$("#postal1_1").prop('required',true);
				$("#country_region1_1").prop('required',true);
				$("#Address_type1_1").prop('required',true);
				$("#email").prop('required',true);
				$("#mobilenumber1_1").prop('required',true);
				$("#bill_fname").prop('required',true);
				$("#bill_lname").prop('required',true);
			}else{
				$('#billAddress').slideUp();
				$("#Address1_1").prop('required',false);
				$("#email").prop('required',false);
				$("#province1_1").prop('required',false);
				$("#City_Municipality1_1").prop('required',false);
				$("#Barangay1_1").prop('required',false);
				$("#postal1_1").prop('required',false);
				$("#country_region1_1").prop('required',false);
				$("#Address_type1_1").prop('required',false);
				$("#email").prop('required',false);
				$("#mobilenumber1_1").prop('required',false);
				$("#bill_fname").prop('required',false);
				$("#bill_lname").prop('required',false);
			}

			$('#Address_type1_1').change(function(){
				if($(this).val() == "Business Address"){
					$('#bill_for_business').slideDown();
					$("#bill_business_name").prop('required',true);
				}else{
					$('#bill_for_business').slideUp();
					$("#bill_business_name").prop('required',false);
				}
			});
		});

		$('#ship_Address_type1_1').change(function(){
			if($(this).val() == "Business Address"){
				$('#ship_for_business').slideDown();
				$("#ship_business_name").prop('required',true);
			}else{
				$('#ship_for_business').slideUp();
				$("#ship_business_name").prop('required',false);
			}
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

	});

</script>
@endsection

