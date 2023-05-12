@extends('backend.layout', [
'namePage' => 'Admin',
'activePage' => 'admin_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Admin List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Admin List Page</li>
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
                                    <form action="/admin/user_management/list" method="GET">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" id="search-box" name="email" placeholder="Search" value="{{request()->get('email')}}">
                                            </div>
                                                
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Search</button>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="/admin/user_management/add" class="btn btn-primary float-right"><i class="fa fa-plus"></i>&nbsp;New Admin</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>User Type</th>
                                                <th>Account Name</th>
                                                <th>Last Login</th>
                                                <th>Last Login IP</th>
                                                <th>Active</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($admin as $a)
                                                <tr>
                                                    <td>{{ $a->id }}</td>
                                                    <td>
                                                        {{ $a->username }}<br/>
                                                        @if (!$a->xstatus && $a->remarks == 'Locked Out')
                                                            <span class="badge badge-secondary" style="font-size: 7pt;">{{ $a->remarks }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $a->user_type }}</td>
                                                    <td>{{ $a->account_name }}</td>
                                                    <td>{{ date('M d, Y h:i A', strtotime($a->last_login)) }}</td>
                                                    <td>{{ $a->last_login_ip }}</td>
                                                    <td>
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="toggle" id="toggle_{{ $a->id }}" name="status" {{ ($a->xstatus == 1) ? 'checked' : '' }} value="{{ $a->id }}"/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adminModal-{{ $a->id }}"><i class="fa fa-edit"></i></a>
                                                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#changePassModal-{{ $a->id }}"><i class="fas fa-unlock"></i>&nbsp;Change Password</a>
                                                        </center>

                                                        <div class="modal fade" id="adminModal-{{ $a->id }}" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="adminModalLabel">Edit Information</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="/admin/user_management/edit" method="post">
                                                                        <div class="modal-body">
                                                                            @csrf
                                                                            <label>Username:</label> {{ $a->username }}
                                                                            <input type="text" name="username" value="{{ $a->username }}" class="form-control" required readonly hidden>
                                                                            <div class="form-group">
                                                                                <label for="name">Account Name</label>
                                                                                <input type="text" name="account_name" value="{{ $a->account_name }}" class="form-control">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                @php
                                                                                    $user_type = ['Accounting Admin', 'Marketing Admin', 'Sales Admin', 'System Admin'];
                                                                                @endphp
                                                                                <label for="name">User Type</label>
                                                                                <select class="form-control" name="user_type" required>
                                                                                    <option {{ ($a->user_type == "" ) ? 'selected' : '' }} disabled value="">Select User Type</option>
                                                                                    @foreach ($user_type as $user)
                                                                                        <option value="{{ $user }}" {{ $a->user_type == $user ? 'selected' : null }}>{{ $user }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="name">Mobile Number (Two-Factor Authentication)</label>
                                                                                <input class="form-control" type="text" name="mobile_number" value="{{ $a->mobile_number }}">
                                                                            </div>
                                                                            <div class="float-right font-italic">
                                                                                <small>Last modified by: {{ ($a) ? $a->last_modified_by . ' - ' . $a->last_modified_at : '' }}</small>
                                                                            </div>                                                                  
                                                                        </div>
                                                                        <div class="modal-footer container-fluid">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal fade" id="changePassModal-{{ $a->id }}" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="adminModalLabel">Edit Information</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="/admin/user_management/change_password/{{ $a->id }}" method="post">
                                                                        <div class="modal-body">
                                                                            @csrf
                                                                            <label>Username:</label> {{ $a->username }}
                                                                            <div class="form-group">
                                                                                <label for="password">New Password</label>
                                                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="confirm">Confirm New Password</label>
                                                                                <input type="password" class="form-control" name="confirm" placeholder="Confirm Password" required>
                                                                            </div>                                                                    
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $admin->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 16px;
        }
    
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
    
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        .slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        input:checked + .slider {
            background-color: #2196F3;
        }
    
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
    
        input:checked + .slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }
    
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
    
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".toggle").change(function(){
                var data = {
                    'status': $(this).prop('checked') == true ? 1 : 0,
                    'admin_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/admin/user_management/change_status',
                    data: data,
                    success: function (response) {
                        console.log(status);
                    },
                    error: function () {
                        alert('An error occured.');
                    }
                });
            });
        });
    </script>
@endsection