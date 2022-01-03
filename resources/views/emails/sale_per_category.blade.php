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
            Hi {{ $customer_name }},<br/><br/>
            Enjoy these huge discounts on selected categories.<br/>
            <table border="0" style="width: 100%">
                <tr>
                    <th style="width: 40%; padding: 5%; text-align: center">Category</th>
                    <th style="width: 20%; padding: 5%; text-align: center">Discount</th>
                </tr>
                @foreach ($categories as $category)
                    <tr>
                        <td style="text-align: left;">{{ $category['category_name'] }}</td>
                        <td style="white-space: nowrap !important; text-align: center">
                            @if ($category['discount_type'] == 'By Percentage')
                                <span style="border-radius: 8px; background-color: #FF0000; padding: 3px; color: #fff; font-weight: 700">{{ $category['discount_rate'] }}% OFF</span>
                            @else
                                <span style="border-radius: 8px; background-color: #FF0000; padding: 3px; color: #fff; font-weight: 700">₱ {{ number_format($category['discount_rate'], 2) }} OFF</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
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