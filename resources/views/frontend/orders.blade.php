@extends('frontend.layout', [
    'namePage' => 'My Orders',
    'activePage' => 'myorders'
])

@section('content')
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
	</style>

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
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<br><br>
					<center><h3>Order History</h3></center>
					<br><br>
				</div>
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<table class="table">
						{{-- <col style="width: 20%;">
						<col style="width: 15%;">
						<col style="width: 15%;">
						<col style="width: 35%;">
						<col style="width: 15%;"> --}}
						<thead>
							<tr>
								<th class="text-center">Order Number</th>
								<th class="text-center">Date</th>
								<th class="text-center">Details</th>
								<th class="text-center">Est. Delivery Date</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($orders_arr as $order)
							<tr>
								<td class="text-center align-middle">{{ $order['order_number'] }}</td>
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
																{{-- <td>{{ $item['discount'] }}</td>
																<td>₱ {{ number_format($item['orig_price'], 2) }}</td> --}}
																@php
																	$orig_price = number_format($item['orig_price'], 2);
																	$price = number_format($item['price'], 2);
																@endphp
																<td>
																	@if($item['discount'] > 0)
																		<p><span style="text-decoration: line-through;">₱ {{ $orig_price }}</span> <b>{{ $price }}</b></p>
																	@else
																		<p>₱ {{ $price }}</p>
																	@endif
																</td>
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
								<td class="text-center align-middle">{{ $order['edd'] }}</td>
								@php
									if($order['status'] == "Order Placed"){
										$badge = '#ffc107';
									}else if($order['status'] == "Cancelled"){
										$badge = '#6c757d';
									}else if($order['status'] == "Delivered"){
										$badge = '#28a745';
									}else{
										$badge = 'primary';
									}
								@endphp
								<td class="text-center align-middle"><span class="badge text-dark" style="background-color: {{ $badge }}; font-size: 0.9rem;">{{ $order['status'] }}</span></td>
							</tr>
							@empty
								<tr>
									<td class="text-center p-3" colspan="4">No transactions found.</td>
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
		<br/>&nbsp;
	</main>
@endsection
