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
					<h1 class="m-0">Create New Shipping</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                  <li class="breadcrumb-item"><a href="/admin/shipping/list">Shipping</a></li>
						<li class="breadcrumb-item active">Create New Shipping</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<form action="/admin/shipping/save" method="POST" id="shipping-carrier-form">
			<div class="row">
				@csrf
          	<!-- left column -->
				<div class="col-md-12">
					<div class="alert alert-success d-none text-center font-weight-bold" id="alert-div"></div>
           	 	<!-- general form elements -->
					<div class="card">
					<!-- /.card-header -->
						<div class="card-body">
							<h4>Shipping Service Registration</h4>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="shipping-service-type" class="form-label">* Shipping Rules</label>
										<select name="shipping_service_type" id="shipping-service-type" class="form-control" required>
											<option value="">-</option>
											<option value="Standard Delivery">Standard Delivery</option>
											<option value="Express Delivery">Express Delivery</option>
											<option value="Store Pickup">Store Pickup</option>
											<option value="Free Delivery">Free Delivery</option>
										</select>
									</div>
									<div class="row" id="leadtimes">
										<div class="col-md-6">
											<div class="form-group">
												<label for="min-leadtime" class="form-label">* Min. Leadtime in Day(s)</label>
												<input type="number" class="form-control" id="min-leadtime" name="min_leadtime" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="max-leadtime" class="form-label">* Max. Leadtime in Day(s)</label>
												<input type="number" class="form-control" id="max-leadtime" name="max_leadtime" required>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="shipping-description">Description</label>
										<textarea class="form-control" rows="3" id="shipping-description" name="shipping_service_description"></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group d-none">
										<label for="shipping-method" class="form-label">* Shipping Condition</label>
										<select name="shipping_calculation" id="shipping-method" class="form-control">
											<option value="">-</option>
											<option value="Flat Rate">Flat Rate</option>
											<option value="Per Amount">Per Amount</option>
											<option value="Per Quantity">Per Quantity</option>
											<option value="Per Weight">Per Weight</option>
											<option value="Per Cubic cm">Per Cubic cm</option>
										</select>
									</div>
									<div class="row">
										<div class="col-md-6 d-none">
											<div class="form-group">
												<label for="flat-rate-amount" class="form-label">* Amount</label>
												<input type="text" class="form-control" id="flat-rate-amount" name="amount" placeholder="0.00">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group d-none">
												<label for="min-charge-amount" class="form-label">* Min. Charge Amount</label>
												<input type="text" class="form-control" id="min-charge-amount" name="min_charge_amount" placeholder="0.00">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group d-none">
												<label for="max-charge-amount" class="form-label">* Max. Charge Amount</label>
												<input type="text" class="form-control" id="max-charge-amount" name="max_charge_amount" placeholder="0.00">
											</div>
										</div>
									</div>
								</div>
							</div>
							<h5 class="store-locations d-none mt-3">Store Location(s)</h5>
							<hr class="store-locations d-none">
							<div class="row store-locations d-none">
								<div class="col-md-8 offset-md-2">
									<div class="float-right mt-2">
										 <button class="btn btn-outline-primary btn-sm mb-2" type="button" id="add-store-btn">Add Store</button>
									</div>
									<table class="table table-bordered" id="stores-table">
										 <thead>
											  <tr>
													<th style="width: 50%;" scope="col" class="text-center">Store Location</th>
													<th style="width: 35%;" scope="col" class="text-center">Allowance before pickup (in Hours)</th>
													<th style="width: 15%;"></th>
											  </tr>
										 </thead>
										 <tbody></tbody>
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
								@foreach ($categories as $id => $name)
								<option value="{{ $id }}">{{ $name }}</option>
								@endforeach
							</select>

							
							<h5 class="shipping-conditions d-none mt-3">Shipping Condition(s)</h5>
							<hr class="shipping-conditions d-none">
							<div class="row shipping-conditions d-none">
								<div class="col-md-10 offset-md-1">
									<div class="float-right">
										 <button class="btn btn-outline-primary btn-sm mb-2" type="button" id="add-shipping-condition-btn">Add Shipping Condition</button>
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
										 <tbody></tbody>
									</table>
							  </div>
							</div>
							<h5 class="shipping-zone-rates d-none mt-3">Shipping Zone Rate(s)</h5>
							<hr class="shipping-zone-rates d-none">
							<div class="row shipping-zone-rates d-none">
								<div class="col-md-10 offset-md-1">
									<label>Allow shipping rule to these region/city only.</label>
									<div class="float-right">
										 <button class="btn btn-outline-primary btn-sm mb-2" id="add-shipping-zone-btn">Add Shipping Zone</button>
									</div>
									<table class="table table-bordered" id="shipping-zone-table">
										 <thead>
											  <tr>
													<th style="width: 30%;" scope="col" class="text-center">Region / Province</th>
													<th style="width: 23%;" scope="col" class="text-center">Town / City</th>
													<th style="width: 10%;"></th>
											  </tr>
										 </thead>
										 <tbody></tbody>
									</table>
							  </div>
							</div>
							<h5 class="shipping-category d-none mt-3">Product Category</h5>
							<hr class="shipping-category d-none">
							<div class="row shipping-category d-none">
								<div class="col-md-6 offset-md-3">
									<label id="label-cat">Allowed product categories</label>
									<div class="float-right">
										 <button class="btn btn-outline-primary btn-sm mb-2" id="add-product-category-btn">Add Product Category</button>
									</div>
									<table class="table table-bordered" id="product-category-table">
										 <thead>
											<tr>
												<th style="width: 40%;" scope="col" class="text-center">Product Category Name</th>
												<th style="width: 25%;" scope="col" class="text-center">Min. Leadtime</th>
												<th style="width: 25%;" scope="col" class="text-center">Max. Leadtime</th>
												<th style="width: 10%;"></th>
										  </tr>
										 </thead>
										 <tbody></tbody>
									</table>
							  </div>
							</div>
						</div>
						<!-- /.card-body -->
						<div class="card-footer text-center">
							<button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
						</div>
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
			$('.shipping-category').addClass('d-none');

			$('#shipping-method').val("");

			$('#leadtimes').addClass('d-none');
			$('#min-leadtime').removeAttr('required');
			$('#max-leadtime').removeAttr('required');

			$('#shipping-zone-table tbody').empty();
			$('#shipping-condition-table tbody').empty();
			$('#stores-table tbody').empty();
			$('#product-category-table tbody').empty();

			if (shipping_service_type !== 'Store Pickup') {
				$('#leadtimes').removeClass('d-none');
				$('#min-leadtime').attr('required', true);
				$('#max-leadtime').attr('required', true);
			}

			if (shipping_service_type == '') {
				$('#shipping-method').closest('.form-group').addClass('d-none');
			} else  if (shipping_service_type == 'Store Pickup'){
				add_store_row('#stores-table tbody');
				add_shipping_category();
				$('#shipping-method').closest('.form-group').addClass('d-none');
				$('.store-locations').removeClass('d-none');
				$('.shipping-category').removeClass('d-none');
				$('#label-cat').text('Override leadtime for the following product categories');
			} else {
				if (shipping_service_type != 'Express Delivery'){
					$('#label-cat').text('Override leadtime for the following product categories');
				} else {
					$('#label-cat').text('Not applicable for the following product categories');
				}
				add_shipping_zone_rate_row('#shipping-zone-table tbody');
				$('#shipping-method').closest('.form-group').removeClass('d-none');
				$('.store-locations').addClass('d-none');
				$('.shipping-category').removeClass('d-none');
				add_shipping_category();
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
				add_shipping_condition_row('#shipping-condition-table tbody');
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
		
		$('#add-store-btn').click(function(e){
			e.preventDefault();
			
			add_store_row('#stores-table tbody');
		});

		$('#add-shipping-zone-btn').click(function(e){
			e.preventDefault();

			add_shipping_zone_rate_row('#shipping-zone-table tbody');
		});

		$('#add-shipping-condition-btn').click(function(e){
			e.preventDefault();

			add_shipping_condition_row('#shipping-condition-table tbody');
		});

		function add_store_row(table){
			var clone_select = $('#store-location').html();
			var row = '<tr>' +
				'<td class="p-2">' + 
					'<select name="store[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="p-2"">' +
					'<input type="text" name="allowed_hours[]" class="form-control" placeholder="0.00" required>' +
				'</td>' +
				'<td class="text-center">' +
					'<button class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$(table).append(row);
		}
			
		function add_shipping_zone_rate_row(table){
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
					'<button class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$(table).append(row);
			
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
		}

		function add_shipping_condition_row(table){
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
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$(table).append(row);
		}

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

		$('#shipping-carrier-form').submit(function(e){
			e.preventDefault();
			var frm = $('#shipping-carrier-form');
			$.ajax({
				url: frm.attr('action'),
				type:"POST",
				data: frm.serialize(),
				success:function(data){
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
			add_shipping_category();
		});

		function add_shipping_category() {
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
					'<button class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$('#product-category-table tbody').append(row);
		}
	})();

</script>
@endsection
