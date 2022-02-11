

@extends('emails.template', [
    'namePage' => 'Email Verification'
])

@section('content')
  <table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
     <tr>
      <td class="h2" style="padding: 5% 0 0 5%;">
        Request for Email Verification
      </td>
   </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
          Thank you for registering at Fumaco.com. Let's get your email address verified.
        </td>
     </tr>
     <tr>
        <td class="button borderbottom" style="padding: 6% 5% 6% 5%; text-align: left;">
            <a href="{{ route('account.verify', $token) }}" style="background: #e05443; padding: 2% 5%;">Verify Email</a>
        </td>
     </tr>

     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection