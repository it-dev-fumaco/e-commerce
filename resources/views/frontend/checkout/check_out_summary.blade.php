@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
	'activePage' => 'checkout_customer_form'
])

@section('content')
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{asset('/assets/site-img/header3-sm.png')}}"alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 14px !important;">
				<li class="breadcrumb-item"><a href="/checkout/review_order" style="color: #000000 !important; text-decoration: none;">Review Your Orders</a></li>
				<li class="breadcrumb-item"><a href="{{ url()->previous() }}" style="color: #000000 !important; text-decoration: underline;">Billing / Shipping Address</a></li>
				<li class="breadcrumb-item active"><a href="#" style="color: #c1bdbd !important; text-decoration: none;">Place Order</a></li>
			</ol>
		</nav>  
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container marketing">
		  	<br>
			<div class="row">
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
						@php
							$shipping_fname = $summary_arr[0]['address'][0]['xfname'];
							$shipping_lname = $summary_arr[0]['address'][0]['xlname'];
							$shipping_address1 = $summary_arr[0]['address'][0]['xshippadd1'];
							$shipping_address2 = $summary_arr[0]['address'][0]['xshippadd2'];
							$shipping_province = $summary_arr[0]['address'][0]['xshiprov'];
							$shipping_city = $summary_arr[0]['address'][0]['xshipcity'];
							$shipping_brgy = $summary_arr[0]['address'][0]['xshipbrgy'];
							$shipping_postal = $summary_arr[0]['address'][0]['xshippostalcode'];
							$shipping_country = $summary_arr[0]['address'][0]['xshipcountry'];
							$shipping_address_type = $summary_arr[0]['address'][0]['xshiptype'];
							$shipping_mobile = $summary_arr[0]['ship_mobile'];

							if($summary_arr[0]['same_address'] == 1){
								$checkbox = 'd-block';
								$col = "12";
								$ship_text = " & Billing";
							}else{
								$checkbox = "d-none";
								$col = "6";
								$ship_text = '';
							}
						@endphp
						<div class="card-body he1x" style="padding-bottom: 0px !important;"><strong>Your Order ID : # {{ $summary_arr[0]['address'][0]['order_tracker_code'] }}</strong>
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Your Customer Name :  {{ $summary_arr[0]['address'][0]['xfname']." ".$summary_arr[0]['address'][0]['xlname'] }}
						</div>
						<div class="card-body he1x" style="padding-bottom: 0px !important;">Your Customer Email Address :  {{ $summary_arr[0]['address'][0]['xemail'] }}
						<div class="card-body he1x" style="padding-bottom: 0px !important;">
							<div class="accordion" id="accordionExample">
								<div class="row">

									<div class="col-md-{{ $col }} d-flex align-items-stretch">
										<div class="card" style="width: 100%">
											<div class="card-header" id="headingOne">
												<h2 class="mb-0">
													<div class="row">
														<div class="col-md-6">
															<button class="btn btn-link he1x" type="button" data-toggle="collapse" data-target="" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; color:#2c2c2d;">
																<b>Shipping{{ $ship_text }} Address</b>
															</button>
														</div>
														<div class="col-md-6" style="text-align: right;">
															@if (Auth::check())
																<a href="/myprofile/address" style="font-size: 14px; width:100%; text-decoration: none;">UPDATE YOUR ADDRESS</a>
															@endif
														</div>
													</div>
												</h2>
											</div>
											
											<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
												<div class="card-body">
													<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $shipping_fname. " " .$shipping_lname }}</div>

													<div class="card-body he1x" style="padding-bottom: 0px !important;">Address Type :  {{ $shipping_address_type }}</div>

													<div class="card-body he1x" style="padding-bottom: 0px !important;">
														{{ $shipping_address1." ".$shipping_address2.", ".$shipping_brgy.", ".$shipping_city.", ".$shipping_province.", ".$shipping_country." ".$shipping_postal }}
													</div>
													<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Number :  {{ $shipping_mobile }}<br/>&nbsp;</div>

													<div class="form-check {{ $checkbox }} ">
														<input class="form-check-input" type="checkbox"  checked disabled>
														<label class="form-check-label" class="formslabelfnt">Billing address is the same as above</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									@if ($summary_arr[0]['same_address'] == 0)
										<div class="col-md-6 d-flex align-items-stretch">
											<div class="card" style="width: 100%">
												<div class="card-header" id="headingOne1">
													<h2 class="mb-0">
														<div class="row">
															<div class="col-md-6">
																<button class="btn btn-link he1x" type="button" data-toggle="collapse" data-target="" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; color:#2c2c2d;">
																	<b>Billing Address</b>
																</button>
															</div>
															<div class="col-md-6" style="text-align: right;">
																@if (Auth::check())
																	<a href="/myprofile/address" style="font-size: 14px; width:100%; text-decoration: none;">UPDATE YOUR ADDRESS</a>
																@endif
															</div>
														</div>
													</h2>
												</div>
			
												<div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1" data-parent="#accordionExample">
													<div class="card-body">
														<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Person :  {{ $summary_arr[0]['address'][0]['xcontact_person'] }}</div>

														<div class="card-body he1x" style="padding-bottom: 0px !important;">Address Type :  {{ $summary_arr[0]['address'][0]['xaddresstype'] }}</div>

														<div class="card-body he1x" style="padding-bottom: 0px !important;">
															{{ $summary_arr[0]['address'][0]['xadd1']." ".$summary_arr[0]['address'][0]['xadd2'].", ".$summary_arr[0]['address'][0]['xbrgy'].", ".$summary_arr[0]['address'][0]['xcity'].", ".$summary_arr[0]['address'][0]['xprov'].", ".$summary_arr[0]['address'][0]['xcountry']." ".$summary_arr[0]['address'][0]['xpostal'] }}
														</div>
			
														<div class="card-body he1x" style="padding-bottom: 0px !important;">Contact Number :  {{ $summary_arr[0]['bill_mobile'] }}<br/>&nbsp;</div>
													</div>
												</div>
											</div>
										</div>
									@endif
									

									
								</div>
								<br/>
								<div class="card col-md-12">
									<div class="card-header" id="headingTwo">
										<h2 class="mb-0">
											<button class="btn btn-link he1x collapsed" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration: none; color:#2c2c2d;">
												<i class="fas fa-chevron-down"></i>&nbsp;&nbsp;Products
											</button>
										</h2>
									</div>
	
									<div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
										<div class="card-body">
											<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
												<div class="d-flex justify-content-between align-items-center">
													<table class="table">
														<tr>
															<th>Products</th>
															<th>Total</th>
														</tr>
														@foreach($orders_arr as $orders)
															<tr>
																<td>{{ $orders['item_name'] }}</td>
																<td>{{ ($orders['item_qty'] * $orders['item_price'] ) }}</td>
															</tr>
														@endforeach
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
								<br/>
								<table class="table">
									<tr>
										<td style="width: 90%;">Subtotal<br/></td>
										<td>{{ $summary_arr[0]['subtotal'] }}</td>
									</tr>
									<tr>
										<td style="width: 90%;">Shipping<br/></td>
										<td>{{ $summary_arr[0]['shipping'] }}</td>
									</tr>
									<tr style="color:#CBA04F !important;">
										<td style="width: 90%;">Total<br/></td>
										<td>{{ $summary_arr[0]['grand_total'] }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<br>
					@php
						$password = 'q5m12345';
						$serviceid = 'Q5M';
						$merchantapprovalurl = $summary_arr[0]['base_url']."/cart-success";
						$merchantunapprovalurl = $summary_arr[0]['base_url']."/checkout_summary";

						$string = $password . $serviceid .  $summary_arr[0]['address'][0]['order_tracker_code'] . $summary_arr[0]['base_url']."/cart" . $merchantapprovalurl . $merchantunapprovalurl . $summary_arr[0]['subtotal'] . "PHP" . $summary_arr[0]['address'][0]['order_ip'] . "600";

						$hash = hash('sha256', $string);	
					@endphp
					<center>
						<form action="https://pay.e-ghl.com/IPGSG/Payment.aspx" method="post" name="adminForm" enctype="multipart/form-data">
							@csrf
							<div style="display: none">
								<input name="TransactionType" value="SALE">
								<input name="PymtMethod" value="ANY">
								<input name="ServiceID" value="Q5M">
								<input name="PaymentID" value="{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="OrderNumber" value="{{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="PaymentDesc" value="Fumaco Online Sale / Tracker Code : {{ $summary_arr[0]['address'][0]['order_tracker_code'] }}">
								<input name="MerchantReturnURL" value="{{ $summary_arr[0]['base_url']."/cart" }}">
								<input name="MerchantApprovalURL" value="{{ $merchantapprovalurl }}">
								<input name="MerchantUnApprovalURL" value="{{ $merchantunapprovalurl }}">
								<input name="Amount" value="{{ $summary_arr[0]['subtotal'] }}">
								<input name="CurrencyCode" value="PHP">
								<input name="CustIP" value="{{ $summary_arr[0]['address'][0]['order_ip'] }}">
								<input name="CustName" value="{{ $summary_arr[0]['address'][0]['xfname']. " " .$summary_arr[0]['address'][0]['xlname'] }}">
								<input name="CustEmail" value="{{ $summary_arr[0]['address'][0]['xemail'] }}">
								<input name="CustPhone" value="{{ $summary_arr[0]['ship_mobile'] }}">
								<input name="PageTimeout" value="600">
								<input name="HashValue" value="{{ $hash }}">
								<input name="order_id" value="FUMAC02020">
								<input name="orderpayment_id" value="FUMAC02020">
								<input name="orderpayment_type" value="payment_eghl">
								<input name="task" value="confirmPayment">
							</div>
							@php
								if(Auth::check()){
									$back_btn = "/checkout/review_order";
								}else{
									$back_btn = "/checkout/billing";
								}
							@endphp
							<a href="{{ $back_btn }}" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #777575 !important; border-color: #777575 !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BACK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>

							<input class="btn btn-lg btn-outline-primary" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROCEED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" type="submit">
						</form>
					</center>
					<br>
				</div>
			</div>
		</div>
	</main>

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
	
	
		.he1x {
	
			font-weight: 500 !important;
			font-size: 15px !important;
	
		}
	
	
		.he2x {
	
			font-weight: 200 !important;
			font-size: 12px !important;
	
		}
	
	
	
		.he2x2 {
	
			font-weight: 200 !important;
			font-size: 14px !important;
	
		}
	
	
		.he2x2x {
	
			font-weight: 200 !important;
			font-size: 10px !important;
	
		}
	
	
		.he3x1x {
	
			font-weight: 200 !important;
			font-size: 14px !important;
	
		}
	
	
		.btmp {
			margin-bottom: 15px !important;
	
		}
	
		.tbls {
			padding-bottom: 25px !important;
			padding-top: 25px !important;
		}
		
		.flex-1 {
			flex: 1 !important;
			display: flex !important;
		}
	</style>
@endsection