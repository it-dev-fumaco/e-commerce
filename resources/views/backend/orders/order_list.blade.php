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
									<thead class="text-center">
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
											<td class="text-center">{{ $order['date'] }}</td>
											<td class="text-center">{{ $order['order_no'] }}</td>
											<td class="text-center">{{ $order['first_name'] .' '. $order['last_name'] }}</td>
											<td class="text-center">{{ $order['estimated_delivery_date'] }}</td>
											<td class="text-center">{{ $order['shipping_name'] }}</td>
											<td class="text-center">{{ $order['payment_method'] }}</td>
											<td class="text-center">₱ {{ number_format(str_replace(",","",$order['grand_total']), 2) }}</td>
											@php
												$voucher_discount_amount = 0;
												$shipping_discount_amount = 0;
												switch ($order['status']) {
													case 'Order Placed':
														$badge = 'warning';
														break;
													case 'Out for Delivery':
													case 'Ready for Pickup':
														$badge = 'success';
														break;
													case 'Cancelled';
														$badge = 'secondary';
														break;
													case 'Order Confirmed':
														$badge = 'primary';
														break;
													default:
														$badge = null;
														break;
												}
											@endphp
											<td class="text-center"><span class="badge badge-{{ $badge }}" style="font-size: 11pt; {{ ($order['status'] == 'Delivered') ? "background-color: #fd6300 !important; color: #fff;" : '' }}">{{ $order['status'] }}</span></td>
											<td>
												<div class="text-center">
													{{-- <button type="button" class="btn btn-primary btn-sm view-order-btn" data-toggle="modal" data-target="#order-{{ $order['order_no']}}" data-so-status="#sostatus{{ $order['order_no']}}" data-so="{{ $order['erp_sales_order'] }}">View Orders</button> --}}
													<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#order-{{ $order['order_no']}}">View Orders</button>
												</div>
												<div class="modal fade" id="order-{{ $order['order_no'] }}" role="dialog">
													<div class="modal-dialog modal-xl" style="min-width: 70%;">
														<div class="modal-content">
															<div class="modal-header">
																<div class="row container-fluid">
																	<div class="col-md-6">
																		<h4 class="modal-title">ORDER NO. {{ $order['order_no'] }}</h4>
																	</div>
																	<div class="col-md-6">
																		<div class="float-right font-italic m-1" style="font-size: 1.2rem;">
																			<span class="badge badge-info d-inline-block mr-3" style="font-size: 1rem;">{{ $order['shipping_name'] }}</span>
																			{!! ($order['shipping_name'] != 'Store Pickup') ? '<strong>Est. Delivery Date : </strong> ' . $order['estimated_delivery_date'] : '<strong>Pickup by : </strong> ' . \Carbon\Carbon::parse($order['pickup_date'])->format('D, F d, Y') !!}
																		</div>
																	</div>
																</div>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body" id="customer-order-{{ $order['order_no'] }}">
																<div class="row {{ ($order['status'] == 'Delivered') ? 'd-none' : '' }}">
																	@if ($order['status'] == 'Order Placed' && $order['payment_method'] == 'Bank Deposit' && $order['payment_status'] != 'Received')
																		<div class="col-12">
																			<div class="callout callout-info text-center">
																				<small><i class="fas fa-info-circle"></i> &nbsp;Bank Deposit: Please Contact Accounting for Payment Confirmation before you can update the Order Status.</small>
																			</div>
																		</div>
																	@endif
																	<div class="col-4">
																		<p class="mb-0"><strong>Customer Name : </strong> {{ $order['first_name'] . " " . $order['last_name'] }}</p>
																		@if($order['user_email'])
																		<p class="mb-0"><strong>Email Address : </strong> {{ $order['user_email'] }}</p>
																		@endif
																		<p class="text-muted mb-0"><strong>{{ $order['order_type'] }} Checkout</strong></p>
																	</div>
																	<div class="col-8 d-print-none">
																		<div class="row">
																			<div class="col-6 p-0">
																				@if ($order['payment_method'] == 'Bank Deposit' and in_array(Auth::user()->user_type, ['System Admin', 'Accounting Admin']))
																					<div class="row">
																						<div class="col-2">
																							@if ($order['payment_status'] == 'Payment For Confirmation')
																								<a href="{{ asset('/storage/deposit_slips/'.$order['deposit_slip_image']) }}" target="_blank">
																									<img src="{{ asset('/storage/deposit_slips/'.$order['deposit_slip_image']) }}" class="img-thumbnail w-100">
																								</a>
																							@endif
																						</div>
																						
																						<div class="col-10 pt-1">
																							<p class="my-auto"><b>Payment Status:</b> {{ $order['payment_status'] }}</p>
																							
																							@if($order['payment_status'] == 'Pending for Payment')
																								<button class="btn btn-primary btn-sm d-print-none" data-toggle="modal" data-target="#send-deposit-link-{{ $order['order_no'] }}-Modal">Send Deposit Slip Upload Link</button>

																								<div class="modal fade payment-modal" id="send-deposit-link-{{ $order['order_no'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																									<div class="modal-dialog" role="document">
																										<div class="modal-content">
																											<div class="modal-header">
																												<h5 class="modal-title" id="exampleModalLabel">Send Deposit Slip Upload Link</h5>
																												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																													<span aria-hidden="true">&times;</span>
																												</button>
																											</div>
																											<div class="modal-body text-center">
																												Send deposit slip upload link for {{ $order['order_no'] }}?
																											</div>
																											<form action="/admin/order/send_upload_link" method="POST">
																												@csrf
																												<div class="modal-footer">
																													<button type="submit" class="btn btn-sm btn-primary">Confirm</button>
																													<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
																													<div class="d-none">
																														<input type="text" name="order_number" value="{{ $order['order_no'] }}" readonly/>
																														<input type="text" name="billing_email" value="{{ $order['bill_email'] }}" readonly>
																														<input type="text" name="billing_number" value="{{ $order['bill_contact'] }}" readonly>
																													</div>
																												</div>
																											</form>
																										</div>
																									</div>
																								</div>
																							@elseif ($order['payment_status'] == 'Payment For Confirmation')
																								<button class="btn btn-primary btn-sm d-print-none" data-toggle="modal" data-target="#payment-status-{{ $order['order_no'] }}-Modal">Confirm Payment</button>

																								<div class="modal fade payment-modal" id="payment-status-{{ $order['order_no'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																									<div class="modal-dialog" role="document">
																										<div class="modal-content">
																											<div class="modal-header">
																												<h5 class="modal-title" id="exampleModalLabel">Confirm Payment</h5>
																												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																													<span aria-hidden="true">&times;</span>
																												</button>
																											</div>
																											<div class="modal-body text-center">
																												Confirm payment of <b>₱ {{ number_format(str_replace(",","",$order['grand_total']), 2) }}</b>?
																											</div>
																											<form action="/admin/order/status_update" method="POST">
																												@csrf
																												<div class="modal-footer">
																													<button type="submit" class="btn btn-sm btn-primary">Confirm</button>
																													<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
																													<div class="d-none">
																														<input type="text" name="status" value="Order Confirmed">
																														<input type="text" value="{{ $order['order_no'] }}" name="order_number"/>
																														<input type="checkbox" name="member" {{ $order['order_type'] == 'Member' ? 'checked' : '' }} readonly/>
																														<input type="checkbox" name="payment_received" checked readonly>
																													</div>
																												</div>
																											</form>
																										</div>
																									</div>
																								</div>
																							@endif
																						</div>
																					</div>
																				@endif
																			</div>
																			<div class="col-6">
																				@php
																					$update_status = null;
																					if($order['payment_method'] == 'Bank Deposit' and $order['status'] == 'Order Placed' and !in_array($order['payment_status'], ['Payment Received', 'Payment Confirmed'])){
																						$update_status = 'disabled';
																					}
																				@endphp
																				<form class="btn-group" action="/admin/order/status_update" method="POST" style="width: 100%; height: 40px !important;">
																					@csrf
																					<label class="stat-label p-0" for="status">Order Status&nbsp;&nbsp;</label>
																					<select name="status" class="form-control col-md-6" name="order_status" required {{ $update_status }}> 
																						<option value="" {{ ($order['status'] == 'Order Placed') ? 'selected' : '' }} disabled>Order Placed</option>
																						@foreach($order['order_status'] as $status)
																							<option value="{{ $status->status }}" {{ $order['status'] == $status->status ? 'selected disabled' : '' }}>{{ $status->status }}</option>
																						@endforeach
																					</select>
																					<div class="d-none">
																						<input type="text" value="{{ $order['order_no'] }}" name="order_number" readonly/>
																						<input type="checkbox" name="member" {{ $order['order_type'] == 'Member' ? 'checked' : '' }} readonly/>
																						<input type="checkbox" name="payment_received" {{ !in_array($order['status'], ['Order Placed', 'Cancelled']) ? 'checked' : null }} readonly/>
																					</div>
																					<button type="submit" class="form-control col-md-3" style="margin-left: 2%" {{ $update_status }}>Update</button>
																				</form>
																			</div>
																		</div>
																	</div>
																</div>
																<br/>
																<div class="row">
																	<div class="col-md-4">
																		<p>
																			<strong>Order ID : </strong> {{ $order['order_no'] }} <br>
																			<strong>Payment ID : </strong> {{ $order['payment_id'] }}<br>
																			<strong>Payment Method : </strong> {{ $order['payment_method'] }}<br>
																			@if ($order['payment_method'] != 'Bank Deposit')
																			<strong>Issuing Bank : </strong> {{ $order['issuing_bank'] }}<br>
																			@endif
																			<strong>Order Date : </strong> {{ $order['date'] }} <br>
																			<strong>Status : </strong> <span class="badge badge-{{ $badge }}" style="font-size: 1rem;">{{ $order['status'] }}</span>
																			{{-- @if ($order['erp_sales_order'] && $order['status'] == 'Order Confirmed')
																			<span class="d-block mt-2">
																				<b>ERP Sales Order : </b>{{ $order['erp_sales_order'] }} <span class="badge" id="sostatus{{ $order['order_no']}}" style="font-size: 11pt;"></span>
																			</span>
																			@endif  --}}
																		</p>
																	</div>
																	<div class="col-md-4">
																		<p>
																			<strong>Billing Address : </strong><br>
																			<strong>Bill to :</strong> {{ ($order['billing_business_name']) ? $order['billing_business_name'] : $order['bill_contact_person'] }}<br>
																			{!! $order['bill_address1'] . " " . $order['bill_address2'] . ", <br>" . $order['bill_brgy'] . ", " . $order['bill_city'] . "<br>" . $order['bill_province'] . ', ' .  $order['bill_country'] . ' ' . $order['bill_postal'] !!}<br/>
																			{{ $order['bill_email'] }}<br/>
																			{{ $order['bill_contact'] }}
																		</p>
																	</div>
																	<div class="col-md-4">
																		@if ($order['shipping_name'] == 'Store Pickup')
																		<p>
																			<strong>Pickup At : </strong><br>
																			{{ ($order['store']) }}<br>
																			{!! $order['store_address'] !!}<br/>
																			<strong>Pickup Date : </strong>
																			{{ \Carbon\Carbon::parse($order['pickup_date'])->format('D, F d, Y') }}
																		</p>
																		@else
																		<p>
																			<strong>Shipping Address : </strong><br>
																			<strong>Ship to :</strong> {{ ($order['shipping_business_name']) ? $order['shipping_business_name'] : $order['ship_contact_person'] }}<br>
																			{!! $order['ship_address1'] . " " . $order['ship_address2'] . ", <br>" . $order['ship_brgy'] . ", " . $order['ship_city'] . "<br>" . $order['ship_province'] . ', ' .  $order['ship_country'] . ' ' . $order['ship_postal'] !!}<br/>
																			{{ $order['email'] }}<br/>
																			{{ $order['contact'] }}
																		</p>
																		@endif
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
																					<th class="text-center" style="width: 10%;">DISCOUNT</th>
																					@endif
																					<th class="text-center" style="width: 10%;">AMOUNT</th>
																				</tr>
																			</thead>
																			<tbody>
																				@foreach ($order['ordered_items'] as $item)
																				<tr>
																					<td class="text-center">{{ $item['item_code'] }}</td>
																					<td>
																						<div class="row">
																							<div class="col-1 p-0">
																								@php
																									$src = $item['image'] ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
																									$orig = $item['image'] ? '/storage/item_images/'.$item['item_code'].'/gallery/original/'. $item['orig'] : '/storage/no-photo-available.png';
																								@endphp

																								<a data-toggle="modal" data-target="#image-{{ $order['order_id'].'-'.$item['item_code'] }}">
																									<picture>
																										<source srcset="{{ asset(explode('.', $src)[0].'.webp') }}" type="image/webp">
																										<source srcset="{{ asset($src) }}" type="image/jpeg">
																										<img class="img-thumbnail w-100" src="{{ asset($src) }}" loading='lazy'/>
																									</picture>
																								</a>
																								
																								<!-- Modal -->
																								<div class="modal fade" id="image-{{ $order['order_id'].'-'.$item['item_code'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																									<div class="modal-dialog" role="document">
																										<div class="modal-content">
																											<div class="modal-header">
																												<h5 class="modal-title" id="exampleModalLabel">{{ $item['item_code'] }}</h5>
																											</div>
																											<div class="modal-body">
																												<picture>
																													<source srcset="{{ asset(explode('.', $orig)[0].'.webp') }}" type="image/webp">
																													<source srcset="{{ asset($orig) }}" type="image/jpeg">
																													<img class="img-thumbnail w-100" src="{{ asset($orig) }}" loading='lazy'/>
																												</picture>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																							<div class="col-11">
																								{{ $item['item_name'] }}
																								@if (count($item['bundle']) > 0)
																								<br>
																								<ul>
																									@foreach ($item['bundle'] as $bundle)
																									<li style="font-size: 10pt;">
																										<b>{{ $bundle->item_code }}</b> {!! $bundle->item_description !!}
																										<span class="d-block text-muted font-italic">x{{ $bundle->qty . ' ' . $bundle->uom }}</span>
																									</li>
																									@endforeach
																								</ul>
																								@endif
																							</div>
																						</div>
																					</td>
																					<td class="text-center">{{ $item['item_qty'] }}</td>
																					<td class="text-right">₱ {{ number_format(str_replace(",","",$item['item_price']), 2) }}</td>
																					@if ($sum_discount > 0)
																					<td class="text-right">
																						@switch($item['discount_type'])
																							@case('Fixed Amount')
																								₱ {{ number_format(str_replace(",","",$item['item_discount']), 2) }}
																								@break
																							@case('By Percentage')
																							@case('percentage')
																							@case(null)
																								{{ $item['item_discount'] ? $item['item_discount'] . '%' : null }}
																								@break
																							@default
																							-																							
																						@endswitch
																					</td>
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
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","", collect($order['ordered_items'])->sum('item_total')), 2) }}</dd>
																			@if ($order['shipping_discount'])
																				@php
																					$shipping_discount = $order['shipping_discount'];
																					switch ($shipping_discount->discount_type) {
																						case 'By Percentage':
																							$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $order['subtotal'];
																							$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
																							break;
																						default:
																							$shipping_discount_amount = $shipping_discount->discount_rate;
																							break;
																					}
																				@endphp
																				@if ($shipping_discount_amount < $order['subtotal'])
																					<dt class="col-sm-10 text-right">{{ $shipping_discount->sale_name }}</dt>
																					<dd class="col-sm-2 text-right">- ₱ {{ number_format(str_replace(",","",$shipping_discount_amount), 2) }}</dd>
																				@endif
																			@endif
																			@if ($order['voucher_code'])
																				@php
																					$voucher_details = $order['voucher_details'];
																					$voucher_discount_amount = 0;
																					if($voucher_details){
																						switch ($voucher_details->discount_type) {
																							case 'By Percentage':
																								$voucher_discount_amount = ($voucher_details->discount_rate / 100) * $order['subtotal'];
																								break;
																							default:
																								$voucher_discount_amount = $voucher_details->discount_rate;
																								break;
																						}
																					}
																				@endphp
																				<dt class="col-sm-10 text-right"><span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span></dt>
																				<dd class="col-sm-2 text-right">- ₱ {{ number_format(str_replace(",","",$voucher_discount_amount), 2) }}</dd>
																			@endif
																			@if ($order['price_rule'])
																				@php
																					$rule = $order['price_rule'];
																					$pr_discount_amount = $order['discount_amount'] > ($voucher_discount_amount + $shipping_discount_amount) ? $order['discount_amount'] - ($voucher_discount_amount + $shipping_discount_amount) : 0;
																				@endphp
																				@if ($pr_discount_amount > 0)
																					<dt class="col-sm-10 text-right"><span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $rule['discount_name'] }}</span></dt>
																					<dd class="col-sm-2 text-right">- ₱ {{ number_format(str_replace(",","", $pr_discount_amount), 2) }}</dd>
																				@endif
																			@endif
																			<dt class="col-sm-10 text-right">
																				@if ($order['shipping_name'])
																				<span class="badge badge-info" style="font-size: 11pt;">{{ $order['shipping_name'] }}</span>
																				@else
																				{{ $order['shipping_name'] }}
																				@endif
																			</dt>
																			<dd class="col-sm-2 text-right">
																				@if ($order['shipping_amount'] > 0)
																				₱ {{ number_format(str_replace(",","",$order['shipping_amount']), 2) }}
																				@else
																				FREE
																				@endif
																			</dd>
																			<dt class="col-sm-10 text-right">Grand Total</dt>
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['grand_total']), 2) }}</dd>
																		</dl>
																	</div>
																</div>
																<div class="modal-footer d-print-none">
																	@php
																		$dt = \Carbon\Carbon::now();
																		$dt2 = \Carbon\Carbon::parse($order['order_date']);
																		$is_same_day = ($dt->isSameDay($dt2));
																	@endphp	
																	@if($order['payment_method'] == 'Bank Deposit')
																	<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#upload-deposit-slip-{{ $order['order_no'] }}">Upload Deposit Slip</button>
																	@endif
																	<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancel-order-{{ $order['order_no'] }}" {{ !$is_same_day ? 'disabled' : '' }}>Cancel Order</button>
																	<a href="/admin/order/print/{{ $order['order_no'] }}" class="print_order btn btn-sm btn-primary" target="_blank">Print</a>
																	<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tracker-{{ $order['order_no'] }}">Add Tracker Code</button>
																</div>
															</div>
														</div>
													</div>
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
													<div class="modal fade confirm-modal" id="upload-deposit-slip-{{ $order['order_no'] }}" tabindex="-1" role="dialog" aria-labelledby="upload-deposit-slip-{{ $order['order_no'] }}" aria-hidden="true">
														<div class="modal-dialog modal-lg" role="document">
															<form action="/admin/order/upload_deposit_slip/{{ $order['order_id'] }}" method="POST" autocomplete="off" enctype="multipart/form-data">
																@csrf
																<input type="hidden" name="is_admin" value="1">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title">Upload Deposit Slip for <b>{{ $order['order_no'] }}</b></h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body text-center">
																		@if ($order['deposit_slip_image'])
																		<img src="{{ asset('/storage/deposit_slips/' . $order['deposit_slip_image']) }}" id="img-preview-{{ $order['order_no'] }}" class="img-thumbnail w-50">
																		@else
																		<img src="{{ asset('/storage/no-photo-available.png') }}" id="img-preview-{{ $order['order_no'] }}" class="img-thumbnail w-50">
																		@endif
																		<div class="row">
																			<div class="col-md-6 offset-md-3">
																				<div class="custom-file mt-3 text-left">
																					<input type="file" class="custom-file-input img-upload-btn" id="file{{ $order['order_no'] }}" data-id="img-preview-{{ $order['order_no'] }}" name="deposit_slip_image">
																					<label class="custom-file-label" for="file{{ $order['order_no'] }}">Choose file</label>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="submit" class="btn btn-primary">Upload</button>
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																	</div>
																</div>
															</form>
														</div>
													</div>
													<div class="modal fade confirm-modal" id="cancel-order-{{ $order['order_no'] }}" tabindex="-1" role="dialog" aria-labelledby="cancel-order-{{ $order['order_no'] }}" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<form action="/admin/order/cancel/{{ $order['order_id'] }}" method="POST" autocomplete="off">
																@csrf
																<input type="hidden" name="is_admin" value="1">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title">Cancel Order</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		<p class="text-center">Cancel order <b>{{ $order['order_no'] }}</b> ?</p>
																	</div>
																	<div class="modal-footer">
																		<button type="submit" class="btn btn-primary">Confirm</button>
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</td>
											</tr>
											@empty
											<tr><td colspan="9" class="text-center"><b>No Orders</b></td></tr>
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
	.modal{
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

@section('script')
@if (session()->has('for_confirmation'))
	<script>
		$(document).ready(function(){
			$('#order-{{ session()->get("for_confirmation") }}').modal('show');
		});
	</script>
@endif
<script>
	$(function () {
		bsCustomFileInput.init();

		$(document).on('change', '.img-upload-btn', function() {
			var img_div = $(this).data('id');
			const file1 = this.files[0];
			if (file1){
				let reader = new FileReader();
				reader.onload = function(event){
					$('#' + img_div).attr('src', event.target.result);
				}

				reader.readAsDataURL(file1);
			}
		});

		$(document).on('click', '.view-order-btn', function(e){
			var id = $(this).data('so-status');
			var so = $(this).data('so');
			if (so) {
				$.ajax({
					type:"GET",
					url:"/admin/erp_sales_order_status/" + so,
					success:function(response){
						$(id).text(response.status);
						$(id).addClass(response.badge);
					}
				});
			}
		});
	});
</script>
@endsection