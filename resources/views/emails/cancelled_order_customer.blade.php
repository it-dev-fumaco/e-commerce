

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
        <td class="h2" style="padding: 5% 0 0 5%;">Cancelled Order Notification</td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 5%;">
			Hi {{ $order_details->order_name . ' ' . $order_details->order_lastname }},<br><br>
			Your order has been <b>CANCELLED</b>. Here are the details of what you ordered:<br><br>
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
						<td style="padding: 8px;">{!! $item['item_name'] !!}</td>
						<td class="text-center" style="padding: 8px; white-space: nowrap">{{ $item['qty'] }}</td>
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
</table>
@endsection