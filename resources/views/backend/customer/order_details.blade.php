    <div class="modal-header">
        @php
            if($orders_arr['order_status'] == "Order Placed"){
                $badge = '#ffc107;';
            }else if($orders_arr['order_status'] == "Order Delivered" or $orders_arr['order_status'] == 'Order Completed'){
                $badge = '#fd6300;';
            }else if($orders_arr['order_status'] == "Out for Delivery" or $orders_arr['order_status'] == 'Ready for Pickup'){
                $badge = '#28a745;';
            }else{
                $badge = '#007bff;';
            }
        @endphp
        <div class="col-5 text-left">
            <h5 class="modal-title" id="exampleModalLabel">ORDER NO. {{ $orders_arr['order_number'] }}</h5>
        </div>
        <div class="col-6 text-right font-italic">
            <span class="badge badge-info" style="font-size: 11pt;">{{ $orders_arr['shipping_method'] }}</span>&nbsp;
            <span>
               @if ($orders_arr['order_status'] == "Order Delivered" or $orders_arr['order_status'] == 'Order Completed')
               <b>Date Delivered:</b> {{ \Carbon\Carbon::parse($orders_arr['date_delivered'])->format('M. d, Y - h:i A') }}
               @else
               @if ($orders_arr['estimated_delivery_date'])
               <b>Est. Delivery Date:</b> {{ $orders_arr['estimated_delivery_date'] }}
               @else
               <b>Pickup By:</b> {{ date('D, M d, Y', strtotime($orders_arr['pickup_date'])) }}
               @endif
               @endif
            </span>
        </div>
        <button type="button" class="close border" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body text-left">
          <div class="row">
               <div class="col-6">
                    <h4>{{ $orders_arr['ordered_by'] }}</h4>
                    <span>{{ $orders_arr['order_email'] }}</span>
               </div>
          </div>
          <br/>
          <div class="row">
               <div class="col-4">
                    <span><b>Order ID:</b> {{ $orders_arr['order_number'] }}</span><br/>
                    <span><b>Payment ID:</b> {{ $orders_arr['payment_id'] }}</span><br/>
                    <span><b>Payment Method:</b> {{ $orders_arr['payment_method'] }}</span><br/>
                    <span><b>Order Date:</b> {{ \Carbon\Carbon::parse($orders_arr['date_ordered'])->format('M. d, Y - h:i A') }}</span><br/>
                    <span><b>Order Status:</b> <b><span class="badge" style="background-color: {{ $badge }}; font-size: 11pt;">{{ $orders_arr['order_status'] }}</span></b></span>
               </div>
               <div class="col-4">
                    <span><b>Billing Address:</b></span><br/>
                    <span><b>Bill to:</b> {{ $orders_arr['billing_business_name'] ? $orders_arr['billing_business_name'] : $orders_arr['ordered_by'] }}</span>
                    <p>{{ $orders_arr['billing_address'] }} <br />{{ $orders_arr['bill_email'] }}<br/>{{ $orders_arr['bill_contact'] }}</p>
               </div>
               @if ($orders_arr['shipping_method'] == 'Store Pickup')
               <div class="col-4">
                    <span><b>Pickup At:</b></span><br/>
                    <span>{{ $orders_arr['store_location'] }}</span><br/>
                    <span>{{ $orders_arr['store_address'] }}</span><br/>
                    <span><b>Pickup Date:</b> {{ date('D, M d, Y', strtotime($orders_arr['pickup_date'])) }}</span>
               </div>
               @else
               <div class="col-4">
                    <span><b>Shipping Address:</b></span><br/>
                    <span><b>Ship to:</b> {{ $orders_arr['shipping_business_name'] ? $orders_arr['shipping_business_name'] : $orders_arr['ordered_by'] }}</span>
                    <p>{{ $orders_arr['shipping_address'] }}<br/>{{ $orders_arr['email'] }}<br/>{{ $orders_arr['contact'] }}</p>
               </div>
               @endif
          </div>
          <br/>
          <table class="table table-hover table-bordered">
               <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
               </tr>
               @foreach ($orders_arr['ordered_items'] as $item)
               <tr>
                    <td>{{ $item['item_code'] }}</td>
                    <td>{{ $item['item_name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>₱ {{ number_format($item['price'], 2) }}</td>
                    <td>₱ {{ number_format($item['total_price'], 2) }}</td>
               </tr>
               @endforeach
          </table>
          <div class="col-md-8 offset-md-4 mb-4">
               <dl class="row">
                    <dt class="col-sm-10 text-right">Subtotal</dt>
                    <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['subtotal']), 2) }}</dd>
                    @if ($orders_arr['voucher_code'])
                    <dt class="col-sm-10 text-right">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $orders_arr['voucher_code'] }}</span></dt>
                    <dd class="col-sm-2 text-right">- ₱ {{ number_format(str_replace(",","",$orders_arr['discount']), 2) }}</dd>
                    @endif
                    <dt class="col-sm-10 text-right">
                          <span class="badge badge-info" style="font-size: 11pt;">{{ $orders_arr['shipping_method'] }}</span>
                    </dt>
                    <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['shipping']), 2) }}</dd>
                    <dt class="col-sm-10 text-right">Grand Total</dt>
                    <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$orders_arr['grand_total']), 2) }}</dd>
               </dl>
          </div>
    </div>
    <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>