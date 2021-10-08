@extends('backend.layout', [
	'namePage' => 'Products',
	'activePage' => 'product_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Products</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Products</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<!-- /.card-header -->
						<div class="card-body">
							@if(session()->has('success'))
								<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
									{!! session()->get('success') !!}
								</div>
							@endif
							@if(session()->has('error'))
								<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
									{!! session()->get('error') !!}
								</div>
							@endif
							<form action="/admin/product/list" method="GET">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-8">
											<input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
											</div>
											<div class="col-md-4">
												<button type="submit" class="btn btn-primary">Search</button>
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="float-right">
											<a href="/admin/product/add" class="btn btn-primary">Create New Product</a>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered table-hover">
								<col style="width: 35%;">
								<col style="width: 7%;">
								<col style="width: 7%;">
								<col style="width: 7%;">
								<col style="width: 8%;">
								<col style="width: 12%;">
								<col style="width: 8%;">
								<col style="width: 5%;">
								<col style="width: 5%;">
								<col style="width: 6%;">
								<thead>
									<tr>
										<th class="text-center">Item Name</th>
										<th class="text-center">Parent Item</th>
										<th class="text-center">Price</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Reserved Qty</th>
										<th class="text-center"h>Category</th>
										<th class="text-center">Brand</th>
										<th class="text-center">On Sale</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $item)
									<tr>
										<td><span class="d-block font-weight-bold">{{ $item->f_idcode }}</span> {{ $item->f_name_name }}</td>
										<td class="text-center">{{ $item->f_parent_code }}</td>
										<td class="text-center">{{ 'P ' . number_format((float)$item->f_price, 2, '.', ',') }}</td>
										<td class="text-center">{{ number_format($item->f_qty) }}</td>
										<td class="text-center">{{ number_format($item->f_reserved_qty) }}</td>
										<td class="text-center">{{ $item->f_category }}</td>
										<td class="text-center">{{ $item->f_brand }}</td>
										<td class="text-center">
											@if ($item->f_onsale == 1)
												 <span class="badge badge-success">On Sale</span>
											@endif
										</td>
										<td class="text-center">
											@if ($item->f_status == 1)
												<span class="badge badge-primary">Active</span>
											@else
												<span class="badge badge-secondary">Disabled</span>
											@endif
										</td>
										<td class="text-center">
											<div class="dropdown">
												<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
												</button>
												<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
												  <a class="dropdown-item" href="/admin/product/{{ $item->id }}/edit">View Details</a>
												  @if($item->f_status == 1)
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#d{{ $item->id }}"><small>Disable</small></a>
												  @else
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#e{{ $item->id }}">Enable</a>
												  @endif
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dm{{ $item->id }}"><small>Delete</small></a>
												  <a class="dropdown-item" href="/admin/product/images/{{ $item->id }}"><small>Images</small></a>
												</div>
											</div>

											<div class="modal fade" id="d{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="delItemModal" aria-hidden="true">
												<form action="/admin/product/{{ $item->f_idcode }}/disable" method="POST">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="delItemModal">Disable Product</h5>
															</div>
															<div class="modal-body">
																<p>Disable <b>{{ $item->f_idcode }}</b>?</p>
															</div>
															<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Confirm</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="e{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="delItemModal" aria-hidden="true">
												<form action="/admin/product/{{ $item->f_idcode }}/enable" method="POST">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="delItemModal">Set Product as Active</h5>
															</div>
															<div class="modal-body">
																<p>Set <b>{{ $item->f_idcode }}</b> as "Active"?</p>
															</div>
															<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Confirm</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="dm{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="delItemModal" aria-hidden="true">
												<form action="/admin/product/{{ $item->f_idcode }}/delete" method="POST">
													@csrf
													@method('delete')
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="delItemModal">Delete Product</h5>
															</div>
															<div class="modal-body">
																<p>Delete item <b>{{ $item->f_idcode }}</b>?</p>
															</div>
															<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Confirm</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>
										</td>
									 </tr>
									@empty
									<tr>
										<td colspan="10" class="text-center">No products found.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
							<div class="float-right mt-4">
								{{ $list->withQueryString()->links('pagination::bootstrap-4') }}
							</div>
						</div>
					  	<!-- /.card-body -->
					</div>
				  <!-- /.card -->
				</div>
         </div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection

@section('script')

@endsection
