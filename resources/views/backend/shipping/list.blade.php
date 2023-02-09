@extends('backend.layout', [
	'namePage' => 'Shipping',
	'activePage' => 'shipping_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Shipping</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Shipping</li>
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
							<form action="/admin/shipping/list" method="GET">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-8">
											<input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
											</div>
											<div class="col-md-4">
												<button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Search</button>
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="float-right">
											<a href="/admin/shipping/add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp; Create New Shipping</a>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered">
								<col style="width: 5%;">
								<col style="width: 23%;">
								<col style="width: 27%;">
								<col style="width: 15%;">
								<col style="width: 18%;">
								<col style="width: 12%;">
								<thead>
									<tr>
										<th scope="col" class="text-center">No.</th>
										<th scope="col" class="text-center">Shipping Service Name</th>
										<th scope="col" class="text-center">Description</th>
										<th scope="col" class="text-center">Leadtime</th>
										<th scope="col" class="text-center">Shipping Condition</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($shipping_services as $i => $row)
									<tr>
										<td class="text-center">{{ $i + 1 }}</td>
										<td class="text-center">{{ $row->shipping_service_name }}</td>
										<td class="text-center">{{ $row->shipping_service_description }}</td>
										<td class="text-center">{{ $row->min_leadtime . ' - ' . $row->max_leadtime }} day(s)</td>
										<td class="text-center align-middle">{{ $row->shipping_calculation }}</td>
										<td class="text-center align-middle">
											<a href="/admin/shipping/{{ $row->shipping_service_id }}/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
											<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $i }}"><i class="fa fa-trash"></i></button>
										</td>
									</tr>
	
									<!-- Modal -->
									<div class="modal fade" id="delete-{{ $i }}" tabindex="-1" role="dialog">
										<form action="/admin/shipping/{{ $row->shipping_service_id }}/delete" method="POST">
											@csrf
											@method('delete')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title"><i class="fa fa-trash"></i> Delete Shipping Service</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<h6 class="text-center">Delete Shipping Service <span class="font-weight-bold">{{ $row->shipping_service_name }}</span> ?</h6>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
														<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									@empty
									<tr>
										<td colspan="6" class="text-center text-muted">
											<h6 class="text-center">No shipping service(s) found.</h6>
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>
							<div class="float-right mt-4">
								{{-- {{ $product_list->withQueryString()->links('pagination::bootstrap-4') }} --}}
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
