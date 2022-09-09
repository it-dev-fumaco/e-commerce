@extends('frontend.layout', [
	'namePage' => 'My Profile - Address Form',
	'activePage' => 'myprofile_address_form'
])

@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">MY PROFILE</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<main style="background-color:#ffffff;" class="products-head">
		<div class="container-fluid">
			<div class="row" style="padding-left: 15%; padding-right: 0%; padding-top: 25px;">
				<div class="col-lg-2">
					<p class="caption_2">
						<a href="/myprofile/account_details" style="text-decoration: none; color: #000000;">Account Details</a>
					</p>
					<hr>
					<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;">
						<a href="/myprofile/address" style="text-decoration: none;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Address</span></a>
					</p>
					<hr>
					<p class="caption_2">
						<a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a>
					</p>
					<hr>
					<p class="caption_2">
						<a href="/logout" style="text-decoration: none; color: #000000;">Sign Out</a>
					</p>
					<hr>
				</div>
				<div class="col-lg-8">
					@if(count($errors->all()) > 0)
               <div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								@foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach 
						  	</div>
						</div>
					</div>
               @endif
					<form action="/myprofile/address/{{ $type }}/save" method="POST">
						@csrf
						<strong><h4>New {{ ucfirst($type) }} Address :</h4></strong>
						<br>
						<div class="row">
							<div class="col">
								<label for="first_name" class="myprofile-font-form">First Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
							</div>
							<div class="col">
								<label for="last_name" class="myprofile-font-form">Last Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="contact_no" class="myprofile-font-form">Telephone Number : </label>
								<input type="text" class="form-control caption_1" id="contact_no" name="contact_no" value="{{ old('contact_no') }}">
							</div>

							<div class="col">
								<label for="mobile_no" class="myprofile-font-form">Mobile Number : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="mobile_no" name="mobile_no" value="" required/>
							</div>

							<div class="col">
								<label for="email_address" class="myprofile-font-form">Email : <span class="text-danger">*</span></label>
								<input type="email" class="form-control caption_1" id="email_address" name="email_address" value="{{ old('email_address') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="address_line1" class="myprofile-font-form">Address Line 1 : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
							</div>
							<div class="col">
								<label for="address_line2" class="myprofile-font-form">Address Line 2 : </label>
								<input type="text" class="form-control caption_1" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="province" class="myprofile-font-form">Province : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="province" name="province" value="{{ old('province') }}" required>
							</div>
							<div class="col">
								<label for="city" class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="city" name="city" value="{{ old('city') }}" required>
							</div>
							<div class="col">
								<label for="barangay" class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="postal_code" class="myprofile-font-form">Postal Code : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
							</div>
							<div class="col">
								<label for="country" class="myprofile-font-form">Country / Region : <span class="text-danger">*</span></label>
								<select class="form-control caption_1" id="country" name="country" required>
									<option value="Philippines">Philippines</option>
								</select>
							</div>
							<div class="col">
								<label for="address_type" class="myprofile-font-form">Address Type : <span class="text-danger">*</span></label>
								<select class="form-control caption_1" id="address_type" name="address_type" required>
									<option value="">Choose...</option>
									<option value="Business Address" {{ old('address_type') == 'Business Address' ? 'selected' : '' }}>Business Address</option>
									<option value="Home Address" {{ old('address_type') == 'Home Address' ? 'selected' : '' }}>Home Address</option>
								</select>
							</div>
						</div>
						<br>
						<div class="row" id="bill_for_business" style="{{ (old('address_type') != 'Business Address') ? 'display: none;' : '' }}">
							<div class="col">
								<label for="bill_business_name" class="myprofile-font-form">Business Name : <span class="text-danger">*</span></label>
								<input type="text" class="form-control caption_1" id="bill_business_name" name="business_name" value="{{ (old('business_name')) }}"><br class="d-lg-none d-xl-none"/>
							</div>
							<div class="col">
								<label for="bill_tin" class="myprofile-font-form">TIN Number :</label>
								<input type="text" class="form-control caption_1" id="bill_tin" name="tin_no" value="{{ (old('tin_no')) }}"><br class="d-lg-none d-xl-none"/>
							</div>
						</div>
						<button type="submit" class="btn btn-primary mt-3 caption_1">SAVE ADDRESS</button>
						<br><br>
					</form>
				</div>
			</div>
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
		var provinces = [];
		$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
			$.each(obj.results, function(e, i) {
				provinces.push({
					id: i.text,
					code: i.provCode,
					text: i.text
				});
			});

			$('#province').select2({
				placeholder: 'Select Province',
				data: provinces
			});

			$('#city').select2({
				placeholder: 'Select City',
			});

			$('#barangay').select2({
				placeholder: 'Select Barangay',
			});
		});

		$(document).on('select2:select', '#province', function(e){
			var data = e.params.data;
			var select_el = $('#city');
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

		$(document).on('select2:select', '#city', function(e){
			var data = e.params.data;
			var select_el = $('#barangay');
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

		$('#address_type').change(function(){
			if($(this).val() == "Business Address"){
				$('#bill_for_business').slideDown();
				$("#bill_business_name").prop('required',true);
			}else{
				$('#bill_for_business').slideUp();
				$("#bill_business_name").prop('required',false);
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
	.btmp {
		margin-bottom: 15px !important;
	}
	.caption_1, .order-font {
		font-weight: 200 !important;
		font-size: 14px !important;
	}
	.caption_2, .myprofile-font-form {
		font-weight: 500 !important;
		font-size: 14px !important;
	}
	.order-font-sub, .he2 {
		font-weight: 200 !important;
		font-size: 10px !important;
	}
	.order-font-sub-b {
		font-weight: 300 !important;
		font-size: 14px !important;
	}
	.tbls{
		vertical-align: center !important;
	}
</style>

@endsection