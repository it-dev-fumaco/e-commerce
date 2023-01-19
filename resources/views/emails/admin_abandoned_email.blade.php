

@extends('emails.template', [
    'namePage' => 'Abandoned Cart'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>


<table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td class="h2" style="padding: 5% 0 0 5%;">Abandoned Cart Notification</td>
     </tr>
     <tr>
        <td class="bodycopy" style="padding: 3% 5%; width: 100% !important">
            <span>{{ $name }}</span><br>
            <span>{{ $email }}</span><br>
            <span>Contact Number: {{ (substr($contact_number, 0, 2) == 63 ? '+' : null).$contact_number }}</span><br>
            <span><b>Last Transaction Page: {{ $last_transaction_page }}</b></span><br>
            <span><b>Last Transaction Date: {{ $last_transaction_date }}</b></span>
            <br><br>
            <table border="0" style="width: 100% !important; border-collapse: collapse;">
                <thead>
                    <tr style="font-size: 0.9rem; background-color: #e5e7e9;">
                        <th class="text-left" colspan="2" style="width: 70%;padding: 5px;">Item Description</th>
                        <th class="text-center" style="width: 10%;padding: 5px;">Qty</th>
                        <th class="text-center" style="width: 10%;padding: 5px;">Price</th>
                        <th class="text-center" style="width: 10%;padding: 5px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    @php
                    @endphp
                    <tr style="font-size: 0.8rem;">
                        <td class="text-center" style="padding: 3px;">
                            <img src="{{ asset($item['image']) }}" class="img-responsive" alt="" width="50" height="50">
                        </td>
                        <td style="padding: 8px;">
                            <span class="d-des">
                                {!! $item['item_description'] !!}
                            </span>
                        </td>
                        <td class="text-center" style="padding: 8px; white-space: nowrap">{{ $item['qty'] }}</td>
                        <td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">
                            @if ($item['is_discounted'])
                                <span>{{ $item['discounted_price'] }}</span>
                            @else
                                <span>{{ '₱ ' . number_format($item['default_price'], 2) }}</span>
                            @endif
                        </td>
                        <td class="text-right" style="text-align: right; padding: 8px; white-space: nowrap !important">
                            {{ '₱ ' . number_format($item['price'] * $item['qty'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No items found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=4 style="text-align: right !important; padding-right: 10px;">Total Amount</td>
                        <td>{{ '₱ ' . number_format(collect($items)->sum('price'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </td>
     </tr>
</table>
@endsection
