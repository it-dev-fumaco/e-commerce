<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('/assets/admin/dist/css/adminlte.min.css') }}">
    <style type="text/css">
        @media print {
            table{
                border-collapse: unset;
            }
        }
    </style>
</head>
<body>
    @php
        if($orders_arr['status'] == 'Order Placed'){
            $badge = 'warning';
        }else if($orders_arr['status'] == 'Out for Delivery' or $orders_arr['status'] == 'Ready for Pickup'){
            $badge = 'success';
        }else if($orders_arr['status'] == 'Cancelled'){
            $badge = 'secondary';
        }else if($orders_arr['status'] == 'Order Confirmed'){
            $badge = 'primary';
        }else{
            $badge = "";
        }
    @endphp
    <div class="row {{ ($orders_arr['status'] == 'Delivered') ? 'd-none' : '' }}">
        <div class="col-6">
            <p class="mt-3 mb-0"><strong>Customer Name : </strong> {{ $orders_arr['first_name'] . " " . $orders_arr['last_name'] }}</p>
            @if($orders_arr['user_email'])
            <p class="mb-0"><strong>Email Address : </strong> {{ $orders_arr['user_email'] }}</p>
            @endif
            <p class="text-muted mb-0"><strong>{{ $orders_arr['order_type'] }} Checkout</strong></p>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-4">
            <p>
                <strong>Order ID : </strong> {{ $orders_arr['order_no'] }} <br>
                <strong>Payment ID : </strong> {{ $orders_arr['payment_id'] }}<br>
                <strong>Payment Method : </strong> {{ $orders_arr['payment_method'] }}<br>
                <strong>Order Date : </strong> {{ $orders_arr['date'] }} <br>
                <strong>Status : </strong> <span class="badge badge-{{ $badge }}" style="font-size: 1rem;">{{ $orders_arr['status'] }}</span>
            </p>
        </div>
        <div class="col-4">
            <p>
                <strong>Billing Address : </strong><br>
                <strong>Bill to :</strong> {{ ($orders_arr['billing_business_name']) ? $orders_arr['billing_business_name'] : $orders_arr['bill_contact_person'] }}<br>
                {!! $orders_arr['bill_address1'] . " " . $orders_arr['bill_address2'] . ", <br>" . $orders_arr['bill_brgy'] . ", " . $orders_arr['bill_city'] . "<br>" . $orders_arr['bill_province'] . ', ' .  $orders_arr['bill_country'] . ' ' . $orders_arr['bill_postal'] !!}<br/>
                {{ $orders_arr['bill_email'] }}<br/>
                {{ $orders_arr['bill_contact'] }}
            </p>
        </div>
        <div class="col-4">
            @if ($orders_arr['shipping_name'] == 'Store Pickup')
            <p>
                <strong>Pickup At : </strong><br>
                {{ ($orders_arr['store']) }}<br>
                {!! $orders_arr['store_address'] !!}<br/>
                <strong>Pickup Date : </strong>
                {{ \Carbon\Carbon::parse($orders_arr['pickup_date'])->format('D, F d, Y') }}
            </p>
            @else
            <p>
                <strong>Shipping Address : </strong><br>
                <strong>Ship to :</strong> {{ ($orders_arr['shipping_business_name']) ? $orders_arr['shipping_business_name'] : $orders_arr['ship_contact_person'] }}<br>
                {!! $orders_arr['ship_address1'] . " " . $orders_arr['ship_address2'] . ", <br>" . $orders_arr['ship_brgy'] . ", " . $orders_arr['ship_city'] . "<br>" . $orders_arr['ship_province'] . ', ' .  $orders_arr['ship_country'] . ' ' . $orders_arr['ship_postal'] !!}<br/>
                {{ $orders_arr['email'] }}<br/>
                {{ $orders_arr['contact'] }}<br/>
                <strong>Estimated Delivery Date: </strong>
                {{ $orders_arr['estimated_delivery_date'] }}
            </p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-striped">
                @php
                    $sum_discount = collect($orders_arr['ordered_items'])->sum('item_discount');
                    $colspan = ($sum_discount > 0) ? 5 : 4;
                @endphp
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10%;">ITEM CODE</th>
                        <th class="text-center" style="width: 50%;">DESCRIPTION</th>
                        <th class="text-center" style="width: 10%;">QTY</th>
                        <th class="text-center" style="width: 10%;">PRICE</th>
                        @if ($sum_discount > 0)
                        <th class="text-center" style="width: 10%;">DISCOUNT(%)</th>
                        @endif
                        <th class="text-center" style="width: 10%;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders_arr['ordered_items'] as $item)
                    <tr>
                        <td class="text-center">{{ $item['item_code'] }}</td>
                        <td>{{ $item['item_name'] }}</td>
                        <td class="text-center">{{ $item['item_qty'] }}</td>
                        <td class="text-right">₱ {{ number_format(str_replace(",","",$item['item_price']), 2) }}</td>
                        @if ($sum_discount > 0)
                        <td class="text-right">{{ $item['item_discount'] . '%' }}</td>
                        @endif
                        <td class="text-right">₱ {{ number_format(str_replace(",","",$item['item_total']), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-4"><br/></div>
        <div class="col-8">
            <dl class="row">
                <dt class="col-10 text-right">Subtotal</dt>
                <dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['subtotal']), 2) }}</dd>
                @if ($orders_arr['voucher_code'])
                <dt class="col-10 text-right">Discount <span class="badge badge-info" style="font-size: 11pt;">{{ $orders_arr['voucher_code'] }}</span></dt>
                <dd class="col-2 text-right">- ₱ {{ number_format(str_replace(",","",$orders_arr['discount_amount']), 2) }}</dd>
                @endif
                <dt class="col-10 text-right">
                    @if ($orders_arr['shipping_name'])
                    <span class="badge badge-info" style="font-size: 11pt;">{{ $orders_arr['shipping_name'] }}</span>
                    @else
                    {{ $orders_arr['shipping_name'] }}
                    @endif
                </dt>
                <dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['shipping_amount']), 2) }}</dd>
                <dt class="col-10 text-right">Grand Total</dt>
                <dd class="col-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['grand_total']), 2) }}</dd>
            </dl>
        </div>

        <script>
            window.addEventListener("load", window.print());
        </script>
</body>
</html>