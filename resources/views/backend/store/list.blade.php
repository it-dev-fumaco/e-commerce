@extends('backend.layout', [
	'namePage' => 'Store',
	'activePage' => 'store_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Stores</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Store</li>
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
							<form action="/admin/store/list" method="GET">
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
											<a href="/admin/store/add" class="btn btn-primary">Create New Store</a>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th scope="col" class="text-center">No.</th>
										<th scope="col" class="text-center">Store Name</th>
										<th scope="col" class="text-center">Address</th>
										<th scope="col" class="text-center">Office Hours</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($stores as $i => $row)
									<tr>
										<td class="text-center">{{ $i + 1 }}</td>
										<td class="text-center">{{ $row->store_name }}</td>
										<td class="text-center">{{ $row->address }}</td>
										<td class="text-center">{{ date('h:i A', strtotime($row->available_from)) }} - {{ date('h:i A', strtotime($row->available_to)) }}</td>
										<td class="text-center align-middle">
											<div class="btn-group" role="group" aria-label="Basic example">
												<a href="/admin/store/{{ $row->store_id }}/edit" class="btn btn-outline-success btn-sm">Edit</a>
												<button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $i }}">Delete</button>
											</div>
										</td>
									</tr>
	
									<!-- Modal -->
									<div class="modal fade" id="delete-{{ $i }}" tabindex="-1" role="dialog">
										<form action="/admin/store/{{ $row->store_id }}/delete" method="POST">
											@csrf
											@method('delete')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Delete Store</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<h6 class="text-center">Delete Store <span class="font-weight-bold">{{ $row->store_name }}</span> ?</h6>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary">Confirm</button>
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									@empty
									<tr>
										<td colspan="5" class="text-center text-muted">
											<h6 class="text-center">No store(s) found.</h6>
										</td>
									</tr>
									@endforelse
								</tbody>
							</table>
							<div class="float-right mt-4">
								{{ $stores->withQueryString()->links('pagination::bootstrap-4') }}
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
