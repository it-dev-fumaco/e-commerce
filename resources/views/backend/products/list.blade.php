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
											{{-- <div class="col-md-1">
												<div class="form-check mt-2">
													<input type="checkbox" class="form-check-input" id="on-sale" value="1" name="on_sale" {{ (request()->on_sale) ? 'checked' : '' }}>
													<label class="form-check-label" for="on-sale">On Sale</label>
												  </div>
											</div> --}}
											<div class="col-md-1">
												<button type="submit" class="btn btn-secondary">Search</button>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="float-right">
											<div class="dropdown">
												<button class="btn btn-primary dropdown-toggle" type="button" id="dropdowncreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create New Product
												</button>
												<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdowncreate" style="width: 100%;">
													<a class="dropdown-item" href="/admin/product/add/simple_product">Simple Product</a>
													<a class="dropdown-item" href="/admin/product/add/product_bundle">Product Bundle</a>
												</div>
											</div>
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
										{{-- <th class="text-center align-middle" rowspan="2">Price</th> --}}
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
											<span class="d-block font-weight-bold">{{ $item['item_code'] }} <span class="badge badge-success {{ $item['is_new_item'] == 1 ? '' : 'd-none' }}">New Item</span></span> {{ $item['product_name'] }}
										</td>
										<td class="text-center">{{ $item['product_code'] }}</td>
										{{-- <td class="text-center">{{ 'P ' . number_format((float)$item['price'], 2, '.', ',') }}</td> --}}
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
											<a href="/admin/product/{{ $item['id'] }}/featured">{!! ($item['featured']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>' !!}</a>
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
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#pr{{ $item['id'] }}"><small>View Prices</small></a>
												  {{-- @if ($item['on_sale'] == 1) --}}
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sd{{ $item['id'] }}"><small>Disable On Sale</small></a>
												  {{-- @else --}}
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#s{{ $item['id'] }}"><small>Set On Sale</small></a>
												  {{-- @endif --}}
												  @if($item['status'] == 1)
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#d{{ $item['id'] }}"><small>Disable</small></a>
												  @else
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#e{{ $item['id'] }}">Enable</a>
												  @endif
												  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dm{{ $item['id'] }}"><small>Delete</small></a>
												  <a class="dropdown-item" href="/admin/product/images/{{ $item['id'] }}"><small>Images</small></a>
												</div>
											</div>

											<div class="modal fade" id="pr{{ $item['id'] }}" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true" style="z-index: 9999 !important; background-color: rgba(0,0,0, 0.4);">
												<form action="/admin/product/{{ $item['item_code'] }}/disable_on_sale" method="POST">
													@csrf
													<div class="modal-dialog modal-lg" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title"><b>{{ $item['item_code'] }}</b> Price List</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<table class="table table-bordered table-hover table-sm">
																	<thead>
																		<tr>
																			<th>Customer Group</th>
																			<th>Price List</th>
																			<th>Price</th>
																			<th>Status</th>
																			<th>Discount</th>
																			<th>Discounted Price</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td class="text-center">Individual</td>
																			<td>Website Price List</td>
																			<td>₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
																			<td>
																				@if ($item['on_sale'])
																				<span class="badge badge-danger">On Sale</span>
																				@endif
																			</td>
																			<td>
																				@if ($item['on_sale'])
																				@php
																					$discounted_price = ($item['discount_type'] == 'percentage') ? $item['price'] - ($item['price'] * ($item['discount_rate']/100)) : $item['price'] - $item['discount_rate'];
																				@endphp
																				<span class="badge badge-info">
																					@if ($item['discount_type'] == 'percentage')
																					{{ $item['discount_rate'] }}% OFF
																					@else
																					₱ {{ number_format(str_replace(",","",$item['discount_rate']), 2) }} OFF
																					@endif
																				</span>
																				@endif
																			</td>
																			<td>
																				@if ($item['on_sale'])
																				₱ {{ number_format(str_replace(",","", $discounted_price), 2) }}
																				@endif
																			</td>
																		</tr>
																		@forelse ($item['pricelist'] as $price)
																		<tr>
																			<td class="text-center">{{ $price->customer_group_name }}</td>
																			<td>{{ $price->price_list_name }}</td>
																			<td>₱ {{ number_format(str_replace(",","",$price->price), 2) }}</td>
																			<td>
																				@if ($price->on_sale)
																				<span class="badge badge-danger">On Sale</span>
																				@endif
																			</td>
																			<td>
																				@php
																					$discounted_price = ($price->discount_type == 'percentage') ? $price->price - ($price->price * ($price->discount_rate/100)) : $price->price - $price->discount_rate;
																				@endphp
																				<span class="badge badge-info">
																					@if ($price->discount_type == 'percentage')
																					{{ $price->discount_rate }}% OFF
																					@else
																					₱ {{ number_format(str_replace(",","",$price->discount_rate), 2) }} OFF
																					@endif
																				</span>
																			</td>
																			<td>₱ {{ number_format(str_replace(",","", $discounted_price), 2) }}</td>
																		</tr>
																		@empty
																		<tr>
																			<td colspan="4" class="text-center text-muted">No price list found</td>
																		</tr>
																		@endforelse
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="sd{{ $item['id'] }}" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true" style="z-index: 9999 !important; background-color: rgba(0,0,0, 0.4);">
												<form action="/admin/product/{{ $item['item_code'] }}/disable_on_sale" method="POST">
													@csrf
													<div class="modal-dialog modal-lg" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Disable Product On Sale</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p>Select price list to disable on sale for <b>{{ $item['item_code'] }}</b></p>
																<table class="table table-bordered table-hover table-sm">
																	<thead>
																		<tr>
																			<th>Customer Group</th>
																			<th>Price List</th>
																			<th>Price</th>
																			<th>Status</th>
																			<th>Discount</th>
																			<th>Discounted Price</th>
																		</tr>
																	</thead>
																	<tbody>
																		@if ($item['on_sale'])
																		<tr>
																			<td class="text-center">Individual</td>
																			<td class="text-left pl-5">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input" id="personal-cb" name="price_list[]" value="Website Price List">
																					<label class="form-check-label" for="personal-cb">Website Price List</label>
																				</div>
																			</td>
																			<td>₱ {{ number_format(str_replace(",","",$item['price']), 2) }}</td>
																			<td><span class="badge badge-danger">On Sale</span></td>
																			<td>
																				@php
																					$discounted_price = ($item['discount_type'] == 'percentage') ? $item['price'] - ($item['price'] * ($item['discount_rate']/100)) : $item['price'] - $item['discount_rate'];
																				@endphp
																				<span class="badge badge-info">
																					@if ($item['discount_type'] == 'percentage')
																					{{ $item['discount_rate'] }}% OFF
																					@else
																					₱ {{ number_format(str_replace(",","",$item['discount_rate']), 2) }} OFF
																					@endif
																				</span>
																			</td>
																			<td>
																				₱ {{ number_format(str_replace(",","", $discounted_price), 2) }}
																			</td>
																		</tr>
																		@endif
																		@forelse (collect($item['pricelist'])->where('on_sale', 1) as $price)
																		<tr>
																			<td class="text-center">{{ $price->customer_group_name }}</td>
																			<td class="text-left pl-5">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input" id="business-cb{{ $price->item_price_id }}" name="price_list[]" value="{{ $price->item_price_id }}">
																					<label class="form-check-label" for="business-cb{{ $price->item_price_id }}">{{ $price->price_list_name }}</label>
																				</div>
																			</td>
																			<td>₱ {{ number_format(str_replace(",","",$price->price), 2) }}</td>
																			<td><span class="badge badge-danger">On Sale</span></td>
																			<td>
																				@php
																					$discounted_price = ($price->discount_type == 'percentage') ? $price->price - ($price->price * ($price->discount_rate/100)) : $price->price - $price->discount_rate;
																				@endphp
																				<span class="badge badge-info">
																					@if ($price->discount_type == 'percentage')
																					{{ $price->discount_rate }}% OFF
																					@else
																					₱ {{ number_format(str_replace(",","",$price->discount_rate), 2) }} OFF
																					@endif
																				</span>
																			</td>
																			<td>₱ {{ number_format(str_replace(",","", $discounted_price), 2) }}</td>
																		</tr>
																		@empty
																		<tr>
																			@if (!$item['on_sale'])
																			<td colspan="4" class="text-center text-muted">No price list found</td>
																			@endif
																		</tr>
																		@endforelse
																	</tbody>
																</table>
															</div>
															<div class="modal-footer">
																@if (collect($item['pricelist'])->where('on_sale', 1)->count() > 0 || $item['on_sale'])
																<button type="submit" class="btn btn-primary">Submit</button>
																@endif
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</form>
											</div>

											<div class="modal fade" id="s{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="onsalemodal" aria-hidden="true">
												<form action="/admin/product/{{ $item['item_code'] }}/enable_on_sale" method="POST" enctype="multipart/form-data">
													@csrf
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Set Product On Sale</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-12">
																		<div class="row">
																			<div class="col-12">
																				<div class="form-group col-8 mx-auto">
																					<label>Select Customer Group</label>
																					<select name="customer_group" class="form-control">
																						@foreach ($customer_groups as $customer_group)
																						<option value="{{ $customer_group->id }}">{{ $customer_group->customer_group_name }}</option>
																						@endforeach
																					</select>
																				</div>
																				<div class="form-group col-8 mx-auto d-none">
																					<label>Select Price List</label>
																					<select name="price_list_id" class="form-control">
																						@foreach ($item['pricelist'] as $pricelist)
																						<option value="{{ $pricelist->item_price_id }}">{{ $pricelist->price_list_name . ' - ₱ ' . number_format(str_replace(",","",$pricelist->price), 2) }}</option>
																						@endforeach
																					</select>
																				</div>
																				<div class="form-group col-8 mx-auto">
																					<label>Select Discount Type</label>
																					<select name="discount_type" class="form-control">
																						<option value="percentage">Percentage</option>
																						<option value="amount">Amount</option>
																					</select>
																				</div>
																				<div class="form-group col-8 mx-auto">
																					<label>Enter Discount Rate</label>
																					<input type="number" class="form-control" name="discount_rate" placeholder="Rate" min="0" value="0" required>
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

		$(document).on('change', 'select[name="customer_group"]', function(){
			var pricelist_el = $(this).parent().parent().parent().find('select[name="price_list_id"]').eq(0);
			if ($(this).val() === 'Individual') {
				pricelist_el.attr('required', true).parent().addClass('d-none');
				pricelist_el.attr('required', true).parent().addClass('d-none');
			} else {
				pricelist_el.removeAttr('required').parent().removeClass('d-none');
				pricelist_el.removeAttr('required').parent().removeClass('d-none');
			}
		});
	</script>
@endsection