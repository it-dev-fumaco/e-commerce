@extends('emails.template', [
    'namePage' => 'Abandoned Cart'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>

<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%; text-align: center">You're almost there!</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 5%; text-align: left">
        Hi {{ $customer_name }},<br/><br/>
        We noticed you left something in your cart. Would you like to complete your purchase?
    </td>
  </tr>
	<tr>
        <td class="borderbottom" style="padding: 6% 5% 3% 5%; text-align: center;">
            <table border="0" style="width: 100%;">
                <tr>
                    <th colspan=2 style="width: 40%; padding: 5%">Item Description</th>
                    <th style="width: 20%; padding: 5%">Price</th>
                    <th style="width: 20%; padding: 5%">Qty</th>
                    <th style="width: 20%; padding: 5%">Total</th>
                </tr>
                @foreach ($cart_details as $item)
                    @php
                        $src = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
                    @endphp
                    <tr>
                        <td class="text-center" style="padding: 3px;">
                            <img src="{{ asset($src) }}" class="img-responsive" alt="" width="50" height="50">
                        </td>
                        <td style="text-align: left;">{{ $item['name'] }}</td>
                        <td style="white-space: nowrap !important; text-align: left">₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
                        <td style="white-space: nowrap !important; ">{{ $item['qty'] }}</td>
                        <td style="white-space: nowrap !important; text-align: left">₱ {{ number_format(str_replace(",","",$item['total_price_per_item']), 2) }}</td>
                    </tr>
                @endforeach
            </table>
        </td>
     </tr>
     <tr>
       <td>
        <div class="button" style="padding-top: 30px;">
          <a href="{{ route('cart') }}" style="background: #e05443; padding: 2% 5%;">Check your cart</a>
        </div>
       </td>
     </tr>
     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection