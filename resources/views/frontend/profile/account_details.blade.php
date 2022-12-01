@extends('frontend.layout', [
	'namePage' => 'My Profile - Account Details',
	'activePage' => 'myprofile_account_details'
])

@section('content')
@php
	$page_title = 'MY PROFILE';
@endphp
@include('frontend.header')
	<main style="background-color:#ffffff; min-height: 700px;" class="products-head">
		<div class="container-fluid">
			<div class="row acc-container" style="padding-left: 15%; padding-right: 0%; padding-top: 25px;">
				<div class="col-lg-2">
					<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Account Details</span></p>
					<hr>
					<p class="caption_2"><a href="/myprofile/address" style="text-decoration: none; color: #000000;">Address</a></p>
					<hr>
					<p class="caption_2"><a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a></p>
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
						<h6><i class="fas fa-user-tie"></i> {{ $customer_group }} Account</h6>
						<div class="row">
							<div class="col-12 col-md-6">
								<label for="fname" class="myprofile-font-form">First Name :</label>
								<input type="text" class="form-control caption_1" name="first_name" value="{{ Auth::user()->f_name }}" required>
							</div>
							<div class="col-12 col-md-6">
								<label for="lname" class="myprofile-font-form">Last Name :</label>
								<input type="text" class="form-control captio1n_1" name="last_name" value="{{ Auth::user()->f_lname }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-12 col-md-6">
								<label for="eaddname" class="myprofile-font-form">E-Mail Address :</label>
								<input type="email" class="form-control caption_1" name="email_address" value="{{ Auth::user()->username }}" required readonly>
							</div>
							<div class="col-12 col-md-6">
								<label for="mobilename" class="myprofile-font-form">Mobile Number :</label>
								<input type="text" class="form-control caption_1" name="mobile_no" value="{{ Auth::user()->f_mobilenumber }}" required>
							</div>
						</div>
						@if ($customer_group == 'Business')
						<br>
						<div class="row">
							<div class="col">
								<label for="ebusinessname" class="myprofile-font-form">Business Name / Employer:</label>
								<input type="text" class="form-control caption_1" name="business_name" value="{{ Auth::user()->f_business }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-12 col-6">
								<label for="website" class="myprofile-font-form">Website :</label>
								<input type="text" class="form-control caption_1" name="website" value="{{ Auth::user()->f_website }}">
							</div>
							<div class="col-12 col-6">
								<label for="jobpositioname" class="myprofile-font-form">Job Position :</label>
								<input type="text" class="form-control caption_1" name="job_position" value="{{ Auth::user()->f_job_position }}">
							</div>
						</div>
						@endif
						<br>
						<button type="submit" class="btn btn-primary mt-3 caption_1">UPDATE</button>
					</form>
					<br><br>
				</div>
			</div>
		</div>
	</main> 
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
	@media(max-width: 575.98px){
		.products-head, .acc-container{
			padding-left: 10px !important;
			padding-right: 10px !important;
		}
	}
	@media (max-width: 369.98px){
		.products-head, .acc-container{
			padding: 0 !important;
		}
	}
</style>
@endsection
