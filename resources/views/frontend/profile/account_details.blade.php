@extends('frontend.layout', [
	'namePage' => 'My Profile - Account Details',
	'activePage' => 'myprofile_account_details'
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
	
	<main style="background-color:#ffffff; min-height: 700px;" class="products-head">
		<div class="container-fluid">
			<div class="row" style="padding-left: 15%; padding-right: 0%; padding-top: 25px;">
				<div class="col-lg-2">
					<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Account Details</span></p>
					<hr>
					<p class="caption_2"><a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a></p>
					<hr>
					<p class="caption_2"><a href="/myprofile/address" style="text-decoration: none; color: #000000;">Address</a></p>
					<hr>
					<p class="caption_2"><a href="/logout" style="text-decoration: none; color: #000000;">Sign Out</a></p>
					<hr>
				</div>
				<div class="col-lg-8">
					@if(session()->has('success'))
					<div class="row">
						<div class="col">
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								{{ session()->get('success') }}
						  	</div>
						</div>
					</div>
					@endif
					@if(session()->has('error'))
					<div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								{{ session()->get('error') }}
						  	</div>
						</div>
					</div>
					@endif
					<form action="/myprofile/account_details/{{ Auth::user()->id }}/update" method="POST">
						@csrf
						<div class="row">
							<div class="col">
								<label for="fname" class="myprofile-font-form">First Name :</label>
								<input type="text" class="form-control caption_1" name="first_name" value="{{ Auth::user()->f_name }}" required>
							</div>
							<div class="col">
								<label for="lname" class="myprofile-font-form">Last Name :</label>
								<input type="text" class="form-control captio1n_1" name="last_name" value="{{ Auth::user()->f_lname }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="eaddname" class="myprofile-font-form">E-Mail Address :</label>
								<input type="email" class="form-control caption_1" name="email_address" value="{{ Auth::user()->username }}" required>
							</div>
							<div class="col">
								<label for="mobilename" class="myprofile-font-form">Mobile Number :</label>
								<input type="text" class="form-control caption_1" name="mobile_no" value="{{ Auth::user()->f_mobilenumber }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="ebusinessname" class="myprofile-font-form">Business Name / Employer:</label>
								<input type="text" class="form-control caption_1"  name="business_name" value="{{ Auth::user()->f_business }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="website" class="myprofile-font-form">Website :</label>
								<input type="text" class="form-control caption_1" name="website" value="{{ Auth::user()->f_website }}">
							</div>
							<div class="col">
								<label for="jobpositioname" class="myprofile-font-form">Job Position :</label>
								<input type="text" class="form-control caption_1" name="job_position" value="{{ Auth::user()->f_job_position }}">
							</div>
						</div>
						<br>
						<button type="submit" class="btn btn-primary mt-3 caption_1">UPDATE</button>
					</form>
					<br><br>
				</div>
			</div>
		</div>
	</main> 
@endsection

@section('script')

@endsection
