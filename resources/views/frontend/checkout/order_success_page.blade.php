@extends('frontend.layout', [
	'namePage' => 'Payment Request',
	'activePage' => 'checkout_success'
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
		.he2, .he2x2x {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.he1x {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.he2x {
			font-weight: 200 !important;
			font-size: 12px !important;
		}
		.he2x2 {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.he3x1x {
			font-weight: 100 !important;
			font-size: 14px !important;
		}
		.btmp {
			margin-bottom: 15px !important;
		}
		.tbls {
			padding-bottom: 25px !important;
			padding-top: 25px !important;
		}
		.custom-padding{
			padding-left: 15% !important;
			padding-right: 15% !important;
		}
		.bank-accounts{
			margin-bottom: 0 !important
		}
		@media (max-width: 575.98px) {
			.custom-padding{
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			.bank-accounts{
				margin-bottom: 5px !important
			}
		}
  		@media (max-width: 767.98px) {
			.custom-padding{
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			.bank-accounts{
				margin-bottom: 5px !important
			}
		  }
		@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
			.custom-padding{
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			.bank-accounts{
				margin-bottom: 5px !important
			}
		}

	</style>

	<main style="background-color:#0062A5;">
		<br><br><br>
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="col-lg-12 p-0 custom-padding">
						<center>
							<br><br><br>
							<h1 style="color:#186EA9 !important; letter-spacing: 2px !important;">THANK YOU FOR SHOPPING!</h1>
							<br>
                  		</center>
						<div style="color:#58595A !important;">
							<h6 class="font-weight-bold mt-2">Order #: <b>{{ $order_details->order_number }}</b></h6>
							<p class="mt-3 mb-4">Your order has been placed, to process your order please settle your payment thru bank deposit. Please check your email or SMS for link to upload your proof of payment / deposit slip.</p>
							<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
								Payment Method: <b>{{ $order_details->order_payment_method }}</b><br>
								Status: <b>{{ $order_details->payment_status }}</b>
							</div>
							@if ($order_details->order_payment_method == 'Bank Deposit')
							@if (count($bank_accounts) > 0)
							<p>You may send your payments on any of the following account(s):</p>
							<div class="row mb-4">
								@foreach ($bank_accounts as $account)
								<div class="col-md-4 bank-accounts">
									<div class="card p-3">
										<div>
											@if ($account->show_bank_logo && $account->bank_logo)
											<img src="{{ asset('storage/bank_account_images/' . $account->bank_logo) }}" height="30" style="height: 30px !important;">
											@endif
											<span class="d-inline-block" style="vertical-align: bottom !important;"><b>{{ $account->bank_name }}</b></span>
										</div>
										<span class="d-block">Account Name: <b>{{ $account->account_name }}</b></span>
										<span class="d-block">Account No.: <b>{{ $account->account_number }}</b></span>
									</div>
								</div>
								@endforeach
							</div>
							@endif
							@endif
						</div>
						<p>If you have any questions, please contact us at <a href="mailto:support@fumaco.com">support@fumaco.com</a>.</p>
						<table class="table">
							@php
								$sum_discount = collect($items)->sum('discount');
								$colspan = ($sum_discount > 0) ? 5 : 4;
							@endphp
							<thead>
								<tr style="font-size: 0.9rem;">
									<th class="text-left" colspan="2">Item Description</th>
									<th class="text-center d-none d-sm-table-cell">Qty</th>
									@if ($sum_discount > 0)
									<th class="text-center d-none d-sm-table-cell">Discount</th>
									@endif
									<th class="text-center d-none d-sm-table-cell">Price</th>
									<th class="text-center d-none d-sm-table-cell">Amount</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($items as $item)
								@php
								$src = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
								$webp = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. explode('.', $item['image'])[0].'.webp' : '/storage/no-photo-available.webp';
								@endphp
								<tr style="font-size: 0.8rem;">
									<td class="text-center">
										<picture>
											<source srcset="{{ asset($webp) }}" type="image/webp">
											<source srcset="{{ asset($src) }}" type="image/jpeg">
											<img src="{{ $src }}" class="img-responsive" alt="" width="55" height="55">
										</picture>
									</td>
									<td>
										<!-- for desktop -->
										<div class="d-none d-md-block">
											{{ $item['item_name'] }}
										</div>
										<!-- for desktop -->
										<!-- for mobile -->
										<div class="d-md-none" style="text-align: left !important;">
											<span><b>Qty:</b> {{ $item['qty'] }}</span><br>
											@if ($item['discount'])
											<span><b>Discount:</b>
												@switch($item['discount_type'])
													@case('Fixed Amount')
														₱ {{ number_format(str_replace(",","",$item['discount']), 2) }}
														@break
													@case('By Percentage')
													@case('percentage')
													@case(null)
														{{ $item['discount'] ? $item['discount'] . '%' : '-' }}
														@break
													@default
													-
												@endswitch
											</span>
												<br>
											@endif
											<span style="white-space: nowrap !important"><b>Price:</b> ₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</span><br>
											<span style="white-space: nowrap !important"><b>Amount:</b> ₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</span>
										</div>
										<!-- for mobile -->
									</td>
									<td class="text-center d-none d-sm-table-cell">{{ $item['qty'] }}</td>
									@if ($sum_discount > 0)
									<td class="text-center d-none d-sm-table-cell">
										@switch($item['discount_type'])
											@case('Fixed Amount')
												₱ {{ number_format(str_replace(",","",$item['discount']), 2) }}
												@break
											@case('By Percentage')
											@case('percentage')
											@case(null)
												{{ $item['discount'] ? $item['discount'] . '%' : '-' }}
												@break
											@default
											-
										@endswitch
									</td>
									@endif
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
									<td class="text-right d-none d-sm-table-cell" style="text-align: right; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
								 </tr>
								 <tr class="d-md-none" style="font-size: 0.8rem;">
									<td colspan=6 class="text-justify">
										{{ $item['item_name'] }}
									</td>
								 </tr>
								@empty
								<tr>
									<td colspan="6" class="text-center text-muted">No items found.</td>
								</tr>
								@endforelse
							</tbody>
							<tfoot>
								<tr style="font-size: 0.8rem; text-align: right;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">Subtotal</td>
									<td class="pb-1 pt-1 d-md-none" style="white-space: nowrap !important;">Subtotal</td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">₱ {{ number_format(str_replace(",","", collect($items)->sum('amount')), 2) }}</td>
								</tr>
								@if ($shipping_discount)
								@php
									switch ($shipping_discount->discount_type) {
										case 'Fixed Amount':
											$shipping_discount_amount = $shipping_discount->discount_rate;
											break;
										case 'By Percentage':
											$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $order_details->order_subtotal;
											$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
											break;
										default:
											$shipping_discount_amount = 0;
											break;
									}
								@endphp
									@if ($shipping_discount_amount < $order_details->order_subtotal)
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">{{ $shipping_discount->sale_name }}
											</td>
											<td class="pb-1 pt-1 d-md-none">{{ $shipping_discount->sale_name }}
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format(str_replace(",","",$shipping_discount_amount), 2) }}</td>
										</tr>
									@endif
								@endif
								@if ($order_details->voucher_code || $price_rule)
									@if ($order_details->voucher_code)
										@php
											switch ($voucher_details->discount_type) {
												case 'Fixed Amount':
													$voucher_discount_amount = $order_details->order_subtotal > $voucher_details->discount_rate ? $voucher_details->discount_rate : 0;
													break;
												case 'By Percentage':
													$voucher_discount_amount = ($voucher_details->discount_rate / 100) * $order_details->order_subtotal;
													break;
												default:
													$voucher_discount_amount = 0;
													break;
											}
										@endphp
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
											</td>
											<td class="pb-1 pt-1 d-md-none">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span>
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format($voucher_discount_amount, 2) }}</td>
										</tr>
									@endif
									@if ($price_rule)
										<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
											<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $price_rule['discount_name'] }}</span>
											</td>
											<td class="pb-1 pt-1 d-md-none">
												<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $price_rule['discount_name'] }}</span>
											</td>
											<td class="pb-1 pt-1" style="white-space: nowrap !important;">- ₱ {{ number_format($price_rule['discount_amount'], 2) }}</td>
										</tr>
									@endif
								@endif
								<tr style="font-size: 0.8rem; text-align: right; border-top: 0;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}">{{ $order_details->order_shipping }}</td>
									<td class="pb-1 pt-1 d-md-none" style="white-space: nowrap !important;">{{ $order_details->order_shipping }}</td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;">
										@if ($order_details->order_shipping_amount > 0)
										₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}
										@else
										FREE	
										@endif
									</td>
								</tr>
								<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
									<td class="pb-1 pt-1 d-none d-sm-table-cell" colspan="{{ $colspan }}"><b>Grand Total</b></td>
									<td class="pb-1 pt-1 d-md-none" style="white-space: nowrap !important;"><b>Grand Total</b></td>
									<td class="pb-1 pt-1" style="white-space: nowrap !important;"><b>₱ {{ number_format(str_replace(",","",($order_details->order_shipping_amount + ($order_details->order_subtotal - $order_details->discount_amount))), 2) }}</b></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="col-lg-12 text-center mx-auto"><br/><br/>
					<a href="/" class="btn btn-lg btn-outline-primary" role="button" style="background-color: #313131 !important; border-color: #313131 !important;">RETURN TO HOMEPAGE</a>
					<br>
				</div>
				<div class="col-lg-12">&nbsp;&nbsp;
					<br><br><br>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#0062A5;"></main>

@endsection
