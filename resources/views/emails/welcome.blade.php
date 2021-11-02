

@extends('emails.template', [
    'namePage' => 'Welcome Email'
])

@section('content')
<table border="0" width="50%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">
          Congratulations! Your account has been successfully created!
        </td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
          Thank you for registering at Fumaco.com. Your account details are as follows:
        </td>
     </tr>

     <tr>
        <td class="bodycopy" style="padding: 5% 0 0 5%;">
          Username : {{ $details['username'] }}<br>
          Password : {{ str_repeat("*", strlen($details['password'])) }}
        </td>
     </tr>
     <tr>
        <td class="button borderbottom" style="padding: 6% 5% 6% 5%; text-align: left;">
            <a href="{{ route('website') }}" style="background: #e05443; padding: 2% 5%;">Shop Now!</a>
        </td>
     </tr>

     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection