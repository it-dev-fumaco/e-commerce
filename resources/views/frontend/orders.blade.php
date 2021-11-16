@extends('frontend.layout', [
    'namePage' => 'My Orders',
    'activePage' => 'myorders'
])

@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">MY ORDERS</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
		<div class="container">
			<div class="row">
				<div class="container">
					<!-- Nav tabs -->
					{{-- <ul class="nav nav-tabs" role="tablist" style="border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa;">
						<li class="nav-item">
							<a class="nav-link login_2 active" data-toggle="tab" href="#current">Current Order</a>
						</li>
						<li class="nav-item">
							<a class="nav-link login_2" data-toggle="tab" href="#history">Order History</a>
						</li>
					</ul> --}}
					<ul class="nav nav-tabs" role="tablist" style="border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa;">
						<li class="nav-item active active-btn" id="current_tab" style="width: 50%;">
						  <a class="nav-link" data-toggle="tab" href="#current"><center><span class="login_2">Current Order(s)</span></center></a>
						</li>

						<li class="nav-item" id="history_tab" style="width: 50%;">
						  <a class="nav-link" data-toggle="tab" href="#history"><center><span class="login_2">Order History</span></center></a>
						</li>
					  </ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div id="current" class="container tab-pane active pb-4" style="border-color: #efefef; border-style: solid; border-width: 1px; ">
							<br>
							{{-- <center><h3>Current Order(s)</h3></center>
							<br> --}}
							<div id="accordion">
								@forelse ($new_orders_arr as $order)
									@php
										if($order['status'] == "Order Placed"){
											$badge = '#ffc107';
										}else if($order['status'] == "Delivered"){
											$badge = '#fd6300';
										}else if($order['status'] == "Out for Delivery"){
											$badge = '#28a745';
										}else{
											$badge = '#007bff';
										}
									@endphp
									<div class="card">
										<div class="card-header" id="headingOne">
											<h5 class="mb-0">
												<a class="btn" data-toggle="collapse" data-target="#{{ $order['order_number'] }}-details" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;">
													<div class="row">
														<div class="col-md-8" style="text-align: left !important">
															<span class="table-text"><b>{{ $order['order_number'] }}</b></span>&nbsp;<span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span>
														</div>
														<div class="col-md-4" style="text-align: left !important">
															<span class="table-text"><b>Estimated Delivery Date:</b> <br class="d-md-none"/>{{ $order['edd'] ? $order['edd'] : '-' }}</span>
														</div>
													</div>
												</a>
											</h5>
										</div>
									
										<div id="{{ $order['order_number'] }}-details" class="collapse {{ $loop->first ? 'show' : 'hide' }}" aria-labelledby="headingOne" data-parent="#accordion">
											<div class="card-body">
												@php
													if($order['status'] == "Order Placed"){
														$status = 1;
													}else if($order['status'] == "Order Confirmed"){
														$status = 2;
													}else if($order['status'] == "Out for Delivery" or $order['status'] == "Ready for Pickup" ){
														$status = 3;
													}else if($order['status'] == "Delivered"){
														$status = 4;
													}else{
														$status = 0;
													}
													$status_name = array('Order Placed', "Order Confirmed", ($order['status'] == "Out for Delivery") ? "Out for Delivery" : "Ready for Pickup", "Delivered");

												@endphp
												<div style="min-height: 120px !important">
													<div class="track">
														@foreach ($status_name as $key => $name)
															@php
																$key = $key + 1;
																if($name == "Order Placed"){
																	$date = $order['date'] ? $order['date'] : '';
																	$icon = 'check';
																}else if($name == "Order Confirmed"){
																	$date = $order['order_date_confirmed'] ? date('M d, Y H:i A', strtotime($order['order_date_confirmed'])) : '';
																	$icon = 'user';
																}else if($name == "Out for Delivery" or $name == "Ready for Pickup" ){
																	$date = $order['order_date_ready'] ? date('M d, Y H:i A', strtotime($order['order_date_ready'])) : ''; 
																	$icon = 'truck';
																}else if($name == "Delivered"){
																	$date = '';
																	$icon = 'shopping-bag';
																}
															@endphp
															<div class="step {{ $status >= $key ? 'active' : '' }}">
																<span class="icon {{ $status > $key ? 'inactive' : '' }}"><i class="fa fa-{{ $icon }} {{ $status > $key ? 'd-none' : '' }}"></i></span>
																<span class="text status-text">{{ $name }}</span>
																<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
															</div>
														@endforeach
													</div>
												</div>
												<button class="btn btn-sm btn-primary" style="background-color: #008CFF;" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">View Items</button>

												<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $order['order_number'] }}-ModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-xl" style="min-width: 70%;">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">{{ $order['order_number'] }}</h5>
																<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<table class="table" style="width: 100% !important;">
																	<tr>
																		<th class="col-sm-1"></th>
																		<th class="col-sm-3 table-text">Item Name</th>
																		<th class="col-sm-2 table-text">Quantity</th>
																		<th class="col-sm-2 table-text">Price</th>
																	</tr>
																	@foreach ($order['items'] as $item)
																		<tr>
																			<td>
																				<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
																			</td>
																			<td class="table-text">{{ $item['item_name'] }}</td>
																			<td class="table-text">{{ $item['qty'] }}</td>
																			@php
																				$orig_price = number_format($item['orig_price'], 2);
																				$price = number_format($item['price'], 2);
																			@endphp
																			<td class="table-text">
																				@if($item['discount'] > 0)
																					<p><span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b></p>
																				@else
																					<p>₱ {{ $price }}</p>
																				@endif
																			</td>
																		</tr>
																	@endforeach
																	<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																		<td></td>
																		<td class="table-text" colspan=2 style="text-align: right;">Subtotal: </td>
																		<td class="table-text" style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
																	</tr>
																	<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																		<td></td>
																		<td class="table-text" colspan=2 style="text-align: right;">Shipping Fee: </td>
																		<td class="table-text" style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
																	</tr>
																	<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
																		<td></td>
																		<td class="table-text" colspan=2 style="text-align: right;">Grand Total: </td>
																		<td class="table-text" style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
																	</tr>
																</table>
																<div class="d-none d-xl-block">
																	<div class="row">
																		<div class="col-md-10" style="text-align: right;">
																			<span class="table-text">Subtotal: </span>
																		</div>
																		<div class="col-md-2" style="text-align: left;">
																			<span class="table-text">₱ {{ number_format($order['subtotal'], 2) }}</span>
																		</div><br/>&nbsp;
																	</div>
																	<div class="row">
																		<div class="col-md-10" style="text-align: right;">
																			<span class="table-text">Shipping Fee: </span>
																		</div>
																		<div class="col-md-2" style="text-align: left;">
																			<span class="table-text">₱ {{ number_format($order['shipping_fee'], 2) }}</span>
																		</div><br/>&nbsp;
																	</div>
																	<div class="row">
																		<div class="col-md-10" style="text-align: right;">
																			<span class="table-text" style="font-weight: 700">Grand Total: </span>
																		</div>
																		<div class="col-md-2" style="text-align: left;">
																			<span class="table-text" style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
																		</div><br/>&nbsp;
																	</div>
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary table-text" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@empty
								<div class="card">
									<div class="card-header" id="headingOne">
										<h5 class="mb-0">
										No Current Order(s)
										</h5>
									</div>
								</div>
								@endforelse
							</div>
							{{-- <table class="table">
								<thead>
									<tr>
										<th class="text-center d-none d-sm-table-cell">Order No.</th>
										<th class="text-center table-text">Date</th>
										<th class="text-center table-text">Details</th>
										<th class="text-center d-none d-sm-table-cell">Shipping</th>
										<th class="text-center d-none d-sm-table-cell">Estimated Delivery Date</th>
										<th class="text-center d-none d-sm-table-cell">Status</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($new_orders_arr as $order)
									@php
										if($order['status'] == "Order Placed"){
											$badge = '#ffc107';
										}else if($order['status'] == "Delivered"){
											$badge = '#fd6300';
										}else if($order['status'] == "Out for Delivery"){
											$badge = '#28a745';
										}else{
											$badge = '#007bff';
										}
									@endphp
									<tr>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['order_number'] }}</td>
										<td class="text-center align-middle"><span class="table-text">{{ $order['date'] }}</span></td>
										<td class="text-center align-middle">
											<a href="#" class="table-text" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">Item Purchase</a>

											<!-- Modal -->
											<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $order['order_number'] }}-ModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl" style="min-width: 70%;">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">{{ $order['order_number'] }}</h5>
															<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<table class="table" style="width: 100% !important;">
																<tr>
																	<th class="col-sm-1"></th>
																	<th class="col-sm-3">Item Name</th>
																	<th class="col-sm-2">Quantity</th>
																	<th class="col-sm-2">Price</th>
																</tr>
																@foreach ($order['items'] as $item)
																	<tr>
																		<td>
																			<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
																		</td>
																		<td>{{ $item['item_name'] }}</td>
																		<td>{{ $item['qty'] }}</td>
																		@php
																			$orig_price = number_format($item['orig_price'], 2);
																			$price = number_format($item['price'], 2);
																		@endphp
																		<td>
																			@if($item['discount'] > 0)
																				<p><span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b></p>
																			@else
																				<p>₱ {{ $price }}</p>
																			@endif
																		</td>
																	</tr>
																@endforeach
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Subtotal: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
																</tr>
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Shipping Fee: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
																</tr>
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Grand Total: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
																</tr>
															</table>
															<div class="d-none d-xl-block">
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Subtotal: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Shipping Fee: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span style="font-weight: 700">Grand Total: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
											<div class="d-md-none table-text" style="text-align: left;">
												<br/>
												<b>Order Number:</b><br/>{{ $order['order_number'] }}<br/><br/>
												<b>Shipping Name:</b> {{ $order['shipping_name'] }}<br/><br/>
												<b>Est. Delivery Date:</b><br class="d-sm-block d-md-none"/> {{ $order['edd'] }}<br/>
												<span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span>
											</div>
										</td>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['shipping_name'] }}</td>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['edd'] }}</td>
										<td class="text-center align-middle d-none d-sm-table-cell"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></td>
									</tr>
									@empty
										<tr>
											<td class="text-center p-3" colspan="6">No transactions found.</td>
										</tr>
									@endforelse
								</tbody>
							</table> --}}
						</div>
						<div id="history" class="container tab-pane fade">
							<br>
							{{-- <center><h3 class="h3">Order History</h3></center>
							<br><br/> --}}
							<table class="table">
								<thead>
									<tr>
										<th class="text-center d-none d-sm-table-cell">Order No.</th>
										<th class="text-center table-text">Date</th>
										<th class="text-center table-text">Details</th>
										<th class="text-center d-none d-sm-table-cell">Shipping</th>
										<th class="text-center d-none d-sm-table-cell">Estimated Delivery Date</th>
										<th class="text-center d-none d-sm-table-cell">Status</th>
										<th class="text-center d-none d-sm-table-cell">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($orders_arr as $order)
									@php
										if($order['status'] == "Order Placed"){
											$badge = '#ffc107';
										}else if($order['status'] == "Cancelled"){
											$badge = '#6c757d';
										}else if($order['status'] == "Delivered"){
											$badge = '#fd6300';
										}else if($order['status'] == "Out for Delivery"){
											$badge = '#28a745';
										}else{
											$badge = '#007bff';
										}
									@endphp
									<tr>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['order_number'] }}</td>
										<td class="text-center align-middle table-text">{{ $order['date'] }}</td>
										<td class="text-center align-middle">
											<a href="#" class="table-text" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">Item Purchase</a>

											<!-- Modal -->
											<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $order['order_number'] }}-ModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl" style="min-width: 70%;">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">{{ $order['order_number'] }}</h5>
															<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<table class="table" style="width: 100% !important;">
																<tr>
																	<th class="col-sm-1"></th>
																	<th class="col-sm-3">Item Name</th>
																	<th class="col-sm-2">Quantity</th>
																	<th class="col-sm-2">Price</th>
																</tr>
																@foreach ($order['items'] as $item)
																	<tr>
																		<td>
																			<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
																		</td>
																		<td>{{ $item['item_name'] }}</td>
																		<td>{{ $item['qty'] }}</td>
																		@php
																			$orig_price = number_format($item['orig_price'], 2);
																			$price = number_format($item['price'], 2);
																		@endphp
																		<td>
																			@if($item['discount'] > 0)
																				<p><span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b></p>
																			@else
																				<p>₱ {{ $price }}</p>
																			@endif
																		</td>
																	</tr>
																@endforeach
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Subtotal: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
																</tr>
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Shipping Fee: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
																</tr>
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Grand Total: </td>
																	<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
																</tr>
															</table>
															<div class="d-none d-xl-block">
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Subtotal: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Shipping Fee: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
																<div class="row">
																	<div class="col-md-10" style="text-align: right;">
																		<span style="font-weight: 700">Grand Total: </span>
																	</div>
																	<div class="col-md-2" style="text-align: left;">
																		<span style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
																	</div><br/>&nbsp;
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
											<div class="d-md-none table-text" style="text-align: left;">
												<br/>
												<b>Order Number:</b><br/>{{ $order['order_number'] }}<br/><br/>
												<b>Shipping Name:</b> {{ $order['shipping_name'] }}<br/><br/>
												<b>Est. Delivery Date:</b> {{ $order['edd'] }}<br/>
												<p><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></p>
												<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#reorder{{ $order['order_number'] }}Modal"  {{ $order['status'] == 'Cancelled' ? 'disabled' : '' }}>Re-Order</button>
											</div>
										</td>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['shipping_name'] }}</td>
										<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['edd'] }}</td>
										<td class="text-center align-middle d-none d-sm-table-cell"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></td>
										<td class="text-center align-middle d-none d-sm-table-cell"><button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#reorder{{ $order['order_number'] }}Modal" {{ $order['status'] == 'Cancelled' ? 'disabled' : '' }}>Re-Order</button></td>

										<!-- Re-Order Modal -->
										<div class="modal fade" id="reorder{{ $order['order_number'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Re-Order Item</h5>
														<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<form action="/product_actions" method="POST" autocomplete="off">
														@csrf
														<div class="modal-body">
															<span class="table-text">Re-Order {{ $order['order_number'] }}?</span>
															<div class="d-none">
																<input type="text" name="order_number" value="{{ $order['order_number'] }}" />
																<input type="checkbox" name="reorder" checked/>
															</div>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-sm btn-primary">Re-Order</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</tr>
									@empty
										<tr>
											<td class="text-center p-3" colspan="6">No transactions found.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
							<div style="float: right;">
								{{ $orders->links('pagination::bootstrap-4') }}
							</div>
						</div>
					</div>
				  </div>
			</div>
			{{-- <div class="row d-none">
				<div class="col-md-6">
					<br>
					<center><h3 class="h3">Current Orders</h3></center>
					<br><br/>
					<table class="table">
						<thead>
							<tr>
								<th class="text-center d-none d-sm-table-cell">Order No.</th>
								<th class="text-center table-text">Date</th>
								<th class="text-center table-text">Details</th>
								<th class="text-center d-none d-sm-table-cell">Shipping</th>
								<th class="d-none d-sm-table-cell">Estimated Delivery Date</th>
								<th class="text-center d-none d-sm-table-cell">Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($new_orders_arr as $order)
							@php
								if($order['status'] == "Order Placed"){
									$badge = '#ffc107';
								}else if($order['status'] == "Delivered"){
									$badge = '#fd6300';
								}else if($order['status'] == "Out for Delivery"){
									$badge = '#28a745';
								}else{
									$badge = '#007bff';
								}
							@endphp
							<tr>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['order_number'] }}</td>
								<td class="text-center align-middle">{{ $order['date'] }}</td>
								<td class="text-center align-middle">
									<a href="#" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">Item Purchase</a>

									<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $order['order_number'] }}-ModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" style="min-width: 70%;">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">{{ $order['order_number'] }}</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<table class="table" style="width: 100% !important;">
														<tr>
															<th class="col-sm-1"></th>
															<th class="col-sm-3">Item Name</th>
															<th class="col-sm-2">Quantity</th>
															<th class="col-sm-2">Price</th>
														</tr>
														@foreach ($order['items'] as $item)
															<tr>
																<td>
																	<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
																</td>
																<td>{{ $item['item_name'] }}</td>
																<td>{{ $item['qty'] }}</td>
																@php
																	$orig_price = number_format($item['orig_price'], 2);
																	$price = number_format($item['price'], 2);
																@endphp
																<td>
																	@if($item['discount'] > 0)
																		<p><span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b></p>
																	@else
																		<p>₱ {{ $price }}</p>
																	@endif
																</td>
															</tr>
														@endforeach
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Subtotal: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
														</tr>
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Shipping Fee: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
														</tr>
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Grand Total: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
														</tr>
													</table>
													<div class="d-none d-xl-block">
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span>Subtotal: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span>Shipping Fee: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span style="font-weight: 700">Grand Total: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<div class="d-md-none" style="text-align: left;">
										<br/>
										<b>Shipping Name:</b> {{ $order['shipping_name'] }}<br/><br/>
										<b>Est. Delivery Date:</b> {{ $order['edd'] }}<br/>
										<span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span>
									</div>
								</td>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['shipping_name'] }}</td>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['edd'] }}</td>
								<td class="text-center align-middle d-none d-sm-table-cell"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></td>
							</tr>
							@empty
								<tr>
									<td class="text-center p-3" colspan="6">No transactions found.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<center><h3>Order History</h3></center>
					<br><br/>
					<table class="table">
						<thead>
							<tr>
								<th class="text-center d-none d-sm-table-cell">Order No.</th>
								<th class="text-center">Date</th>
								<th class="text-center">Details</th>
								<th class="text-center d-none d-sm-table-cell">Shipping</th>
								<th class="d-none d-sm-table-cell">Estimated Delivery Date</th>
								<th class="text-center d-none d-sm-table-cell">Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($orders_arr as $order)
							@php
								if($order['status'] == "Order Placed"){
									$badge = '#ffc107';
								}else if($order['status'] == "Cancelled"){
									$badge = '#6c757d';
								}else if($order['status'] == "Delivered"){
									$badge = '#fd6300';
								}else if($order['status'] == "Out for Delivery"){
									$badge = '#28a745';
								}else{
									$badge = '#007bff';
								}
							@endphp
							<tr>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['order_number'] }}</td>
								<td class="text-center align-middle">{{ $order['date'] }}</td>
								<td class="text-center align-middle">
									<a href="#" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">Item Purchase</a>

									<!-- Modal -->
									<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $order['order_number'] }}-ModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" style="min-width: 70%;">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">{{ $order['order_number'] }}</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<table class="table" style="width: 100% !important;">
														<tr>
															<th class="col-sm-1"></th>
															<th class="col-sm-3">Item Name</th>
															<th class="col-sm-2">Quantity</th>
															<th class="col-sm-2">Price</th>
														</tr>
														@foreach ($order['items'] as $item)
															<tr>
																<td>
																	<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
																</td>
																<td>{{ $item['item_name'] }}</td>
																<td>{{ $item['qty'] }}</td>
																@php
																	$orig_price = number_format($item['orig_price'], 2);
																	$price = number_format($item['price'], 2);
																@endphp
																<td>
																	@if($item['discount'] > 0)
																		<p><span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b></p>
																	@else
																		<p>₱ {{ $price }}</p>
																	@endif
																</td>
															</tr>
														@endforeach
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Subtotal: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
														</tr>
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Shipping Fee: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
														</tr>
														<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
															<td></td>
															<td colspan=2 style="text-align: right;">Grand Total: </td>
															<td style="text-align: left; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
														</tr>
													</table>
													<div class="d-none d-xl-block">
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span>Subtotal: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span>Shipping Fee: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
														<div class="row">
															<div class="col-md-10" style="text-align: right;">
																<span style="font-weight: 700">Grand Total: </span>
															</div>
															<div class="col-md-2" style="text-align: left;">
																<span style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
															</div><br/>&nbsp;
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<div class="d-md-none" style="text-align: left;">
										<br/>
										<b>Shipping Name:</b> {{ $order['shipping_name'] }}<br/><br/>
										<b>Est. Delivery Date:</b> {{ $order['edd'] }}<br/>
										<span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span>
									</div>
								</td>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['shipping_name'] }}</td>
								<td class="text-center align-middle d-none d-sm-table-cell">{{ $order['edd'] }}</td>
								<td class="text-center align-middle d-none d-sm-table-cell"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></td>
							</tr>
							@empty
								<tr>
									<td class="text-center p-3" colspan="6">No transactions found.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					<div style="float: right;">
						{{ $orders->links('pagination::bootstrap-4') }}
					</div>

				</div>
			</div> --}}

		</div>
		<br/>&nbsp;
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
		font-weight: 200 !important;
		font-size: 10px !important;
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
	.clear-bg{
		background-color: rgba(0,0,0,0) !important;
		border: none !important;
	}
	.clear-bg:focus{
		outline: none !important;
	}
	.accordion-btn{
		text-decoration: none !important;
		text-transform: none !important;
		color: #000 !important;
	}
	@media (max-width: 575.98px) { /* Mobile */
		.table-text{
			font-size: 14px !important;
		}
	}
    @media (max-width: 767.98px) { /* Mobile */
		.table-text{
			font-size: 14px !important;
		}
	}

