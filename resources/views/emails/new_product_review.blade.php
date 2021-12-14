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
    <td class="h2" style="padding: 5% 0 0 5%;">New Product Review Notification</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
      <strong>Item Code :</strong> {{ $data['item_code'] }}<br><br>
      <strong>User Email :</strong> {{ $data['user_email'] }}<br><br>
      <strong>Rating :</strong> {{ number_format($data['rating'], 1) }}<br><br>
      <strong>Message :</strong> {{ $data['message'] }}<br><br>
    </td>
  </tr>
</table>
@endsection