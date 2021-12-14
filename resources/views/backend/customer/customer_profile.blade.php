@extends('backend.layout', [
'namePage' => 'Customer Profile',
'activePage' => 'customers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Customers Profile Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Customers Profile Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary">
                                <div class="card-body">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success fade show" role="alert">
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="alert alert-warning fade show" role="alert">
                                            {{ session()->get('error') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-4">
                                            <h4>{{ $customer->f_name.' '.$customer->f_lname }}</h4>
                                            <p><b>{{ $customer->username }}</b></p>
                                        </div>
                                        <div class="col-4">
                                            <p>Customer Group: <b>{{ $customer->customer_group }}</b></p>
                                            <p>Date Registered: <b>{{ date('M d, Y', strtotime($customer->created_at)) }}</b></p>
                                            <p>Last Login Date: <b>{{ $customer->last_login ? date('M d, Y h:i A', strtotime($customer->last_login)) : '' }}</b></p>
                                            <p>Total Number of Visits: <span class="badge badge-primary">{{ $customer->no_of_visits }}</span></p>
                                        </div>
                                        <div class="col-4">
                                            <form action="/admin/customer/profile/{{ $customer->id }}/change_customer_group" method="post">
                                                @csrf
                                                @php
                                                    $customer_group = array('Personal', 'Business');
                                                @endphp
                                                <select class="form-control" name="customer_group" id="customer_group">
                                                    <option disabled value="">Select Customer Group</option>
                                                    @foreach ($customer_group as $group)
                                                        <option value="{{ $group }}" {{ $group == $customer->customer_group ? 'selected' : '' }}>{{ $group }}</option>
                                                    @endforeach
                                                </select>
                                                <br/>
                                                <div id="pricelist">
                                                    <select class="form-control" name="pricelist">
                                                        <option selected disabled value="">Select Price List</option>
                                                        <option value="99">For Testing</option>
                                                    </select>
                                                    <br/>
                                                </div>
                                                <button type="submit" class="btn btn-primary float-right">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h4>Shipping Address(es)</h4>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th style="width: 15%">Address Type</th>
                                            <th style="width: 10%">Business Name</th>
                                            <th style="width: 10%">TIN</th>
                                            <th style="width: 15%">Contact Person</th>
                                            <th style="width: 10%">Contact Number</th>
                                            <th style="width: 10%">Contact Email</th>
                                            <th style="width: 30%">Address</th>
                                        </tr>
                                        @forelse ($shipping_address as $shipping)
                                            <tr>
                                                <td>
                                                    {{ $shipping->add_type }}&nbsp;<span class="badge badge-primary {{ $shipping->xdefault != 1 ? 'd-none' : '' }}">Default</span>
                                                </td>
                                                <td>{{ $shipping->xbusiness_name }}</td>
                                                <td>{{ $shipping->xtin_no }}</td>
                                                <td>{{ $shipping->xcontactname1.' '.$shipping->xcontactlastname1 }}</td>
                                                <td>{{ $shipping->xcontactnumber1 != 0 ? $shipping->xcontactnumber1 : null }}</td>
                                                <td>{{ $shipping->xcontactemail1 }}</td>
                                                <td>
                                                    @php
                                                        $ship_address2 = str_replace(' ', '', $shipping->xadd2) ? $shipping->xadd2.', ' : null;
                                                    @endphp
                                                    {{ $shipping->xadd1.', '.$ship_address2.$shipping->xbrgy.', '.$shipping->xcity.', '.$shipping->xprov.', '.$shipping->xcountry.' '.$shipping->xpostal }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan=7>No Shipping Address(es)</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                            
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h4>Billing Address(es)</h4>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th style="width: 15%">Address Type</th>
                                            <th style="width: 10%">Business Name</th>
                                            <th style="width: 10%">TIN</th>
                                            <th style="width: 15%">Contact Person</th>
                                            <th style="width: 10%">Contact Number</th>
                                            <th style="width: 10%">Contact Email</th>
                                            <th style="width: 30%">Address</th>
                                        </tr>
                                        @forelse ($billing_address as $billing)
                                            <tr>
                                                <td>
                                                    {{ $billing->add_type }}&nbsp;<span class="badge badge-primary {{ $billing->xdefault != 1 ? 'd-none' : '' }}">Default</span>
                                                </td>
                                                <td>{{ $billing->xbusiness_name }}</td>
                                                <td>{{ $billing->xtin_no }}</td>
                                                <td>{{ $billing->xcontactname1.' '.$billing->xcontactlastname1 }}</td>
                                                <td>{{ $billing->xcontactnumber1 != 0 ? $billing->xcontactnumber1 : null }}</td>
                                                <td>{{ $billing->xcontactemail1 }}</td>
                                                <td>
                                                    @php
                                                        $bill_address2 = str_replace(' ', '', $billing->xadd2) ? $billing->xadd2.', ' : null;
                                                    @endphp
                                                    {{ $billing->xadd1.', '.$bill_address2.$billing->xbrgy.', '.$billing->xcity.', '.$billing->xprov.', '.$billing->xcountry.' '.$billing->xpostal }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan=7>No Billing Address(es)</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                            <br/>
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h4>Item(s) on Cart</h4>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Quantity</th>
                                        </tr>
                                        @forelse ($cart_items as $cart)
                                            <tr>
                                                <td>{{ $cart->item_code }}</td>
                                                <td>{{ $cart->item_description }}</td>
                                                <td>{{ $cart->qty }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan=3>No Item(s) on Cart</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                            <br/>
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h4>Order History</h4>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th>Order Date</th>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Estimated Delivery Date</th>
                                            <th>Shipping Method</th>
                                            <th>Payment Method</th>
                                            <th>Grand Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
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
                                                <td>{{ date('M d, Y - h:i A', strtotime($order['date_ordered'])) }}</td>
                                                <td>{{ $order['order_number'] }}</td>
                                                <td>{{ $order['ordered_by'] }}</td>
                                                <td>{{ $order['estimated_delivery_date'] }}</td>
                                                <td>{{ $order['shipping_method'] }}</td>
                                                <td>{{ $order['payment_method'] }}</td>
                                                <td>₱ {{ number_format($order['grand_total'], 2) }}</td>
                                                <td><span class="badge" style="background-color: {{ $badge }}; font-size: 11pt;">{{ $order['order_status'] }}</span></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#order{{ $order['order_number'] }}Modal">
                                                        View Details
                                                    </button>
                                                      
                                                      <!-- Modal -->
                                                    <div class="modal fade" id="order{{ $order['order_number'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <div class="col-5 text-left">
                                                                        <h5 class="modal-title" id="exampleModalLabel">ORDER NO. {{ $order['order_number'] }}</h5>
                                                                    </div>
                                                                    <div class="col-6 text-right font-italic">
                                                                        <span class="badge badge-info" style="font-size: 11pt;">{{ $order['shipping_method'] }}</span>&nbsp;
                                                                        <span>
                                                                            @if ($order['estimated_delivery_date'])
                                                                                <b>Est. Delivery Date:</b> {{ $order['estimated_delivery_date'] }}
                                                                            @else
                                                                                <b>Pickup By:</b> {{ date('D, M d, Y', strtotime($order['pickup_date'])) }}
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
                                                                            <h4>{{ $order['ordered_by'] }}</h4>
                                                                            <span>{{ $order['order_email'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <br/>
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <span><b>Order ID:</b> {{ $order['order_number'] }}</span><br/>
                                                                            <span><b>Payment ID:</b> {{ $order['payment_id'] }}</span><br/>
                                                                            <span><b>Payment Method:</b> {{ $order['payment_method'] }}</span><br/>
                                                                            <span><b>Order Date:</b> {{ $order['date_ordered'] }}</span><br/>
                                                                            <span><b>Order Status:</b> <b><span class="badge" style="background-color: {{ $badge }}; font-size: 11pt;">{{ $order['order_status'] }}</span></b></span>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <span><b>Billing Address:</b></span><br/>
                                                                            <span><b>Bill to:</b> {{ $order['billing_business_name'] ? $order['billing_business_name'] : $order['ordered_by'] }}</span>
                                                                            <p>{{ $order['billing_address'] }}</p>
                                                                        </div>
                                                                        @if ($order['shipping_method'] == 'Store Pickup')
                                                                            <div class="col-4">
                                                                                <span><b>Pickup At:</b></span><br/>
                                                                                <span>{{ $order['store_location'] }}</span><br/>
                                                                                <span>{{ $order['store_address'] }}</span><br/>
                                                                                <span><b>Pickup Date:</b> {{ date('D, M d, Y', strtotime($order['pickup_date'])) }}</span>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-4">
                                                                                <span><b>Shipping Address:</b></span><br/>
                                                                                <span><b>Ship to:</b> {{ $order['shipping_business_name'] ? $order['shipping_business_name'] : $order['ordered_by'] }}</span>
                                                                                <p>{{ $order['shipping_address'] }}</p>
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
                                                                        @foreach ($order['ordered_items'] as $item)
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
                                                                            <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['subtotal']), 2) }}</dd>
                                                                            @if ($order['voucher_code'])
                                                                            <dt class="col-sm-10 text-right">Discount <span class="text-white" style="border: 1px dotted #ffff; padding: 3px 8px; margin: 2px; font-size: 7pt; background-color:#1c2833;">{{ $order['voucher_code'] }}</span></dt>
                                                                            <dd class="col-sm-2 text-right">- ₱ {{ number_format(str_replace(",","",$order['discount']), 2) }}</dd>
                                                                            @endif
                                                                            <dt class="col-sm-10 text-right">
                                                                                <span class="badge badge-info" style="font-size: 11pt;">{{ $order['shipping_method'] }}</span>
                                                                            </dt>
                                                                            <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['shipping']), 2) }}</dd>
                                                                            <dt class="col-sm-10 text-right">Grand Total</dt>
                                                                            <dd class="col-sm-2 text-right">₱ {{ number_format(str_replace(",","",$order['grand_total']), 2) }}</dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan=9>No Order(s)</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <style>
        .table>tbody>tr:hover {
            background-color: #ffffff;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            customerGroup();

            $('#customer_group').change(function(){
                customerGroup();
            });

            function customerGroup(){
                if($('#customer_group').val() == 'Business'){
                    $('#pricelist').slideDown();
                    $('#pricelist').prop('required', true);
                }else{
                    $('#pricelist').slideUp();
                    $('#pricelist').prop('required', false);
                }
            }
        });
    </script>
@endsection