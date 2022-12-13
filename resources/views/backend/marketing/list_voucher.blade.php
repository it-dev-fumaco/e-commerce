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
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mt-3 tab-card">
                                        <div class="card-header tab-card-header">
                                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                                <li class="nav-item tab-btns">
                                                    <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Active</a>
                                                </li>
                                                <li class="nav-item tab-btns">
                                                    <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Expired</a>
                                                </li>
                                            </ul>
                                        </div>
                                
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                                                <div class="col-md-12">
                                                    <form action="/admin/marketing/voucher/list" class="text-center" method="GET">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-left">
                                                                <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                            </div>
                                                            <div class="col-1">
                                                                <button type="submit" class="btn btn-secondary mx-auto" style='width: 100%'>Search</button>
                                                            </div>
                                                            <div class="col-6"></div>
                                                            <div class="col-1">
                                                                <a href="/admin/marketing/voucher/add_voucher" class="btn btn-primary mx-auto" style='width: 100%'>Add</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <table class="table table-hover table-bordered">
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Code</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Allotment</th>
                                                        <th class="text-center">Consumed</th>
                                                        <th class="text-center">Min. Spend</th>
                                                        <th class="text-center">Discount Type</th>
                                                        <th class="text-center">Discount Amount/Rate</th>
                                                        <th class="text-center">Capped Amount</th>
                                                        <th class="text-center">Validity Date</th>
                                                        <th class="text-center">Remarks</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    @forelse ($coupon as $c)
                                                        @if(!$c->unlimited)
                                                            @if ($c->total_consumed >= $c->total_allotment)
                                                                @continue
                                                            @endif
                                                        @endif
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $c->name }} <br>
                                                                <span class="badge badge-primary {{ !$c->auto_apply ? 'd-none' : null }}" style="font-size: 8pt;">Auto-applied in Checkout</span>
                                                            </td>
                                                            <td class="text-center">
                                                                @php
                                                                    $badge = 'secondary';
                                                                    if(\Carbon\Carbon::parse($c->validity_date_start)->startOfDay() <= \Carbon\Carbon::now() and \Carbon\Carbon::parse($c->validity_date_end)->endOfDay() >= \Carbon\Carbon::now()){ // Check if coupon is valid
                                                                        $badge = 'danger';
                                                                    }
                                                                @endphp
                                                                <span class="badge badge-{{ $badge }}" style="font-size: 15px">{{ $c->code }}</span>
                                                            </td>
                                                            <td class="text-center">{{ $c->coupon_type }}</td>
                                                            <td class="text-center">{{ $c->unlimited ? '∞' : $c->total_allotment }}</td>
                                                            <td class="text-center">{{ $c->total_consumed }}</td>
                                                            <td class="text-center">
                                                                @if($c->minimum_spend)
                                                                    ₱ {{ number_format($c->minimum_spend, 2, '.', ',') }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $c->discount_type }}</td>
                                                            <td class="text-center">
                                                                @if ($c->discount_type == 'Fixed Amount')  
                                                                    ₱ {{ number_format($c->discount_rate, 2, '.', ',') }}
                                                                @elseif($c->discount_type == 'By Percentage')
                                                                    {{ $c->discount_rate }}%
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($c->capped_amount)
                                                                    ₱ {{ number_format($c->capped_amount, 2, '.', ',') }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $c->validity_date_start ? date('M d, Y', strtotime($c->validity_date_start)).' - '.date('M d, Y', strtotime($c->validity_date_end)) : '' }}</td>
                                                            <td class="text-center">{{ $c->remarks }}</td>
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
                                                            <td colspan=12 class="text-center">No Vouchers</td>
                                                        </tr>
                                                    @endforelse
                                                </table>
                                                <div class="float-right mt-4">
                                                    {{ $coupon->withQueryString()->links('pagination::bootstrap-4') }}
                                                </div>   
                                            </div>
                                            
                                            <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
                                                <div class="col-md-12">
                                                    <form action="/admin/marketing/voucher/list" class="text-center" method="GET">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-left">
                                                                <input type="text" class="form-control" id="search-box" name="expired_q" placeholder="Search" value="{{request()->get('expired_q')}}">
                                                            </div>
                                                            <div class="col-1">
                                                                <button type="submit" class="btn btn-secondary mx-auto" style='width: 100%'>Search</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <table class="table table-hover table-bordered">
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Code</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Allotment</th>
                                                        <th class="text-center">Consumed</th>
                                                        <th class="text-center">Min. Spend</th>
                                                        <th class="text-center">Discount Type</th>
                                                        <th class="text-center">Discount Amount/Rate</th>
                                                        <th class="text-center">Capped Amount</th>
                                                        <th class="text-center">Validity Date</th>
                                                        <th class="text-center">Remarks</th>
                                                    </tr>
                                                    @forelse ($invalid_coupon as $exp_c)
                                                        <tr>
                                                            <td class="text-center">{{ $exp_c->name }}</td>
                                                            <td class="text-center">
                                                                <span class="badge badge-secondary" style="font-size: 15px">{{ $exp_c->code }}</span>
                                                            </td>
                                                            <td class="text-center">{{ $exp_c->coupon_type }}</td>
                                                            <td class="text-center">{{ $exp_c->unlimited ? '∞' : $exp_c->total_allotment }}</td>
                                                            <td class="text-center">{{ $exp_c->total_consumed }}</td>
                                                            <td class="text-center">
                                                                @if($exp_c->minimum_spend)
                                                                    ₱ {{ number_format($exp_c->minimum_spend, 2, '.', ',') }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $exp_c->discount_type }}</td>
                                                            <td class="text-center">
                                                                @if ($exp_c->discount_type == 'Fixed Amount')  
                                                                    ₱ {{ number_format($exp_c->discount_rate, 2, '.', ',') }}
                                                                @elseif($exp_c->discount_type == 'By Percentage')
                                                                    {{ $exp_c->discount_rate }}%
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($exp_c->capped_amount)
                                                                    ₱ {{ number_format($exp_c->capped_amount, 2, '.', ',') }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $exp_c->validity_date_start ? date('M d, Y', strtotime($exp_c->validity_date_start)).' - '.date('M d, Y', strtotime($exp_c->validity_date_end)) : '' }}</td>
                                                            <td class="text-center">{{ $exp_c->remarks }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan=11 class="text-center">No Vouchers</td>
                                                        </tr>
                                                    @endforelse
                                                </table>
                                                <div class="float-right mt-4">
                                                    {{ $invalid_coupon->withQueryString()->links('pagination::bootstrap-4') }}
                                                </div>         
                                            </div>                                      
                                        </div>
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
        .tab-card {
  border:1px solid #eee;
}

.tab-card-header {
  background:none;
}
/* Default mode */
.tab-card-header > .nav-tabs {
  border: none;
  margin: 0px;
}
.tab-card-header > .nav-tabs > li {
  margin-right: 2px;
}
.tab-card-header > .nav-tabs > li > a {
  border: 0;
  border-bottom:2px solid transparent;
  margin-right: 0;
  color: #737373;
  padding: 2px 15px;
}

.tab-card-header > .nav-tabs > li > .active {
    border-bottom:2px solid #007bff;
    color: #007bff;
}
.tab-card-header > .nav-tabs > li > a:hover {
    color: #007bff;
}

.tab-card-header > .tab-content {
  padding-bottom: 0;
}
    </style>
@endsection
