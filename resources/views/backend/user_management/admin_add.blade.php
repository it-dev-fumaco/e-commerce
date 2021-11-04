@extends('backend.layout', [
'namePage' => 'Admin List',
'activePage' => 'admin_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Admin Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add New Admin Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="alert alert-warning">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif
                                <div class="card-body">
                                    <form action="/admin/user_management/add_admin" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="account_name">Account Name</label>
                                                <input type="text" class="form-control" name="account_name" placeholder="Account Name" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="username">Username</label>
                                                <input type="email" class="form-control" name="username" placeholder="Username" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="user_type">User Type</label>
                                                <select class="form-control" name="user_type" required>
                                                    <option selected disabled value="">Select User Type</option>
                                                    <option value="System Admin">System Admin</option>
                                                    <option value="Sales Admin">Sales Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirm">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirm" placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>     
@endsection
@section('script')

@endsection