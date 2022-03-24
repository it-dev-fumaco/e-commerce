

@extends('emails.template', [
    'namePage' => 'Order Success'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">Order Placed</td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 5%;">
			Hi {{ $order_details->order_name . ' ' . $order_details->order_lastname }},<br><br>
			<strong>Order No. :</strong> {{ $order_details->order_number }}<br><br>
			Your order has been placed, to process your order please settle your payment thru bank deposit. You may upload your proof of payment/bank deposit slip by clicking <a href="{{ route('upload_deposit_slip', ['token' => $order_details->deposit_slip_token]) }}">here</a><br><br>
			<div style="text-align: center;" class="button">
				<a href="{{ route('upload_deposit_slip', ['token' => $order_details->deposit_slip_token]) }}" style="background: #e05443; padding: 2% 5%;">Upload Now</a>
			</div>
			<br><br>
			<div style="background-color: rgba(207, 244, 252, 0.8); width: 100%; padding: 10px; color: #055160">
				<p>Payment Method: <b>Bank Deposit</b><br>
				Status: <b>Pending for Payment</b></p>
			</div>
			<p>You may send your payments on any of the following account(s):</p>
			@foreach ($bank_accounts as $account)
				<div style="border: 1px solid #DFDFDF; padding: 10px; margin: 5px; width: 30%; display: inline-block">
					<p><b>{{ $account->bank_name }}</b>
					<br/>Account Name: <b>{{ $account->account_name }}</b>
					<br/>Account No.: <b>{{ $account->account_number }}</b></p>
				</div>
			@endforeach
        </td>
     </tr>
     <tr>
        <td class="bodycopy" style="padding: 3% 5%;">
			<table border="0" style="width: 100%; border-collapse: collapse;">
				@php
					$sum_discount = collect($items)->sum('discount');
					$colspan = ($sum_discount > 0) ? 5 : 4;
				@endphp
				<thead>
					<tr style="font-size: 0.9rem; background-color: #e5e7e9;">
						<th class="text-left" colspan="2" style="width: 47%;padding: 5px;">Item Description</th>
						<th class="text-center" style="width: 15%;padding: 5px;">Qty</th>
						@if ($sum_discount > 0)
						<th class="text-center" style="width: 12%;padding: 5px;">Discount(%)</th>
						@endif
						<th class="text-center" style="width: 13%;padding: 5px;">Price</th>
						<th class="text-center" style="width: 13%;padding: 5px;">Amount</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($items as $item)
					@php
					$src = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
					@endphp
					<tr style="font-size: 0.8rem;">
						<td class="text-center" style="padding: 3px;">
							<img src="{{ asset($src) }}" class="img-responsive" alt="" width="50" height="50">
						</td>
						<td style="padding: 8px;">{{ $item['item_name'] }}</td>
						<td class="text-center" style="padding: 8px;">{{ $item['qty'] }}</td>
						@if ($sum_discount > 0)
						<td class="text-center" style="padding: 8px;">{{ $item['discount'] . '%' }}</td>
						@endif
						<td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
						<td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
					 </tr>
					@empty
					<tr>
						<td colspan="6" class="text-center text-muted">No items found.</td>
					</tr>
					@endforelse
				</tbody>
				<tfoot>
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">Subtotal</td>
						<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$order_details->order_subtotal), 2) }}</td>
					</tr>
					@if ($order_details->voucher_code)
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order_details->voucher_code }}</span></td>
						<td class="pb-1 pt-1" style="padding: 6px;">- ₱ {{ number_format(str_replace(",","",$order_details->discount_amount), 2) }}</td>
					</tr>
					@endif
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">{{ $order_details->order_shipping }}</td>
						<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}</td>
					</tr>
					<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
						<td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Grand Total</b></td>
						<td class="pb-1 pt-1" style="padding: 8px; white-space: nowrap !important"><b>₱ {{ number_format(str_replace(",","",($order_details->order_shipping_amount + $order_details->order_subtotal)), 2) }}</b></td>
					</tr>
					<tr style="font-size: 0.9rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Amount Paid</b></td>
						<td class="pb-1 pt-1" style="padding: 8px; white-space: nowrap !important"><b>₱ {{ number_format(str_replace(",","",($order_details->amount_paid)), 2) }}</b></td>
					</tr>
				</tfoot>
			</table>
        </td>
     </tr>
     <tr>
    <td class="innerpadding bodycopy">
        If you did not initiate this request or if you have any questions, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection