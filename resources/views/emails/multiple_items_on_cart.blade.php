@extends('emails.template', [
    'namePage' => 'Items on Cart Sale'
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
            There are items in your {{ $type }} that are on sale.<br/>
            <table border="0" style="width: 100%">
                <tr>
                    <th colspan=2 style="width: 40%; padding: 5%; text-align: center">Item Description</th>
                    <th style="width: 20%; padding: 5%; text-align: center">Price</th>
                </tr>
                @foreach ($items as $item)
                    @php
                        $src = ($item['image']) ? '/storage/item_images/'. $item['item_code'].'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
                    @endphp
                    <tr>
                        <td class="text-center" style="padding: 3px;">
                            <img src="{{ asset($src) }}" class="img-responsive" alt="" width="50" height="50">
                        </td>
                        <td style="text-align: left;">{{ $item['name'] }}</td>
                        <td style="white-space: nowrap !important; text-align: center">
                            <s style="color: #B3B3B3">₱ {{ number_format(str_replace(",","",$item['original_price']), 2) }}</s>
                            @if ($item['discount_type'] == 'By Percentage')
                                <span style="border-radius: 8px; background-color: #FF0000; padding: 3px; color: #fff; font-weight: 700">{{ $item['discount_rate'] }}% OFF</span>
                            @else
                                <span style="border-radius: 8px; background-color: #FF0000; padding: 3px; color: #fff; font-weight: 700">₱ {{ number_format($item['discount_rate'], 2) }} OFF</span>
                            @endif
                            <strong>₱ {{ number_format(str_replace(",","",$item['discounted_price']), 2) }}</strong>
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