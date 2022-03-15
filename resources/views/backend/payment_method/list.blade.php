@extends('backend.layout', [
	'namePage' => 'Payment Method',
	'activePage' => 'payment_method'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Payment Methods</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Payment Method</li>
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
							<form action="/admin/payment_method/list" method="GET">
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
                                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add-payment-method">Add Payment Method</button>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered">
								<col style="width: 5%;">
								<col style="width: 20%;">
								<col style="width: 20%;">
								<col style="width: 15%;">
                                <col style="width: 10%;">
								<col style="width: 18%;">
								<col style="width: 12%;">
								<thead>
									<tr>
										<th scope="col" class="text-center">No.</th>
										<th scope="col" class="text-center">Payment Method Name</th>
                                        <th scope="col" class="text-center">Payment Type</th>
										<th scope="col" class="text-center">Issuing Bank</th>
										<th scope="col" class="text-center">Is Enabled</th>
										<th scope="col" class="text-center">Last modified at</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $row)
									<tr>
										<td class="text-center">{{ $row->payment_method_id }}</td>
										<td class="text-center">{{ $row->payment_method_name }}</td>
                                        <td class="text-center">{{ $row->payment_type }}</td>
										<td class="text-center">{{ $row->issuing_bank }}</td>
										<td class="text-center">
                                            <span class="badge bg-{{ ($row->is_enabled) ? 'success' : 'secondary' }}" style="font-size: 10pt;">
                                                {{ ($row->is_enabled) ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
										<td class="text-center align-middle">{{ \Carbon\Carbon::parse($row->last_modified_at)->format('M d, Y - h:i A') }}</td>
										<td class="text-center align-middle">
											<div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#edit{{ $row->payment_method_id }}">Edit</button>
												<button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#delete{{ $row->payment_method_id }}">Delete</button>
											</div>
										</td>
									</tr>
	
									<!-- Modal -->
									<div class="modal fade" id="delete{{ $row->payment_method_id }}" tabindex="-1" role="dialog">
										<form action="/admin/payment_method/{{ $row->payment_method_id }}/delete" method="POST">
											@csrf
											@method('delete')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Delete Payment Method</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<h6 class="text-center">Delete Payment Method <span class="font-weight-bold">{{ $row->payment_method_name }}</span> ?</h6>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary">Confirm</button>
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</form>
									</div>

                                    <!-- Modal -->
									<div class="modal fade" id="edit{{ $row->payment_method_id }}" tabindex="-1" role="dialog">
										<form action="/admin/payment_method/{{ $row->payment_method_id }}/update" method="POST">
											@csrf
											@method('put')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Payment Method</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="edit-payment-method-name">Payment Method Name</label>
                                                            <input type="text" name="payment_method_name" class="form-control" id="edit-payment-method-name" value="{{ $row->payment_method_name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-payment-type">Payment Type</label>
                                                            <input type="text" name="payment_type" class="form-control" id="edit-payment-type" value="{{ $row->payment_type }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-issuing-bank">Issuing Bank</label>
                                                            <input type="text" name="issuing_bank" class="form-control" id="edit-issuing-bank" value="{{ $row->issuing_bank }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-remarks">Remarks</label>
                                                            <textarea class="form-control" name="remarks" id="edit-remarks" rows="4">{{ $row->remarks }}</textarea>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="is_enabled" type="checkbox" value="1" id="is_enabled" {{ $row->is_enabled ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_enabled"> Is Enabled</label>
                                                          </div>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary">Save</button>
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									@empty
									<tr>
										<td colspan="7" class="text-center text-muted">
											<h6 class="text-center">No payment method(s) found.</h6>
										</td>
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

 <div class="modal fade" id="add-payment-method" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
    <form action="/admin/payment_method/save" method="POST">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="payment-method-name">Payment Method Name</label>
                        <input type="text" name="payment_method_name" class="form-control" id="payment-method-name">
                    </div>
                    <div class="form-group">
                        <label for="payment-type">Payment Type</label>
                        <input type="text" name="payment_type" class="form-control" id="payment-type">
                    </div>
                    <div class="form-group">
                        <label for="issuing-bank">Issuing Bank</label>
                        <input type="text" name="issuing_bank" class="form-control" id="issuing-bank">
                    </div>
					<div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="4"></textarea>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
