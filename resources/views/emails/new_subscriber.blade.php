@extends('emails.template', [
    'namePage' => 'New Subscriber'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}

    .badge-danger{
        background-color: #e74c3c;
        color: #ffff;
        padding: 2% 3%;
        border-radius: 0.3rem;
        font-size: 0.7rem;
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
    <td class="borderbottom" style="padding: 2% 2% 6% 2%; text-align: center;">
        <h2 class="h2" style="font-size: 1.2rem;">Featured Product(s)</h2>
        <table border="0" style="width: 100%;">
            <tr>
            @foreach ($featured as $item)
                <td style="width: 25%; padding: 0.8%;">
                    <div style="border: 1px solid; border-color:  #d7dbdd ; padding: 2%;">
                        <div style="height: 200px;">
                            @php
                                $img = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
                            @endphp
                            <img src="{{ asset($img) }}" alt="{{ $item['item_code'] }}" style="width: 100% !important; height: 100% !important;">
                        </div>
                        <p style="margin: 1%; color:#0062A5 !important; font-size: 12px !important; font-weight: 500 !important; text-align: left; min-height: 70px;">{{ $item['item_name'] }}</p>
                        <p style="color:#000000 !important;  min-height: 70px; margin: 1%; font-size: 13px; text-align: left;">
                            @if ($item['is_discounted'])
                            ₱ {{ number_format(str_replace(",","",$item['new_price']), 2) }}&nbsp;&nbsp;<small><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$item['orig_price']), 2) }}</s></small>&nbsp;&nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;">{{ $item['discount'] }}% OFF</span>
                            @else
                            ₱ {{ number_format(str_replace(",","",$item['orig_price']), 2) }}<br>&nbsp;
                            @endif
                            </p>
                    </div>
                   
                </td>
                @endforeach
            </tr>
        </table>
        <br><br>
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