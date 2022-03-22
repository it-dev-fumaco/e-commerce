@extends('frontend.layout', [
    'namePage' => 'My Orders',
    'activePage' => 'myorders'
])

@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: 100% !important;">
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
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#current">Current Order(s)</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#history">Order History</a>
						</li>
					</ul>
				  
					<!-- Tab panes -->
					<div class="tab-content"> 
						<div id="current" class="container tab-pane active" style="padding: 8px 0 0 0;">
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
							<div class="accordion" id="orderAccordion">
								@forelse ($new_orders_arr as $i => $order)
								@php
									if($order['status'] == "Order Placed"){
										$badge = '#ffc107';
									}else if($order['status'] == "Delivered" or $order['status'] == 'Order Completed'){
										$badge = '#fd6300';
									}else if($order['status'] == "Out for Delivery" or $order['status'] == 'Ready for Pickup'){
										$badge = '#28a745';
									}else{
										$badge = '#007bff';
									}
								@endphp
								<div class="accordion-item border-bottom">
									<h2 class="accordion-header" id="heading{{ $i }}">
										<button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" aria-expanded="true" aria-controls="collapse{{ $i }}">
											<div class="w-100">
												<div class="row">
													<div class="col-md-6" style="text-align: left !important; padding: 5px 0 5px 5px !important;">
														<span class="d-inline-block table-text text-dark" style="margin-right: 5px;"><b>{{ $order['order_number'] }}</b></span>
														<span class="d-inline-block badge text-white m-0" style="background-color: {{ $badge }}; font-size: 0.9rem;">{{ $order['status'] }}</span>
													</div>
													<div class="col-md-6 p-1 text-dark text-center" style="padding: 8px 0 5px 5px !important;">
														<p class="d-block d-lg-none m-0" style="text-align: left;">
															@if($order['shipping_name'] != 'Store Pickup' )
															<span class="table-text"><b>Estimated Delivery Date:</b> <br class="d-md-none"/>{{ $order['edd'] }}</span>
														@else
															<span class="table-text"><b>Pickup Date:</b> <br class="d-md-none"/>{{ date('M d, Y', strtotime($order['pickup_date'])) }}</span>
														@endif
														</p>
														<p class="d-none d-lg-block text-center m-0">
															@if($order['shipping_name'] != 'Store Pickup' )
															<span class="table-text"><b>Estimated Delivery Date:</b> <br class="d-md-none"/>{{ $order['edd'] }}</span>
														@else
															<span class="table-text"><b>Pickup Date:</b> <br class="d-md-none"/>{{ date('M d, Y', strtotime($order['pickup_date'])) }}</span>
														@endif
														</p>
													</div>
												</div>
											</div>
										</button>
									</h2>
									<div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : 'hide' }}" aria-labelledby="heading{{ $i }}" data-bs-parent="#orderAccordion">
									  	<div class="accordion-body p-1">
											<table class="table" style="width: 100% !important;">
												<tr>
													<th style="width: 10%;"></th>
													<th style="width: 50%;" class="table-text">Item Name</th>
													<th style="width: 20%;" class="table-text text-center">
														<span class="d-none d-md-block">Quantity</span>
														<span class="d-md-none">Qty</span>
													</th>
													<th style="width: 20%;" class="table-text text-center">Price</th>
												</tr>
												@foreach ($order['items'] as $item)
												<tr>
													<td class="text-center">
														<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
													</td>
													<td class="table-text">{{ $item['item_name'] }}</td>
													<td class="table-text text-center">{{ $item['qty'] }}</td>
													@php
														$orig_price = number_format($item['orig_price'], 2);
														$price = number_format($item['price'], 2);
													@endphp
													<td class="table-text">
														<p class="d-block d-lg-none" style="text-align: right; padding-right: 10px;">
															@if($item['discount'] > 0)
																<span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b>
															@else
																₱ {{ $price }}
															@endif
														</p>
														<p class="d-none d-lg-block" style="text-align: right; padding-right: 20px;">
															@if($item['discount'] > 0)
																<span style="text-decoration: line-through; font-size: 9pt;">₱ {{ $orig_price }}</span><br/><b>₱ {{ $price }}</b>
															@else
																₱ {{ $price }}
															@endif
														</p>
													</td>
												</tr>
												@endforeach
												<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important;">
													<td colspan="3" class="table-text" style="text-align: right;">Subtotal:</td>
													<td class="table-text" style="text-align: right; white-space: nowrap !important; padding-right: 20px;">₱ {{ number_format($order['subtotal'], 2) }}</td>
												</tr>
												@if ($order['voucher_code'])
												<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
													<td colspan="3" class="table-text" style="text-align: right;">
														Discount: <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span>
													</td>
													<td class="table-text" style="text-align: right; white-space: nowrap !important; padding-right: 20px;">₱ {{ number_format($order['discount_amount'], 2) }}</td>
												</tr>
												@endif
												<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
													<td colspan="3" class="table-text" style="text-align: right;">{{ $order['shipping_name'] }}: </td>
													<td class="table-text" style="text-align: right; white-space: nowrap !important; padding-right: 20px;">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
												</tr>
												<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
													<td colspan="3" class="table-text" style="text-align: right;">Grand Total: </td>
													<td class="table-text" style="text-align: right; white-space: nowrap !important; padding-right: 20px;">₱ {{ number_format($order['grand_total'], 2) }}</td>
												</tr>
											</table>
											<div class="d-none d-lg-block d-xl-block">
												<div class="row m-1">
													<div class="col-md-10 p-0" style="text-align: right;">
														<span>Subtotal:</span>
													</div>
													<div class="col-md-2" style="text-align: right; padding-right: 25px;">
														<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
													</div>
												</div>
												@if ($order['voucher_code'])
												<div class="row m-1">
													<div class="col-md-10 p-0" style="text-align: right;">
														<span>Discount:  <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span>
														</span>
													</div>
													<div class="col-md-2" style="text-align: right; padding-right: 25px;">
														<span>₱ {{ number_format($order['discount_amount'], 2) }}</span>
													</div>
												</div>
												@endif
												<div class="row m-1">
													<div class="col-md-10 p-0" style="text-align: right;">
														<span>{{ $order['shipping_name'] }}:</span>
													</div>
													<div class="col-md-2" style="text-align: right; padding-right: 25px;">
														<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
													</div>
												</div>
												<div class="row m-1">
													<div class="col-md-10 p-0" style="text-align: right;">
														<span style="font-weight: 700">Grand Total:</span>
													</div>
													<div class="col-md-2" style="text-align: right; padding-right: 25px;">
														<span style="font-weight: 700">₱ {{ number_format($order['grand_total'], 2) }}</span>
													</div>
												</div>
											</div>
											<div class="m-3">
												<button class="btn btn-primary table-text" data-toggle="modal" data-target="#{{ $order['order_number'] }}-Modal">Track Order</button>
												@php
													$dt = \Carbon\Carbon::now();
													$dt2 = \Carbon\Carbon::parse($order['order_date']);
													$is_same_day = ($dt->isSameDay($dt2));
												@endphp	
												<button class="btn btn-secondary table-text" data-toggle="modal" data-target="#cancel-order{{ $i }}-modal" {{ !$is_same_day ? 'disabled' : '' }} {{ ($order['status'] != "Order Placed") ? 'disabled' : '' }}>Cancel Order</button>
											</div>
									  	</div>
									</div>
								</div>

								@if ($order['status'] == "Order Placed")
								<div class="modal fade" id="cancel-order{{ $i }}-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-order{{ $i }}-modal" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<form action="/myorder/cancel/{{ $order['order_id'] }}" method="POST" autocomplete="off">
											@csrf
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Cancel Order</h5>
													<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<p class="text-center">Cancel order <b>{{ $order['order_number'] }}</b> ?</p>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Confirm</button>
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								@endif

								<div class="modal fade" id="{{ $order['order_number'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="order-details" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Track {{ $order['order_number'] }}</h5>
												<button type="button" class="close clear-bg" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="container-fluid">
													<ul class="list-group vertical-steps" style="margin-left: -30px">
														@php
															$step = $order['current_order_status_sequence'];
														@endphp
														<li class="fa-ul list-group-item completed">
															<span class="fa-li" style="margin-left: 15px; margin-top: -6px">
																<i class="fas fa-circle" style="color: #008CFF !important"></i>
															</span>
															<span class="text">Order Placed</span>
															<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $order['date'] }}</span>
														</li>
														@if ($order['payment_method'] == 'Bank Deposit')
															@php
																$order_tracker_payment = collect($order['order_tracker'])->groupBy('track_payment_status');
															@endphp
															@foreach ($payment_statuses as $s => $status)
																@php
																	$payment_status_icon = null;
																	if($status->status == 'Pending for Payment'){
																		$payment_status_icon = "fa-upload";
																	}else if($status->status == 'Payment For Confirmation'){
																		$payment_status_icon = "fa-hourglass";
																	}

																	$status_step = $status->status;

																	$payment_status = isset($order_tracker_payment[$status_step]) ? 'active' : null;
																	$payment_status_date = isset($order_tracker_payment[$status_step]) ? $order_tracker_payment[$status_step][0]->track_date : null;
																	$payment_status_display_date = $payment_status_date ? date('M d, Y h:i A', strtotime($payment_status_date)) : null;
																	$payment_status_icon_container = 'inactive';
																	$payment_status_step = 'completed';
																	$payment_status_description = null;
																	$payment_status = null;
																	if($order['status'] == 'Order Placed'){
																		$payment_status_icon_container = $order['current_payment_status_sequence'] != $s + 1 ? 'inactive' : null;
																		$payment_status_step = $order['current_payment_status_sequence'] > $s + 1 ? 'completed' : null;
																		$payment_status = $order['current_payment_status_sequence'] < $s + 1 ? 'text-muted' : null;
																		$payment_status_description = $order['current_payment_status_sequence'] >= $s + 1 ? null : 'd-none';
																	}

																	$payment_step_color = null;
																	if($order['current_payment_status_sequence'] < $s + 1){
																		$payment_step_color = '#ece5dd';
																	}else if($order['current_payment_status_sequence'] > $s + 1){
																		$payment_step_color = '#008CFF';
																	}
																@endphp
																<li class="fa-ul list-group-item {{ $payment_status_step }}">
																	@if ($order['status'] == 'Order Placed')
																		<span class="fa-li {{ $order['current_payment_status_sequence'] == $s + 1 ? 'active-icon' : null }}" style="margin-left: 15px;">
																			@if($order['current_payment_status_sequence'] == $s + 1)
																				<i class="fas {{ $payment_status_icon }}" style="font-size: 16px !important"></i>
																			@else
																				<i class="fas fa-circle" style="color: {{ $payment_step_color }} !important"></i>
																			@endif
																		</span>
																	@else
																		<span class="fa-li" style="margin-left: 15px;">
																			<i class="fas fa-circle" style="color: #008CFF !important"></i>
																		</span>
																	@endif
																	<span class="{{ $payment_status }}">{{ $status->status }}</span>
																	<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $payment_status_display_date }}</span>
																	<span class="text status-text {{ $payment_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $status->status_description }}</span>
																</li>
															@endforeach
														@endif
														@php
															$order_status_tracker = collect($order['order_tracker'])->groupBy('track_status');
														@endphp
														@foreach ($order['ship_status'] as $key => $name)
															@php
																$order_status = isset($order_status_tracker[$name->status]) ? 'active' : null;
																$status_date_update = isset($order_status_tracker[$name->status]) ? $order_status_tracker[$name->status][0]->track_date : null;
																$date = $status_date_update ? date('M d, Y h:i A', strtotime($status_date_update)) : null;
																$icon_display = null;
																if($step > $key + 1){
																	$icon_display = 'completed';
																}else if($step == $key + 1){
																	$icon_display = 'active';
																}
																
																$icon = '';
																if($name->status == "Order Confirmed"){
																	$icon = 'user';
																}else if($name->status == "Out for Delivery" or $name->status == "Ready for Pickup"){
																	$icon = 'truck';
																}else if($name->status == "Order Delivered" or $name->status == "Order Completed"){
																	$icon = 'shopping-bag';
																}

																$order_step_color = null;
																if($step < $key + 1){
																	$order_step_color = '#ece5dd';
																}else if($step > $key + 1){
																	$order_step_color = '#008CFF';
																}

																$order_status_description = null;
																$order_status_step = null;
																if($key + 1 > $step){
																	$order_status_description = 'd-none';
																	$order_status_step = 'text-muted';
																}
															@endphp
															<li class="fa-ul list-group-item {{ $icon_display }}">
																<span class="fa-li {{ $step == $key + 1 ? 'active-icon' : null }}" style="margin-left: 15px; {{ $loop->last ? 'margin-top: -6px' : null }}">
																	@if ($step == $key + 1)
																		<i class="fa fa-{{ $icon }}" style="font-size: 16px !important"></i>
																	@else
																		<i class="fas fa-circle" style="color: {{ $order_step_color }} !important"></i>
																	@endif
																</span>
																<span class="text {{ $order_status_step }}" style="{{ $loop->last ? 'margin-top: -6px' : null }}">{{ $name->status }}</span>
																<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
																<span class="text status-text {{ $order_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $name->status_description }}</span>
														  	</li>
														@endforeach
													</ul>  
												</div>
												{{-- <div class="track-container">
													<div class="track">
														@php
															$step = $order['current_order_status_sequence'];
														@endphp
														<div class="step active">
															<span class="icon inactive"><i class="fa fa-check {{ $step > 0 ? 'd-none' : '' }}"></i></span>
															<span class="text status-text">Order Placed</span>
															<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $order['date'] }}</span>
														</div>
														@if ($order['payment_method'] == 'Bank Deposit')
															@php
																$order_tracker_payment = collect($order['order_tracker'])->groupBy('track_payment_status');
															@endphp
															@foreach ($payment_statuses as $s => $status)
																@php
																	$payment_status_icon = null;
																	if($status->status == 'Pending for Upload'){
																		$payment_status_icon = "fa-upload";
																	}else if($status->status == 'Payment For Confirmation'){
																		$payment_status_icon = "fa-hourglass";
																	}
																	$payment_status = isset($order_tracker_payment[$status->status]) ? 'active' : null;
																	$payment_status_date = isset($order_tracker_payment[$status->status]) ? $order_tracker_payment[$status->status][0]->track_date_update : null;
																	$payment_status_display_date = $payment_status_date ? date('M d, Y H:i A', strtotime($payment_status_date)) : null;
																	$payment_status_icon_container = 'inactive';
																	$payment_status_description = 'd-none';
																	if($order['status'] == 'Order Placed'){
																		$payment_status_icon_container = $order['current_payment_status_sequence'] != $s + 1 ? 'inactive' : null;
																		$payment_status_description = $order['current_payment_status_sequence'] != $s + 1 ? 'd-none' : null;
																	}
																@endphp
																<div class="step {{ $payment_status }}">
																	<span class="icon {{ $payment_status_icon_container }}"><i class="fa {{ $payment_status_icon.$order['current_payment_status_sequence'] != $s + 1 ? 'd-none' : null }}"></i></span>
																	<span class="text status-text">{{ $status->status }}</span>
																	<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">
																		{{ $payment_status_display_date }}
																	</span>
																	<span class="text status-text {{ $payment_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $status->status_description }}</span>
																</div>
															@endforeach
														@endif
														@php
															$order_status_tracker = collect($order['order_tracker'])->groupBy('track_status');
														@endphp
														@foreach ($order['ship_status'] as $key => $name)
															@php
																$order_status = isset($order_status_tracker[$name->status]) ? 'active' : null;
																$status_date_update = isset($order_status_tracker[$name->status]) ? $order_status_tracker[$name->status][0]->track_date_update : null;
																$date = $status_date_update ? date('M d, Y H:i A', strtotime($status_date_update)) : null;
																
																$icon = '';
																if($name->status == "Order Confirmed"){
																	$icon = 'user';
																}else if($name->status == "Out for Delivery" or $name->status == "Ready for Pickup"){
																	$icon = 'truck';
																}else if($name->status == "Order Delivered" or $name->status == "Order Completed"){
																	$icon = 'shopping-bag';
																}
															@endphp
															<div class="step {{ $order_status }}">
																<span class="icon {{ $step != $key + 1 ? 'inactive' : '' }}"><i class="fa fa-{{ $icon }} {{ $step != $key + 1 ? 'd-none' : '' }}"></i></span>
																<span class="text status-text">{{ $name->status }}</span>
																<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
																<span class="text status-text {{ $step != $key + 1 ? 'd-none' : '' }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $name->status_description }}</span>
															</div>
														@endforeach
													</div>
												</div> --}}
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								@empty
								<h5 class="font-weight-bold text-center p-3">No transactions found.</h5>
								@endforelse
							</div>
						</div>
						<div id="history" class="container tab-pane fade">
							<br>
							<center><h3 class="h3">Order History</h3></center>
							<br><br/>
							<table class="table">
								<thead>
									<tr>
										<th class="text-center d-none d-lg-table-cell">Order No.</th>
										<th class="text-center table-text">Date</th>
										<th class="text-center table-text">Details</th>
										<th class="text-center d-none d-lg-table-cell">Shipping</th>
										<th class="text-center d-none d-lg-table-cell">Delivery Date</th>
										<th class="text-center d-none d-lg-table-cell">Status</th>
										<th class="text-center d-none d-lg-table-cell">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($orders_arr as $order)
									@php
										if($order['status'] == "Order Placed"){
											$badge = '#ffc107';
										}else if($order['status'] == "Cancelled"){
											$badge = '#6c757d';
										}else if($order['status'] == "Delivered" or $order['status'] == "Order Completed" or $order['status'] == "Order Delivered"){
											$badge = '#fd6300';
										}else if($order['status'] == "Out for Delivery"){
											$badge = '#28a745';
										}else{
											$badge = '#007bff';
										}
									@endphp
									<tr>
										<td class="text-center align-middle d-none d-lg-table-cell">{{ $order['order_number'] }}</td>
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
																		<td style="text-align: left;">{{ $item['item_name'] }}</td>
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
																	<td style="text-align: right; white-space: nowrap !important">₱ {{ number_format($order['subtotal'], 2) }}</td>
																</tr>
																@if ($order['voucher_code'])
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 class="table-text" style="text-align: right;">Discount: <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span>
																		</td>
																	<td class="table-text" style="text-align: right; white-space: nowrap !important">₱ {{ number_format($order['discount_amount'], 2) }}</td>
																</tr>
																@endif
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">{{ $order['shipping_name'] }}: </td>
																	<td style="text-align: right; white-space: nowrap !important">₱ {{ number_format($order['shipping_fee'], 2) }}</td>
																</tr>
																<tr class="d-lg-none d-xl-none" style="border-bottom: rgba(0,0,0,0) !important; font-weight: 700 !important">
																	<td></td>
																	<td colspan=2 style="text-align: right;">Grand Total: </td>
																	<td style="text-align: right; white-space: nowrap !important">₱ {{ number_format($order['grand_total'], 2) }}</td>
																</tr>
															</table>
															<div class="d-none d-xl-block">
																<div class="row m-1">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Subtotal: </span>
																	</div>
																	<div class="col-md-2" style="text-align: right;">
																		<span>₱ {{ number_format($order['subtotal'], 2) }}</span>
																	</div>
																</div>
																@if ($order['voucher_code'])
																<div class="row m-1">
																	<div class="col-md-10" style="text-align: right;">
																		<span>Discount:  <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span>
																		</span>
																	</div>
																	<div class="col-md-2" style="text-align: right;">
																		<span>₱ {{ number_format($order['discount_amount'], 2) }}</span>
																	</div>
																</div>
																@endif
																<div class="row m-1">
																	<div class="col-md-10" style="text-align: right;">
																		<span>{{ $order['shipping_name'] }}: </span>
																	</div>
																	<div class="col-md-2" style="text-align: right;">
																		<span>₱ {{ number_format($order['shipping_fee'], 2) }}</span>
																	</div>
																</div>
																<div class="row m-1">
																	<div class="col-md-10" style="text-align: right;">
																		<span style="font-weight: 700">Grand Total: </span>
																	</div>
																	<div class="col-md-2" style="text-align: right;">
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
											<div class="d-lg-none table-text" style="text-align: left;">
												<br/>
												<b>Order Number:</b><br/>{{ $order['order_number'] }}<br/><br/>
												<b>Shipping Name:</b> {{ $order['shipping_name'] }}<br/><br/>
												<b>Delivery Date:</b> {{ $order['date_delivered'] ? date('M d, Y', strtotime($order['date_delivered'])) : '' }}<br/>
												<p><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></p>
												<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#reorder{{ $order['order_number'] }}Modal"  {{ $order['status'] == 'Cancelled' ? 'disabled' : '' }}>Re-Order</button>
											</div>
										</td>
										<td class="text-center align-middle d-none d-lg-table-cell">{{ $order['shipping_name'] }}</td>
										<td class="text-center align-middle d-none d-lg-table-cell">{{ $order['date_delivered'] ? date('M d, Y', strtotime($order['date_delivered'])) : '' }}</td>
										<td class="text-center align-middle d-none d-lg-table-cell"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem; color: #fff !important;">{{ $order['status'] }}</span></td>
										<td class="text-center align-middle d-none d-lg-table-cell"><button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#reorder{{ $order['order_number'] }}Modal" {{ $order['status'] == 'Cancelled' ? 'disabled' : '' }}>Re-Order</button></td>

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

