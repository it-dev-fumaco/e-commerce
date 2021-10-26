@extends('emails.template', [
    'namePage' => 'Forgot Password'
])

@section('content')
<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">
          Password Reset Request
        </td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
            We have received your request to reset your password. Please click the link below to complete the reset:
        </td>
     </tr>
     <tr>
        <td class="button borderbottom" style="padding: 6% 5% 6% 5%; text-align: left;">
            <a href="{{ route('password.reset', $token) }}" style="background: #e05443; padding: 2% 5%;">Reset my Password</a>
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
@endsection