@extends('emails.template', [
    'namePage' => 'New Subscriber'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%;">Thank your for subscribing to Fumaco.com</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
        Thank you for subscribing to our newsletter. You'll receive updates straight to your inbox!
    </td>
  </tr>
  <tr>
    <td class="borderbottom" style="padding: 6% 5% 3% 5%; text-align: center;">
      <div class="button">
        <a href="{{ route('website') }}" style="background: #e05443; padding: 2% 5%;">Visit our store</a>
      </div>
    </td>
 </tr>
     <tr>
    <td class="innerpadding borderbottom">
    <!--  <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/wide.png" width="100%" border="0" alt="" />-->
    </td>
  </tr>
</table>
@endsection