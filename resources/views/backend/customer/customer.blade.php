@extends('backend.layout', [
'namePage' => 'Customers',
'activePage' => 'customers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Customers List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Customers List Page</li>
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
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="float-right">
                                            <form action="" method="GET">
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                    </div>
                                                        
                                                    <div class="col-sm-2">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <th>First name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Total No. of Visits</th>
                                            <th>Last login</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($user_arr as $user)
                                            <tr>
                                                <td>{{ $user['first_name'] }}</td>
                                                <td>{{ $user['last_name'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>{{ $user['contact'] }}</td>
                                                <td></td>
                                                <td></td>
                                                <td class="col-sm-3">
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#ship-{{ $user['id'] }}">
                                                        Shipping Address
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#bill-{{ $user['id'] }}">
                                                        Billing Address
                                                    </button>

                                                    <!-- Shipping Modal -->
                                                    <div class="modal fade" id="ship-{{ $user['id'] }}" tabindex="-1" role="dialog" aria-labelledby="ship-{{ $user['id'] }}Label" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="ship-{{ $user['id'] }}Label">Shipping Address</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($user['shipping_address'] == 1)
                                                                        <p>Address Type: {{ $user['ship_address1'] }}</p>
                                                                        <p>Address 1: {{ $user['ship_address2'] }}</p>
                                                                        <p>Address 2: {{ $user['ship_province'] }}</p>
                                                                        <p>Province: {{ $user['ship_city'] }}</p>
                                                                        <p>City: {{ $user['ship_brgy'] }}</p>
                                                                        <p>Barangay: {{ $user['ship_postal'] }}</p>
                                                                        <p>Postal Code: {{ $user['ship_country'] }}</p>
                                                                        <p>Country: {{ $user['ship_type'] }}</p>
                                                                    @else
                                                                        <p>No Listed Address</p>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Billing Modal -->
                                                    <div class="modal fade" id="bill-{{ $user['id'] }}" tabindex="-1" role="dialog" aria-labelledby="bill-{{ $user['id'] }}Label" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="bill-{{ $user['id'] }}Label">Billing Address</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($user['billing_address'] == 1)
                                                                        <p>Address Type: {{ $user['bill_address1'] }}</p>
                                                                        <p>Address 1: {{ $user['bill_address2'] }}</p>
                                                                        <p>Address 2: {{ $user['bill_province'] }}</p>
                                                                        <p>Province: {{ $user['bill_city'] }}</p>
                                                                        <p>City: {{ $user['bill_brgy'] }}</p>
                                                                        <p>Barangay: {{ $user['bill_postal'] }}</p>
                                                                        <p>Postal Code: {{ $user['bill_country'] }}</p>
                                                                        <p>Country: {{ $user['bill_type'] }}</p>
                                                                    @else
                                                                        <p>No Listed Address</p>
                                                                    @endif
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
                                                <td colspan=5 class="text-center">No Customers</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $user_info->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>     
@endsection
