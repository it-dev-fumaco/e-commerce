@extends('backend.layout', [
	'namePage' => 'Dashboard',
	'activePage' => 'order_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>List Order Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Main</a></li>
                                <li class="breadcrumb-item active">List Order Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">List Customers</h3>
                                </div>
                                <div class="card-body">
                                    <form action="/admin/order/order_lists/" method="get">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="search" aria-describedby="button-addon2" placeholder="Order ID">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" name="order_status">
                                                    <option selected disabled value="">Order Status</option>
                                                    <option value="Order Placed">Order Placed</option>
                                                    <option value="Order Received">Order Received</option>
                                                    <option value="Ready for Delivery">Ready for Delivery</option>
                                                    <option value="Delivered">Delivered</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-success" type="submit" id="button-addon2">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <table id="example2" data-pagination="true" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order Date</th>
                                                <th>Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders_arr as $order)
                                                <tr>
                                                    <td>{{ $order['date'] }}</td>
                                                    <td>{{ $order['order_no'] }}</td>
                                                    <td>{{ $order['first_name'] .' '. $order['last_name'] }}</td>
                                                    <td>{{ $order['email'] }}</td>
                                                    <td>{{ $order['status'] }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#order-{{ $order['cust_id'] }}">View Orders</button>

                                                        <div class="modal fade" id="order-{{ $order['cust_id'] }}" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">ORDER NUMBER : {{ $order['order_no'] }}</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
        
                                                                    <div class="modal-body">
                                                                        <p><strong>Customer Name : </strong> {{ ($order['bill_contact_person']) ? $order['bill_contact_person'] : $order['first_name'] . " " . $order['last_name'] }}</p>
        
                                                                        <p><strong>Customer Information Address : </strong>{{ $order['bill_address1'] . " " . $order['bill_address2'] . ", " . $order['bill_province'] . " " . $order['bill_city'] . " " . $order['bill_brgy'] . ' ' .  $order['bill_country'] . ' ' . $order['bill_postal'] }}
        
                                                                        </p>
        
                                                                        <p><strong>Customer Shipping Address : </strong>{{ $order['ship_address1'] . " " . $order['ship_address2'] . ", " . $order['ship_province'] . " " . $order['ship_city'] . " " . $order['ship_brgy'] . ' ' .  $order['ship_country'] . ' ' . $order['ship_postal'] }}
                                                                        </p>
        
                                                                        <p><strong>Date Order : </strong> {{ $order['date'] }}</p>
        
                                                                        <hr>

                                                                        <div class="row">
                                                                            <table class="table">
                                                                                <tr>
                                                                                    <th>ITEM CODE</th>
                                                                                    <th>ITEM</th>
                                                                                    <th>QTY</th>
                                                                                    <th>PRICE</th>
                                                                                    <th>TOTAL PRICE</th>
                                                                                </tr>
                                                                                @foreach ($order['ordered_items'] as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item['item_code'] }}</td>
                                                                                        <td>{{ $item['item_name'] }}</td>
                                                                                        <td>{{ $item['item_qty'] }}</td>
                                                                                        <td>{{ $item['item_price'] }}</td>
                                                                                        <td>{{ $item['item_total'] }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </div>   
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p>SHIPPING FEE</p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p style="float: right;">{{ $order['shipping_amount'] }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
        
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p>TOTAL PRICE</p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p style="float: right;">{{ $order['total_amount'] }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
        
        
                                                                    <div class="modal-footer">
        
                                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tracker-{{ $order['cust_id'] }}">
                                                                            Add Tracker Code
                                                                        </button>

                                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#delivered-{{ $order['cust_id'] }}">
                                                                            Delivered Order
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancel-{{ $order['cust_id'] }}">
                                                                            Cancel Order
                                                                        </button>

                                                                        <div class="modal fade confirm-modal" id="tracker-{{ $order['cust_id'] }}" tabindex="-1" role="dialog" aria-labelledby="tracker-{{ $order['cust_id'] }}" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Add Tracker Code</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                      <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="col">
                                                                                        <form action="" method="post">
                                                                                            <label for="tracker">Tracker Code: </label>
                                                                                            <input type="text" class="form-control" id="tracker" name="tracker" required>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                  <a href="" class="btn btn-primary">YES</a>
                                                                                  <button type="button" class="btn btn-secondary" data-dismiss="cmodal">NO</button>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>
                                                                          
                                                                        <div class="modal fade confirm-modal" id="delivered-{{ $order['cust_id'] }}" tabindex="-1" role="dialog" aria-labelledby="delivered-{{ $order['cust_id'] }}" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Order Delivered</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                      <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Order has been delivered?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                  <a href="" class="btn btn-primary">YES</a>
                                                                                  <button type="button" class="btn btn-secondary" data-dismiss="cmodal">NO</button>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal fade confirm-modal" id="cancel-{{ $order['cust_id'] }}" tabindex="-1" role="dialog" aria-labelledby="cancel-{{ $order['cust_id'] }}" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Cancel Order</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                      <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Order has been cancelled?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                  <a href="" class="btn btn-primary">YES</a>
                                                                                  <button type="button" class="btn btn-secondary" data-dismiss="cmodal">NO</button>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
        
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                
                                                
                                            @empty
                                                <tr><td colspan=7 class="text-center"><b>No Orders</b></td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right mt-4">
                                    {{ $orders->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style>
        .confirm-modal{
            background: rgba(0, 0, 0, .7);
        }
    </style>
@endsection