.fa-ul{
		min-height: 60px;
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
	.track-container{
			min-height: 120px
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
		.track-container{
			min-height: 200px
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
		.track-container{
			min-height: 200px
		}
    }
	@media (max-width: 1199.98px) {
		.track-container{
			min-height: 200px
		}
	}

	/*Vertical Steps*/
.list-group.vertical-steps{
  padding-left:10px;
  padding-top: 10px;
}
.list-group.vertical-steps .list-group-item{
  border:none;
  border-left:3px solid #ece5dd;
  box-sizing:border-box;
  border-radius:0;
  /* counter-increment: step-counter; */
  padding-left:25px;
  padding-right:0px;
  padding-bottom:20px;  
  padding-top:0px;
}
.list-group.vertical-steps .list-group-item.active{
  background-color:transparent;
  color:inherit;
}
.list-group.vertical-steps .list-group-item:last-child{
  border-left:3px solid transparent;
  padding-bottom:0;
}
.list-group.vertical-steps .list-group-item::before {
/* font-family: FontAwesome; */
  border-radius: 50%;
  background-color:#ece5dd;
  color:#555;
  /* content: counter(step-counter); */
  /* content: 'f093'; */
  display:inline-block;
  float:left;
  height:25px;
  line-height:25px;
  margin-left:-40px;
  text-align:center;  
  width:25px;  
}
.list-group.vertical-steps .list-group-item span,
.list-group.vertical-steps .list-group-item a{
  display:block;
  overflow:hidden;
  padding-top:2px;
}

/*Active/ Completed States*/
.active-icon{
	background-color: #008CFF !important;
	color: #fff !important;
}

.fa-li{
	border-radius: 50%;
}

.fa-li > .completed{
	background-color:rgba(0, 0, 0, 0);
	color: #008CFF;
}

.fa-li > .incomplete{
	background-color:rgba(0, 0, 0, 0);
	color: #ece5dd;
}

.list-group.vertical-steps .list-group-item.active{
	padding-left: 20px;
}

.list-group.vertical-steps .list-group-item.active::before{
  background-color:#008CFF;
  color:#fff;
  height:40px;
  width:40px;  

}
.list-group.vertical-steps .list-group-item.completed{
  border-left:3px solid #008CFF;
  padding-left: 26px;
}
.list-group.vertical-steps .list-group-item.completed::before{
  background-color:#008CFF;
  color:#fff;
}
.list-group.vertical-steps .list-group-item.completed:last-child{
  border-left:3px solid transparent;
}
</style>
@endsection