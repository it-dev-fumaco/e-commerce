@extends('emails.template', [
'namePage' => 'Test E-mail'
])

@section('content')
<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">
            Hello User,
        </td>
    </tr>
    <tr>
        <td style="padding: 5% 0 0 5%;">
            This is a test email to ensure the mail system is working properly. Please disregard this message and no
            action is required.
        </td>
    </tr>

    <tr>
        <td class="h2 bodycopy borderbottom" style="padding: 3% 5% 5% 5%;">
            FUMACO ITD
        </td>
    </tr>

    <tr>
        <td class="innerpadding bodycopy">
            If you have any issues or concerns, please contact us at <a href="mailto:it@fumaco.com">it@fumaco.com</a>
        </td>
    </tr>
</table>
@endsection