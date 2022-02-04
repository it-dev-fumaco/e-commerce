@extends('backend.layout', [
'namePage' => 'On Sale Promo List',
'activePage' => 'on_sale_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>On Sale Promo List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">On Sale Promo List Page</li>
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
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                                    {!! session()->get('success') !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                                    {!! session()->get('error') !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <form action="/admin/marketing/on_sale/list" class="text-center" method="GET">
                                            <div class="form-group row">
                                                <div class="col-4 text-left">
                                                    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                </div>
                                                <div class="col-1">
                                                    <button type="submit" class="btn btn-secondary mx-auto" style='width: 100%'>Search</button>
                                                </div>
                                                <div class="col-6">&nbsp;</div>
                                                <div class="col-1">
                                                    <a href="/admin/marketing/on_sale/addForm" class="btn btn-primary mx-auto" style='width: 100%'>Add</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Sale Name</th>
                                            <th class="text-center">Sale Duration</th>
                                            {{-- <th class="text-center">Discount For</th> --}}
                                            <th class="text-center">Apply Discount To</th>
                                            <th class="text-center">Sale Details</th>
                                            <th class="text-center">Active</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        @forelse ($sale_arr as $sale)
                                            <tr>
                                                <td class="text-center">{{ $sale['id'] }}</td>
                                                <td class="text-center">{{ $sale['name'] }}</td>
                                                <td class="text-center">{{ $sale['sale_duration'] ? $sale['sale_duration'] : 'Lifelong Validity' }}</td>
                                                {{-- <td class="text-center">{{ $sale['discount_for'] }}</td> --}}
                                                <td class="text-center">{{ $sale['apply_discount_to'] }}</td>
                                                <td class="text-center">
                                                    <a type="button" data-toggle="modal" data-target="#sale{{ $sale['id'] }}Modal" style="color: #007BFF;">
                                                        View Details
                                                    </a>
                                                    @if ($sale['apply_discount_to'] == 'All Items')
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="sale{{ $sale['id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ $sale['name'] }}</h5>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    @if ($sale['banner'])
                                                                        <div class="col-10 mx-auto">
                                                                            <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$sale['banner']) }}" alt="" style="width: 100%">
                                                                        </div>
                                                                        <br/>
                                                                    @endif
                                                                    <p><b>Discount Type:</b> {{ $sale['discount_type'] }}</p>
                                                                    <p><b>Discount Amount/Rate:</b> 
                                                                        @if ($sale['discount_type'] == 'Fixed Amount')
                                                                            ₱ {{ number_format($sale['discount_rate'], 2, '.', ',') }}
                                                                        @elseif($sale['discount_type'] == 'By Percentage')
                                                                            {{ $sale['discount_rate'] }}%
                                                                        @endif
                                                                    </p>
                                                                    @if ($sale['capped_amount'])
                                                                        <p><b>Capped Amount</b> ₱ {{ number_format($sale['capped_amount'], 2, '.', ',') }}</p>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        @elseif ($sale['apply_discount_to'] == 'Per Customer Group')
                                                          <!-- Modal -->
                                                          <div class="modal fade" id="sale{{ $sale['id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Customer Group</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <center>
                                                                        @if ($sale['banner'])
                                                                            <div class="col-4 mx-auto">
                                                                                <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$sale['banner']) }}" alt="" style="width: 100%">
                                                                            </div>
                                                                            <br/>
                                                                        @endif
                                                                        <table class="table-hover table-bordered">
                                                                            <tr>
                                                                                <th class="text-center">ID</th>
                                                                                <th class="text-center">Customer Group Name</th>
                                                                                <th class="text-center">Discount Type</th>
                                                                                <th class="text-center">Discount Amount/Rate</th>
                                                                                <th class="text-center">Capped Amount</th>
                                                                            </tr>
                                                                            @foreach ($sale['customer_group'] as $cg)
                                                                                <tr>
                                                                                    <td class="text-center">{{ $cg['customer_group_id'] }}</td>
                                                                                    <td class="text-center">{{ $cg['customer_group_name'] }}</td>
                                                                                    <td class="text-center">{{ $cg['discount_type'] }}</td>
                                                                                    <td class="text-center">
                                                                                        @if ($cg['discount_type'] == 'Fixed Amount')
                                                                                            ₱ {{ number_format($cg['discount_rate'], 2, '.', ',') }}
                                                                                        @elseif($cg['discount_type'] == 'By Percentage')
                                                                                            {{ $cg['discount_rate'] }}%
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">{{ $cg['capped_amount'] ? '₱ '.number_format($cg['capped_amount'], 2, '.', ',') : 0 }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </center>
                                                                </div>
                                                                <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    @else                                                      
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="sale{{ $sale['id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Categories</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <center>
                                                                        @if ($sale['banner'])
                                                                            <div class="col-4 mx-auto">
                                                                                <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$sale['banner']) }}" alt="" style="width: 100%">
                                                                            </div>
                                                                            <br/>
                                                                        @endif
                                                                        <table class="table-hover table-bordered">
                                                                            <tr>
                                                                                <th class="text-center">ID</th>
                                                                                <th class="text-center">Category Name</th>
                                                                                <th class="text-center">Discount Type</th>
                                                                                <th class="text-center">Discount Amount/Rate</th>
                                                                                <th class="text-center">Capped Amount</th>
                                                                            </tr>
                                                                            @foreach ($sale['categories'] as $category)
                                                                                <tr>
                                                                                    <td class="text-center">{{ $category['category_id'] }}</td>
                                                                                    <td class="text-center">{{ $category['category_name'] }}</td>
                                                                                    <td class="text-center">{{ $category['discount_type'] }}</td>
                                                                                    <td class="text-center">
                                                                                        @if ($category['discount_type'] == 'Fixed Amount')
                                                                                            ₱ {{ number_format($category['discount_rate'], 2, '.', ',') }}
                                                                                        @elseif($category['discount_type'] == 'By Percentage')
                                                                                            {{ $category['discount_rate'] }}%
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">{{ $category['capped_amount'] ? '₱ '.number_format($category['capped_amount'], 2, '.', ',') : '' }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </center>
                                                                </div>
                                                                <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <center>
                                                        <label class="switch">
                                                            <input type="checkbox" class="toggle" name="publish" {{ ($sale['status'] == 1) ? 'checked' : '' }} value="{{ $sale['id'] }}"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </center>
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="/admin/marketing/on_sale/{{ $sale['id'] }}/edit_form">View Details</a>
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{ $sale['id'] }}"><small>Delete</small></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="delete{{ $sale['id'] }}" role="dialog" aria-labelledby="delete{{ $sale['id'] }}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete "On Sale"</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            Delete {{ $sale['name'] }}?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <a href="/admin/marketing/on_sale/{{ $sale['id'] }}/delete" type="button" class="btn btn-danger">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan=10 class="text-center">No "On Sale" Promos</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $on_sale->withQueryString()->links('pagination::bootstrap-4') }}
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
                    'sale_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/admin/marketing/on_sale/set_status',
                    data: data,
                    success: function (response) {
                        console.log('success');
                    },
                    error: function () {
                        alert('An error occured.');
                    }
                });
            });
        });
    </script>
@endsection
