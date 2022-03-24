@extends('emails.template', [
    'namePage' => 'Awaiting Confirmation'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">Bank Deposit Slip Notification</td>
     </tr>
     <tr>
        <td class="bodycopy borderbottom" style="padding: 5%;">
			Bank deposit slip for Order Number: {{ $order_details->order_number }} has been uploaded and is waiting for confirmation. <br><br>
            <center>
                <div class="button">
                    <a href="{{ route('confirm_buffer', ['order_number' => $order_details->order_number]) }}" style="background: #e05443; padding: 2% 5%;">View Details</a>
                </div>
            </center>
        </td>
     </tr>
</table>
@endsection