</style>
<style>
	@import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0.10rem
}

.card-header:first-child {
    border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
}

.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1)
}

.track {
    position: relative;
    background-color: #ddd;
    height: 4px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 60px;
    margin-top: 50px;

}

.track .step {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    width: 25%;
    margin-top: -18px;
    text-align: center;
    position: relative
}

.track .step.active:before {
    background: #008CFF
}

.track .step::before {
    height: 4px;
    position: absolute;
    content: "";
    width: 100%;
    left: 0;
    top: 18px
}

.track .step.active .icon {
    background: #008CFF;
    color: #fff;
}

.track .icon {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 40px;
    position: relative;
    border-radius: 100%;
    background: #ddd;
	margin-top: -5px;
	padding: 10px !important;
}

.inactive{
	height: 20px !important;
	width: 20px !important;
	margin-top: 10px !important;
}

.fa{
	font-size: 24px !important;
}

.track .step.active .text {
    font-weight: 400;
    color: #000
}

.track .text {
    display: block;
    margin-top: 7px
}

.itemside {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    width: 100%
}

.itemside .aside {
    position: relative;
    -ms-flex-negative: 0;
    flex-shrink: 0
}

.img-sm {
    width: 80px;
    height: 80px;
    padding: 7px
}

ul.row,
ul.row-sm {
    list-style: none;
    padding: 0
}

