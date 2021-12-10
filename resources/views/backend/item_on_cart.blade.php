@extends('backend.layout', [
	'namePage' => 'Items on Cart',
	'activePage' => 'items_on_cart'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Items on Cart Report</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Items on Cart Report</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
		<section class="content">
			<div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                      <!-- Custom Tabs -->
                      <div class="card">
                        <div class="card-header d-flex p-0">
                          <ul class="nav nav-pills p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Items on Cart</a></li>
                            {{-- <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Items on Cart List</a></li> --}}
                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Abandoned Items on Cart</a></li>
                          </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6 pr-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="text-left">
                                                    <h5 class="font-weight-bold">Items on Cart <small class="text-muted font-italic">(grouped by location)</small></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-right">
                                                    Total: <b>{{ count($cart_per_loc) }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Location</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($cart_per_loc as $row)
                                                <tr>
                                                    <td class="text-center">{{ $row['location'] }}</td>
                                                    <td class="text-center">
                                                        <a href="#" data-toggle="modal" data-target="#modal-{{ Str::slug($row['location'], '-') }}">View Items <span class="badge badge-secondary">{{ count($row['items']) }}</span></a>
                                                        <div class="modal fade" id="modal-{{ Str::slug($row['location'], '-') }}">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                    <h4 class="modal-title">{{ $row['location'] }}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <th>Item Code</th>
                                                                                <th>Description</th>
                                                                                <th>No. of Carts</th>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($row['items'] as $item)
                                                                                <tr>
                                                                                    <td>{{ $item['item_code'] }}</td>
                                                                                    <td class="text-left">{{ $item['item_description'] }}</td>
                                                                                    <td>{{ $row['item_codes'][$item['item_code']] }}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No records found.</td>
                                                </tr>
                                                @endforelse                                     
                                            </tbody>
                                        </table>
                                        <div class="float-right mt-4 ml-3">
                                            {{-- {{ $per_location->withQueryString()->links('pagination::bootstrap-4') }} --}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="text-left">
                                                    <h5 class="font-weight-bold">Items on Cart <small class="text-muted font-italic">(grouped by item)</small></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-right">
                                                    Total: <b>{{ $list_per_item->total() }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-hover" style="font-size: 11pt;">
                                            <col style="width: 80%;">
                                            <col style="width: 20%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Item Description</th>
                                                    <th class="text-center">No. of Carts</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($list_per_item as $row)
                                                <tr>
                                                    <td>{!! '<b>'. $row->item_code .'</b> - '. $row->item_description !!}</td>
                                                    <td class="text-center">{{ $row->count }}</td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No items found.</td>
                                                </tr>
                                                @endforelse                                     
                                            </tbody>
                                        </table>
                                        <div class="float-right mt-4 ml-3">
                                            {{ $list_per_item->withQueryString()->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="text-left">
                                                    <h5 class="font-weight-bold">Items on Cart <small class="text-muted font-italic">(grouped by item)</small></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-right">
                                                    Total: <b>{{ $list_per_item->total() }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-hover" style="font-size: 11pt;">
                                            <col style="width: 80%;">
                                            <col style="width: 20%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Item Description</th>
                                                    <th class="text-center">No. of Carts</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($list_per_item as $row)
                                                <tr>
                                                    <td>{!! '<b>'. $row->item_code .'</b> - '. $row->item_description !!}</td>
                                                    <td class="text-center">{{ $row->count }}</td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No items found.</td>
                                                </tr>
                                                @endforelse                                     
                                            </tbody>
                                        </table>
                                        <div class="float-right mt-4 ml-3">
                                            {{ $list_per_item->withQueryString()->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="/admin/items_on_cart" method="GET">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="search" aria-describedby="button-addon2" placeholder="Search" value="{{ (request()->get('search')) ? request()->get('search') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-secondary" type="submit">Search</button>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="text-right">
                                                        Total: <b>{{ $abandoned_cart->total() }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <table class="table table-bordered table-hover" style="font-size: 11pt;">
                                            <col style="width: 5%;">
                                            <col style="width: 35%;">
                                            <col style="width: 10%;">
                                            <col style="width: 10%;">
                                            <col style="width: 10%;">
                                            <col style="width: 20%;">
                                            <col style="width: 10%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">ID</th>
                                                    <th class="text-center">Item Description</th>
                                                    <th class="text-center">User Type</th>
                                                    <th class="text-center">User Account</th>
                                                    <th class="text-center">IP Address</th>
                                                    <th class="text-center">Location</th>
                                                    <th class="text-center">Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($abandoned_cart as $row)
                                                <tr>
                                                    <td class="text-center">{{ $row->id }}</td>
                                                    <td>{!! '<b>'. $row->item_code .'</b> - '. $row->item_description !!}</td>
                                                    <td class="text-center">{{ $row->user_type }}</td>
                                                    <td class="text-center">{{ $row->user_email }}</td>
                                                    <td class="text-center">{{ $row->ip }}</td>
                                                    <td class="text-center">{{ $row->city .' '. $row->region .' '. $row->country }}</td>
                                                    <td class="text-center">{{ $row->last_modified_at }}</td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No items found.</td>
                                                </tr>
                                                @endforelse                                     
                                            </tbody>
                                        </table>
                                        <div class="float-right mt-4 ml-3">
                                            {{ $abandoned_cart->withQueryString()->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                      </div>
                      <!-- ./card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </section>
    </div>
</div>
@endsection