@extends('emails.template', [
    'namePage' => 'Order Delivered'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>

  <table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%;">Your order has been delivered!</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 5%;">
      Hi {{ $customer_name }},<br><br>
      Your order has been delivered. Track your shipment to see the delivery status.
      <br><br>
      <strong>Order No. :</strong> {{ $id }}<br>
    </td>
  </tr>
	<tr>
        <td class="borderbottom" style="padding: 6% 5% 3% 5%; text-align: center;">
          <div class="button">
            <a href="{{ route('track_order', ['id' => $id]) }}" style="background: #e05443; padding: 2% 5%;">Track my Order</a>
          </div>
          <br>
          <small>or</small>
          <br>
          <a href="{{ route('website') }}">Visit our store</a>
        </td>
     </tr>

     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection