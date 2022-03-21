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
										<form action="/admin/payment_method/{{ $row->payment_method_id }}/update" method="POST" enctype="multipart/form-data">
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
                                                            <label for="edit-payment-method-name{{ $row->payment_method_id }}">Payment Method Name</label>
                                                            <input type="text" name="payment_method_name" class="form-control" id="edit-payment-method-name{{ $row->payment_method_id }}" value="{{ $row->payment_method_name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-payment-type{{ $row->payment_method_id }}">Payment Type</label>
															<select name="payment_type" class="form-control" id="edit-payment-type{{ $row->payment_method_id }}">
																<option value="">Select Payment Type</option>
																<option value="CC" {{ $row->payment_type == 'CC' ? 'selected' : '' }}>CC - Credit Cart</option>
																<option value="WA" {{ $row->payment_type == 'WA' ? 'selected' : '' }}>WA - e-Wallet</option>
																<option value="Bank Deposit" {{ $row->payment_type == 'Bank Deposit' ? 'selected' : '' }}>Bank Deposit</option>
															</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-issuing-bank{{ $row->payment_method_id }}">Issuing Bank</label>
                                                            <input type="text" name="issuing_bank" class="form-control" id="edit-issuing-bank{{ $row->payment_method_id }}" value="{{ $row->issuing_bank }}">
                                                        </div>
														<div class="form-check mb-2">
															<input type="checkbox" class="form-check-input edit-show-icon" id="edit-show-icon{{ $row->payment_method_id }}" {{ $row->show_image ? 'checked' : '' }} name="show_icon" value="1">
															<label class="form-check-label" for="edit-show-icon{{ $row->payment_method_id }}">Show payment icon / logo</label>
														</div>
														<div class="form-group {{ ($row->image) ? 'd-block' : 'd-none' }} edit-show-icon{{ $row->payment_method_id }}">
															<img src="{{ asset('/storage/payment_method/'. $row->image) }}" alt="{{ $row->image }}" class="img-thumbnail" id="edit-payment-icon{{ $row->payment_method_id }}-preview">
														</div>
                                                        <div class="form-group edit-show-icon{{ $row->payment_method_id }}">
                                                            <label for="edit-payment-icon{{ $row->payment_method_id }}">Payment Logo</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input edit-payment-icon" id="edit-payment-icon{{ $row->payment_method_id }}" name="payment_icon">
                                                                    <label class="custom-file-label" for="edit-payment-icon{{ $row->payment_method_id }}">{{ $row->image ? $row->image : 'Choose file' }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-remarks{{ $row->payment_method_id }}">Remarks</label>
                                                            <textarea class="form-control" name="remarks" id="edit-remarks{{ $row->payment_method_id }}" rows="4">{{ $row->remarks }}</textarea>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="is_enabled" type="checkbox" value="1" id="is_enabled{{ $row->payment_method_id }}" {{ $row->is_enabled ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_enabled{{ $row->payment_method_id }}"> Is Enabled</label>
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
    <form action="/admin/payment_method/save" method="POST" enctype="multipart/form-data">
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
						<select name="payment_type" class="form-control" id="payment-type">
							<option value="">Select Payment Type</option>
							<option value="CC">CC - Credit Cart</option>
							<option value="WA">WA - e-Wallet</option>
							<option value="Bank Deposit">Bank Deposit</option>
						</select>
                    </div>
                    <div class="form-group">
                        <label for="issuing-bank">Issuing Bank</label>
                        <input type="text" name="issuing_bank" class="form-control" id="issuing-bank">
                    </div>
					<div class="form-check mb-2">
						<input type="checkbox" class="form-check-input" id="show-icon" checked name="show_icon" value="1">
						<label class="form-check-label" for="show-icon">Show payment icon / logo</label>
					</div>
					<div class="form-group d-none" id="payment-icon-preview-1">
						<img src="#" alt="#" class="img-thumbnail" id="payment-icon-preview-img">
					</div>
                    <div class="form-group" id="payment-icon-browse-file">
                        <label for="payment-icon">Payment Logo</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="payment-icon" name="payment_icon">
                                <label class="custom-file-label" for="payment-icon">Choose file</label>
                            </div>
                        </div>
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
@section('script')
<script>
	$(function () {
		bsCustomFileInput.init();

		$(document).on('change', '.edit-show-icon', function() {
			var id = $(this).attr('id');
			if($(this).is(":checked")) {
				$('.' + id).removeClass('d-none').addClass('d-block');
			} else {
				$('.' + id).removeClass('d-block').addClass('d-none');
			}
		});

		$('#show-icon').change(function() {
			if($(this).is(":checked")) {
				if($('#payment-icon').val() != '') {
					$('#payment-icon-preview-1').removeClass('d-none').addClass('d-block');
				}
				$('#payment-icon-browse-file').removeClass('d-none').addClass('d-block');
			} else {
				$('#payment-icon-preview-1').removeClass('d-block').addClass('d-none');
				$('#payment-icon-browse-file').removeClass('d-block').addClass('d-none');
			}
		});

		$('#payment-icon').change(function(){
			const file = this.files[0];
			if (file){
				let reader = new FileReader();
				reader.onload = function(event){
					$('#payment-icon-preview-img').attr('src', event.target.result);
				}

				$('#payment-icon-preview-1').removeClass('d-none').addClass('d-block');
				reader.readAsDataURL(file);
			}
		});

		$(document).on('change', '.edit-payment-icon', function() {
			var img_div = $(this).attr('id');
			const file1 = this.files[0];
			if (file1){
				let reader = new FileReader();
				reader.onload = function(event){
					$('#' + img_div + '-preview').attr('src', event.target.result);
				}

				reader.readAsDataURL(file1);
			}
		});
	});
</script>
@endsection