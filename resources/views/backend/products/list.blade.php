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
									<div class="col-md-10">
										<div class="form-group row">
											<div class="col-md-3">
											<input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
											</div>
											<div class="col-md-2">
												<input type="text" class="form-control" id="search-box" name="parent_code" placeholder="Parent Code" value="{{request()->get('parent_code')}}">
											</div>
											<div class="col-md-2">
												<select class="form-control" name="brands">
                                                    <option {{ (request()->get('brands') == '') ? 'selected' : '' }} disabled value="">Select Brand</option>
                                                    @foreach ($brands as $b)
														<option {{ (request()->get('brands') == $b->f_brand) ? 'selected' : ''}} value="{{ $b->f_brand }}">{{ $b->f_brand }}</option>
													@endforeach
                                                </select>
											</div>
											<div class="col-md-2">
												<select class="form-control" name="category">
                                                    <option {{ (request()->get('category') == '') ? 'selected' : '' }}  disabled value="">Select Category</option>
                                                    @foreach ($categories as $c)
														<option {{ (request()->get('category') == $c->id) ? 'selected' : ''}} value="{{ $c->id }}">{{ $c->name }}</option>
													@endforeach
                                                </select>
											</div>
											<div class="col-md-1">
												<div class="form-check mt-2">
													<input type="checkbox" class="form-check-input" id="is_featured" value="1" name="is_featured" {{ (request()->is_featured) ? 'checked' : '' }}>
													<label class="form-check-label" for="is_featured">Is Featured</label>
												  </div>
											</div>
											<div class="col-md-1">
												<div class="form-check mt-2">
													<input type="checkbox" class="form-check-input" id="on-sale" value="1" name="on_sale" {{ (request()->on_sale) ? 'checked' : '' }}>
													<label class="form-check-label" for="on-sale">On Sale</label>
												  </div>
											</div>
											<div class="col-md-1">
												<button type="submit" class="btn btn-primary">Search</button>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="float-right">
											<a href="/admin/product/add" class="btn btn-primary">Create New Product</a>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center align-middle" rowspan="2">Image</th>
										<th class="text-center align-middle" rowspan="2">Item Name</th>
										<th class="text-center align-middle" rowspan="2">Parent</th>
										<th class="text-center align-middle" rowspan="2">Price</th>
										<th class="text-center p-1" colspan="3">Inventory</th>
										<th class="text-center align-middle" rowspan="2">Category</th>
										<th class="text-center align-middle" rowspan="2">Brand</th>
										<th class="text-center align-middle" rowspan="2">Featured</th>
										<th class="text-center align-middle" rowspan="2">On Sale</th>
										<th class="text-center align-middle" rowspan="2">Status</th>
										<th class="text-center align-middle" rowspan="2">Action</th>
									</tr>
									<tr>
										<th class="text-center p-1">Actual</th>
										<th class="text-center p-1">Reserved</th>
										<th class="text-center p-1">Available</th>
									
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $item)
									<tr>
										<td class="align-middle">
											@php
												$image_webp = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.png';
												$image = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
											@endphp
											 <picture>
												<source srcset="{{ asset($image_webp) }}" type="image/webp" class="img-responsive rounded  d-inline-block" alt="" width="70" height="70">
												<source srcset="{{ asset($image) }}" type="image/jpeg" class="img-responsive rounded  d-inline-block" alt="" width="70" height="70"> 
												<img src="{{ asset($image) }}" alt="{{ $item['item_code'] }}" class="img-responsive rounded  d-inline-block" alt="" width="70" height="70">
											</picture>
										</td>
										<td>
											<span class="d-block font-weight-bold">{{ $item['item_code'] }}</span> {{ $item['product_name'] }}
										</td>
										<td class="text-center">{{ $item['product_code'] }}</td>
										<td class="text-center">{{ 'P ' . number_format((float)$item['price'], 2, '.', ',') }}</td>
										<td class="text-center">{{ number_format($item['qty']) }}<br>
											@if($item['erp_stock'])
											<span class="badge badge-info">ERP Stock</span>
											@endif
										</td>
										<td class="text-center">{{ number_format($item['reserved_qty']) }}</td>
										<td class="text-center font-weight-bold" style="font-size: 1.2rem;">{{ number_format($item['qty'] - $item['reserved_qty']) }}</td>
										<td class="text-center">{{ $item['product_category'] }}</td>
										<td class="text-center">{{ $item['brand'] }}</td>
										<td class="text-center" style="font-size: 1.2rem;">
											<a href="/admin/product/{{ $item['id'] }}/featured">	{!! ($item['featured']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>' !!}</a>
										</td>
										<td class="text-center">
											@if ($item['on_sale'] == 1)
												 <span class="badge badge-danger">On Sale</span>
											@endif
										</td>
										<td class="text-center">
											@if ($item['status'] == 1)
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
												  <a class="dropdown-item" href="/admin/product/{{ $item['id'] }}/edit">View Details</a>
												  @if ($item['on_sale'] == 1)
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#onsale-details-{{ $item['id'] }}"><small>View On Sale Details</small></a>
												  {{-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sd{{ $item['id'] }}"><small>Disable On Sale</small></a> --}}
												  @else
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#s{{ $item['id'] }}"><small>Set On Sale</small></a>
												  @endif
												  @if($item['status'] == 1)
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#d{{ $item['id'] }}"><small>Disable</small></a>
												  @else
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#e{{ $item['id'] }}">Enable</a>
												  @endif
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dm{{ $item['id'] }}"><small>Delete</small></a>
												  <a class="dropdown-item" href="/admin/product/images/{{ $item['id'] }}"><small>Images</small></a>
												</div>
											</div>

											<div class="modal fade" id="sd{{ $item['id'] }}" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true" style="z-index: 9999 !important; background-color: rgba(0,0,0, 0.4);">
												<form action="/admin/product/{{ $item['item_code'] }}/disable_on_sale" method="POST">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Disable Product On Sale</h5>
															</div>
															<div class="modal-body">
																<p>Disable <b>{{ $item['item_code'] }}</b> on sale?</p>
																<p>Original Price: <b>{{ 'P ' . number_format((float)$item['price'], 2, '.', ',') }}</b></p>
																<p>Discount (%): <b>{{ $item['discount_percentage'] }}%</b></p>
																<p>Discounted Price: <b>{{ 'P ' . number_format((float)$item['new_price'], 2, '.', ',') }}</b></p>
															</div>
															<div class="modal-footer">
																<button type="submit" class="btn btn-primary">Submit</button>
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="s{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/enable_on_sale" method="POST" enctype="multipart/form-data">
													@csrf
													<div class="modal-dialog modal-xl" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Set Product On Sale</h5>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-12">
																		<p>Original Price: <b>{{ 'P ' . number_format((float)$item['price'], 2, '.', ',') }}</b></p>
																		<div class="row">
																			<div class="col-6 mx-auto">
																				<div class="form-group col-8 mx-auto">
																					<label>Enter Discount Percentage (%)</label>
																					<input type="number" class="form-control" name="discount_percentage" placeholder="Discount %" required>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-6">
																		<div class="form-group col-8 mx-auto">
																			<label for="img_primary">"On Sale" Image Primary</label>
																			<div class="input-group">
																				<div class="custom-file text-left">
																					<input type="file" class="custom-file-input" name="on_sale_img_primary">
																					<label id="primary_label" class="custom-file-label">Choose file</label>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-6">
																		<div class="form-group col-8 mx-auto">
																			<label for="img_zoom">"On Sale" Image Zoom</label>
																			<div class="input-group">
																				<div class="custom-file text-left">
																					<input type="file" class="custom-file-input" name="on_sale_img_zoom">
																					<label id="zoom_label" class="custom-file-label">Choose file</label>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="modal-footer">
																<button type="submit" class="btn btn-primary">Submit</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="onsale-details-{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/enable_on_sale" method="POST" enctype="multipart/form-data">
													@csrf{{-- For adding "On Sale" Images --}}
												<div class="modal-dialog modal-xl" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">On Sale Details</h5>
														</div>
														<div class="modal-body">
															<div class="row">
																<div class="col-12">
																	<p>Original Price: <b>{{ 'P ' . number_format((float)$item['price'], 2, '.', ',') }}</b></p>
																	<p>Discount Percentage (%): <b>{{ $item['discount_percentage'] }}</b></p>
																	<input type="checkbox" name="on_sale_enabled" checked readonly hidden>
																</div>
															</div>
															
															<div class="row">
																<div class="col-6">
																	<div class="form-group col-8 mx-auto">
																		<label for="img_primary">"On Sale" Image Primary</label>
																		<div class="input-group">
																			<div class="custom-file text-left">
																				<input type="file" class="custom-file-input" name="on_sale_img_primary">
																				<label id="primary_label" class="custom-file-label">Choose file</label>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-6">
																	<div class="form-group col-8 mx-auto">
																		<label for="img_zoom">"On Sale" Image Zoom</label>
																		<div class="input-group">
																			<div class="custom-file text-left">
																				<input type="file" class="custom-file-input" name="on_sale_img_zoom">
																				<label id="zoom_label" class="custom-file-label">Choose file</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-12">
																	<table class="table table-hover">
																		<tr>
																			<th>ID</th>
																			<th>Primary Image</th>
																			<th>Original Image</th>
																			<th>Action</th>
																		</tr>
																		@forelse ($item['on_sale_image'] as $onsaleimg)
																			<tr>
																				<td>{{ $onsaleimg['id'] }}</td>
																				<td>{{ $onsaleimg['orig'] }}</td>
																				<td>{{ $onsaleimg['primary'] }}</td>
																				<td>
																					<a type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal">
																						Delete
																					</a>

																					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																						<div class="modal-dialog" role="document" style="background-color: rgba(0,0,0, 0.4) !important">
																							<div class="modal-content">
																								<div class="modal-header">
																									<h5 class="modal-title" id="exampleModalLabel">Delete</h5>
																								</div>
																								<div class="modal-body">
																									Delete On Sale Image?
																								</div>
																								<div class="modal-footer">
																									<a href="/admin/delete_product_image/{{ $onsaleimg['id'] }}" class="btn btn-sm btn-danger">Delete</a>
																								</div>
																							</div>
																						</div>
																					</div>
																				</td>
																			</tr>
																		@empty
																			<tr>
																				<td colspan=4 class="text-center">No "On Sale" Images</td>
																			</tr>
																		@endforelse
																	</table>
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Upload</button>
															<a class="btn btn-danger" href="#" data-toggle="modal" data-target="#sd{{ $item['id'] }}"><small>Disable On Sale</small></a>
														</div>
													</div>
												</div>
												</form>
											</div>

											<div class="modal fade" id="d{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/disable" method="POST">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Disable Product</h5>
															</div>
															<div class="modal-body">
																<p>Disable <b>{{ $item['item_code'] }}</b>?</p>
															</div>
															<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Confirm</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="e{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="enablemodal" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/enable" method="POST">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Set Product as Active</h5>
															</div>
															<div class="modal-body">
																<p>Set <b>{{ $item['item_code'] }}</b> as "Active"?</p>
															</div>
															<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Confirm</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="dm{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="deletemodel" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/delete" method="POST">
													@csrf
													@method('delete')
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Delete Product</h5>
															</div>
															<div class="modal-body">
																<p>Delete item <b>{{ $item['item_code'] }}</b>?</p>
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
								Total Item(s) : <b>{{ $product_list->total() }}</b>
							</div>
							<div class="float-right mt-4">
								{{ $product_list->withQueryString()->links('pagination::bootstrap-4') }}
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
 	<script>
		 // Add the following code if you want the name of the file appear on select
		$(".custom-file-input").change(function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	</script>
@endsection