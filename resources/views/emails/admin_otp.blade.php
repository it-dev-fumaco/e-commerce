

@extends('emails.template', [
    'namePage' => 'Admin OTP'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
     <tr>
        <td class="bodycopy borderbottom" style="padding: 5%; text-align: center">
			Hi,<br><br>
			Please use the verification code below to login in Fumaco Website Admin Page<br><br>
            <h1>{{ $otp }}</h1>
            <br><br>
            Verification code is valid only within 10 mins. For any help, please contact us at it@fumaco.com
        </td>
     </tr>
     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection