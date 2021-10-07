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
                                    <table id="example2" data-pagination="true" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Date Update</th>
                                                <th>Tracking Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- $item_data00_fumaco = $data_1['xtempcode'];
                                            $item_data0_fumaco = $data_1['xfname'];
                                            $item_data1_fumaco = $data_1['xlname'];
                
                                            $item_data2_fumaco = $data_1['xadd1'];
                                            $item_data3_fumaco = $data_1['xadd2'];
                
                                            $item_data4_fumaco = $data_1['xprov'];
                                            $item_data5_fumaco = $data_1['xcity'];
                                            $item_data6_fumaco = $data_1['xbrgy'];
                                            $item_data7_fumaco = $data_1['xpostal'];
                                            $item_data8_fumaco = $data_1['xcountry'];
                                            $item_data9_fumaco = $data_1['xaddresstype'];
                
                                            $item_data10_fumaco = $data_1['xemail'];
                                            $item_data11_fumaco = $data_1['xmobile'];
                                            $item_data12_fumaco = $data_1['xcontact'];
                
                                            $item_data13_fumaco = $data_1['xshippadd1'];
                                            $item_data14_fumaco = $data_1['xshippadd2'];
                                            $item_data15_fumaco = $data_1['xshiprov'];
                                            $item_data16_fumaco = $data_1['xshipcity'];
                                            $item_data17_fumaco = $data_1['xshipbrgy'];
                                            $item_data18_fumaco = $data_1['xshippostalcode'];
                                            $item_data19_fumaco = $data_1['xshipcountry'];
                                            $item_data20_fumaco = $data_1['xshiptype'];
                                            $item_data21_fumaco = $data_1['xdateupdate'];
                                            $item_data22_fumaco = $data_1['xstatus'];
                
                                            $item_data23_fumaco = $data_1['order_status'];
                                            $item_data24_fumaco = $data_1['order_tracker_code'];
                                            $item_data25_fumaco = $data_1['order_shipping_type'];
                                           $item_data27_fumaco = $data_1['xlogs'];
                                           $item_data28_fumaco = $data_1['xdateupdate']; --}}

                                            @foreach($orders_arr as $order)
                                                <tr>
                                                    <td>{{ $order['order_no'] }}</td>
                                                    <td>{{ $order['first_name'] }}</td>
                                                    <td>{{ $order['last_name'] }}</td>
                                                    <td>{{ $order['email'] }}</td>
                                                    <td>{{ $order['date'] }}</td>
                                                    <td>{{ $order['order_tracker_code'] }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#order-{{ $order['cust_id'] }}">View Orders</button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="order-{{ $order['cust_id'] }}" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">ORDER NUMBER : {{ $order['order_no'] }}</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <p><strong>Customer Name : </strong> {{ $order['first_name'] . " " . $order['last_name'] }}</p>

                                                                <p><strong>Customer Information Address : </strong> '.$item_data2_fumaco.' '.$item_data3_fumaco.', '.$item_data4_fumaco.' '.$item_data5_fumaco.' '.$item_data6_fumaco.' '.$item_data8_fumaco.' '.$item_data7_fumaco.'</p>

                                                                <p><strong>Customer Shipping Address : </strong> '.$item_data13_fumaco.' '.$item_data14_fumaco.', '.$item_data15_fumaco.' '.$item_data16_fumaco.' '.$item_data17_fumaco.' '.$item_data19_fumaco.' '.$item_data18_fumaco.'</p>

                                                                <p><strong>Date Order : </strong> '.$item_data21_fumaco.'</p>

                                                                <hr>



                                                                <div class="row">
                                                                <div class="col-md-2">
                                                                <strong>ITEM CODE</strong>
                                                                </div>


                                                                <div class="col-md-4">
                                                                    <strong>ITEM</strong>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <strong>QTY</strong>
                                                                </div>


                                                                <div class="col-md-2">
                                                                    <strong>PRICE</strong>
                                                                </div>


                                                                <div class="col-md-2">
                                                                    <strong>TOTAL PRICE</strong>
                                                                </div>


                                                                </div>

                                                                <hr>



                                                                ';

                                                                $A1_sqlx = "SELECT * FROM fumaco_order_items WHERE order_number = '$item_data27_fumaco'";
                                                                $data1_xx = $fumaco_conn ->query($A1_sqlx);

                                                                if ($data1_xx->num_rows > 0) {

                                                                while($data_1x = $data1_xx->fetch_assoc())

                                                                    {


                                                                    $xxxx0 = $data_1x['item_code'];
                                                                    $xxxx1 = $data_1x['item_name'];
                                                                    $xxxx2 = $data_1x['item_qty'];

                                                                    $xxxx4x = $data_1x['item_price'];


                                                                    $xxxx4 = number_format("$xxxx4x",2);


                                                                    $xxxx5x = $data_1x['item_total_price'];

                                                                    $xxxx5 = number_format("$xxxx5x",2);

                                                                    echo '

                                                                    <div class="row">
                                                                    <div class="col-md-2">
                                                                        '.$xxxx0.'

                                                                    </div>


                                                                        <div class="col-md-4">
                                                                        '.$xxxx1.'

                                                                        </div>

                                                                        <div class="col-md-2">
                                                                        '.$xxxx2.'

                                                                        </div>


                                                                        <div class="col-md-2">
                                                                        '.$xxxx4.'

                                                                        </div>


                                                                        <div class="col-md-2">
                                                                        '.$xxxx5.'

                                                                        </div>


                                                                    </div>
                                                                    <br>
                                                                <hr>
                                                                <div class="row">
                                                                <div class="col-md-2">
                                                                    SHIPPING FEE

                                                                </div>

                                                                <div class="col-md-4">
                                                                    &nbsp

                                                                </div>

                                                                <div class="col-md-2">
                                                                    &nbsp

                                                                </div>




                                                                <div class="col-md-2">
                                                                    &nbsp

                                                                </div>


                                                                <div class="col-md-2">
                                                                    0.00
                                                                </div>
                                                                </div>
                                                                <hr>

                                                                <div class="row">
                                                                <div class="col-md-2">
                                                                    TOTAL PRICE

                                                                </div>

                                                                <div class="col-md-4">
                                                                    &nbsp

                                                                </div>

                                                                <div class="col-md-2">
                                                                    &nbsp

                                                                </div>


                                                                <div class="col-md-2">
                                                                    &nbsp

                                                                </div>


                                                                <div class="col-md-2">

                                                                
                                                                </div>
                                                                </div>
                                                            </div>


                                                            <div class="modal-footer">


                                                            <span id="imagex1" style="color: red; font-size: 24px; display:none;">Order has been delivered? <a href="orderdeliver.php?id='.$item_data27_fumaco.'" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">YES</a> / <button type="button" class="btn btn-warning btn-sm active" onclick="show_hide()">No</button>&nbsp;&nbsp;&nbsp;&nbsp;</span>

                                                            <span id="imagex2" style="color: red; font-size: 24px; display:none;">Order has been cancelled? <a href="cancelorder_1.php?id='.$item_data27_fumaco.'" class="btn btn-danger btn-sm active" role="button" aria-pressed="true">YES</a> / <button type="button" class="btn btn-warning btn-sm active" onclick="show_hide1()">No</button>&nbsp;&nbsp;&nbsp;&nbsp;</span>


                                                            <a href="add_tracker_code.php?id='.$item_data27_fumaco.'" class="btn btn-info btn-sm active" role="button" aria-pressed="true">Add Tracking Code</a>

                                                            <button type="button" class="btn btn-success btn-sm active" onclick="show_hide()">Delivered Order</button>
                                                            <button type="button" class="btn btn-danger btn-sm active" onclick="show_hide1()">Cancel Order</button>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection