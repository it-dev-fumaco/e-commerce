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
                                    <form action="/admin/customer/list" method="GET">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-secondary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-hover table-bordered" style="font-size: 11pt;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Email Address</th>
                                                <th class="text-center">Customer Group</th>
                                                <th class="text-center">Last Login Date</th>
                                                <th class="text-center">Last Login Used</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        @forelse ($users as $user)
                                            <tr>
                                                <td class="text-center">{{ $user->id }}</td>
                                                <td class="text-left">{{ $user->f_name . ' ' . $user->f_lname }}</td>
                                                <td class="text-center">{{ $user->username }}</td>
                                                <td class="text-center">
                                                    {{ (array_key_exists($user->customer_group, $customer_groups->toArray())) ? $customer_groups[$user->customer_group] : null; }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y - h:i A') }}</td>
                                                <td class="text-center">{{ $user->login_used }}</td>
                                                <td class="text-center">
                                                    <a href="/admin/customer/profile/{{ $user->id }}" class="btn btn-sm btn-primary">View Profile</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=5 class="text-center">No Customers</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
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
