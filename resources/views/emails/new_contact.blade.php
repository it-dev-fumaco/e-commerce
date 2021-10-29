@extends('emails.template', [
    'namePage' => 'New Contact'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>

@if($client)
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
@else
<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
    Hi {{ $new_contact['name'] }},<br><br>
    Thank you for reaching out!<br><br>
    We received your message and will get back to you as soon as possible.<br><br>
    We look forward to chatting soon!<br><br><br>
    Customer Service<br>
    FUMACO Inc.<br>
    </td>
 </tr>

     <tr>
    <td class="innerpadding borderbottom">
    <!--  <img class="fix" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/wide.png" width="100%" border="0" alt="" />-->
    </td>
  </tr>
  <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at support@fumaco.com
    </td>
  </tr>
</table>

@endif
@endsection