.itemside .info {
    padding-left: 15px;
    padding-right: 7px
}

.itemside .title {
    display: block;
    margin-bottom: 5px;
    color: #212529
}

p {
    margin-top: 0;
    margin-bottom: 1rem
}

.btn-warning {
    color: #ffffff;
    background-color: #ee5435;
    border-color: #ee5435;
    border-radius: 1px
}

.btn-warning:hover {
    color: #ffffff;
    background-color: #ff2b00;
    border-color: #ff2b00;
    border-radius: 1px
}
.login_2 {
		font-weight: 400 !important;
		font-size: 14px !important;
		color: #655f5f !important;
	}
	.active-btn {
		border-bottom: 3px solid #dc6f12;
	}
@media (max-width: 575.98px) {
		.products-head{
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
		.table-text{
			font-size: 14px;
		}
		.status-text{
			font-size: 12px;
		}
    }

    @media (max-width: 767.98px) {
		.products-head{
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
		.table-text{
			font-size: 14px;
		}
		.status-text{
			font-size: 12px;
		}
    }
</style>
@endsection
@section('script')
	<script>
		$(document).ready(function(){
			$('#current_tab').click(function(){
				$(this).addClass('active-btn');
				$('#history_tab').removeClass('active-btn');
			});

			$('#history_tab').click(function(){
				$(this).addClass('active-btn');
				$('#current_tab').removeClass('active-btn');
			});
		});
	</script>
@endsection