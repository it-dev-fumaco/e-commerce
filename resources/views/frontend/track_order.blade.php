@extends('frontend.layout', [
	'namePage' => 'Track Order',
	'activePage' => 'track_order'
])

@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
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
				@if($track_order_details and $order_details)
				<div class="col-md-8 offset-md-2">
					<div class="row mb-2">
						<div class="col-md-6 mt-4">Order No. : <b>{{ request()->id }}</b>
							@if($order_details->order_status == "Cancelled")
								<span class="badge" style="background-color: #DC3545; font-size: 0.9rem;">{{ $order_details->order_status }}</span>
							@endif
						</div>
						<div class="col-md-6 mt-4 track-order-eta" style="text-align: right;">Estimated Delivery Date : <br class="d-lg-none d-xl-none"/><b>{{ $order_details->estimated_delivery_date ? $order_details->estimated_delivery_date : "-"}}</b></div>
					</div>
				</div>
				@php
					if($track_order_details->track_status == "Order Placed"){
						$status = 1;
					}else if($track_order_details->track_status == "Order Confirmed"){
						$status = 2;
					}else if($track_order_details->track_status == "Ready for Delivery"){
						$status = 3;
					}else if($track_order_details->track_status == "Out for Delivery" or $track_order_details->track_status == "Ready for Pickup" ){
						$status = 4;
					}else if($track_order_details->track_status == "Delivered"){
						$status = 5;
					}else{
						$status = 0;
					}
				@endphp
				<div class="row">
					<div class="col-md-8 mx-auto" style="margin-bottom: 100px !important">
						<div class="card-body">
							<div class="track">
								<div class="step {{ $status >= 1 ? 'active' : '' }}"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order placed</span> </div>
								<div class="step {{ $status >= 2 ? 'active' : '' }}"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
								@if($order_details->order_shipping == 'Store Pickup')
									<div class="step {{ $status >= 4 ? 'active' : '' }}"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text">Ready for Pickup</span> </div>
								@else
									<div class="step {{ $status >= 4 ? 'active' : '' }}"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text">Out for Delivery</span> </div>
								@endif
								
								<div class="step {{ $status >= 5 ? 'active' : '' }}"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Delivered</span> </div>
							</div>
							<hr> <a href="#TrackItemsData" data-toggle="modal" class="btn btn-warning" data-abc="true">View Order Details</a>
						</div>
					</div>
				</div>

				{{-- <div class="col-lg-10 mx-auto">
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
				</div> --}}
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
							<h4 class="modal-title">Order Details</h4>
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
    height: 7px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 60px;
    margin-top: 50px
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
    background: #FF5722
}

.track .step::before {
    height: 7px;
    position: absolute;
    content: "";
    width: 100%;
    left: 0;
    top: 18px
}

.track .step.active .icon {
    background: #ee5435;
    color: #fff
}

.track .icon {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    position: relative;
    border-radius: 100%;
    background: #ddd
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
</style>
@endsection
