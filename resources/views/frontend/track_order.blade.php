@extends('frontend.layout', [
	'namePage' => 'Track Order',
	'activePage' => 'track_order'
])
@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">ORDER STATUS</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#ffffff; min-height: 550px;" class="products-head">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 mx-auto" >
					<br><br>
					<center><h3>Order Tracking</h3></center>
					<br>
					<center><h4>Please enter your order reference number</h4></center>
					<br>
					<center>
						<form action="/track_order" class="form-inline p-0" method="GET">
							<div class="form-group col-md-6 d-inline-block m-2">
								<label class="sr-only" for="email">Code:</label>
								<input type="text" class="form-control m-1" id="text" placeholder="Enter Code"  name="id" value="{{ request()->get('id') }}" required style="width: 100%;">
							</div>
							<br/>
							<div class="form-group d-inline-block p-0" style="padding: 0; margin: 0">
								<input type="submit" class="btn btn-success" value="Search" style="color: #fff; background-color: #1a6ea9 !important; border-color: #1a6ea9 !important; border-radius: 0rem; margin: 0;">

							</div>

						</form>
					</center>
				</div>
				@if(count($track_order_details) > 0)
				<div class="col-md-8 offset-md-2">
					<div class="row mb-2">
						<div class="col-md-6 mt-4">Order No. : <b>{{ request()->id }}</b></div>
						<div class="col-md-6 mt-4 track-order-eta" style="text-align: right;">Estimated Delivery Date : <br class="d-lg-none d-xl-none"/><b>{{ $order_details->estimated_delivery_date }}</b></div>
					</div>
				</div>

				<div class="col-lg-10 mx-auto">
					<table class="table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Details</th>
								<th class="d-none d-sm-table-cell">Description</th>
								<th class="d-none d-sm-table-cell">Status</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($track_order_details as $order)
						<tr>
							@php
								if($order->track_status == "Order Placed"){
									$badge = '#ffc107';
								}else if($order->track_status == "Cancelled"){
									$badge = '#6c757d';
								}else if($order->track_status == "Delivered"){
									$badge = '#fd6300';
								}else if($order->track_status == "Out for Delivery"){
									$badge = '#28a745';
								}else{
									$badge = '#007bff';
								}
							@endphp
							<td>{{ date('M d, Y - h:m: A', strtotime($order->track_date)) }}</td>
							<td><a href="#TrackItemsData" data-toggle="modal">{{ $order->track_item }}</a>
								<br/><span class="d-lg-none d-xl-none">{{ $order->track_description }}</span>
								<br/><span class="badge d-lg-none d-xl-none" style="background-color: {{ $badge }}; font-size: 0.9rem;">{{ $order->track_status }}</span>
							</td>
							<td class="d-none d-sm-table-cell">{{ $order->track_description }}</td>
							<td class="d-none d-sm-table-cell"><span class="badge" style="background-color: {{ $badge }}; font-size: 0.9rem;">{{ $order->track_status }}</span></td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				@else
				@if(request()->get('id'))
				<div class="col-md-6 offset-md-3">
					<div class="alert alert-warning fade show text-center mt-5 p-2" role="alert">
						<h5 class="p-2 m-0">Sorry, transaction code "<b>{{ request()->get('id') }}</b>" not found.</h5>
					</div>
				</div>
				@endif
				@endif
			</div>

			<div id="TrackItemsData" class="modal fade" role="dialog">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">List of Orders</h4>
						</div>
						<div class="modal-body">
							<table class="table">
								<thead>
									<tr style="font-size: 16px;">
										<th></th>
										<th class="text-center">ITEM DESCRIPTION</th>
										<th class="text-center">QTY</th>
										<th class="text-center">PRICE</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($items as $item)
									<tr style="font-size: 11pt;">
										<td class="text-center">
											<img src="{{ asset('/storage/item_images/'. $item['item_code'] .'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
										</td>
										<td>{{ $item['item_name'] }}</td>
										<td class="text-center">{{ $item['quantity'] }}</td>
										<td class="text-center">{{ 'P ' . number_format($item['price'], 2) }}</td>
										<td class="text-center">{{ 'P ' . number_format($item['amount'], 2) }}</td>
									</tr>
									@empty
									<tr>
										<td colspan="6" class="text-center">No items found.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	@media (max-width: 575.98px) {
		.track-order-eta{
			text-align: left !important;
		}
	}
	@media (max-width: 767.98px) {
		.track-order-eta{
			text-align: left !important;
		}
	}
</style>
@endsection
