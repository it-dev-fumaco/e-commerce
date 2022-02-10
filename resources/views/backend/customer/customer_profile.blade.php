@extends('backend.layout', [
'namePage' => 'Customer Profile',
'activePage' => 'customers_list'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Customer Profile</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Customer Profile</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="card card-primary card-outline">
							<div class="card-body box-profile">
								<div class="text-center pb-2">
									<div class="avatar">
										<div class="avatar__letters">
											{{ substr($customer->f_name, 0, 1) . substr($customer->f_lname, 0, 1) }}
										</div>
									</div>
								</div>
								<h3 class="profile-username text-center">{{ $customer->f_name.' '.$customer->f_lname }}</h3>
								<p class="text-muted text-center">{{ $customer->username }}</p>
								<p class="text-center font-weight-bold" style="font-size: 14pt;">₱ {{ number_format(str_replace(",","",$total_sales), 2) }}</p>
								<ul class="list-group list-group-unbordered mb-3">
									<li class="list-group-item">
										<b>Date Registered:</b> <a class="float-right">{{ date('M d, Y', strtotime($customer->created_at)) }}</a>
									</li>
									<li class="list-group-item">
										<b>Last Login Date:</b> <a class="float-right">{{ $customer->last_login ? date('M d, Y h:i A', strtotime($customer->last_login)) : '' }}</a>
									</li>
									<li class="list-group-item">
										<b>Total no. of visits:</b> <span class="badge badge-primary float-right">{{ $customer->no_of_visits }}</span>
									</li>
									<li class="list-group-item">
										<b>Customer Group:</b> <a class="float-right">
											{{ (array_key_exists($customer->customer_group, $customer_groups->toArray())) ? $customer_groups[$customer->customer_group] : null }}</a>
									</li>
									<li class="list-group-item">
										<b>Business Name:</b> <a class="float-right">{{ $customer->business_name }}</a>
									</li>
									<li class="list-group-item">
										<b>Price List:</b> <a class="float-right">{{ $customer->pricelist }}</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="card card-info">
							<div class="card-header">
								<h3 class="card-title">Item(s) on Cart</h3>
							</div>
							<div class="card-body p-2">
								<table class="table table-hover table-bordered" style="font-size: 10pt;">
									<thead>
										<tr>
											<th>Item Description</th>
											<th>Quantity</th>
										</tr>
									</thead>
									@forelse ($cart_items as $cart)
									<tr>
										<td>
											<span class="font-weight-bold">{{ $cart->item_code }}</span> - {{ $cart->item_description }}</td>
										<td class="text-center">{{ $cart->qty }}</td>
									</tr>
									@empty
									<tr>
										<td class="text-center" colspan="2">No Item(s) on Cart</td>
									</tr>
									@endforelse
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						@if(session()->has('success'))
						<div class="alert alert-success fade show" role="alert">
							{{ session()->get('success') }}
						</div>
						@endif
						@if(session()->has('error'))
						<div class="alert alert-warning fade show" role="alert">
							{{ session()->get('error') }}
						</div>
						@endif
						<div class="card">
							<div class="card-header p-2">
								<h5 class="mt-2 mb-3 ml-1">Customer Address</h5>
								<div class="float-right">
									<button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#edit-details">Update Customer Group</button>
								</div>
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Shipping Address</a></li>
									<li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Billing Address</a></li>
								</ul>
							</div>
							<div class="card-body p-1">
								<div class="tab-content p-1">
									<div class="active tab-pane m-0" id="activity" style="min-height: 200px;">
										<div id="shipping-address-div"></div>
									</div>
									<div class="tab-pane" id="timeline" style="min-height: 200px;">
										<div id="billing-address-div"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header p-2">
								<h5 class="mt-2 mb-3 ml-1">Customer Order(s)</h5>
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#current-order" data-toggle="tab">Current Order</a></li>
									<li class="nav-item"><a class="nav-link" href="#order-history" data-toggle="tab">Order History</a></li>
								</ul>
							</div>
							<div class="card-body p-1">
								<div class="tab-content p-1">
									<div class="active tab-pane m-0" id="current-order" style="min-height: 400px;">
										<div id="current-orders-div"></div>
									</div>
									<div class="tab-pane" id="order-history" style="min-height: 400px;">
										<div id="orders-div"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>


<div class="modal fade" id="order-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div id="view-order-div" class="modal-content"></div>
	</div>
</div>

<div class="modal fade" id="edit-details" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
	<form action="/admin/customer/profile/{{ $customer->id }}/change_customer_group" method="post">
		 @csrf
		 <div class="modal-dialog" role="document">
			  <div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title">Edit Details</h5>
						 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
						 </button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="">Customer Group:</label>
							<select class="form-control" name="customer_group" required id="customer-group">
								<option disabled value="">Select Customer Group</option>
								@foreach ($customer_groups as $id => $group)
								<option value="{{ $id }}" {{ $id == $customer->customer_group ? 'selected' : '' }} data-val="{{ $group }}">{{ $group }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="">Business Name:</label>
							<input type="text" name="business_name" class="form-control" value="{{ $customer->business_name }}">
						</div>
						<div class="form-group">
							<label for="">Price List:</label>
							<select class="form-control" name="pricelist" required id="pricelist">
								<option disabled value="">Select Price List</option>
								@foreach ($pricelist as $row)
								<option value="{{ $row->id }}" {{ $row->id == $customer->pricelist_id ? 'selected' : '' }}>{{ $row->price_list_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="modal-footer">
						 <button type="submit" class="btn btn-primary">Submit</button>
						 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
			  </div>
		 </div>
	</form>
</div>

<style>
	.avatar {
		/* Center the content */
		display: inline-block;
		vertical-align: middle;

		/* Used to position the content */
		position: relative;

		/* Colors */
		background-color: rgba(0, 0, 0, 0.3);
		color: #fff;

		/* Rounded border */
		border-radius: 50%;
		height: 120px;
		width: 120px;
	}

	.avatar__letters {
		/* Center the content */
		font-size: 50px;
		left: 50%;
		position: absolute;
		top: 50%;
		transform: translate(-50%, -50%);
	}
</style>
@endsection
@section('script')
	<script>
		$(document).ready(function(){
			getAddress('Delivery');
			getAddress('Billing');
			getOrders();
			getCurrentOrders();

			$(document).on('click', '.view-order', function(e){
				e.preventDefault();
				viewOrder($(this).data('id'));
			});

			function viewOrder(id) {
				$.ajax({
					type:'GET',
					url:'/admin/customer/order/' + id,
					success: function (response) {
						$('#view-order-div').html(response);
						$('#order-details-modal').modal('show');
					}
				});
			}

			function getOrders(page) {
				$.ajax({
					type:'GET',
					url:'/admin/customer/orders/{{ $customer->id }}?page=' + page,
					success: function (response) {
						$('#orders-div').html(response);
					}
				});
			}

			function getCurrentOrders(page) {
				$.ajax({
					type:'GET',
					url:'/admin/customer/orders/{{ $customer->id }}?page=' + page + '&current=1',
					success: function (response) {
						$('#current-orders-div').html(response);
					}
				});
			}

			function getAddress(address_type, page) {
				if (address_type == 'Delivery') {
					var el = $('#shipping-address-div');
				} else {
					var el = $('#billing-address-div');
				}
				$.ajax({
					type:'GET',
					url:'/admin/customer/address/' + address_type + '/{{ $customer->id }}?page=' + page,
					success: function (response) {
						el.html(response);
					}
				});
			}

			$(document).on('click', '#shipping-address-paginate a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
				getAddress('Delivery', page);
			});

			$(document).on('click', '#billing-address-paginate a', function(event){
				event.preventDefault(); 
				var page = $(this).attr('href').split('page=')[1];
				getAddress('Billing', page);
			});

			$(document).on('click', '#orders-paginate a', function(event){
				event.preventDefault(); 
				var page = $(this).attr('href').split('page=')[1];
				getOrders(page);
			});

			$(document).on('click', '#current-orders-paginate a', function(event){
				event.preventDefault(); 
				var page = $(this).attr('href').split('page=')[1];
				getCurrentOrders(page);
			});

			customerGroup();

			$('#customer-group').change(function(){
					customerGroup();
			});

			function customerGroup(){
				if($('#customer-group option:selected').data('val') == 'Business'){
					$('#pricelist').parent().slideDown();
					$('input[name="business_name"]').parent().slideDown();
					$('#pricelist').prop('required', true);
					$('input[name="business_name"]').prop('required', true);
				}else{
					$('#pricelist').parent().slideUp();
					$('input[name="business_name"]').parent().slideUp();
					$('#pricelist').prop('required', false);
					$('input[name="business_name"]').prop('required', false);
				}
			}
		});
	</script>
@endsection