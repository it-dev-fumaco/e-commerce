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
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th>First name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Date Registered</th>
                                            <th>Total No. of Visits</th>
                                            <th>Last login</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($user_arr as $user)
                                            <tr>
                                                <td>{{ $user['first_name'] }}</td>
                                                <td>{{ $user['last_name'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>{{ $user['created_at'] }}</td>
                                                <td>{{ $user['no_of_visits'] }}</td>
                                                <td>{{ $user['last_login'] }}</td>
                                                <td class="text-center">
                                                    <a href="/admin/customer/profile/{{ $user['id'] }}" class="btn btn-sm btn-primary">View Profile</a>
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
