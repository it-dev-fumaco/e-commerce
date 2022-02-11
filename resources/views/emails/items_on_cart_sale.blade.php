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
            @php
                $src = ($image) ? '/storage/item_images/'. $item_code.'/gallery/preview/'. $image : '/storage/no-photo-available.png';
            @endphp
            Hi {{ $customer_name }},<br/><br/>
            There is an item in your {{ $type }} that is on sale.<br/><br/>
            <div class="img-container">
                <img src="{{ $src }}" width="100%">
            </div><br/><br/>
            Get <strong>{{ $item_details }}</strong> with <strong>{{ $percentage }}% OFF</strong>.<br/><br/>
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