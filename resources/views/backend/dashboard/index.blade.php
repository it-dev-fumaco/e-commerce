@extends('backend.layout', [
	'namePage' => 'Dashboard',
	'activePage' => 'admin_dashboard'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
	  <div class="container-fluid">
		 <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0">Dashboard</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				 <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
				 <li class="breadcrumb-item active">Dashboard</li>
			  </ol>
			</div><!-- /.col -->
		 </div><!-- /.row -->
	  </div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
	  <div class="container-fluid">
		 <!-- Small boxes (Stat box) -->
		 <div class="row">
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-info">
				 <div class="inner">
					<h3>{{ $new_orders }}</h3>

					<p>New Order(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-bag"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-success">
				 <div class="inner">
					<h3>{{ $total_orders }}</h3>

					<p>Total Order(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-stats-bars"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-warning">
				 <div class="inner">
					<h3>{{ $users }}</h3>

					<p>User Registration(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-person-add"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-danger">
				 <div class="inner">
					<h3>₱ {{ number_format($total_sales, 2, '.', ',') }}</h3>

					<p>Total Sales</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-pie-graph"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
		 </div>
		 <!-- /.row -->
		 <!-- Main row -->
		 <div class="row">
			<!-- Left col -->
			<div class="col-8">
			  <!-- Custom tabs (Charts with tabs)-->
			  <div class="card h-100">
				 <div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-chart-pie mr-1"></i> Monthly Sales Report
					</h3>
					<div class="card-tools">
						<ul class="nav nav-pills ml-auto">
							<li class="nav-item">
								<select name="year" class="form-control" id="year-filter" required> 
									<option value="" disabled>Year</option>
									<option value="2020">2020</option>
									@foreach ($sales_year as $year)
										@php
											$selected = null;
											if(!request()->get('year')){
												if(\Carbon\Carbon::now()->format('Y') == $year->{'YEAR(order_date)'}){
													$selected = 'selected';
												}
											}else{
												if($year->{'YEAR(order_date)'} == request()->get('year')){
													$selected = 'selected';
												}
											}
										@endphp
										<option {{ $selected }} value="{{ $year->{'YEAR(order_date)'} }}">{{ $year->{'YEAR(order_date)'} }}</option>
									@endforeach
								</select>
							</li>
						</ul>
					</div>
				 </div><!-- /.card-header -->
				 <div class="card-body">
					<div class="tab-content p-0">
						<div class="container mt-4">
							<div class="col-10 mx-auto" id="chart-container">
					  			<canvas id="myChart"></canvas>
							</div>
						</div>
					</div>
				 </div><!-- /.card-body -->
			  </div>
			  <!-- /.card -->
			</div>
			<!-- /.Left col -->
			<div class="col-4">
				<div class="card h-100">
					<div class="card-header">
						<h3 class="card-title container-fluid">
							<div class="row">
								<div class="col-9 text-bold"><i class="fas fa-search mr-1"></i> Most Searched Terms </div>
								<div class="col-3 text-bold">Frequency</div>
							</div>
						</h3>
					</div>
					<div class="card-body">
						<table class="table table-hover table-bordered">
							@foreach ($search_terms as $search)
								<tr>
									<td>
										<a href="#" data-toggle="modal" data-target="#{{ Str::slug($search['search_term']) }}Modal" style="font-size: 11pt">
											{{ $search['search_term'] }}
										</a>
										<div class="modal fade" id="{{ Str::slug($search['search_term']) }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">{{ $search['search_term'] }}</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<table class="table table-hover table-bordered">
															<tr>
																<th class="text-center">Location</th>
																<th class="text-center">Frequency</th>
															</tr>
															@foreach ($search['location'] as $location)
																<tr>
																	<td class="text-center">
																		{{ $location->city ? $location->city : '-' }}
																	</td>
																	<td class="text-center">{{ $location->count }}</td>
																</tr>
															@endforeach
														</table>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
									</td>
									<td class="text-center" style="font-size: 11pt">{{ $search['search_term_count'] }}</td>
								</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="fas fa-shopping-cart"></i> Items on Cart
						</h3>
					</div>
					<div class="card-body">
						<table class="table table-hover table-bordered table-striped">
							<tr>
								<th>Cart Owner</th>
								<th>Cart Status</th>
								<th>Last Online</th>
								<th>Products</th>
								<th>Action</th>
							</tr>
							@foreach ($cart_collection as $cart)
								@php
									$status_color = '#DC3545';
									if($cart['status'] == 'Active'){
										$status_color = '#007BFF';
									}else if($cart['status'] == 'Converted'){
										$status_color = '#28A745';
									}
								@endphp
								<tr>
									<td>{{ $cart['user_type'] == 'member' ? $cart['owner'] : 'Guest' }}</td>
									<td class="text-center">
										<span class="badge w-100" style="font-size: 11pt; background-color: {{ $status_color }}; color: #fff">{{ $cart['status'] }}</span>
									</td>
									<td style="font-size: 11pt">{{ $cart['last_online'] ? \Carbon\Carbon::parse($cart['last_online'])->format('M d, Y - h:i a') : null }}</td>
									<td>
										@foreach ($cart['items'] as $item)
											<a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="badge badge-primary" target="_blank">{{ $item['item_code'] }}</a>
										@endforeach
									<td class="text-center">
										@if($cart['status'] == 'Converted')
											<a href="#" data-toggle="modal" data-target="#view{{ $cart['transaction_id'] }}Modal">
												View Order
											</a>
											
											<div class="modal fade" id="view{{ $cart['transaction_id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<div class="row container-fluid">
																<div class="col-md-6 text-left">
																	<h4 class="modal-title">ORDER NO. {{ $cart['transaction_id'] }}</h4>
																</div>
																<div class="col-md-6">
																	<div class="float-right font-italic m-1" style="font-size: 1.2rem;">
																		<span class="badge badge-info d-inline-block mr-3" style="font-size: 1rem;">{{ $cart['shipping_name'] }}</span>
																		{!! ($cart['shipping_name'] != 'Store Pickup') ? '<strong>Est. Delivery Date : </strong> ' . $cart['estimated_delivery_date'] : '<strong>Pickup by : </strong> ' . \Carbon\Carbon::parse($cart['pickup_date'])->format('D, F d, Y') !!}
																	</div>
																</div>
															</div>
														</div>
														<div class="modal-body text-left">
															@php
															if($cart['order_status'] == 'Order Placed'){
																$badge = 'warning';
															}else if($cart['order_status'] == 'Out for Delivery' or $cart['order_status'] == 'Ready for Pickup'){
																$badge = 'success';
															}else if($cart['order_status'] == 'Cancelled'){
																$badge = 'secondary';
															}else if($cart['order_status'] == 'Order Confirmed'){
																$badge = 'primary';
															}else{
																$badge = "";
															}
														@endphp
														<div class="row {{ ($cart['order_status'] == 'Delivered') ? 'd-none' : '' }}">
															<div class="col-6">
																<p class="mt-3 mb-0"><strong>Customer Name : </strong> {{ $cart['first_name'] . " " . $cart['last_name'] }}</p>
																@if($cart['user_email'])
																<p class="mb-0"><strong>Email Address : </strong> {{ $cart['user_email'] }}</p>
																@endif
																<p class="text-muted mb-0"><strong>{{ $cart['order_type'] }} Checkout</strong></p>
															</div>
														</div>
														<br/>
														<div class="row">
															<div class="col-4">
																<p>
																	<strong>Order ID : </strong> {{ $cart['transaction_id'] }} <br>
																	<strong>Payment ID : </strong> {{ $cart['payment_id'] }}<br>
																	<strong>Payment Method : </strong> {{ $cart['payment_method'] }}<br>
																	<strong>Order Date : </strong> {{ $cart['date'] }} <br>
																	<strong>Status : </strong> <span class="badge badge-{{ $badge }}" style="font-size: 1rem;">{{ $cart['order_status'] }}</span>
																</p>
															</div>
															<div class="col-4">
																<p>
																	<strong>Billing Address : </strong><br>
																	<strong>Bill to :</strong> {{ ($cart['billing_business_name']) ? $cart['billing_business_name'] : $cart['bill_contact_person'] }}<br>
																	{!! $cart['bill_address1'] . " " . $cart['bill_address2'] . ", <br>" . $cart['bill_brgy'] . ", " . $cart['bill_city'] . "<br>" . $cart['bill_province'] . ', ' .  $cart['bill_country'] . ' ' . $cart['bill_postal'] !!}<br/>
																	{{ $cart['bill_email'] }}<br/>
																	{{ $cart['bill_contact'] }}
																</p>
															</div>
															<div class="col-4">
																@if ($cart['shipping_name'] == 'Store Pickup')
																<p>
																	<strong>Pickup At : </strong><br>
																	{{ ($cart['store']) }}<br>
																	{!! $cart['store_address'] !!}<br/>
																	<strong>Pickup Date : </strong>
																	{{ \Carbon\Carbon::parse($cart['pickup_date'])->format('D, F d, Y') }}
																</p>
																@else
																<p>
																	<strong>Shipping Address : </strong><br>
																	<strong>Ship to :</strong> {{ ($cart['shipping_business_name']) ? $cart['shipping_business_name'] : $cart['ship_contact_person'] }}<br>
																	{!! $cart['ship_address1'] . " " . $cart['ship_address2'] . ", <br>" . $cart['ship_brgy'] . ", " . $cart['ship_city'] . "<br>" . $cart['ship_province'] . ', ' .  $cart['ship_country'] . ' ' . $cart['ship_postal'] !!}<br/>
																	{{ $cart['email'] }}<br/>
																	{{ $cart['contact'] }}
																</p>
																@endif
															</div>
														</div>
														<div class="row">
															<div class="col-12 table-responsive">
																<table class="table table-bordered table-striped">
																	@php
																		$sum_discount = collect($cart['ordered_items'])->sum('item_discount');
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
																		@foreach ($cart['items'] as $item)
																			<tr>
																				<td class="text-center">{{ $item['item_code'] }}</td>
																				<td>{{ $item['item_name'] }}</td>
																				<td class="text-center">{{ $item['qty'] }}</td>
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
															<div class="col-4"><br/></div>
															<div class="col-8">
																<dl class="row">
																	<dt class="col-10 text-right">Subtotal</dt>
																	<dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$cart['subtotal']), 2) }}</dd>
																	@if ($cart['voucher_code'])
																	<dt class="col-10 text-right">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $cart['voucher_code'] }}</span></dt>
																	<dd class="col-2 text-right">- ₱ {{ number_format(str_replace(",","",$cart['discount_amount']), 2) }}</dd>
																	@endif
																	<dt class="col-10 text-right">
																		@if ($cart['shipping_name'])
																		<span class="badge badge-info" style="font-size: 11pt;">{{ $cart['shipping_name'] }}</span>
																		@else
																		{{ $cart['shipping_name'] }}
																		@endif
																	</dt>
																	<dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$cart['shipping_amount']), 2) }}</dd>
																	<dt class="col-10 text-right">Grand Total</dt>
																	<dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$cart['grand_total']), 2) }}</dd>
																</dl>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
										@elseif($cart['user_type'] == 'member' and $cart['status'] == 'Abandoned')
											<a href="#" data-toggle="modal" data-target="#view{{ $cart['transaction_id'] }}Modal">
												Email
											</a>
											
											<div class="modal fade" id="view{{ $cart['transaction_id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel">{{ $cart['transaction_id'] }}</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															Send Abandoned Cart Email?
														</div>
														<div class="modal-footer">
															<a href="/admin/send_abandoned_cart_email/{{ $cart['transaction_id'] }}" class="btn btn-primary">Send Email</a>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
										@else
											<span class="text-muted">No Actions Available</span>
										@endif
									</td>
								</tr>
							@endforeach
						</table>
						<div class="float-right mt-4">
							{{ $cart_collection->withQueryString()->links('pagination::bootstrap-4') }}
						</div>
					</div>
				</div>
			</div>
		</div>
		 <!-- /.row (main row) -->
	  </div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>

