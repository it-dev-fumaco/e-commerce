@extends('emails.template', [
    'namePage' => 'Order Status'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%;">We've just shipped your order!</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 5%;">
      Hi {{ $order_details->order_name . ' ' . $order_details->order_lastname }},<br><br>
      This is just a quick update to let you know that your order is now on it's way to you. To track your shipment and view it's delivery status, click the link below.
      <br><br>
      <strong>Order No. :</strong> {{ $order_details->order_number }}<br>
    </td>
  </tr>
	<tr>
        <td class="borderbottom" style="padding: 6% 5% 3% 5%; text-align: center;">
          <div class="button">
            <a href="{{ route('track_order', ['id' => $order_details->order_number]) }}" style="background: #e05443; padding: 2% 5%;">Track my Order</a>
          </div>
          <br>
          <small>or</small>
          <br>
          <a href="{{ route('website') }}">Visit our store</a>
        </td>
     </tr>
     <tr>
      <td class="bodycopy" style="padding: 3% 5%;">
    <table border="0" style="width: 100%; border-collapse: collapse; border-color: #85929e;">
      <thead>
        <tr style="font-size: 0.8rem; background-color: #e5e7e9;">
          <th class="text-left" colspan="2" style="width: 50%;padding: 5px;" colspan="2">SUMMARY</th>
          <th class="text-left" style="width: 50%;padding: 5px;">SHIPPING ADDRESS</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 20%;"><strong>Order No. :</strong></td>
          <td style="width: 30%;">{{ $order_details->order_number }}</td>
          <td rowspan="2">{{ $order_details->order_ship_address1 . ' ' . $order_details->order_ship_address2 . ', ' . $order_details->order_ship_brgy . ', ' . $order_details->order_ship_city .', ' . $order_details->order_ship_prov . ', ' . $order_details->order_ship_country .', ' . $order_details->order_ship_postal }}</td>
        </tr>
        <tr>
          <td style="width: 20%;"><strong>Order Date :</strong></td>
          <td style="width: 30%;">{{ date('M-d-Y', strtotime($order_details->order_date)) }}</td>
        </tr>
      </tbody>
    </table>
      </td>
     </tr>
     <tr>
      <td class="bodycopy" style="padding: 3% 5%;">
    <table border="0" style="width: 100%;">
      @php
        $sum_discount = collect($items)->sum('discount');
        $colspan = ($sum_discount > 0) ? 5 : 4;
      @endphp
      <thead>
        <tr style="font-size: 0.8rem; background-color: #e5e7e9;">
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
          <td class="text-right" style="text-align: right; padding: 8px;">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
          <td class="text-right" style="text-align: right; padding: 8px;">₱ {{ number_format(str_replace(",","",$item['amount']), 2) }}</td>
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
          <td class="pb-1 pt-1" style="padding: 6px;">₱ {{ number_format(str_replace(",","",$order_details->order_subtotal), 2) }}</td>
        </tr>
        @if ($order_details->voucher_code)
					<tr style="font-size: 0.8rem; text-align: right;">
						<td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['order_details']->voucher_code }}</span></td>
						<td class="pb-1 pt-1" style="padding: 6px;">- ₱ {{ number_format(str_replace(",","",$order_details->discount_amount), 2) }}</td>
					</tr>
					@endif
        <tr style="font-size: 0.8rem; text-align: right;">
          <td class="pb-1 pt-1" style="padding: 6px;" colspan="{{ $colspan }}">{{ $order_details->order_shipping }}</td>
          <td class="pb-1 pt-1" style="padding: 6px;">₱ {{ number_format(str_replace(",","",$order_details->order_shipping_amount), 2) }}</td>
        </tr>
        <tr style="font-size: 0.9rem; text-align: right; border-top: 2px solid;">
          <td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Grand Total</b></td>
          <td class="pb-1 pt-1" style="padding: 8px;"><b>₱ {{ number_format(str_replace(",","",($order_details->order_shipping_amount + ($order_details->order_subtotal - $order_details->discount_amount))), 2) }}</b></td>
        </tr>
        <tr style="font-size: 0.9rem; text-align: right;">
          <td class="pb-1 pt-1" style="padding: 8px;" colspan="{{ $colspan }}"><b>Amount Paid</b></td>
          <td class="pb-1 pt-1" style="padding: 8px;"><b>₱ {{ number_format(str_replace(",","",($order_details->amount_paid)), 2) }}</b></td>
        </tr>
      </tfoot>
    </table>
      </td>
   </tr>
     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection