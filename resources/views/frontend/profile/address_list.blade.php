@extends('frontend.layout', [
	'namePage' => 'My Profile - Address',
	'activePage' => 'myprofile_address'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: 100% !important;">
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
		<div class="row" style="padding-left: 0%; padding-right: 0%; padding-top: 25px;">
			<div class="col-lg-2">
				<p class="caption_2"><a href="/myprofile/account_details" style="text-decoration: none; color: #000000;">Account Details</a></p>
				<hr>
				<p class="caption_2"><a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a></p>
				<hr>
				<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Address</span></p>
				<hr>
				<p class="caption_2"><a href="/logout" style="text-decoration: none; color: #000000;">Sign Out</a></p>
				<hr>
			</div>
			<div class="col-lg-10">
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
				<strong><h4>Shipping Address : </h4></strong>
				<br>
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Address</th>
							<th>Contacts</th>
							<th>Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($shipping_addresses as $shipping_address)
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
							<td>{{ $shipping_address->xadd1 .' '. $shipping_address->xadd2 .' '. $shipping_address->xprov }}</td>
							<td>{{ $shipping_address->xcontactlastname1 .', '.$shipping_address->xcontactname1 }}</td>
							<td>{{ $shipping_address->add_type }}</td>
							<td>
								<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#shipping-view{{ $shipping_address->id }}">
									<i class="fas fa-eye"></i>
								</button>
								<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#shipping-del{{ $shipping_address->id }}">
									<i class="fas fa-trash-alt"></i>
								</button>
								@if($shipping_address->xdefault)
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
								@else
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
								@endif
								<div id="shipping-view{{ $shipping_address->id }}" class="modal fade" role="dialog">
									<div class="modal-dialog" style="max-width: 80% !important;">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Shipping Information</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col">
														<label for="x" class="myprofile-font-form"><strong>Contact Person</strong></label>
														<br>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">First Name : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $shipping_address->xcontactname1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Last Name : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $shipping_address->xcontactlastname1 }}">
													</div>
												</div>
												<div class="row"><br></div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">Contact Number : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $shipping_address->xcontactnumber1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Contact Email : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $shipping_address->xcontactemail1 }}">
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
														<label for="Address1_1" class="myprofile-font-form">Address Line 1 : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $shipping_address->xadd1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Address Line 2 : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $shipping_address->xadd2 }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label for="province1_1" class="myprofile-font-form">Province : </label>
														<input type="text" class="form-control caption_1" id="province1_1" value="{{ $shipping_address->xprov }}">
													</div>
													<div class="col">
														<label for="City_Municipality1_1" class="myprofile-font-form">City / Municipality : </label>
														<input type="text" class="form-control caption_1" id="City_Municipality1_1" value="{{ $shipping_address->xcity }}">
													</div>
													<div class="col">
														<label for="Barangay1_1" class="myprofile-font-form">Barangay : </label>
														<input type="text" class="form-control caption_1" id="Barangay1_1" value="{{ $shipping_address->xbrgy }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label for="postal1_1" class="myprofile-font-form">Postal Code : </label>
														<input type="text" class="form-control caption_1" id="postal1_1" value="{{ $shipping_address->xpostal }}">
													</div>
													<div class="col">
														<label for="country_region1_1" class="myprofile-font-form">Country / Region : </label>
														<input type="text" class="form-control caption_1" id="ctounry_1" value="{{ $shipping_address->xcountry }}">
													</div>
													<div class="col">
														<label for="Address_type1_1" class="myprofile-font-form">Address Type :</label>
														<input type="text" class="form-control caption_1" id="Address_type1_1_type" value="{{ $shipping_address->add_type }}">
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
											</div>
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
				<br>
				<a href="/myprofile/address/shipping/new" class="btn btn-primary" role="button">Add New Shipping Address</a>
				<br><br><br>
				<strong><h4>Billing Address : </h4></strong>
				<br>
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Address</th>
							<th>Contacts</th>
							<th>Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($billing_addresses as $billing_address)
						<tr>
							<td>
								<a href="/myprofile/address/{{ $billing_address->id }}/billing/change_default">
								@if ($billing_address->xdefault)
									<i class="fas fa-check-circle" style="font-size: 24px;"></i>
								@else
									<i class="far fa-check-circle" style="font-size: 24px; color:#ada8a8;"></i>
								@endif
								</a>
							</td>
							<td>{{ $billing_address->xadd1 . ' ' . $billing_address->xadd2 .' '. $billing_address->xprov}}</td>
							<td>{{ $billing_address->xcontactlastname1 . ', ' . $billing_address->xcontactname1 }}</td>
							<td>{{ $billing_address->add_type }}</td>
							<td>
								<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myadd{{ $billing_address->id }}">
									<i class="fas fa-eye"></i>
								</button>
								
								<div id="myadd{{ $billing_address->id }}" class="modal fade" role="dialog">
									<div class="modal-dialog" style="max-width: 80% !important;">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Billing Information</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col">
														<label for="x" class="myprofile-font-form"><strong>Contact Person</strong></label>
														<br>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">First Name : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $billing_address->xcontactname1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Last Name : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $billing_address->xcontactlastname1 }}">
													</div>
												</div>
												<div class="row"><br></div>
												<div class="row">
													<div class="col">
														<label for="Address1_1" class="myprofile-font-form">Contact Number : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $billing_address->xcontactnumber1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Contact Email : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $billing_address->xcontactemail1 }}">
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
														<label for="Address1_1" class="myprofile-font-form">Address Line 1 : </label>
														<input type="text" class="form-control caption_1" id="Address1_1" value="{{ $billing_address->xadd1 }}">
													</div>
													<div class="col">
														<label for="Address2_1" class="myprofile-font-form">Address Line 2 : </label>
														<input type="text" class="form-control caption_1" id="Address2_1" value="{{ $billing_address->xadd2 }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label for="province1_1" class="myprofile-font-form">Province : </label>
														<input type="text" class="form-control caption_1" id="province1_1" value="{{ $billing_address->xprov }}">
													</div>
													<div class="col">
														<label for="City_Municipality1_1" class="myprofile-font-form">City / Municipality : </label>
														<input type="text" class="form-control caption_1" id="City_Municipality1_1" value="{{ $billing_address->xcity }}">
													</div>
													<div class="col">
														<label for="Barangay1_1" class="myprofile-font-form">Barangay : </label>
														<input type="text" class="form-control caption_1" id="Barangay1_1" value="{{ $billing_address->xbrgy }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col">
														<label for="postal1_1" class="myprofile-font-form">Postal Code : </label>
														<input type="text" class="form-control caption_1" id="postal1_1" value="{{ $billing_address->xpostal }}">
													</div>
													<div class="col">
														<label for="country_region1_1" class="myprofile-font-form">Country / Region : </label>
														<input type="text" class="form-control caption_1" id="ctounry_1" value="{{ $billing_address->xcountry }}">
													</div>
													<div class="col">
														<label for="Address_type1_1" class="myprofile-font-form">Address Type :</label>
														<input type="text" class="form-control caption_1" id="Address_type1_1_type" value="{{ $billing_address->add_type }}">
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>

								<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myDelete{{ $billing_address->id }}">
									<i class="fas fa-trash-alt"></i>
								</button>
								@if ($billing_address->xdefault)
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
								@else
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
								@endif
							</td>
						</tr> 
						@empty
						<tr>
							<td colspan="5" class="text-center">No billing address found.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				<br>
				<a href="/myprofile/address/billing/new" class="btn btn-primary" role="button">Add New Billing Address</a>
				<br><br><br>
				
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
@endsection
