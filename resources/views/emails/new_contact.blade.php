@extends('emails.template', [
    'namePage' => 'New Contact'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h2" style="padding: 5% 0 0 5%;">New Contact Notification</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
      <strong>Name :</strong> {{ $new_contact['name'] }}<br><br>
      <strong>Email Address :</strong> {{ $new_contact['email'] }}<br><br>
      <strong>Phone :</strong> {{ $new_contact['phone'] }}<br><br>
      <strong>Subject :</strong> {{ $new_contact['subject'] }}<br><br>
      <strong>Message :</strong> {{ $new_contact['message'] }}<br><br>
    </td>
  </tr>
     <tr>
    <td class="innerpadding borderbottom">
    <!--  <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/wide.png" width="100%" border="0" alt="" />-->
    </td>
  </tr>
</table>
@endsection