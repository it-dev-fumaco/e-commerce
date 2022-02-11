@extends('emails.template', [
    'namePage' => 'Sale Per Category'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
    .img-container{
        width: 30%;
        margin: 0 auto 0 auto;
    }
</style>

<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%; text-align: center">Enjoy this huge discount</td>
    </tr>
    <tr>
        <td class="bodycopy borderbottom" style="padding: 5%; text-align: left">
            @php
                $discount = null;
                if($discount_type == 'By Percentage'){
                    $discount = $discount_rate.'%';
                }else{
                    $discount = '₱ '.number_format($discount_rate, 2);
                }
            @endphp
            Hi {{ $customer_name }},<br/><br/>
            Don't let this great opportunity pass you by. Save <strong>{{ $discount }} OFF</strong> sitewide and get the products you've always wanted!<br/><br>
            <div class="button">
                <a href="{{ route('login') }}" style="background: #e05443; padding: 2% 5%;">Login</a>
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