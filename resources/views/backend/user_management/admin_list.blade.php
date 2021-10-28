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
                                    <form action="/admin/user_management/list" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="search-box" name="email" placeholder="Search" value="{{request()->get('email')}}">
                                            </div>
                                                
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary">Search</button>
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
                                                <th>Action</th>
                                                <th>Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($admin as $a)
                                                <tr>
                                                    <td>{{ $a->id }}</td>
                                                    <td>{{ $a->username }}</td>
                                                    <td>{{ $a->user_type }}</td>
                                                    <td>{{ $a->account_name }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adminModal-{{ $a->id }}">
                                                            Edit
                                                        </button>

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
                                                                                <input type="text" name="account_name" class="form-control">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="name">User Type</label>
                                                                                <select class="form-control" name="user_type" required>
                                                                                    <option {{ ($a->user_type == "" ) ? 'selected' : '' }} disabled value="">Select User Type</option>
                                                                                    <option value="System Admin" {{ ($a->user_type == "System Admin" ) ? 'selected' : '' }}>System Admin</option>
                                                                                    <option value="Sales Admin" {{ ($a->user_type == "Sales Admin" ) ? 'selected' : '' }}>Sales Admin</option>
                                                                                </select>
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
                                                    <td>
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="toggle" id="toggle_{{ $a->id }}" name="status" {{ ($a->xstatus == 1) ? 'checked' : '' }} value="{{ $a->id }}"/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
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