@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
	var xValues = {!! '['.collect($sales_arr)->pluck('month_name')->implode(',').']' !!};
	var yValues = [{{ collect($sales_arr)->pluck('sales')->implode(',') }}];
	getChartData(xValues, yValues);

	function getChartData(xValues, yValues){
		new Chart("myChart", {
			type: "bar",
			data: {
				labels: xValues,
				datasets: [{
					backgroundColor: "rgba(0,123,255,1)",
					data: yValues
				}]
			},
			options: {
				legend: {display: false},
				scales: {
					yAxes: [
						{
							ticks: {
								beginAtZero: true,
								callback: function(label, index, labels) {
									if(parseInt(label) >= 1000){
										return '₱ '+label/1000+'k';
									}else{
										return '₱ '+label;
									}
								}
							},
							scaleLabel: {
								display: true,
								labelString: '1k = ₱ 1,000'
							}
						}
					]
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem) {
							return "₱ " + Number(tooltipItem.yLabel).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
						}
					}
				}
			}
		});
	}

	$('#year-filter').change(function(){
		var data = {
			'year': $(this).val()
		}
		$.ajax({
			type: 'GET',
			url: '/admin/dashboard',
			dataType: "json",
			data: data,
			success: function (result)
			{
				document.getElementById("chart-container").innerHTML = '&nbsp;';
				document.getElementById("chart-container").innerHTML = '<canvas id="myChart"></canvas>';
				var months = [result[1]].join(',');
				var month_names = months.replace(new RegExp('"', 'g'), "");
				const months_arr = [];
				$.each( month_names.split(','), function( i,month ) {
					months_arr.push(month);
				});
				
				var xValues = months_arr;
				var yValues = JSON.parse('['+[result[0]].join(',')+']');

				getChartData(xValues, yValues);
			}
		});
	});
</script>
@endsection