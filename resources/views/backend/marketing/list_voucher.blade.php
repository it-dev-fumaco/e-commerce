@extends('backend.layout', [
'namePage' => 'Vouchers List',
'activePage' => 'vouchers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Vouchers List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Vouchers List Page</li>
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
                                        <form action="/admin/marketing/voucher/list" class="text-center" method="GET">
                                            <div class="form-group row">
                                                <div class="col-4 text-left">
                                                    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                </div>
                                                <div class="col-1">
                                                    <button type="submit" class="btn btn-secondary mx-auto" style='width: 100%'>Search</button>
                                                </div>
                                                <div class="col-1">
                                                    <a href="/admin/marketing/voucher/add_voucher" class="btn btn-primary mx-auto" style='width: 100%'>Add</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Coupon Code</th>
                                            <th class="text-center">Total Allotment</th>
                                            <th class="text-center">Total Consumed</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        @forelse ($coupon as $c)
                                            <tr>
                                                <td class="text-center">{{ $c->id }}</td>
                                                <td class="text-center">{{ $c->name }}</td>
                                                <td class="text-center">{{ $c->code }}</td>
                                                <td class="text-center">{{ $c->unlimited == 1 ? 'Unlimited' : $c->total_allotment }}</td>
                                                <td class="text-center">{{ $c->total_consumed }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="/admin/marketing/voucher/{{ $c->id }}/edit_form">View Details</a>
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{ $c->id }}"><small>Delete</small></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="delete{{ $c->id }}" role="dialog" aria-labelledby="delete{{ $c->id }}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete "On Sale"</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            Delete {{ $c->name }}?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <a href="/admin/marketing/voucher/{{ $c->id }}/delete" type="button" class="btn btn-danger">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan=9 class="text-center">No Vouchers</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $coupon->withQueryString()->links('pagination::bootstrap-4') }}
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
