<table class="table table-hover table-bordered" style="font-size: 11pt;">
    <thead>
        <tr>
            <th class="text-center">Order Date</th>
            <th class="text-center">Order ID</th>
            <th class="text-center">Customer Name</th>
            <th class="text-center">Est. Delivery Date</th>
            <th class="text-center">Shipping Method</th>
            <th class="text-center">Payment Method</th>
            <th class="text-center">Grand Total</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    @forelse ($orders_arr as $order)
    @php
        if($order['order_status'] == "Order Placed"){
            $badge = '#ffc107;';
        }else if($order['order_status'] == "Order Delivered" or $order['order_status'] == 'Order Completed'){
            $badge = '#fd6300;';
        }else if($order['order_status'] == "Out for Delivery" or $order['order_status'] == 'Ready for Pickup'){
            $badge = '#28a745;';
        }else{
            $badge = '#007bff;';
        }
    @endphp
    <tr>
        <td class="text-center">{{ date('M d, Y - h:i A', strtotime($order['date_ordered'])) }}</td>
        <td class="text-center">{{ $order['order_number'] }}</td>
        <td class="text-center">{{ $order['ordered_by'] }}</td>
        <td class="text-center">{{ $order['estimated_delivery_date'] }}</td>
        <td class="text-center">{{ $order['shipping_method'] }}</td>
        <td class="text-center">{{ $order['payment_method'] }}</td>
        <td class="text-center">₱ {{ number_format($order['grand_total'], 2) }}</td>
        <td class="text-center"><span class="badge" style="background-color: {{ $badge }}; font-size: 11pt;">{{ $order['order_status'] }}</span></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-primary view-order" data-id="{{ $order['id'] }}"><i class="fas fa-eye"></i></button>
        </td>
    </tr>
    @empty
    <tr>
        <td class="text-center" colspan="9">No Order(s)</td>
    </tr>
    @endforelse
</table>

<div class="float-right" id="current-orders-paginate">
    {{ $order_history->links('pagination::bootstrap-4') }}
</div>