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
	
	<main style="background-color:#ffffff;" class="products-head">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<br><br>
					<center><h3>List of Transaction</h3></center>
					<br><br>
				</div>
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<table class="table">
						<col style="width: 20%;">
						<col style="width: 15%;">
						<col style="width: 15%;">
						<col style="width: 35%;">
						<col style="width: 15%;">
						<thead>
							<tr>
								<th class="text-center">Date</th>
								<th class="text-center">Transaction ID</th>
								<th class="text-center">Details</th>
								<th class="text-center">Description</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($orders as $order)
							<tr>
								<td class="text-center align-middle">{{ \Carbon\Carbon::parse($order->track_date)->format('M-d-Y g:i:A')}}</td>
								<td class="text-center align-middle">{{ $order->track_code }}</td>
								<td class="text-center align-middle">
									<a href="/myorder/{{ $order->track_code }}">{{ $order->track_item }}</a>
								</td>
								<td>{{ $order->track_description }}</td>
								<td class="text-center align-middle">{{ $order->track_status }}</td>
							</tr>
							@empty	 
							<tr>
								<td class="text-center p-3" colspan="5">No transactions found.</td>
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
	</main>
@endsection

@section('script')

@endsection
