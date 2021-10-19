@extends('frontend.layout', [
	'namePage' => 'Track Order',
	'activePage' => 'track_order'
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
							<center><h3 class="carousel-header-font">ORDER STATUS</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 offset-lg-2" style="padding-left: 15%; padding-right: 15%;">
					<br><br>
					<center><h3>Order Tracking</h3></center>
					<br>
					<center><h4>Please enter your order reference number</h4></center>
					<br>
					<center>
						<form action="/track_order" class="form-inline" method="GET">
							<div class="form-group">
								<label class="sr-only" for="email">Code:</label>
								<input type="text" class="form-control" id="text" placeholder="Enter Code"  name="id" value="{{ request()->get('id') }}" required>
							</div>
							<br>
							<input type="submit" class="btn btn-success" value="Search" style="color: #fff; background-color: #1a6ea9 !important; border-color: #1a6ea9 !important; border-radius: 0rem;">
						</form>
					</center>
				</div>
				@if(count($track_order_details) > 0)
				<div class="col-md-8 offset-md-2">
					<div class="row mb-2">
						<div class="col-md-6 mt-4">Order No. : <b>{{ request()->id }}</b></div>
						<div class="col-md-6 mt-4" style="text-align: right;">Estimated Delivery Date : <b>{{ $order_details->estimated_delivery_date }}</b></div>
					</div>
				</div>
				
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<table class="table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Details</th>
								<th>Description</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($track_order_details as $order)
						<tr>
							<td>{{ $order->track_date }}</td>
							<td><a href="#TrackItemsData" data-toggle="modal">{{ $order->track_item }}</a></td>
							<td>{{ $order->track_description }}</td>
							<td>{{ $order->track_status }}</td>
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
				<div class="modal-dialog" style="max-width: 85%;">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">List of Orders</h4>
						</div>
						<div class="modal-body">
							<table class="table">
								<thead>
									<tr style="font-size: 16px;">
										<th class="text-center">ORDER NUMBER</th>
										<th></th>
										<th class="text-center">ITEM NAME</th>
										<th class="text-center">QTY</th>
										<th class="text-center">PRICE</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($items as $item)
									<tr style="font-size: 11pt;">
										<td class="text-center">{{ $item['order_number'] }}</td>
										<td class="text-center">
											<img src="{{ asset('/storage/item/images/'. $item['item_code'] .'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
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

@section('script')

@endsection
