@extends('emails.template', [
    'namePage' => 'Password Success'
])

@section('content')
  <table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">
          Your password has been changed.
        </td>
      </tr>
      <tr>
        <td class="bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
            The password for your FUMACO account <b>{{ $username }}</b> was changed.
        </td>
      </tr>
      <tr>
        <td class="button borderbottom" style="padding: 6% 5% 6% 5%; text-align: left;">
            <a href="{{ route('login') }}" style="background: #e05443; padding: 2% 5%; white-space: nowrap">Login</a>
        </td>
      </tr>
      <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection