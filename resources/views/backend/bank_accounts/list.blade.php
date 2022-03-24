@extends('backend.layout', [
	'namePage' => 'Bank Accounts',
	'activePage' => 'bank_account'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Bank Accounts</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Bank Accounts</li>
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
							@if(count($errors->all()) > 0)
								<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
									@foreach ($errors->all() as $error)
										{{ $error }}
									@endforeach 
								</div>
							@endif
							<form action="/admin/bank_account/list" method="GET">
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
                                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add-bank-account">Add Bank Account</button>
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
										<th scope="col" class="text-center">Bank Name</th>
                                        <th scope="col" class="text-center">Account Name</th>
										<th scope="col" class="text-center">Account Number</th>
										<th scope="col" class="text-center">Is Active</th>
										<th scope="col" class="text-center">Last modified at</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $row)
									<tr>
										<td class="text-center">{{ $row->bank_account_id }}</td>
										<td class="text-center">{{ $row->bank_name }}</td>
                                        <td class="text-center">{{ $row->account_name }}</td>
										<td class="text-center">{{ $row->account_number }}</td>
										<td class="text-center">
                                            <span class="badge bg-{{ ($row->is_active) ? 'success' : 'secondary' }}" style="font-size: 10pt;">
                                                {{ ($row->is_active) ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
										<td class="text-center align-middle">{{ \Carbon\Carbon::parse($row->last_modified_at)->format('M d, Y - h:i A') }}</td>
										<td class="text-center align-middle">
											<div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#edit{{ $row->bank_account_id }}">Edit</button>
												<button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#delete{{ $row->bank_account_id }}">Delete</button>
											</div>
										</td>
									</tr>
	
									<!-- Modal -->
									<div class="modal fade" id="delete{{ $row->bank_account_id }}" tabindex="-1" role="dialog">
										<form action="/admin/bank_account/{{ $row->bank_account_id }}/delete" method="POST">
											@csrf
											@method('delete')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Delete Bank Account</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<h6 class="text-center">Delete Bank Account <span class="font-weight-bold">{{ $row->bank_name }}</span> ?</h6>
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
									<div class="modal fade" id="edit{{ $row->bank_account_id }}" tabindex="-1" role="dialog">
										<form action="/admin/bank_account/{{ $row->bank_account_id }}/update" method="POST" enctype="multipart/form-data">
											@csrf
											@method('put')
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Bank Account</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="edit-bank-name{{ $row->bank_account_id }}">Bank Name</label>
                                                            <input type="text" name="bank_name" class="form-control" id="edit-bank-name{{ $row->bank_account_id }}" value="{{ $row->bank_name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="edit-account-name{{ $row->bank_account_id }}">Account Name</label>
                                                            <input type="text" name="account_name" class="form-control" id="edit-account-name{{ $row->bank_account_id }}" value="{{ $row->account_name }}" required>
                                                        </div>
														<div class="form-group">
                                                            <label for="edit-account-number{{ $row->bank_account_id }}">Account Number</label>
                                                            <input type="text" name="account_number" class="form-control" id="edit-account-number{{ $row->bank_account_id }}" value="{{ $row->account_number }}" required>
                                                        </div>
														<div class="form-check">
                                                            <input class="form-check-input" name="is_active" type="checkbox" value="1" id="is_enabled{{ $row->bank_account_id }}" {{ $row->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_enabled{{ $row->bank_account_id }}"> Is Active</label>
                                                        </div>
														<div class="form-check mb-2">
															<input type="checkbox" class="form-check-input edit-show-icon" id="edit-show-icon{{ $row->bank_account_id }}" {{ $row->show_bank_logo ? 'checked' : '' }} name="show_icon" value="1">
															<label class="form-check-label" for="edit-show-icon{{ $row->bank_account_id }}">Show bank icon / logo</label>
														</div>
														<div class="form-group {{ ($row->bank_logo) ? 'd-block' : 'd-none' }} edit-show-icon{{ $row->bank_account_id }}">
															<img src="{{ asset('/storage/bank_account_images/'. $row->bank_logo) }}" alt="{{ $row->bank_logo }}" class="img-thumbnail" id="edit-bank-icon{{ $row->bank_account_id }}-preview">
														</div>
                                                        <div class="form-group edit-show-icon{{ $row->bank_account_id }}">
                                                            <label for="edit-bank-icon{{ $row->bank_account_id }}">Bank Logo</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input edit-bank-icon" id="edit-bank-icon{{ $row->bank_account_id }}" name="bank_logo">
                                                                    <label class="custom-file-label" for="edit-bank-icon{{ $row->bank_account_id }}">{{ $row->bank_logo ? $row->bank_logo : 'Choose file' }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
														<div class="form-group">
															<label for="remarks">Remarks</label>
															<textarea class="form-control" name="remarks" id="remarks" rows="3">{{ $row->remarks }}</textarea>
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
											<h6 class="text-center">No bank account(s) found.</h6>
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

 <div class="modal fade" id="add-bank-account" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
    <form action="/admin/bank_account/save" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bank Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bank-name">Bank</label>
                        <input type="text" name="bank_name" class="form-control" id="bank-name" required>
                    </div>
					<div class="form-group">
                        <label for="account-name">Account Name</label>
                        <input type="text" name="account_name" class="form-control" id="account-name" required>
                    </div>
					<div class="form-group">
                        <label for="account-number">Account Number</label>
                        <input type="text" name="account_number" class="form-control" id="account-number" required>
                    </div>
					<div class="form-check">
						<input class="form-check-input" name="is_active" type="checkbox" value="1" id="is-active" checked>
						<label class="form-check-label" for="is-active"> Is Active</label>
					</div>
					<div class="form-check mb-2">
						<input type="checkbox" class="form-check-input" id="show-icon" checked name="show_icon" value="1">
						<label class="form-check-label" for="show-icon">Show bank icon / logo</label>
					</div>
					<div class="form-group d-none" id="bank-icon-preview-1">
						<img src="#" alt="#" class="img-thumbnail" id="bank-icon-preview-img">
					</div>
                    <div class="form-group" id="bank-icon-browse-file">
                        <label for="bank-icon">Bank Logo</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="bank-icon" name="bank_logo">
                                <label class="custom-file-label" for="bank-icon">Choose file</label>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="remarks">Remarks</label>
						<textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
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
				if($('#bank-icon').val() != '') {
					$('#bank-icon-preview-1').removeClass('d-none').addClass('d-block');
				}
				$('#bank-icon-browse-file').removeClass('d-none').addClass('d-block');
			} else {
				$('#bank-icon-preview-1').removeClass('d-block').addClass('d-none');
				$('#bank-icon-browse-file').removeClass('d-block').addClass('d-none');
			}
		});

		$('#bank-icon').change(function(){
			const file = this.files[0];
			if (file){
				let reader = new FileReader();
				reader.onload = function(event){
					$('#bank-icon-preview-img').attr('src', event.target.result);
				}

				$('#bank-icon-preview-1').removeClass('d-none').addClass('d-block');
				reader.readAsDataURL(file);
			}
		});

		$(document).on('change', '.edit-bank-icon', function() {
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