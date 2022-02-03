@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
	'activePage' => 'checkout_customer_form'
])

@section('content')

	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
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
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 8pt !important; white-space: nowrap !important">
				<li class="breadcrumb-item">
					<a href="/cart" style="color: #000000 !important; text-decoration: none;">Shopping Cart</a>
				</li>
				<li class="breadcrumb-item">
					<a href="#" style="color: #000000 !important; text-decoration: underline;">Billing Address</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="#" style="color: #c1bdbd !important; text-decoration: none;">Place Order</a>
				</li>
			</ol>
		</nav>
	</main>
	
	<main style="background-color:#ffffff; min-height: 700px;" class="products-head">
		<div class="container-fluid">
	
			<form action="/checkout/set_billing" method="post">
				@csrf
				<div class="row">
					<div class="col-md-12 col-lg-8 mx-auto">
						<table class="table">
							<tr>
								<td class='col-md-9'>
									<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Billing Information</p>
								</td>
							</tr>
						</table>
						<div class="row">
							<div class="col-md-6">
								<label for="fname" class="formslabelfnt">First Name : *</label>
								<input type="hidden" class="form-control formslabelfnt" id="logtype" name="logtype" value="1" required>
								<input type="text" class="form-control formslabelfnt" id="fname" name="fname" value="{{ (old('fname')) ? old('fname') : (Auth::check() ? Auth::user()->f_name : '') }}" required>
							</div>
							<div class="col-md-6">
								<label for="lname" class="formslabelfnt">Last Name : *</label>
								<input type="text" class="form-control formslabelfnt" id="lname" value="{{ (old('lname')) ? old('lname') : (Auth::check() ? Auth::user()->f_lname : '') }}" name="lname" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="Address1_1" class="formslabelfnt">Address Line 1 : *</label>
								<input type="text" class="form-control formslabelfnt" id="Address1_1" name="Address1_1" required>
							</div>
							<div class="col-md-6">
								<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
								<input type="text" class="form-control formslabelfnt" id="Address2_1" name="Address2_1">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<label for="province1_1" class="formslabelfnt">Province : *</label>
								<input type="text" class="form-control formslabelfnt" id="province1_1" name="province1_1" required>
							</div>
							<div class="col-md-4">
								<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : *</label>
								<input type="text" class="form-control formslabelfnt" id="City_Municipality1_1" name="City_Municipality1_1" required>
							</div>
							<div class="col-md-4">
								<label for="Barangay1_1" class="formslabelfnt">Barangay : *</label>
								<input type="text" class="form-control formslabelfnt" id="Barangay1_1" name="Barangay1_1" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4">
								<label for="postal1_1" class="formslabelfnt">Postal Code : *</label>
								<input type="text" class="form-control formslabelfnt" id="postal1_1" name="postal1_1" required>
							</div>
							<div class="col-md-4">{{-- Country Select --}}
								@php
									$countries = ["Philippines"];
								@endphp
								<label for="country_region1_1" class="formslabelfnt">Country / Region : *</label>
								<select class="form-control formslabelfnt" id="country_region1_1" name="country_region1_1" required>
									@foreach ($countries as $country)
									@php
										if (old('country_region1_1')) {
											$s1 = old('country_region1_1') == $country ? 'selected' : '';
										} else {
											$s1 = $country == 'Philippines' ? 'selected' : '';
										}
									@endphp
									<option value="{{ $country }}" {{ $s1 }}>{{ $country }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-4">
								<label for="Address_type1_1" class="formslabelfnt">Address Type : *</label>
								<select class="form-control formslabelfnt" id="Address_type1_1" name="Address_type1_1" required>
									<option selected disabled value="">Choose...</option>
									<option value="Business Address">Business Address</option>
									<option value="Home Address">Home Address</option>
								</select>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="email1_1" class="formslabelfnt">Email Address : *</label>
								<input type="email" class="form-control formslabelfnt" id="email" name="email" value="{{ (old('email')) ? old('email') : (Auth::check() ? Auth::user()->username : '') }}" required>
							</div>
							<div class="col-md-6">
								<label for="mobilenumber1_1" class="formslabelfnt">Mobile Number : *</label>
								<input type="text" class="form-control formslabelfnt" id="mobilenumber1_1" name="mobilenumber1_1" required>
							</div>
						</div>
					</div>					
				</div>
				<br/>&nbsp;
				<div class="row mb-4">
					<div class="col-md-9 mx-auto">
						<div class="col-md-4 d-none d-xl-block">
							<a href="/cart" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 94%;margin-left: 5%;">BACK</a>
						</div>
						<div class="col-md-4 d-none d-xl-block" style="float: right !important">
							<button type="submit" class="btn btn-lg btn-outline-primary" style="width: 100% !important">PROCEED</button>
						</div>
						<div class="col-md-4 d-md-none d-lg-none d-xl-none">
							<button type="submit" class="btn btn-lg btn-outline-primary" style="width: 100% !important">PROCEED</button>
						</div>
						<br/>
						<div class="col-md-4 d-md-none d-lg-none d-xl-none">
							<a href="/cart" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important; float: left; width: 100%;">BACK</a>
						</div>
						<input type="text" name="order_no" class="p" value="{{ 'FUM-'.random_int(10000000, 99999999) }}" hidden readonly/>
					</div>
					
				</div>
			</form>
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

			/* select2 white block space issue */
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
		$('#province1_1').val('METRO MANILA');
		$('#province1_1').select2().trigger('change');
		loadcities("1339");
		function loadcities(code) {
			var select_el = $('#City_Municipality1_1');
			var cities = [];

			select_el.empty();
			$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
				var filtered_cities = $.grep(obj.results, function(v) {
					return v.provCode === code;
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
		}

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
			
		$('#province1_1').select2({
			placeholder: 'Select Province',
			data: provinces
		});

		$('#Barangay1_1').select2({
			placeholder: 'Select Barangay',
		});
	});


	$(document).on('select2:select', '#province1_1', function(e){
		var data = e.params.data;
		loadcities(data.code);
	});

	$(document).on('select2:select', '#City_Municipality1_1', function(e){
		var data = e.params.data;
		var select_el = $('#Barangay1_1');
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
		});
	});

</script>
@endsection

@section('style')
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