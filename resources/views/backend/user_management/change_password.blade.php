@extends('backend.layout', [
'namePage' => 'Change Password',
'activePage' => 'change_password'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Change Password Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Change Password Page</li>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Username:</label> {{ $user->username }}
                                            <br/>
                                            <label>User Type:</label> {{ $user->user_type }}
                                        </div>
                                    </div>
                                    <br/>
                                    <form action="/admin/user_management/change_user_password" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="current">Current Password</label>
                                                <input type="password" class="form-control" name="current" placeholder="Current Password" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="password">New Password</label>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="confirm">Confirm New Password</label>
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