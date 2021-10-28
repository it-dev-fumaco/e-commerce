@extends('backend.layout', [
	'namePage' => 'Orders',
	'activePage' => 'order_list'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>New Orders</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Orders</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-primary">
							<div class="card-body">
								@if(session()->has('success'))
									<div class="row">
										<div class="col">
											<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
												{!! session()->get('success') !!}
											</div>
										</div>
									</div>
								@endif
								@if(session()->has('error'))
									<div class="row">
										<div class="col">
											<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
												{!! session()->get('error') !!}
											</div>
										</div>
									</div>
								@endif
								<form action="/admin/order/order_lists/" method="get">
									<div class="row">
										<div class="col-md-3">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="search" aria-describedby="button-addon2" placeholder="Order ID" value="{{ (request()->get('search')) ? request()->get('search') : '' }}">
											</div>
										</div>
										<div class="col-md-3">
											<select class="form-control" name="order_status">
												<option {{ (request()->get('order_status') == "" ) ? 'selected' : '' }} disabled value="">Order Status</option>
												<option value="Order Placed" {{ (request()->get('order_status') == "Order Placed" ) ? 'selected' : '' }}>Order Placed</option>
												<option value="Order Confirmed" {{ (request()->get('order_status') == "Order Confirmed" ) ? 'selected' : '' }}>Order Confirmed</option>
												<option value="Ready for Delivery" {{ (request()->get('order_status') == "Ready for Delivery" ) ? 'selected' : '' }}>Ready for Delivery</option>
												<option value="Out for Delivery" {{ (request()->get('order_status') == "Out for Delivery" ) ? 'selected' : '' }}>Out for Delivery</option>
											</select>
										</div>
										<div class="col-md-4">
											<button class="btn btn-success" type="submit">Search</button>
										</div>
									</div>
								</form>
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Order Date</th>
											<th>Order ID</th>
											<th>Customer Name</th>
											<th>Est. Delivery Date</th>
											<th>Shipping Method</th>
											<th>Payment Method</th>
											<th>Grand Total</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($orders_arr as $order)
										<tr>
											<td>{{ $order['date'] }}</td>
											<td>{{ $order['order_no'] }}</td>
											<td>{{ $order['first_name'] .' '. $order['last_name'] }}</td>
											<td>{{ $order['estimated_delivery_date'] }}</td>
											<td>{{ $order['shipping_name'] }}</td>
											<td>{{ $order['payment_method'] }}</td>
											<td>₱ {{ $order['grand_total'] }}</td>
											@php
												if($order['status'] == 'Order Placed'){
													$badge = 'warning';
												}else if($order['status'] == 'Out for Delivery'){
													$badge = 'success';
												}else if($order['status'] == 'Cancelled'){
													$badge = 'secondary';
												}else if($order['status'] == 'Order Confirmed'){
													$badge = 'primary';
												}else{
													$badge = "";
												}
											@endphp
											<td><span class="badge badge-{{ $badge }}" style="font-size: 11pt; {{ ($order['status'] == 'Delivered') ? "background-color: #fd6300 !important; color: #fff;" : '' }}">{{ $order['status'] }}</span></td>
											<td>
												<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#order-{{ $order['order_no']}}">View Orders</button>
												<div class="modal fade" id="order-{{ $order['order_no'] }}" role="dialog">
													<div class="modal-dialog modal-xl" style="min-width: 70%;">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">ORDER NO. {{ $order['order_no'] }}</h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<div class="row {{ ($order['status'] == 'Delivered') ? 'd-none' : '' }}">
																	<div class="col-md-6"></div>
																	<div class="col-md-6">
																		<form class="btn-group" action="/admin/order/status_update" method="POST" style="width: 100%; height: 40px !important;">
																			@csrf
																			<label class="stat-label" for="status">Order Status</label>
																			<select name="status" class="form-control col-md-6" name="order_status" required> 
																				<option value="" {{ ($order['status'] == 'Order Placed') ? 'selected' : '' }} disabled>Order Placed</option>
																				<option value="Order Confirmed" {{ ($order['status'] == 'Order Confirmed') ? 'selected disabled' : '' }}>Order Confirmed</option>
																				<option value="Out for Delivery" {{ ($order['status'] == 'Out for Delivery') ? 'selected disabled' : '' }}>Out for Delivery</option>
																				<option value="Delivered">Delivered Order</option>
																				<option value="Cancelled">Cancel Order</option>
																			</select>
																			<input type="text" value="{{ $order['order_no'] }}" name="order_number" hidden readonly/>
																			<button type="submit" class="form-control col-md-3" style="margin-left: 2%">Update</button>
																		</form>
																	</div>
																</div>
																<br/>
																<div class="row">
																	<div class="col-md-8"> 
																		<p><strong>Customer Name : </strong> {{ $order['first_name'] . " " . $order['last_name'] }}</p>
																	</div>
																	<div class="col-md-4">
																		<p><strong>Est. Delivery Date : </strong> {{ $order['estimated_delivery_date'] }}
																		</p>
																	</div>
																	<div class="col-md-4">
																		<p>
																			<strong>Order ID : </strong> {{ $order['order_no'] }} <br>
																			<strong>Payment ID : </strong> {{ $order['payment_id'] }}<br>
																			<strong>Payment Method : </strong> {{ $order['payment_method'] }}<br>
																			<strong>Order Date : </strong> {{ $order['date'] }} <br>
																			<strong>Status : </strong> <span class="badge badge-{{ $badge }}" style="font-size: 1rem;">{{ $order['status'] }}</span>
																		</p>
																	</div>
																	<div class="col-md-4">
																		<p>
																			<strong>Billing Address : </strong><br>
																			{!! $order['bill_address1'] . " " . $order['bill_address2'] . ", <br>" . $order['bill_brgy'] . ", " . $order['bill_city'] . "<br>" . $order['bill_province'] . ' ' .  $order['bill_country'] . ' ' . $order['bill_postal'] !!}
																		</p>
																	</div>
																	<div class="col-md-4">
																		<p>
																			<strong>Shipping Address : </strong><br>
																			{!! $order['ship_address1'] . " " . $order['ship_address2'] . ", <br>" . $order['ship_brgy'] . ", " . $order['ship_city'] . "<br>" . $order['ship_province'] . ', ' .  $order['ship_country'] . ' ' . $order['ship_postal'] !!}
																		</p>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-12">
																		<table class="table table-bordered">
																			@php
																				$sum_discount = collect($order['ordered_items'])->sum('item_discount');
																				$colspan = ($sum_discount > 0) ? 5 : 4;
																			@endphp
																			<thead>
																				<tr>
																					<th class="text-center" style="width: 10%;">ITEM CODE</th>
																					<th class="text-center" style="width: 50%;">DESCRIPTION</th>
																					<th class="text-center" style="width: 10%;">QTY</th>
																					<th class="text-center" style="width: 10%;">PRICE</th>
																					@if ($sum_discount > 0)
																					<th class="text-center" style="width: 10%;">DISCOUNT(%)</th>
																					@endif
																					<th class="text-center" style="width: 10%;">AMOUNT</th>
																				</tr>
																			</thead>
																			<tbody>
																				@foreach ($order['ordered_items'] as $item)
																				<tr>
																					<td class="text-center">{{ $item['item_code'] }}</td>
																					<td>{{ $item['item_name'] }}</td>
																					<td class="text-center">{{ $item['item_qty'] }}</td>
																					<td class="text-right">₱ {{ number_format(str_replace(",","",$item['item_price']), 2) }}</td>
																					@if ($sum_discount > 0)
																					<td class="text-right">{{ $item['item_discount'] . '%' }}</td>
																					@endif
																					<td class="text-right">₱ {{ number_format(str_replace(",","",$item['item_total']), 2) }}</td>
																				</tr>
																				@endforeach
																			</tbody>
																		</table>
																	</div>
																	<div class="col-md-8 offset-md-4 mb-4">
																		<dl class="row">
																			<dt class="col-sm-10 text-right">Subtotal</dt>
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['subtotal']), 2) }}</dd>
																			<dt class="col-sm-10 text-right">{{ $order['shipping_name'] }}</dt>
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['shipping_amount']), 2) }}</dd>
																			<dt class="col-sm-10 text-right">Grand Total</dt>
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['grand_total']), 2) }}</dd>
																		</dl>
																	</div>


