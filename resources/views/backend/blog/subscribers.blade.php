@extends('backend.layout', [
'namePage' => 'Subscribers',
'activePage' => 'subscribers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Subscribers List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Subscribers List Page</li>
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
                                    <form action="/admin/blog/subscribers" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="search-box" name="email" placeholder="Search" value="{{request()->get('q')}}">
                                            </div>
                                                
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Membership Status</th>
                                                <th>Date Subscribed</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subs_arr as $subs)
    
                                                <tr>
                                                    <td>{{ $subs['email'] }}</td>
                                                    <td>{{ $subs['membership_status'] }}</td>
                                                    <td>{{ $subs['subscribe_date'] }}</td>
                                                    <td>
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="toggle" id="toggle_{{ $subs['id'] }}" name="publish" {{ ($subs['status'] == 1) ? 'checked' : '' }} value="{{ $subs['id'] }}"/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan=2 class="text-center">No Subscribers</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $subscribers->withQueryString()->links('pagination::bootstrap-4') }}
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
                    'sub_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                // console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/admin/subscribe/change_status',
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