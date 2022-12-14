@extends('backend.layout', [
	'namePage' => 'Orders',
	'activePage' => 'order_cancel'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>List of Cancelled Orders</h1>
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
								<form action="/admin/order/cancelled/" method="get">
									<div class="row">
										<div class="col-md-3">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="search" aria-describedby="button-addon2" placeholder="Order ID" value="{{ (request()->get('search')) ? request()->get('search') : '' }}">
											</div>
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
											<th>Date Cancelled</th>
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
											{{-- <td><span class="badge badge-danger">{{ $order['status'] }}</span></td> --}}
											<td>{{ $order['date_cancelled'] }}</td>
											<td>
												<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#order-{{ $order['cust_id'] }}">View Orders</button>
												<div class="modal fade" id="order-{{ $order['cust_id'] }}" role="dialog">
													<div class="modal-dialog modal-xl" style="min-width: 70%;">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">ORDER NO. {{ $order['order_no'] }}</h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-md-8">
																		<p><strong>Customer Name : </strong> {{ $order['first_name'] . " " . $order['last_name'] }}</p>
																	</div>
																	<div class="col-md-4">
																		{{-- <p><strong>Est. Delivery Date : </strong> {{ $order['estimated_delivery_date'] }}</p> --}}
																		
																		<p><strong>Date Cancelled : </strong> {{ $order['date_cancelled'] }}
																		</p>
																	</div>
																	<div class="col-md-4">
																		<p>
																			<strong>Order ID : </strong> {{ $order['order_no'] }} <br>
																			<strong>Payment ID : </strong> {{ $order['payment_id'] }}<br>
																			<strong>Payment Method : </strong> {{ $order['payment_method'] }}<br>
																			<strong>Order Date : </strong> {{ $order['date'] }} <br>
																			<strong>Status : </strong> <span class="badge badge-secondary" style="font-size: 1rem;">{{ $order['status'] }}</span>
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

																								<a data-toggle="modal" data-target="#image-{{ $order['order_no'].'-'.$item['item_code'] }}">
																									<picture>
																										<source srcset="{{ asset(explode('.', $src)[0].'.webp') }}" type="image/webp">
																										<source srcset="{{ asset($src) }}" type="image/jpeg">
																										<img class="img-thumbnail w-100" src="{{ asset($src) }}" loading='lazy'/>
																									</picture>
																								</a>
																								
																								<!-- Modal -->
																								<div class="modal fade" id="image-{{ $order['order_no'].'-'.$item['item_code'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
																							<div class="col-11">{{ $item['item_name'] }}</div>
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
																			<dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['subtotal']), 2) }}</dd>
																			@if ($order['shipping_discount'])
																				@php
																					$shipping_discount = $order['shipping_discount'];
																					switch ($shipping_discount->discount_type) {
																						case 'Fixed Amount':
																							$shipping_discount_amount = $shipping_discount->discount_rate;
																							break;
																						case 'By Percentage':
																							$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $order['subtotal'];
																							$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
																							break;
																						default:
																							$shipping_discount_amount = 0;
																							break;
																					}
																				@endphp
																				@if ($shipping_discount_amount < $order['subtotal'])
																					<dt class="col-sm-10 text-right">{{ $shipping_discount->sale_name }}</dt>
																					<dd class="col-sm-2 text-right">₱ {{ number_format($shipping_discount_amount, 2) }}</dd>
																				@endif
																			@endif
																			@if ($order['voucher_code'])
																				@php
																					$voucher_discount_amount = 0;
																					$voucher_details = $order['voucher_details'];
																					if($voucher_details){
																						switch ($voucher_details->discount_type) {
																							case 'Fixed Amount':
																								$voucher_discount_amount = $voucher_details->discount_rate;
																								break;
																							case 'By Percentage':
																								$voucher_discount_amount = ($voucher_details->discount_rate / 100) * $order['subtotal'];
																								break;
																							default:
																								$voucher_discount_amount = 0;
																								break;
																						}
																					}
																				@endphp
																				<dt class="col-sm-10 text-right">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span></dt>
																				<dd class="col-sm-2 text-right">₱ {{ number_format($voucher_discount_amount, 2) }}</dd>
																			@endif
																			<dt class="col-sm-10 text-right">{{ $order['shipping_name'] }}</dt>
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


<div class="modal-footer">





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
	.modal{
		background: rgba(0, 0, 0, .7);
	}
</style>
@endsection