</div>


<div class="modal-footer">

<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tracker-{{ $order['order_no'] }}">
Add Tracker Code
</button>

<div class="modal fade confirm-modal" id="tracker-{{ $order['order_no'] }}" tabindex="-1" role="dialog" aria-labelledby="tracker-{{ $order['order_no'] }}" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Add Tracker Code</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<div class="col">
<form action="" method="post">
<label for="tracker">Tracker Code: </label>
<input type="text" class="form-control" id="tracker" name="tracker" required>
</form>
</div>
</div>
<div class="modal-footer">
<a href="" class="btn btn-primary">YES</a>
<button type="button" class="btn btn-secondary" data-dismiss="cmodal">NO</button>
</div>
</div>
</div>
</div>

</div>
</div>

</div>
</div>
</td>
</tr>



@empty
<tr><td colspan=8 class="text-center"><b>No Orders</b></td></tr>
@endforelse
</tbody>
</table>
</div>
<div class="float-right mt-4">
{{ $orders->withQueryString()->links('pagination::bootstrap-4') }}
</div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>

<style>
.confirm-modal{
background: rgba(0, 0, 0, .7);
}

.stat-label {
	height: 100%;
	padding: 0 10px;
	white-space: normal;
	word-break: break-word;
	display: flex;
	align-items: center;
}
</style>
@endsection