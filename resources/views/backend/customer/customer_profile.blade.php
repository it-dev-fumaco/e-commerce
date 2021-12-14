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
                                    <div class="row">
                                        <div class="col-6">
                                            <h4>{{ $customer->f_name.' '.$customer->f_lname }}</h4>
                                            <p><b>{{ $customer->username }}</b></p>
                                        </div>
                                        <div class="col-6">
                                            <p>Date Registered: <b>{{ date('M d, Y', strtotime($customer->created_at)) }}</b></p>
                                            <p>Last Login Date: <b>{{ $customer->last_login ? date('M d, Y h:i A', strtotime($customer->last_login)) : '' }}</b></p>
                                            <p>Total Number of Visits: <span class="badge badge-primary">{{ $customer->no_of_visits }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>     
@endsection
