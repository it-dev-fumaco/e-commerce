@extends('backend.layout', [
	'namePage' => 'Shipping',
	'activePage' => 'shipping_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">View Shipping</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="/admin/shipping/list">Shipping</a></li>
						<li class="breadcrumb-item active">View Shipping</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<form action="/admin/shipping/{{ $details->shipping_service_id }}/update" method="POST" id="shipping-carrier-form">
			<div class="row">
				@csrf
          	<!-- left column -->
				<div class="col-md-12">
					<div class="alert alert-success d-none text-center font-weight-bold" id="alert-div"></div>
           	 	<!-- general form elements -->
					<div class="card">
					<!-- /.card-header -->
						<div class="card-body">
							<h4 class="d-inline-block">Shipping Service Details</h4>
								<div class="float-right">
									<a href="/admin/shipping/add" class="btn btn-secondary mr-2"><i class="fa fa-plus"></i>&nbsp;Create New Shipping</a>
									<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Update</button>
								</div>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										@php
											$shipping_options = [
												'Standard Delivery',
												'Express Delivery',
												'Store Pickup',
												'Free Delivery'
											];
											$shipping_options = collect($shipping_options)->merge($third_party_shipping);
										@endphp
										<label for="shipping-service-type" class="form-label">* Shipping Rules</label>
										<select name="shipping_service_type" id="shipping-service-type" class="form-control" required>
											<option value="">-</option>
											@foreach ($shipping_options as $shipping)
												<option value="{{ $shipping }}" {{ $details->shipping_service_name == $shipping ? 'selected' : '' }}>{{ $shipping }}</option>
											@endforeach
										</select>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="min-leadtime" class="form-label">* Min. Leadtime in Day(s)</label>
												<input type="number" class="form-control" id="min-leadtime" name="min_leadtime" value="{{ $details->min_leadtime }}" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="max-leadtime" class="form-label">* Max. Leadtime in Day(s)</label>
												<input type="number" class="form-control" id="max-leadtime" name="max_leadtime" value="{{ $details->max_leadtime }}"  required>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="shipping-description">Description</label>
										<textarea class="form-control" rows="3" id="shipping-description" name="shipping_service_description">{{ $details->shipping_service_description }}</textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{ ($details->shipping_service_name == 'Store Pickup') ? 'd-none' : ''  }}">
										<label for="shipping-method" class="form-label">* Shipping Condition</label>
										<select name="shipping_calculation" id="shipping-method" class="form-control">
											<option value="">-</option>
											<option value="Flat Rate" {{ ($details->shipping_calculation == 'Flat Rate') ? 'selected' : '' }}>Flat Rate</option>
											<option value="Per Amount" {{ ($details->shipping_calculation == 'Per Amount') ? 'selected' : '' }}>Per Amount</option>
											<option value="Per Quantity" {{ ($details->shipping_calculation == 'Per Quantity') ? 'selected' : '' }}>Per Quantity</option>
											<option value="Per Weight" {{ ($details->shipping_calculation == 'Per Weight') ? 'selected' : '' }}>Per Weight</option>
											<option value="Per Cubic cm" {{ ($details->shipping_calculation == 'Per Cubic cm') ? 'selected' : '' }}>Per Cubic cm</option>
										</select>
									</div>
									<div class="row">
										<div class="col-md-6 {{ ($details->shipping_calculation != 'Flat Rate') ? 'd-none' : ''  }}">
											<div class="form-group">
												<label for="flat-rate-amount" class="form-label">* Amount</label>
												<input type="text" class="form-control" id="flat-rate-amount" name="amount" placeholder="0.00" value="{{ $details->amount }}">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group {{ ($details->shipping_calculation == 'Flat Rate' || $details->shipping_service_name == 'Store Pickup') ? 'd-none' : ''  }}">
												<label for="min-charge-amount" class="form-label">* Min. Charge Amount</label>
												<input type="text" class="form-control" id="min-charge-amount" name="min_charge_amount" placeholder="0.00" value="{{ $details->min_charge_amount }}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group {{ ($details->shipping_calculation == 'Flat Rate' || $details->shipping_service_name == 'Store Pickup') ? 'd-none' : ''  }}">
												<label for="max-charge-amount" class="form-label">* Max. Charge Amount</label>
												<input type="text" class="form-control" id="max-charge-amount" name="max_charge_amount" placeholder="0.00" value="{{ $details->max_charge_amount }}" >
											</div>
										</div>
									</div>
								</div>
							</div>
							<h5 class="store-locations {{ ($details->shipping_service_name != 'Store Pickup') ? 'd-none' : ''  }} mt-3">Store Location(s)</h5>
							<hr class="store-locations {{ ($details->shipping_service_name != 'Store Pickup') ? 'd-none' : ''  }}">
							<div class="row store-locations {{ ($details->shipping_service_name != 'Store Pickup') ? 'd-none' : ''  }}">
								<div class="col-md-8 offset-md-2">
									<div class="float-right mt-2">
										 <button class="btn btn-outline-primary btn-sm mb-2 add-tbl-row" type="button" data-table="#stores-table" data-select="#store-location"><i class="fa fa-plus"></i>&nbsp;Add Store</button>
									</div>
									<table class="table table-bordered" id="stores-table">
										 <thead>
											  <tr>
													<th style="width: 50%;" scope="col" class="text-center">Store Location</th>
													<th style="width: 35%;" scope="col" class="text-center">Allowance before pickup (in Hours)</th>
													<th style="width: 15%;"></th>
											  </tr>
										 </thead>
										 <tbody>
											@foreach ($shipping_service_stores as $row)
											<tr>
												 <td class="p-2">
													  <input type="hidden" name="shipping_service_store_id[]" value="{{ $row->shipping_service_store_id }}">
													  <select name="store[]" class="form-control w-100" style="width: 100%;" required>
															<option value="">-</option>
															@foreach ($stores as $store)
															<option value="{{ $store->store_id }}" {{ ($store->store_id == $row->store_id) ? 'selected' : ''  }}>{{ $store->store_name }}</option>
															@endforeach
													  </select>
												 </td>
												 <td class="p-2">
													<input type="text" name="allowed_hours[]" class="form-control" placeholder="0.00" value="{{ $row->allowance_in_hours }}" required>
												 </td>
												 <td class="text-center">
													  <button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
												 </td>
											</tr>
											@endforeach
										 </tbody>
									</table>
							  </div>
							</div>
							<select class="d-none" id="store-location">
								<option value="">-</option>
								@foreach ($stores as $store)
								<option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
								@endforeach
							</select>
							<select class="d-none" id="product-categories">
								<option value="">-</option>
								@foreach ($product_categories as $id => $name)
								<option value="{{ $id }}">{{ $name }}</option>
								@endforeach
							</select>
							<h5 class="shipping-conditions {{ ($details->shipping_calculation == 'Flat Rate' || !$details->shipping_calculation) ? 'd-none' : ''  }} mt-3">Shipping Condition(s)</h5>
							<hr class="shipping-conditions {{ ($details->shipping_calculation == 'Flat Rate' || !$details->shipping_calculation) ? 'd-none' : ''  }}">
							<div class="row shipping-conditions {{ ($details->shipping_calculation == 'Flat Rate' || !$details->shipping_calculation) ? 'd-none' : ''  }}">
								<div class="col-md-10 offset-md-1">
									<div class="float-right">
										<button class="btn btn-outline-primary btn-sm mb-2 add-tbl-row" type="button" data-table="#shipping-condition-table"><i class="fa fa-plus"></i>&nbsp;Add Shipping Condition</button>
									</div>
									<table class="table table-bordered" id="shipping-condition-table">
										 <thead>
											  <tr>
													<th style="width: 25%;" scope="col" class="text-center">Shipping Condition</th>
													<th style="width: 20%;" scope="col" class="text-center">Condition</th>
													<th style="width: 20%;" scope="col" class="text-center">Value</th>
													<th style="width: 25%;" scope="col" class="text-center">Shipping Amount</th>
													<th style="width: 10%;"></th>
											  </tr>
										 </thead>
										 <tbody>
											@foreach ($shipping_conditions as $conditions)
											<tr>
												 <td class="p-2">
													  <input type="text" class="form-control" name="condition[]" value="{{ $conditions->type }}" readonly required>
												 </td>
												 <td class="p-2">
													  <select class="form-control" name="conditional_op[]" required>
															<option value=">" {{ ($conditions->conditional_operator == '>') ? 'selected' : ''  }}>></option>
															<option value=">=" {{ ($conditions->conditional_operator == '>=') ? 'selected' : ''  }}>>=</option>
															<option value="==" {{ ($conditions->conditional_operator == '==') ? 'selected' : ''  }}>==</option>
															<option value="<" {{ ($conditions->conditional_operator == '<') ? 'selected' : ''  }}><</option>
															<option value="<=" {{ ($conditions->conditional_operator == '<=') ? 'selected' : ''  }}><=</option>
													  </select>
												 </td>
												 <td class="p-2">
													  <input type="text" class="form-control" name="value[]" value="{{ $conditions->value }}" required>
												 </td>
												 <td class="p-2">
													  <input type="text" name="shipping_amount[]" class="form-control" value="{{ $conditions->shipping_amount }}" placeholder="0.00" required>
												 </td>
												 <td class="text-center">
													  <button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
												 </td>
											</tr>
											@endforeach
										 </tbody>
									</table>
							  </div>
							</div>
							<h5 class="shipping-zone-rates {{ ($details->shipping_service_name == 'Store Pickup' || !$details->shipping_calculation) ? 'd-none' : ''  }} mt-3">Shipping Zone Rate(s)</h5>
							<hr class="shipping-zone-rates {{ ($details->shipping_service_name == 'Store Pickup' || !$details->shipping_calculation) ? 'd-none' : ''  }}">
							<div class="row shipping-zone-rates {{ ($details->shipping_service_name == 'Store Pickup' || !$details->shipping_calculation) ? 'd-none' : ''  }}">
								<div class="col-md-10 offset-md-1">
									<label>Allow shipping rule to these region/city only.</label>
									<div class="float-right">
										 <button class="btn btn-outline-primary btn-sm mb-2 add-tbl-row" data-table="#shipping-zone-table"><i class="fa fa-plus"></i>&nbsp;Add Shipping Zone</button>
									</div>
									<table class="table table-bordered" id="shipping-zone-table">
										 <thead>
											  <tr>
													<th style="width: 30%;" scope="col" class="text-center">Region / Province</th>
													<th style="width: 23%;" scope="col" class="text-center">Town / City</th>
													<th style="width: 10%;"></th>
											  </tr>
										 </thead>
										 <tbody>
											@foreach ($shipping_zone_rates as $row)
											<tr>
												 <td class="p-2">
													  <input type="hidden" name="shipping_zone_rate_id[]" value="{{ $row->shipping_zone_rate_id }}">
													  <input type="hidden" class="selected-province-text" name="province_text[]">
											 <input type="hidden" class="selected-city-text" name="city_text[]">
													  <select name="province[]" class="form-control province-select w-100" style="width: 100%;" data-value="{{ $row->province_code }}">
															<option value=""></option>
													  </select>
												 </td>
												 <td class="p-2">
													  <select name="city[]" class="form-control city-select w-100" style="width: 100%;" data-value="{{ $row->city_code }}" data-province="{{ $row->province_code }}">
															<option value=""></option>
													  </select>
												 </td>
												 <td class="text-center">
													  <button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
												 </td>
											</tr>
											@endforeach
										 </tbody>
									</table>
							  </div>
							</div>
							<h5 class="shipping-category mt-3">Product Category</h5>
							<hr class="shipping-category">
							<div class="row shipping-category">
								<div class="col-12">
									<label id="label-cat">{{ $details->shipping_service_name == 'Store Pickup' ? 'Override leadtime for the following product categories' : 'Not applicable for the following product categories' }}</label>
									<div class="float-right">
										 <button class="btn btn-outline-primary btn-sm mb-2 add-tbl-row" data-table="#product-category-table" data-select='#product-categories'><i class="fa fa-plus"></i>&nbsp;Add Product Category</button>
									</div>
									<table class="table table-bordered" id="product-category-table">
										<thead>
											<tr>
												<th style="width: 40%;" scope="col" class="text-center">Product Category Name</th>
												<th style="width: 10%;" scope="col" class="text-center">Condition</th>
												<th style="width: 10%;" scope="col" class="text-center">Qty</th>
												<th style="width: 15%;" scope="col" class="text-center">Min. Leadtime</th>
												<th style="width: 15%;" scope="col" class="text-center">Max. Leadtime</th>
												<th style="width: 10%;"></th>
											</tr>
										</thead>
										 <tbody>
											 @foreach($categories as $row) 
											 <tr>
												<td class="p-2">
													<select name="product_category[]" class="form-control w-100" style="width: 100%;" required>
														<option value="">-</option>
														@foreach ($product_categories as $id => $name)
														<option value="{{ $id }}" {{ $row->category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
														@endforeach
													</select>
												</td>
												<td class="p-2">
													<input type="text" name="c_conditional_op[]" class="form-control" value="{{ $row->condition }}" placeholder="Condition" required>
												</td>
												<td class="p-2">
													<input type="text" name="c_value[]" class="form-control" value="{{ $row->qty }}" placeholder="0.00" required>
												</td>
												<td class="p-2">
													<input type="text" name="c_min_leadtime[]" class="form-control" value="{{ $row->min_leadtime }}" placeholder="0.00" required>
												</td>
												<td class="p-2">
													<input type="text" name="c_max_leadtime[]" class="form-control" value="{{ $row->max_leadtime }}" placeholder="0.00" required>
												</td>
												<td class="text-center">
													<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
											 @endforeach
										 </tbody>
									</table>
							  </div>
						
						</div>
						<div class="float-right font-italic">
							<small>Last modified by: {{ $details->last_modified_by }} - {{ $details->last_modified_at }}</small><br>
							<small>Created by: {{ $details->created_by }} - {{ $details->created_at }}</small>
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.card -->
				</div>
			</div>
		</form>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>

 <div id="custom-overlay" style="display: none;">
  <div class="custom-spinner"></div>
  <br/>
  Loading...
</div>

<style>
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
	@keyframes rotate {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
</style>
@endsection

@section('script')
<script>
	(function() {

		$('#shipping-service-type').change(function(e){
			e.preventDefault();
			var shipping_service_type = $(this).val();

			$('#flat-rate-amount').closest('.col-md-6').addClass('d-none');
			$('.shipping-conditions').addClass('d-none');
			$('.shipping-zone-rates').addClass('d-none');

			$('#shipping-method').val("");

			$('#shipping-zone-table tbody').empty();
			$('#shipping-condition-table tbody').empty();
			$('#stores-table tbody').empty();

			if (shipping_service_type == '') {
				$('#shipping-method').closest('.form-group').addClass('d-none');
			} else  if (shipping_service_type == 'Store Pickup'){
				// add_store_row('#stores-table tbody');
				add_tbl_row('#stores-table', '#store-location');
				$('#shipping-method').closest('.form-group').addClass('d-none');
				$('.store-locations').removeClass('d-none');
				$('#label-cat').text('Override leadtime for the following product categories');
			} else {
				if (shipping_service_type != 'Express Delivery'){
					$('#label-cat').text('Override leadtime for the following product categories');
				} else {
					$('#label-cat').text('Not applicable for the following product categories');
				}
				// add_shipping_zone_rate_row('#shipping-zone-table tbody');
				add_tbl_row('#shipping-zone-table');
				$('#shipping-method').closest('.form-group').removeClass('d-none');
				$('.store-locations').addClass('d-none');
			}
		});

		$('#shipping-method').change(function(e){
			e.preventDefault();	

			$('#shipping-condition-table tbody').empty();
			$('#flat-rate-amount').val('');

			$('.shipping-conditions').addClass('d-none');
			$('.shipping-zone-rates').addClass('d-none');
			if($(this).val() == 'Flat Rate'){
				$('#flat-rate-amount').closest('.col-md-6').removeClass('d-none');
				$('#min-charge-amount').closest('.form-group').addClass('d-none');
				$('#max-charge-amount').closest('.form-group').addClass('d-none');

				$('.shipping-zone-rates').removeClass('d-none');
			}else if($(this).val() && $(this).val() != 'Flat Rate'){
				// add_shipping_condition_row('#shipping-condition-table tbody');
				add_tbl_row('#shipping-condition-table');
				$('#flat-rate-amount').closest('.col-md-6').addClass('d-none');
				$('#min-charge-amount').closest('.form-group').removeClass('d-none');
				$('#max-charge-amount').closest('.form-group').removeClass('d-none');

				$('.shipping-conditions').removeClass('d-none');
				$('.shipping-zone-rates').removeClass('d-none');
			} else {
				$('#shipping-condition-table tbody').empty();
				$('#flat-rate-amount').val('');
				$('.shipping-conditions').addClass('d-none');
				$('.shipping-zone-rates').addClass('d-none');
			}
		});

		$(document).on('click', '.add-tbl-row', function(e){
			e.preventDefault();
			var table = $(this).data('table');
			var select = (typeof $(this).data('select') !== 'undefined') ? $(this).data('select') : '';
			add_tbl_row(table, select);
		});

		function add_tbl_row(table, select){
			var clone_select = $(select).html();
			switch (table) {
				case '#stores-table': // add_store_row
					var row = '<tr>' +
						'<td class="p-2">' + 
							'<select name="store[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
						'</td>' +
						'<td class="p-2"">' +
							'<input type="text" name="allowed_hours[]" class="form-control" placeholder="0.00" required>' +
						'</td>' +
						'<td class="text-center">' +
							'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
						'</td>' +
					'</tr>';
					break;
				case '#shipping-zone-table': // add_shipping_zone_rate_row
					var row = '<tr>' +
						'<td class="p-2">' + 
							'<input type="hidden" class="selected-province-text" name="province_text[]">' +
							'<input type="hidden" class="selected-city-text" name="city_text[]">' +
							'<select name="province[]" class="form-control province-select w-100" style="width: 100%;" required><option value=""></option></select>' +
						'</td>' +
						'<td class="p-2">' +
							'<select name="city[]" class="form-control city-select w-100" style="width: 100%;" required><option value=""></option></select>' +
						'</td>' +
						'<td class="text-center">' +
							'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
						'</td>' +
					'</tr>';

					var provinces = [];
					$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
						$.each(obj.results, function(e, i) {
							provinces.push({
								id: i.provCode,
								text: i.text
							});
						});

						$('.province-select').select2({
							placeholder: 'Select Province',
							data: provinces
						});
					});

					$('.city-select').select2({
						placeholder: 'Select City',
					});
					break;
				case '#product-category-table': // add_shipping_category
					var row = '<tr>' +
						'<td class="p-2">' +
							'<select name="product_category[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
						'</td>' +
						'<td class="p-2">' + 
							'<select class="form-control" name="c_conditional_op[]" required>' +
								'<option value=">">></option>' +
								'<option value=">=">>=</option>' +
								'<option value="==">==</option>' +
								'<option value="<"><</option>' +
								'<option value="<="><=</option>' +
							'</select>' +
						'</td>' +
						'<td class="p-2">' +
							'<input type="text" class="form-control" name="c_value[]" required>' +
						'</td>' +
						'<td class="p-2">' +
							'<input type="text" name="c_min_leadtime[]" class="form-control" placeholder="0 Day(s)" required>' +
						'</td>' +
						'<td class="p-2">' +
							'<input type="text" name="c_max_leadtime[]" class="form-control" placeholder="0 Day(s)" required>' +
						'</td>' +
						'<td class="text-center">' +
							'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
						'</td>' +
					'</tr>';
					break;
				case '#shipping-condition-table': // add_shipping_condition_row
					var row = '<tr>' +
						'<td class="p-2">' +
							'<input type="text" class="form-control" name="condition[]" value="' + $('#shipping-method').val() + '" readonly required>' +
						'</td>' +
						'<td class="p-2">' + 
							'<select class="form-control" name="conditional_op[]" required>' +
								'<option value=">">></option>' +
								'<option value=">=">>=</option>' +
								'<option value="==">==</option>' +
								'<option value="<"><</option>' +
								'<option value="<="><=</option>' +
							'</select>' +
						'</td>' +
						'<td class="p-2">' +
							'<input type="text" class="form-control" name="value[]" required>' +
						'</td>' +
						'<td class="p-2">' +
							'<input type="text" name="shipping_amount[]" class="form-control" placeholder="0.00" required>' +
						'</td>' +
						'<td class="text-center">' +
							'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
						'</td>' +
					'</tr>';
					break;
				
				default:
					return false;
					break;
			}
			
			$(table + ' tbody').append(row);
		}
		
		// $('#add-store-btn').click(function(e){
		// 	e.preventDefault();
			
		// 	add_store_row('#stores-table tbody');
		// });

		// $('#add-shipping-zone-btn').click(function(e){
		// 	e.preventDefault();

		// 	add_shipping_zone_rate_row('#shipping-zone-table tbody');
		// });

		// $('#add-shipping-condition-btn').click(function(e){
		// 	e.preventDefault();

		// 	add_shipping_condition_row('#shipping-condition-table tbody');
		// });

		// function add_store_row(table){
		// 	var clone_select = $('#store-location').html();
		// 	var row = '<tr>' +
		// 		'<td class="p-2">' + 
		// 			'<select name="store[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
		// 		'</td>' +
		// 		'<td class="p-2"">' +
		// 			'<input type="text" name="allowed_hours[]" class="form-control" placeholder="0.00" required>' +
		// 		'</td>' +
		// 		'<td class="text-center">' +
		// 			'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
		// 		'</td>' +
		// 	'</tr>';

		// 	$(table).append(row);
		// }
			
		// function add_shipping_zone_rate_row(table){
		// 	var row = '<tr>' +
		// 		'<td class="p-2">' + 
		// 			'<input type="hidden" class="selected-province-text" name="province_text[]">' +
		// 			'<input type="hidden" class="selected-city-text" name="city_text[]">' +
		// 			'<select name="province[]" class="form-control province-select w-100" style="width: 100%;" required><option value=""></option></select>' +
		// 		'</td>' +
		// 		'<td class="p-2">' +
		// 			'<select name="city[]" class="form-control city-select w-100" style="width: 100%;" required><option value=""></option></select>' +
		// 		'</td>' +
		// 		'<td class="text-center">' +
		// 			'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
		// 		'</td>' +
		// 	'</tr>';

		// 	$(table).append(row);
			
		// 	var provinces = [];
		// 	$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
		// 		$.each(obj.results, function(e, i) {
		// 			provinces.push({
		// 				id: i.provCode,
		// 				text: i.text
		// 			});
		// 		});

		// 		$('.province-select').select2({
		// 			placeholder: 'Select Province',
		// 			data: provinces
		// 		});
		// 	});

		// 	$('.city-select').select2({
		// 		placeholder: 'Select City',
		// 	});
		// }

		// function add_shipping_condition_row(table){
		// 	var row = '<tr>' +
		// 		'<td class="p-2">' +
		// 			'<input type="text" class="form-control" name="condition[]" value="' + $('#shipping-method').val() + '" readonly required>' +
		// 		'</td>' +
		// 		'<td class="p-2">' + 
		// 			'<select class="form-control" name="conditional_op[]" required>' +
		// 				'<option value=">">></option>' +
		// 				'<option value=">=">>=</option>' +
		// 				'<option value="==">==</option>' +
		// 				'<option value="<"><</option>' +
		// 				'<option value="<="><=</option>' +
		// 				'</select>' +
		// 		'</td>' +
		// 		'<td class="p-2">' +
		// 			'<input type="text" class="form-control" name="value[]" required>' +
		// 		'</td>' +
		// 		'<td class="p-2">' +
		// 			'<input type="text" name="shipping_amount[]" class="form-control" placeholder="0.00" required>' +
		// 		'</td>' +
		// 		'<td class="text-center">' +
		// 			'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
		// 		'</td>' +
		// 	'</tr>';

		// 	$(table).append(row);
		// }

		$(document).on('select2:select', '.province-select', function(e){
			var data = e.params.data;
			var row = $(this).closest('tr');
			var select_el = row.find('.city-select');
			var cities = [{id: -1, text: "ALL"}];

			row.find('.selected-province-text').eq(0).val(data.text);
			row.find('.selected-city-text').eq(0).val('ALL');
			
			select_el.empty();
			$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
				var filtered_cities = $.grep(obj.results, function(v) {
					return v.provCode === data.id;
				});

				$.each(filtered_cities, function(e, i) {
					cities.push({
						id: i.citymunCode,
						text: i.text
					});
				});

				select_el.select2({
					placeholder: 'Select City',
					data: cities
				});
			});
		});

		$(document).on('select2:select', '.city-select', function(e){
			var data = e.params.data;
			var row = $(this).closest('tr');
			
			row.find('.selected-city-text').eq(0).val(data.text);
		});

		$(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});

		$('#shipping-zone-table tbody select').each(function() {
			var this_el = $(this);
			var provinces = [];
			var row = $(this).closest('tr');
			
			this_el.empty();
			if(this_el.hasClass('province-select')){
				$.getJSON("{{ asset('/json/provinces.json') }}", function(obj){
					$.each(obj.results, function(e, i) {
						provinces.push({
							id: i.provCode,
							text: i.text
						});
					});

					this_el.select2({
						placeholder: 'Select Province',
						data: provinces
					});

					this_el.val(this_el.data('value'));
					this_el.select2().trigger('change');

					row.find('.selected-province-text').eq(0).val(this_el.find('option:selected').text());
				});
			}

			if(this_el.hasClass('city-select')){
				var cities = [{id: -1, text: "ALL"}];
				var this_prov = this_el.data('province');
				$.getJSON("{{ asset('/json/cities.json') }}", function(obj){
					var filtered_cities = $.grep(obj.results, function(v) {
						return v.provCode === this_prov.toString();
					});

					$.each(filtered_cities, function(e, i) {
						cities.push({
							id: i.citymunCode,
							text: i.text
						});
					});

					this_el.select2({
						placeholder: 'Select City',
						data: cities
					});

					this_el.val($(this_el).data('value'));
					this_el.select2().trigger('change');

					row.find('.selected-city-text').eq(0).val(this_el.find('option:selected').text());
				});
			}
		});

		$('#shipping-carrier-form').submit(function(e){
			e.preventDefault();
			var frm = $('#shipping-carrier-form');
			$.ajax({
				url: frm.attr('action'),
				type:"POST",
				data: frm.serialize(),
				success:function(data){
					console.log(data);
					if(data.status){
						$('#alert-div').removeClass('d-none alert-danger').addClass('alert-success').text(data.message);
						if(data.new){
							setTimeout(function(){ window.location.href = data.redirect_to; }, 1500);
						}
					}else{
						$('#alert-div').removeClass('d-none alert-success').addClass('alert-danger').text(data.message);
					}
				},
				error : function(data) {
					$('#alert-div').removeClass('d-none alert-success').addClass('alert-danger').text(data.responseText);
				}
			});
		});

		$('#add-product-category-btn').click(function(e){
			e.preventDefault();

			var clone_select = $('#product-categories').html();
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="product_category[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="p-2">' +
					'<input type="text" name="c_min_leadtime[]" class="form-control" placeholder="0 Day(s)" required>' +
				'</td>' +
				'<td class="p-2">' +
					'<input type="text" name="c_max_leadtime[]" class="form-control" placeholder="0 Day(s)" required>' +
				'</td>' +
				'<td class="text-center">' +
					'<button class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
				'</td>' +
			'</tr>';

			$('#product-category-table tbody').append(row);
		});
	})();

</script>
@endsection
