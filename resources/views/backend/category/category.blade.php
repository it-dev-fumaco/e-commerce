@extends('backend.layout', [
'namePage' => 'Products Categories',
'activePage' => 'product_category'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Categories List Page</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Categories List Page</li>
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
							<div class="card-header">
								<h3 class="card-title">List Category</h3>
							</div>
							<div class="card-body">
								@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
								@endif
								@if(session()->has('error'))
								<div class="alert alert-danger">
									{{ session()->get('error') }}
								</div>
								@endif
								@php
									$category_icons = ['nav1.jpg','nav2.jpg','nav3.jpg','nav4.jpg','nav5.jpg','nav6.jpg','nav7.jpg','nav8.jpg','nav9.jpg','nav10.jpg','nav11.jpg','nav12.jpg','nav13.jpg','nav14.jpg','nav16.jpg','nav17.jpg','nav18.jpg','nav19.jpg','icons_27_Fumaco-Water.jpg','icons_26_Wall-lights.jpg','icons_25_Tracklights.jpg','icons_24_Striplights.jpg','icons_23_Bollard.jpg','icons_22_Downlight-Recessed.jpg','icons_21_Electrical-Boxes.jpg','icons_20_Sockets.jpg','icons_19_Switches.jpg','icons_18_Panel-Board.jpg','icons_17_Circuit-Breaker.jpg','icons_16_Batten Type.jpg','icons_15_IP-rated-Luminaire.jpg'];
								@endphp
								<table id="example2" data-pagination="true" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Code</th>
											<th>Name</th>
											<th>image</th>
											<th>slug</th>
											<th>Action</th>
											<th class="text-center">Publish</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($categories as $c)
										<tr>
											<td>{{ $c->id }}</td>
											<td>
												<div class="row">
													@if ($c->banner_img)
														<div class="col-3 text-center">
															<img src="{{ asset('assets/site-img/'.$c->banner_img) }}" width="80">
														</div>	
													@endif
													<div class="col-{{ $c->banner_img ? 9 : 12 }}">
														@php
															$is_new = 0;
															if($c->new > 0){
																if($c->new_tag_start && $c->new_tag_end){
																	$start = Carbon\Carbon::parse($c->new_tag_start)->startOfDay();
																	$end = Carbon\Carbon::parse($c->new_tag_end)->endOfDay();

																	if(Carbon\Carbon::now() > $start && Carbon\Carbon::now() < $end){
																		$is_new = 1;
																	}
																}
															}
														@endphp
														{{ $c->name }} <span class="badge badge-primary {{ $is_new == 0 ? 'd-none' : null }}">New</span>
													</div>
												</div>
											</td>
											<td><img src="{{ asset('assets/site-img/icon/')."/".$c->image }}" width="30" ></td>
											<td>{{ $c->slug }}</td>
											<td>
												<button type="button" class="btn btn-info btn-sm active" data-toggle="modal" data-target="#PPPEdit{{ $c->id }}">Edit</button>
												<div id="PPPEdit{{ $c->id }}" class="modal fade" role="dialog">
													<div class="modal-dialog modal-xl">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Edit Category : {{ $c->name }}</h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-md-12">{{-- Edit Category Start --}}
																			<form role="form" action="/admin/category/edit/{{ $c->id }}" method="post" enctype="multipart/form-data">
																				@csrf
																				<div class="card-body">
																					<div class="row">
																						<div class="col-8 offset-2 mb-2">
																							@if ($c->banner_img)
																								<a href="#" data-toggle="modal" data-target="#preview-banner-{{ $c->id }}">
																									<img src="{{ asset('assets/site-img/'.$c->banner_img) }}" width="100%">
																								</a>

																								<!-- Modal -->
																								<div class="modal fade" id="preview-banner-{{ $c->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																									<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
																										<div class="modal-content">
																											<div class="modal-header">
																												{{-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> --}}
																												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																													<span aria-hidden="true">&times;</span>
																												</button>
																											</div>
																											<div class="modal-body">
																												<img src="{{ asset('assets/site-img/'.$c->banner_img) }}" width="100%">
																											</div>
																										</div>
																									</div>
																								</div>
																							@endif
																						</div>
																						<div class="col-6">
																							<div class="form-group">
																								<label for="add_cat_name">Banner Image : </label>
																								<div class="custom-file">
																									<input type="file" class="custom-file-input" id="customFile" name="banner_img">
																									<label class="custom-file-label" for="customFile">Choose File</label>
																								</div>
																							</div>
																						</div>
																						{{-- <div class="col-6">&nbsp;</div> --}}
																						<div class="col-md-6">
																							<div class="form-group">
																								<label for="edit_cat_name">Category Name :</label>
																								<input type="text" class="form-control" id="edit_cat_name" name="edit_cat_name" value="{{ $c->name }}" required>
																							</div>
																						</div>
																						<div class="col-6">
																							<div class="form-group">
																								<label for="x2">Image : </label>
																								<br>
																								@foreach ($category_icons as $icon1)
																									<div class="form-check form-check-inline">
																										<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="{{ $icon1 }}" {{ $icon1 == $c->image ? 'checked' : null }} required>
																										<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/'.$icon1) }}" width="30" ></label>
																									</div>
																								@endforeach
																							</div>
																							<div class="form-check mt-4 mb-3">
																								<input type="checkbox" class="form-check-input edit_is_external_link" id="externalLink{{ $c->id }}" name="edit_is_external_link" value="1" {{ ($c->external_link) ? 'checked' : '' }} data-tid="external-link{{ $c->id }}">
																								<label class="form-check-label" for="externalLink{{ $c->id }}">External Link</label>
																							</div>
																							<div class="form-group {{ (!$c->external_link) ? 'd-none' : '' }}">
																								<label for="external-link{{ $c->id }}">External Link : </label>
																								<input type="text" class="form-control" id="external-link{{ $c->id }}" name="external_link" value="{{ $c->external_link }}">
																							</div>
																							<div class="form-check">
																								<input type="checkbox" name="set_as_new" class="form-check-input set_as_new" data-target="{{ $c->id }}" {{ $is_new == 1 ? 'checked' : null }}><label class="form-check-label">
																									Set as "New"
																									@if($c->new > 0 && $c->new_tag_start && $c->new_tag_end)
																										({{ Carbon\Carbon::parse($c->new_tag_start)->format('M d, Y').' - '.Carbon\Carbon::parse($c->new_tag_end)->format('M d, Y') }})
																									@endif
																								</label> <br>
																								<input type="text" id="d-{{ $c->id }}" class="new-duration w-100 form-control" name="new_tag_duration" style="display: {{ $is_new == 1 ? 'block' : 'none' }}">
																							</div>
																							<div class="form-group text-right">
																								<input class="form-check-input" name="hide_na" type="checkbox" {{ ($c->hide_none == 1) ? 'checked' : '' }}/>
																								<label for="edit_cat_slug">Hide "N/A" Attribute Values </label>
																							</div>
																						</div>
																						<div class="col-md-6">
																							<input type="text" name="id" value="{{ $c->id }}" hidden readonly>
																							<div class="form-group">
																								<label for="cat_meta_keywords">Meta Keywords :</label>
																								<input type="text" class="form-control" id="edit_cat_name" name="cat_meta_keywords" value="{{ $c->meta_keywords }}">
																							</div>
																							<div class="form-group">
																								<label for="cat_meta_desc">Meta Description :</label>
																								<input type="text" class="form-control" id="edit_cat_name" name="cat_meta_desc" value="{{ $c->meta_description }}">
																							</div>
																							<div class="form-group">
																								<label for="edit_cat_slug">Slug :</label>
																								<input type="text" class="form-control" id="edit_cat_slug" name="edit_cat_slug" value="{{ $c->slug }}">
																							</div>
																							<div class="float-right font-italic">
																								<small>Last modified by: {{ $c->last_modified_by }} - {{ $c->last_modified_at }}</small><br>
																								<small>Created by: {{ $c->created_by }} - {{ $c->created_at }}</small>
																							</div>
																						</div>
																					</div>
																				</div>
																				<!-- /.card-body -->
																				<div>
																					<center><input type="submit" class="btn btn-primary" value="Update"></center>
																				</div>
																			</form>
																		<!-- /.card -->
																	</div>{{-- Edit Category End --}}
																</div>
															</div>

														</div>
													</div>
												</div>
												<a href="{{ (!$c->external_link) ? '/admin/category/settings/' . $c->id : '' }}" class="btn btn-success btn-sm active  {{ ($c->external_link) ? 'disabled' : '' }}" role="button" aria-pressed="true">Sort Items</a>
												<a href="/admin/category/delete/{{ $c->id }}" class="float-right btn btn-danger btn-sm active" role="button" aria-pressed="true">Delete</a>
											</td>
											<td class="col-sm-1">
												<center>
													<label class="switch">
														<input type="checkbox" class="toggle" id="toggle_{{ $c->id }}" name="publish" {{ ($c->publish == 1) ? 'checked' : '' }} value="{{ $c->id }}"/>
														<span class="slider round"></span>
													</label>
												</center>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-12">{{-- Add Category --}}
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">Add Category</h3>
							</div>
							<form role="form" action="/admin/category/add" method="post" enctype="multipart/form-data">
								@csrf
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="add_cat_name">Category Name : </label>
												<input type="text" class="form-control" id="add_cat_name" name="add_cat_name" value="" required>
											</div>
											<div class="form-group">
												<label for="add_cat_name">Banner Image : </label>
												<div class="custom-file mb-3">
													<input type="file" class="custom-file-input" id="customFile" name="banner_img">
													<label class="custom-file-label" for="customFile">Choose File</label>
												</div>
											</div>
											<div class="form-group">
												<label for="x2">Image : </label>
												<br/>
												@foreach ($category_icons as $icon2)
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="{{ $icon2 }}" required>
														<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/'.$icon2) }}" width="30" ></label>
													</div>
												@endforeach
												<br>
											</div>
											<div class="form-check">
												<label><input type="checkbox" class="form-check-input set_as_new" data-target="duration" name="is_new"> "New" Tag Duration</label>
												<input type="text" name="new_tag_duration" class="form-control new-duration" id="d-duration" style="display: none">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check mt-4 mb-3">
												<input type="checkbox" class="form-check-input" id="externalLink" name="is_external_link" value="1">
												<label class="form-check-label" for="externalLink">External Link</label>
											 </div>
											<div class="form-group d-none" id="external-link-div">
												<label for="external-link">External Link : </label>
												<input type="text" class="form-control" id="external-link" name="external_link">
											</div>
											<div class="form-group">
												<label for="cat_meta_keywords">Meta Keywords :</label>
												<input type="text" class="form-control" name="add_meta_keywords">
											</div>
											<div class="form-group">
												<label for="cat_meta_desc">Meta Description :</label>
												<input type="text" class="form-control" name="add_meta_desc">
											</div>
											<div class="form-group">
												<label for="edit_cat_slug">Slug :</label>
												<input type="text" class="form-control" name="add_cat_slug">
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="submit" class="btn btn-primary" value="Add Category">
								</div>
							</form>
						</div>
						<!-- /.card -->
					</div>
				</div>
			</div>
		</section>
	</div>
</div> 
<style>
	.modal{
		background-color: rgba(0,0,0,0.4);
	}
	.switch {
		position: relative;
		display: inline-block;
		width: 30px;
		height: 16px;
	}

	.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 10px;
		width: 10px;
		left: 3px;
		bottom: 3px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked + .slider {
		background-color: #2196F3;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(16px);
		-ms-transform: translateX(16px);
		transform: translateX(16px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
</style>
@endsection
@section('script')
<script>
  	$(document).ready(function() {
		// Add the following code if you want the name of the file appear on select
        $(".custom-file-input").change(function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

		$('input[name="is_external_link"]').prop('checked', false);
	  	$('input[name="is_external_link"]').click(function(){
			if ($(this).prop('checked')) {
				$('#external-link-div').removeClass('d-none');
				$('#external-link').attr('required', true);
			} else {
				$('#external-link-div').addClass('d-none');
				$('#external-link').removeAttr('required');
			}
	  	});

		$(document).on('click', '.edit_is_external_link', function(){
			var tid = $(this).data('tid');
			if ($(this).prop('checked')) {
				$('#' + tid).closest('.form-group').removeClass('d-none');
				$('#' + tid).attr('required', true);
			} else {
				$('#' + tid).closest('.form-group').addClass('d-none');
				$('#' + tid).removeAttr('required');
			}
		});

		$(".toggle").change(function(){
			var data = {
				'publish': $(this).prop('checked') == true ? 1 : 0,
				'cat_id': $(this).val(),
				'_token': "{{ csrf_token() }}",
			}
			$.ajax({
				type:'POST',
				url:'/admin/category/publish',
				data: data,
				success: function (response) {
					console.log(status);
				},
				error: function () {
					alert('An error occured.');
				}
			});
		});

		$('.new-duration').daterangepicker({
			opens: 'left',
			startDate: moment(),
			endDate: moment().add(7, 'days'),
			minDate: moment(),
			locale: {
				format: 'MMM DD, YYYY'
			}
		});

		$(document).on('click', ".set_as_new", function(){
			var target = $(this).data('target');
			if($(this).is(':checked')){
				$('#d-' + target).slideDown();
			}else{
				$('#d-' + target).slideUp();
			}
		});
	});
</script>
@endsection