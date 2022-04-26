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
							<center><h3 class="carousel-header-font">TRACK ORDER</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#ffffff; min-height: 550px;" class="products-head">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 mx-auto {{ $order_details ? 'd-none' : '' }}" >
					@if(session()->has('error'))
						<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
							{!! session()->get('error') !!}
						</div>
					@endif
					<br><br>
					<center><h3>Order Tracking</h3></center>
					<br>
					<center><h4>Please enter your order reference number</h4></center>
					<br>
					<center>
						<div class="form-group col-md-6 d-inline-block m-2">
							<label class="sr-only" for="email">Code:</label>
							<input type="text" class="order-number form-control m-1" id="text" placeholder="Enter Code" name="id" value="{{ request()->get('id') }}" required style="width: 100%;">
						</div>
						<br/>
						<div class="form-group d-inline-block p-0" style="padding: 0; margin: 0">
							<button class="search-btn btn btn-primary" style="color: #fff; background-color: #1a6ea9 !important; border-color: #1a6ea9 !important; border-radius: 0rem; margin: 0;">Search</button>
						</div>
					</center>
				</div>
				@if($track_order_details and $order_details)
				<div class="col-lg-10 col-xl-8 mx-auto">
					<div class="row mb-2">
						<div class="col-md-6 mt-4">Order No. : <b>{{ $order_details->order_number }}</b>
							@if($order_details->order_status == "Cancelled")
								<span class="badge" style="background-color: #DC3545; font-size: 0.9rem;">{{ $order_details->order_status }}</span>
							@endif
						</div>
						@if($order_details->order_shipping != 'Store Pickup')
							<div class="col-md-6 mt-4 track-order-eta" style="text-align: right;">Estimated Delivery Date : <br class="d-lg-none"/><b>{{ $order_details->estimated_delivery_date }}</b></div>
						@else
							<div class="col-md-6 mt-4 track-order-eta" style="text-align: right;">Pickup Date : <br class="d-lg-none"/><b>{{ $order_details->pickup_date }}</b></div>
						@endif
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-xl-10 mx-auto" style="margin-bottom: 100px !important">
						<div class="card-body">
							<div class="card-body p-1">
								<div class="row">
									{{-- Desktop Tracker --}}
									<div class="track-container d-none d-lg-block">
										<div class="track">
											<div class="step active">
												<span class="icon inactive"><i class="fa fa-check d-none"></i></span>
												<span class="text status-text">Order Placed</span>
												<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $order_details->created_at ? date('M d, Y h:i A', strtotime($order_details->created_at)) : null }}</span>
											</div>
											@if ($order_details->order_payment_method == 'Bank Deposit')
												@php
													$order_tracker_payment = collect($track_order_details)->groupBy('track_payment_status');
												@endphp
												@foreach ($payment_statuses as $s => $status)
													@php
														$status_date_update = isset($order_tracker_payment[$status->status]) ? $order_tracker_payment[$status->status][0]->track_date_update : null;
														$date = $status_date_update ? date('M d, Y h:i A', strtotime($status_date_update)) : null;

														$step_status = 'active';
														$payment_icon_display = 'inactive';
														$payment_status_description = null;
														$payment_status_step = null;
														if($order_details->order_status == 'Order Placed'){
															$step_status = isset($order_tracker_payment[$status->status]) ? 'active' : null;
															$payment_icon_display = $payment_status_sequence != $s + 1 ? 'inactive' : null;
															$payment_status_description = $payment_status_sequence >= $s + 1 ? null : 'd-none';
															$payment_status_step = $payment_status_sequence < $s + 1 ? 'text-muted' : null;
														}

														$payment_status_icon = null;
														if($status->status == 'Pending for Payment'){
															$payment_status_icon = "fa-upload";
														}else if($status->status == 'Payment For Confirmation'){
															$payment_status_icon = "fa-hourglass";
														}
													@endphp
													<div class="step {{ $step_status }}">
														<span class="icon {{ $payment_icon_display }}"><i class="fas {{ $payment_status_icon }} {{ $payment_status_sequence == $s + 1 ? null : 'd-none' }}"></i></span>
														<span class="text status-text {{ $payment_status_step }}">{{ $status->status }}</span>
														<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
														<span class="text status-text {{ $payment_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $status->status_description }}</span>
													</div>
												@endforeach
											@endif
											@php
												$order_status_tracker = collect($track_order_details)->groupBy('track_status');
											@endphp
											@foreach ($order_status as $key => $name)
												@php
													$status_date_update = isset($order_status_tracker[$name->status]) ? $order_status_tracker[$name->status][0]->track_date_update : null;
													$date = $status_date_update ? date('M d, Y h:i A', strtotime($status_date_update)) : null;
													
													$icon = '';
													if($name->status == "Order Confirmed"){
														$icon = 'user';
													}else if($name->status == "Out for Delivery" or $name->status == "Ready for Pickup" ){
														$icon = 'truck';
													}else if($name->status == "Order Delivered" or $name->status == "Order Completed"){
														$icon = 'shopping-bag';
													}

 													$order_status_description = null;
 													$order_status_step = null;
													if($key + 1 > $status_sequence){
 														$order_status_description = 'd-none';
 														$order_status_step = 'text-muted';
													}
												@endphp
												<div class="step {{ collect($track_order_details)->contains('track_status', $name->status) ? 'active' : '' }}">
													<span class="icon {{ $status_sequence != $key + 1 ? 'inactive' : '' }}"><i class="fa fa-{{ $icon }} {{ $status_sequence != $key + 1 ? 'd-none' : '' }}"></i></span>
													<span class="text status-text {{ $order_status_step }}">{{ $name->status }}</span>
													<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
													<span class="text status-text {{ $order_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $name->status_description }}</span>
												</div>
											@endforeach
										</div>
										<br><br>
									</div>
									{{-- Desktop Tracker --}}
									
									<div class="container mt-5">
										<table class="table" style="border-top: 1px solid #000">
											<thead>
												<tr class="tr-font">
													<th colspan=2 class="text-center">ITEM DESCRIPTION</th>
													<th class="text-center">QTY</th>
													<th class="text-center">PRICE</th>
													<th class="text-center">TOTAL</th>
												</tr>
											</thead>
											<tbody>
												@forelse ($items as $item)
												<tr style="font-size: 11pt;">
													<td class="text-center">
														<img src="{{ asset('/storage/item_images/'. $item['item_code'] .'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="50" height="50">
													</td>
													<td>{{ $item['item_name'] }}</td>
													<td class="text-center">{{ $item['quantity'] }}</td>
													<td class="text-center" style="white-space: nowrap !important;">{{ '₱ ' . number_format($item['price'], 2) }}</td>
													<td class="text-center" style="white-space: nowrap !important;">{{ '₱ ' . number_format($item['amount'], 2) }}</td>
												</tr>
												@empty
												<tr>
													<td colspan="6" class="text-center">No items found.</td>
												</tr>
												@endforelse
												<tr style="border-bottom: rgba(0,0,0,0) !important; font-size: 10pt;">
													<td colspan="4" class="table-text p-1" style="text-align: right;">Subtotal : </td>
													<td class="table-text p-1" style="text-align: right; white-space: nowrap !important">₱ {{ number_format($order_details->order_subtotal, 2) }}</td>
												</tr>
												@if ($order_details->voucher_code)
												<tr style="border-bottom: rgba(0,0,0,0) !important; font-size: 10pt;">
													<td colspan="4" class="table-text p-1" style="text-align: right;">Discount :
														<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
														</td>
													<td class="table-text p-1" style="text-align: right; white-space: nowrap !important">- ₱ {{ number_format($order_details->discount_amount, 2) }}</td>
												</tr>
												@endif 
												
												<tr style="border-bottom: rgba(0,0,0,0) !important; font-size: 10pt;">
													<td colspan="4" class="table-text p-1" style="text-align: right;">{{ $order_details->order_shipping }} : </td>
													<td class="table-text p-1" style="text-align: right; white-space: nowrap !important">
														@if ($order_details->order_shipping_amount > 0)
														₱ {{ number_format($order_details->order_shipping_amount, 2) }}
														@else
														FREE
														@endif
													</td>
												</tr>
												<tr style="border-bottom: rgba(0,0,0,0) !important; font-size: 11pt; font-weight: 700 !important">
													@php
														$grand_total = $order_details->order_shipping_amount + ($order_details->order_subtotal - $order_details->discount_amount);
													@endphp
													<td colspan="4" class="table-text p-1" style="text-align: right;">Grand Total : </td>
													<td class="table-text p-1" style="text-align: right; white-space: nowrap !important">₱ {{ number_format($grand_total, 2) }}</td>
												</tr>
												<tr style="border-bottom: rgba(0,0,0,0) !important; font-size: 11pt;">
													<td colspan="4" class="table-text p-1" style="text-align: right;">Payment Method : </td>
													<td class="table-text p-1" style="text-align: right; white-space: nowrap !important">{{ $order_details->order_payment_method }}</td>
												</tr>
											</tbody>
										</table>
									</div>
									{{-- Mobile/Tablet Tracker --}}
									<div class="container-fluid d-block d-lg-none">
										<ul class="list-group vertical-steps">
											<li class="fa-ul list-group-item completed">
												<span class="fa-li" style="margin-left: 15px; margin-top: -10px">
													<i class="fas fa-circle" style="color: #008CFF !important; font-size: 12px !important"></i>
												</span>
												<span style="margin-top: -6px">Order Placed</span>
												<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">
													{{ $order_details->created_at ? date('M d, Y h:i A', strtotime($order_details->created_at)) : null }}
												</span>
											</li>
											@if ($order_details->order_payment_method == 'Bank Deposit')
												@foreach ($payment_statuses as $s => $status)
													@php
														$payment_status_icon = null;
														if($status->status == 'Pending for Payment'){
															$payment_status_icon = "fa-upload";
														}else if($status->status == 'Payment For Confirmation'){
															$payment_status_icon = "fa-hourglass";
														}
									
														$payment_status = isset($order_tracker_payment[$status->status]) ? 'active' : null;
														$payment_status_date = isset($order_tracker_payment[$status->status]) ? $order_tracker_payment[$status->status][0]->track_date : null;
														$payment_status_display_date = $payment_status_date ? date('M d, Y h:i A', strtotime($payment_status_date)) : null;
														$payment_status_icon_container = 'inactive';
														$payment_status_step = 'completed';
														$payment_status_description = null;
														$payment_step_status = null;
														if($order_details->order_status == 'Order Placed'){
															$payment_status_icon_container = $payment_status_sequence != $s + 1 ? 'inactive' : null;
															$payment_status_step = $payment_status_sequence > $s + 1 ? 'completed' : null;
															$payment_status_description = $payment_status_sequence >= $s + 1 ? null : 'd-none';
															$payment_step_status = $payment_status_sequence < $s + 1 ? 'text-muted' : null;
														}
									
														$payment_step_color = null;
														if($payment_status_sequence < $s + 1){
															$payment_step_color = '#ece5dd';
														}else if($payment_status_sequence > $s + 1){
															$payment_step_color = '#008CFF';
														}
													@endphp
													<li class="fa-ul list-group-item {{ $payment_status_step }}">
														@if ($order_details->order_status == 'Order Placed')
															<span class="fa-li {{ $payment_status_sequence == $s + 1 ? 'active-icon' : null }}" style="margin-left: 15px;">
																@if($payment_status_sequence == $s + 1)
																	<i class="fas {{ $payment_status_icon }}" style="font-size: 16px !important"></i>
																@else
																	<i class="fas fa-circle" style="color: {{ $payment_step_color }} !important; font-size: 12px !important"></i>
																@endif
															</span>
														@else
															<span class="fa-li" style="margin-left: 15px;">
																<i class="fas fa-circle" style="color: #008CFF !important; font-size: 12px !important"></i>
															</span>
														@endif
														<span class="{{ $payment_step_status }}">{{ $status->status }}</span>
														<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $payment_status_display_date }}</span>
														<span class="text status-text {{ $payment_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $status->status_description }}</span>
													</li>
												@endforeach
											@endif
											@foreach ($order_status as $key => $name)
												@php
													$order_status = isset($order_status_tracker[$name->status]) ? 'active' : null;
													$status_date_update = isset($order_status_tracker[$name->status]) ? $order_status_tracker[$name->status][0]->track_date : null;
													$date = $status_date_update ? date('M d, Y H:i A', strtotime($status_date_update)) : null;
													$icon_display = null;
													if($status_sequence > $key + 1){
														$icon_display = 'completed';
													}else if($status_sequence == $key + 1){
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
													if($status_sequence < $key + 1){
														$order_step_color = '#ece5dd';
													}else if($status_sequence > $key + 1){
														$order_step_color = '#008CFF';
													}
									
													$order_status_description = null;
 													$order_status_step = null;
													if($key + 1 > $status_sequence){
 														$order_status_description = 'd-none';
 														$order_status_step = 'text-muted';
													}
												@endphp
												<li class="fa-ul list-group-item {{ $icon_display }}" style=" {{ $loop->last ? 'margin-top: -10px' : null }}">
													<span class="fa-li {{ $status_sequence == $key + 1 ? 'active-icon' : null }}" style="margin-left: 15px;">
														@if ($status_sequence == $key + 1)
															<i class="fa fa-{{ $icon }}" style="font-size: 16px !important"></i>
														@else
															<i class="fas fa-circle" style="color: {{ $order_step_color }} !important; font-size: 12px !important"></i>
														@endif
													</span>
													<span class="{{ $order_status_step }}">{{ $name->status }}</span>
													<span class="text status-text" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $date }}</span>
													<span class="text status-text {{ $order_status_description }}" style="font-size: 9pt; color: #a39f9f !important; font-style: italic !important">{{ $name->status_description }}</span>
												</li>
											@endforeach
										</ul>  
									</div>
									{{-- Mobile/Tablet Tracker --}}
								</div>
							</div>
						</div>
					</div>
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
	.fa-ul{
		min-height: 60px;
	}
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

.fa, .fas{
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
	.track-container{
		min-height: 120px;
	}

	.tr-font{
		font-size: 16px;
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
		.tr-font{
			font-size: 10pt !important;
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
		.tr-font{
			font-size: 10pt !important;
		}
    }
	@media (max-width: 1199.98px) {
		.track-container{
			min-height: 200px
		}
		.tr-font{
			font-size: 10pt !important;
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

@section('script')
	<script>
		$(document).ready(function(){
			$('.search-btn').click(function(){
				var order_number = $('.order-number').val();
				window.location.href = "/track_order/" + order_number;
			});
		});
	</script>
@endsection
