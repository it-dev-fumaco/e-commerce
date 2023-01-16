@extends('backend.layout', [
'namePage' => 'Pricing Rule',
'activePage' => 'pricing_rule'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Pricing Rule List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Pricing Rule</li>
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
                                    <div class="alert alert-success alert-dismissible fade show text-center d-none" id="custom-alert" role="alert"></div>
                                    @if(session()->has('success'))
                                    <div class="row">
                                        <div class="col">
                                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">{!! session()->get('success') !!}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(session()->has('error'))
                                    <div class="row">
                                        <div class="col">
                                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">{!! session()->get('error') !!}</div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <form action="/admin/marketing/pricing_rule/list" class="text-center" method="GET">
                                            <div class="form-group row">
                                                <div class="col-4 text-left">
                                                    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search Price Rule Name" value="{{ request()->get('q') }}">
                                                </div>
                                                <div class="col-1">
                                                    <button type="submit" class="btn btn-secondary mx-auto" style='width: 100%'>Search</button>
                                                </div>
                                                <div class="col-6">&nbsp;</div>
                                                <div class="col-1">
                                                    <a href="/admin/marketing/pricing_rule/add" class="btn btn-primary mx-auto" style='width: 100%'>Add</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Price Rule Name</th>
                                            <th class="text-center">Apply On</th>
                                            <th class="text-center">Discount Type</th>
                                            <th class="text-center">Conditions Based On</th>
                                            <th class="text-center">Period</th>
                                            <th class="text-center">Enabled</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @forelse ($price_rules as $r)
                                            <tr>
                                                <td class="text-center align-middle">{{ $r->price_rule_id }}</td>
                                                <td class="text-center align-middle">{{ $r->name }}</td>
                                                <td class="text-center align-middle">{{ $r->apply_on }}</td>
                                                <td class="text-center align-middle">{{ $r->discount_type }}</td>
                                                <td class="text-center align-middle">{{ $r->conditions_based_on }}</td>
                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($r->valid_from)->format('M. d, Y') . ' - ' . \Carbon\Carbon::parse($r->valid_to)->format('M. d, Y') }}</td>
                                                <td class="text-center align-middle">
                                                    <center>
                                                        <label class="switch mt-1">
                                                            <input type="checkbox" class="toggle" name="publish" {{ ($r->enabled == 1) ? 'checked' : '' }} value="{{ $r->price_rule_id }}"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </center>
                                                </td>
                                                <td class="text-center p-1 align-middle">
                                                    <div class="dropdown m-0">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="/admin/marketing/pricing_rule/{{ $r->price_rule_id }}/edit">View Details</a>
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{ $r->price_rule_id }}"><small>Delete</small></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="delete{{ $r->price_rule_id }}" role="dialog" aria-labelledby="delete{{ $r->price_rule_id }}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="/admin/marketing/pricing_rule/{{ $r->price_rule_id }}/delete" method="POST" autocomplete="off">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Price Rule</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="price_rule_name" value="{{ $r->name }}">
                                                                Delete <b>{{ $r->name }}</b>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No Price Rule(s) found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $price_rules->withQueryString()->links('pagination::bootstrap-4') }}
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
                var id = $(this).val();
                var data = {
                    'status': $(this).prop('checked') == true ? 1 : 0,
                    '_token': "{{ csrf_token() }}",
                }
                
                $.ajax({
                    type:'POST',
                    url:'/admin/marketing/pricing_rule/' + id + '/set_status',
                    data: data,
                    success:function(response){
                        if (!response.status) {
                            $('#custom-alert').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                        }
                    },
                    error: function () {
                        $('#custom-alert').removeClass('alert-success d-none').addClass('alert-danger').html('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
