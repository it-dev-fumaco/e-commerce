@extends('frontend.layout', [
	'namePage' => 'My Profile - Address Form',
	'activePage' => 'myprofile_address_form'
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
		.btmp {
			margin-bottom: 15px !important;
		}
		.caption_1 {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.caption_2 {
			font-weight: 500 !important;
			font-size: 14px !important;
		}
		.order-font {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.order-font-sub {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.order-font-sub-b {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.order-font-sub-b {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.tbls{
			vertical-align: center !important;
		}
		.myprofile-font-form {
			font-weight: 500 !important;
			font-size: 14px !important;
		}
	</style>

	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
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
					<p class="caption_2">
						<a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a>
					</p>
					<hr>
					<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;">
						<a href="/myprofile/address" style="text-decoration: none;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Address</span></a>
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
								<label for="first_name" class="myprofile-font-form">First Name : *</label>
								<input type="text" class="form-control caption_1" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
							</div>
							<div class="col">
								<label for="last_name" class="myprofile-font-form">Last Name : *</label>
								<input type="text" class="form-control caption_1" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="contact_no" class="myprofile-font-form">Contact Number : *</label>
								<input type="text" class="form-control caption_1" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" required>
							</div>

							<div class="col">
								<label for="mobile_no" class="myprofile-font-form">Mobile Number : *</label>
								<input type="text" class="form-control caption_1" id="mobile_no" name="mobile_no" value="" required/>
							</div>

							<div class="col">
								<label for="email_address" class="myprofile-font-form">Email : *</label>
								<input type="email" class="form-control caption_1" id="email_address" name="email_address" value="{{ old('email_address') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="address_line1" class="myprofile-font-form">Address Line 1 : *</label>
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
								<label for="province" class="myprofile-font-form">Province : *</label>
								<input type="text" class="form-control caption_1" id="province" name="province" value="{{ old('province') }}" required>
							</div>
							<div class="col">
								<label for="city" class="myprofile-font-form">City / Municipality : *</label>
								<input type="text" class="form-control caption_1" id="city" name="city" value="{{ old('city') }}" required>
							</div>
							<div class="col">
								<label for="barangay" class="myprofile-font-form">Barangay : *</label>
								<input type="text" class="form-control caption_1" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="postal_code" class="myprofile-font-form">Postal Code : *</label>
								<input type="text" class="form-control caption_1" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
							</div>
							<div class="col">
								<label for="country" class="myprofile-font-form">Country / Region : *</label>
								<select class="form-control caption_1" id="country" name="country" required>
									<option value="Philippines">Philippines</option>
								</select>
							</div>
							<div class="col">
								<label for="address_type" class="myprofile-font-form">Address Type : *</label>
								<select class="form-control caption_1" id="address_type" name="address_type" required>
									<option value="Business" {{ (old('address_type') == 'Business') ? 'selected' : '' }}>Business</option>
									<option value="Home" {{ (old('address_type') == 'Home') ? 'selected' : '' }}>Home</option>
								</select>
							</div>
						</div>
						<button type="submit" class="btn btn-primary mt-3 caption_1">SAVE ADDRESS</button>
						<br><br>
					</form>
				</div>
			</div>
		</div>
	</main>
@endsection

@section('script')

@endsection
