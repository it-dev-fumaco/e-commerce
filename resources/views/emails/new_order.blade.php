

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
        <td class="h2" style="padding: 5% 0 0 5%;">New Order Notification</td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 5%;">
			A new order has been placed. Please update the order status to "Order Confirmed" in Admin Page<br><br>
			Order Summary : {{ date("Y-m-d h:i a") }}
			<br>
			<br>
			<strong>Order No. :</strong> {{ $order_details->order_number }}<br><br>
			<strong>Customer :</strong> {{ $order_details->order_name . ' ' . $order_details->order_lastname }}<br><br>
			@if ($order_details->order_shipping == 'Store Pickup')
			<strong>Shipping Method :</strong> {{ $order_details->order_shipping }}<br><br>
			<strong>Store Location :</strong> {!! $order_details->store_location . ' - ' . $store_address !!}<br><br>
			<strong>Pickup by :</strong> {{ \Carbon\Carbon::parse($order_details->pickup_date)->format('D, F d, Y') }}<br>
			@else
			<strong>Shipping Address :</strong> {{ $order_details->order_ship_address1 . ' ' . $order_details->order_ship_address2 . ', ' . $order_details->order_ship_brgy . ', ' . $order_details->order_ship_city .', ' . $order_details->order_ship_prov . ', ' . $order_details->order_ship_country .', ' . $order_details->order_ship_postal }}<br><br>
			<strong>Estimated Delivery Date :</strong> {{ $order_details->estimated_delivery_date }}<br>
			@endif
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
						<th class="text-center" style="width: 12%;padding: 5px;">Discount</th>
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
						<td style="padding: 8px;">
							<span class="d-des">
								{!! $item['item_name'] !!}
							</span>
						</td>
						<td class="text-center" style="padding: 8px; white-space: nowrap">{{ $item['qty'] }}</td>
						@if ($sum_discount > 0)
						<td class="text-center" style="padding: 8px;">
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
						<td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
						<td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
					 </tr>
					 <tr class="d-mob-row" style="font-size: 0.8rem;">
						<td colspan=6>
						  {!! $item['item_name'] !!}
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
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">Payment Method</td>
						<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">{{ $order_details->order_payment_method }}</td>
					</tr>
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">Subtotal</td>
						<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$order_details->order_subtotal), 2) }}</td>
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
						@if ($order_details->order_subtotal > $shipping_discount_amount)
							<tr style="font-size: 0.8rem; text-align: right;">
								<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">
									{{ $shipping_discount->sale_name }}
								</td>
								<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">- ₱ {{ number_format($shipping_discount_amount, 2) }}</td>
							</tr>
						@endif
					@endif
					@if ($order_details->voucher_code)
						@php
							switch ($voucher_details->discount_type) {
								case 'Fixed Amount':
									$voucher_discount_amount = $voucher_details->discount_rate;
									break;
								case 'By Percentage':
									$voucher_discount_amount = ($voucher_details->discount_rate / 100) * $order_details->order_subtotal;
									break;
								default:
									$voucher_discount_amount = 0;
									break;
							}
						@endphp
						<tr style="font-size: 0.8rem; text-align: right;">
							<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">
								Discount&nbsp;<span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833; color: #fff !important;">{{ $order_details->voucher_code }}</span>
							</td>
							<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">- ₱ {{ number_format($voucher_discount_amount, 2) }}</td>
						</tr>
					@endif
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">{{ $order_details->order_shipping }}</td>
						<td class="pb-1 pt-1" style="padding: 6px; white-space: nowrap !important">
							@if ($order_details->order_shipping_amount > 0)
							₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}
							@else
							FREE	
							@endif
						</td>
					</tr>
					@php
						$discount = $order_details->discount_amount;
						$grand_total = ($order_details->order_shipping_amount + $order_details->order_subtotal) - $discount;
					@endphp
					<tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
						<td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Grand Total</b></td>
						<td class="pb-1 pt-1" style="padding: 8px; white-space: nowrap !important"><b>₱ {{ number_format($grand_total, 2) }}</b></td>
					</tr>
					<tr style="font-size: 0.9rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Amount Paid</b></td>
						<td class="pb-1 pt-1" style="padding: 8px; white-space: nowrap !important"><b>₱ {{ number_format($order_details->amount_paid, 2) }}</b></td>
					</tr>
				</tfoot>
			</table>
        </td>
     </tr>
</table>
@endsection
