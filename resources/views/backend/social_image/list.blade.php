@extends('backend.layout', [
	'namePage' => 'Social Images',
	'activePage' => 'social_images'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">List of Social Images</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Social Images</li>
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
									<div class="col-md-10">
										<div class="form-group row">
											<div class="col-md-3">
											    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
											</div>
											<div class="col-md-1">
												<button type="submit" class="btn btn-secondary">Search</button>
											</div>
										</div>
									</div>
									<div class="col-md-2">
                                        <div class="float-right">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#addSocialImage" type="button">Add Social Image</button>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center align-middle" style="width: 10%;">Image</th>
										<th class="text-center align-middle" style="width: 25%;">Filename</th>
										<th class="text-center align-middle" style="width: 10%;">Page</th>
										<th class="text-center align-middle" style="width: 15%;">Category</th>
										<th class="text-center align-middle" style="width: 10%;">Is Default</th>
										<th class="text-center align-middle" style="width: 10%;">Created by</th>
                                        <th class="text-center align-middle" style="width: 10%;">Last modified at</th>
										<th class="text-center align-middle" style="width: 10%;">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $row)
									<tr>
										<td class="text-center align-middle">
											@php
												$image_webp = ($row->filename) ? '/storage/social_images/'.explode(".", $row->filename)[0] .'.webp' : '/storage/no-photo-available.png';
												$image = ($row->filename) ? '/storage/social_images/'. $row->filename : '/storage/no-photo-available.png';
											@endphp
											 <picture>
												<source srcset="{{ asset($image_webp) }}" type="image/webp" class="img-responsive rounded  d-inline-block" alt="{{ $row->filename }}" width="70" height="70">
												<source srcset="{{ asset($image) }}" type="image/jpeg" class="img-responsive rounded  d-inline-block" alt="{{ $row->filename }}" width="70" height="70"> 
												<img src="{{ asset($image) }}" class="img-responsive rounded  d-inline-block" alt="{{ $row->filename }}" width="70" height="70">
											</picture>
										</td>
										<td>{{ $row->filename }}</td>
										<td class="text-center">{{ ($row->page_type == 'main_page') ? 'Main Page' : 'Product Category' }}</td>
										<td class="text-center">{{ ($row->page_type != 'main_page') ? $product_categories[$row->category_id] : null }}</td>
										<td class="text-center align-middle">
											<div class="form-group">
												<div class="custom-control custom-switch">
													<input type="checkbox" class="custom-control-input toggle" id="toggle_{{ $row->id }}" {{ ($row->is_default == 1) ? 'checked' : '' }} value="{{ $row->id }}">
													<label class="custom-control-label" for="toggle_{{ $row->id }}"></label>
												</div>
											</div>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->last_modified_by }}</td>
                                        <td class="text-center align-middle">{{ $row->last_modified_at }}</td>
										<td class="text-center align-middle">
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{ $row->id }}" type="button">Delete</button>

                                            <div class="modal fade" id="delete{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="deletemodal" aria-hidden="true">
                                                <form action="/admin/marketing/social/delete/{{ $row->id }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deletemodal">Delete Social Media Image</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete image {{ $row->filename }}?
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
							<div class="float-left mt-4">
								Total Item(s) : <b>{{ $list->total() }}</b>
							</div>
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

 <div class="modal fade" id="addSocialImage" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
    <form action="/admin/marketing/social/create" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Social Media Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="social-image">Select Image (600 x 315 pixels) *</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="social-image" name="img" required>
                            <label class="custom-file-label" for="social-image">Choose file</label>
                        </div>
                    </div>
					<div class="form-group">
						<label for="page-type">Page Type *</label>
						<select class="form-control" id="page-type" name="page_type" required>
							<option value="main_page">Main Page</option>
							<option value="product_category">Product Category</option>
						</select>
					</div>
					<div class="form-group d-none">
						<label for="product-category">Category *</label>
						<select class="form-control" id="product-category" name="product_category">
							<option value="">Select Category</option>
							@foreach ($product_categories as $i => $category)
							<option value="{{ $i }}">{{ $category }}</option>
							@endforeach
						</select>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
 	<script>
		 // Add the following code if you want the name of the file appear on select
		$(".custom-file-input").change(function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

		$(document).on('change', '.toggle', function(e){
			e.preventDefault();
			$.ajax({
				type:'get',
				url:'/admin/marketing/social/default/' + $(this).val(),
				success: function (response) {
					window.location.href="/admin/marketing/social/images";
				},
				error: function () {
					alert('An error occured.');
				}
			});
		});

		$('#page-type').change(function(e){
			e.preventDefault();
			if ($(this).val() != 'main_page') {
				$('#product-category').parent().removeClass('d-none');
				$('#product-category').attr('required', true);
			} else {
				$('#product-category').parent().addClass('d-none');
				$('#product-category').removeAttr('required');
			}
		});
	</script>
@endsection