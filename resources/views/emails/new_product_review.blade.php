@extends('emails.template', [
    'namePage' => 'New Product Review'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%;" colspan="2">New Product Review Notification</td>
  </tr>
  <tr>
    <td style="width: 30%;">
      @php
      $img = ($data['image']) ? '/storage/item_images/'. $data['item_code'] .'/gallery/original/'. $data['image'] : '/storage/no-photo-available.png';
      $img_webp = ($data['image']) ? '/storage/item_images/'. $data['item_code'] .'/gallery/original/'. explode(".", $data['image'])[0] .'.webp' : '/storage/no-photo-available.png';
    @endphp

      <picture>
        <source srcset="{{ asset($img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
        <source srcset="{{ asset($img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
        <img src="{{ asset($img) }}" alt="{{ Str::slug(explode(".", $data['image'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
      </picture>
    </td>
    <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
      <strong>Item Code :</strong> {{ $data['item_code'] }}<br><br>
      <strong>Description :</strong> {{ $data['item_description'] }}<br><br>
      <strong>User Email :</strong> {{ $data['user_email'] }}<br><br>
      <strong>Rating :</strong>  
      @for ($i = 0; $i < 5; $i++)
      @if (number_format($data['rating'], 1) <= $i)
      <span class="fa fa-star starcolorgrey"></span>
      @else
      <span class="fa fa-star" style="color: #FFD600;"></span>
      @endif
      @endfor 
      ({{ number_format($data['rating'], 1) }})<br><br>
      <strong>Message :</strong> {{ $data['message'] }}<br><br>
    </td>
  </tr>
</table>
@endsection