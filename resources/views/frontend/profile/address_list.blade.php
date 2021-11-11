@extends('frontend.layout', [
	'namePage' => 'My Profile - Address',
	'activePage' => 'myprofile_address'
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
						@forelse ($shipping_addresses as $key => $shipping_address)
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
								<button type="button" class="shipping btn btn-success btn-xs {{ $key }}" data-toggle="modal" data-target="#shipping-view{{ $shipping_address->id }}">
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
														<label for="Address1_1" class="myprofile-font-form">Contact Number : </label>
														<input type="text" name="contact" class="form-control caption_1" value="{{ ($shipping_address->xcontactnumber1 != 0) ? $shipping_address->xcontactnumber1 : '' }}">
													</div>
													<div class="col-md-4">
														<label for="Address2_1" class="myprofile-font-form">Mobile Number : <span class="text-danger">*</span></label>
														<input type="text" name="mobile" class="form-control caption_1" value="{{ $shipping_address->xmobile_number }}" required>
													</div>
													<div class="col-md-4">
														<label for="Address2_1" class="myprofile-font-form">Contact Email : <span class="text-danger">*</span></label>
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
														<label for="province1_1" class="myprofile-font-form">Province : <span class="text-danger">*</span></label>
														<input type="text" name="province" class="form-control caption_1" id="province1_1_{{ $key }}" value="{{ $shipping_address->xprov }}" required>
													</div>
													<div class="col-md-4">
														<label for="City_Municipality1_1" class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
														<input type="text" name="city" class="form-control caption_1" id="City_Municipality1_1_{{ $key }}" value="{{ $shipping_address->xcity }}" required>
													</div>
													<div class="col-md-4">
														<label for="Barangay1_1" class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
														<input type="text" name="brgy" class="form-control caption_1" id="Barangay1_1_{{ $key }}" value="{{ $shipping_address->xbrgy }}" required>
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-4">
														<label for="postal1_1" class="myprofile-font-form">Postal Code : <span class="text-danger">*</span></label>
														<input type="text" name="postal" class="form-control caption_1" id="postal1_1" value="{{ $shipping_address->xpostal }}" required>
													</div>
													<div class="col-md-4">
														<label for="country_region1_1" class="myprofile-font-form">Country / Region : <span class="text-danger">*</span></label>
														<input type="text" name="country" class="form-control caption_1" id="country_1" value="{{ $shipping_address->xcountry }}" required>
													</div>
													<div class="col-md-4">
														<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
														<select class="form-control formslabelfnt ship_type" id="ship_Address_type1_1" name="Address_type1_1">
															<option value="">Choose...</option>
															<option value="Business Address" {{ old('ship_Address_type1_1') == 'Business Address' ? 'selected' : '' }}>Business Address</option>
															<option value="Home Address" {{ old('ship_Address_type1_1') == 'Home Address' ? 'selected' : '' }}>Home Address</option>
														</select>
													</div>
												</div>
												<br/>
												<div class="row" class="ship_for_business" id="ship_for_business_{{ $key }}" style="display: none">
													<div class="col-md-6">
														<label for="business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
														<input type="text" class="form-control formslabelfnt" id="ship_business_name_{{ $key }}" name="business_name"><br class="d-lg-none d-xl-none"/>
													</div>
													<div class="col-md-6">
														<label for="tin" class="formslabelfnt">TIN Number :</label>
														<input type="text" class="form-control formslabelfnt" id="tin" name="tin"><br class="d-lg-none d-xl-none"/>
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
						@forelse ($billing_addresses as $key => $billing_address)
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
								<button type="button" class="billing btn btn-success btn-xs {{ $key }}" data-toggle="modal" data-target="#myadd{{ $billing_address->id }}">
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
													<div class="col-md-6">
														<label for="Address1_1" class="myprofile-font-form">First Name : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="first_name" value="{{ $billing_address->xcontactname1 }}" required>
													</div>
													<div class="col-md-6">
														<label for="Address2_1" class="myprofile-font-form">Last Name : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="last_name" value="{{ $billing_address->xcontactlastname1 }}" required>
													</div>
												</div>
												<div class="row"><br></div>
												<div class="row">
													<div class="col-md-4">
														<label for="Address1_1" class="myprofile-font-form">Contact Number : </label>
														<input type="text" class="form-control caption_1" name="contact" value="{{ $billing_address->xcontactnumber1 != 0 ? $billing_address->xcontactnumber1 : '' }}">
													</div>
													<div class="col-md-4">
														<label for="Address1_1" class="myprofile-font-form">Mobile Number : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="mobile" value="{{ $billing_address->xmobile_number }}" required>
													</div>
													<div class="col-md-4">
														<label for="Address2_1" class="myprofile-font-form">Contact Email : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="email" value="{{ $billing_address->xcontactemail1 }}" required>
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
														<input type="text" class="form-control caption_1" name="address1" id="Address1_1" value="{{ $billing_address->xadd1 }}" required>
													</div>
													<div class="col-md-6">
														<label for="Address2_1" class="myprofile-font-form">Address Line 2 : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="address2" id="Address2_1" value="{{ $billing_address->xadd2 }}">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-4">
														<label for="province1_1" class="myprofile-font-form">Province : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="province" id="bill_province1_1_{{ $key }}" value="{{ $billing_address->xprov }}" required>
													</div>
													<div class="col-md-4">
														<label for="City_Municipality1_1" class="myprofile-font-form">City / Municipality : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="city" id="bill_City_Municipality1_1_{{ $key }}" value="{{ $billing_address->xcity }}" required>
													</div>
													<div class="col-md-4">
														<label for="Barangay1_1" class="myprofile-font-form">Barangay : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="brgy" id="bill_Barangay1_1_{{ $key }}" value="{{ $billing_address->xbrgy }}" required>
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-4">
														<label for="postal1_1" class="myprofile-font-form">Postal Code : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="postal" id="postal1_1" value="{{ $billing_address->xpostal }}" required>
													</div>
													<div class="col-md-4">
														<label for="country_region1_1" class="myprofile-font-form">Country / Region : <span class="text-danger">*</span></label>
														<input type="text" class="form-control caption_1" name="country" id="country_1" value="{{ $billing_address->xcountry }}" required>
													</div>
													<div class="col-md-4">
														<label for="Address_type1_1" class="formslabelfnt">Address Type : <span class="text-danger">*</span></label>
														<select class="form-control formslabelfnt bill_type" id="bill_Address_type1_1" name="Address_type1_1" required>
															<option value="">Choose...</option>
															<option value="Business Address" {{ old('ship_Address_type1_1') == 'Business Address' ? 'selected' : '' }}>Business Address</option>
															<option value="Home Address" {{ old('ship_Address_type1_1') == 'Home Address' ? 'selected' : '' }}>Home Address</option>
														</select>
													</div>
												</div>
												<br/>
												<div class="row" id="bill_for_business_{{ $key }}" style="display: none">
													<div class="col-md-6">
														<label for="business_name" class="formslabelfnt">Business Name : <span class="text-danger">*</span></label>
														<input type="text" class="form-control formslabelfnt" id="bill_business_name_{{ $key }}" name="business_name"><br class="d-lg-none d-xl-none"/>
													</div>
													<div class="col-md-6">
														<label for="tin" class="formslabelfnt">TIN Number :</label>
														<input type="text" class="form-control formslabelfnt" id="tin" name="tin"><br class="d-lg-none d-xl-none"/>
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
	.select2-selection__rendered {
		line-height: 34px !important;
	}
	.select2-container .select2-selection--single {
		height: 37px !important;
	}
	.select2-selection__arrow {
		height: 35px !important;
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
</style>
@endsection

@section('script')
<!-- Select2 -->
<script src="{{ asset('/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
	$(document).ready(function() {
		var provinces = [];
		// Shipping Modal
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
		// Shipping Modal

		// Billing Modal
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
		// Billing Modal

	});

</script>
@endsection