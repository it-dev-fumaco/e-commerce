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
                                        <div class="float-right">
                                            <form action="/admin/marketing/search/list" class="text-center" method="GET">
                                                <div class="form-group row">
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                    </div>
                                                        
                                                    <div class="col-sm-2 mr-2">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <a href="/admin/marketing/on_sale/addForm" class="btn btn-primary">Add</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Sale Name</th>
                                            <th class="text-center">Sale Duration</th>
                                            <th class="text-center">Discount Type</th>
                                            <th class="text-center">Discount Rate</th>
                                            <th class="text-center">Capped Amount</th>
                                            <th class="text-center">Apply Discount to</th>
                                            <th class="text-center">Coupon Code</th>
                                            <th class="text-center">Active</th>
                                        </tr>
                                        @forelse ($sale_arr as $sale)
                                            <tr>
                                                <td class="text-center">{{ $sale['id'] }}</td>
                                                <td class="text-center">{{ $sale['name'] }}</td>
                                                <td class="text-center">{{ $sale['sale_duration'] }}</td>
                                                <td class="text-center">{{ $sale['discount_type'] }}</td>
                                                <td class="text-center">{{ $sale['discount_rate'] }}</td>
                                                <td class="text-center">{{ $sale['capped_amount'] }}</td>
                                                <td class="text-center">
                                                    @if ($sale['discount_for'] == 'All Items')
                                                        {{ $sale['discount_for'] }}
                                                    @else
                                                        <a type="button" data-toggle="modal" data-target="#sale{{ $sale['id'] }}Modal" style="color: #007BFF;">
                                                            View Categories
                                                        </a>
                                                      
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="sale{{ $sale['id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Apply Discount to</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <center>
                                                                        <table class="table-hover table-bordered">
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Category Name</th>
                                                                            </tr>
                                                                            @foreach ($sale['categories'] as $category)
                                                                                <tr>
                                                                                    <td>{{ $category['id'] }}</td>
                                                                                    <td>{{ $category['name'] }}</td>
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
                                                <td class="text-center">{{ $sale['coupon'] }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="/admin/marketing/on_sale/{{ $sale['id'] }}/edit">View Details</a>
                                                        @if ($sale['status'] == 1)
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#rm{{ $sale['id'] }}">Remove Active</a>
                                                        @else
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sm{{ $sale['id'] }}">Set Active</a>
                                                        @endif
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#d{{ $sale['id'] }}"><small>Delete</small></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=9 class="text-center">No "On Sale" Promos</td>
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
@endsection
