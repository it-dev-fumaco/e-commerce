@extends('frontend.layout', [
    'namePage' => $product_category->name,
    'activePage' => 'product_list'
])
@section('meta')
<meta name="description" content="{{ $product_category->meta_description }}">
	<meta name="keywords" content="{{ $product_category->meta_keywords }}" />

	<meta property="og:url" content="https://www.fumaco.com/products/{{ ($product_category->slug) ? $product_category->slug : $product_category->id }}" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $product_category->name }}" />
	<meta property="og:description" content="{{ $product_category->meta_description }}" />
	@if ($image_for_sharing)
	<meta property="og:image" content="{{ $image_for_sharing }}" />
	@endif
@endsection
@section('content')
	@php
		$page_title = $product_category->name;
	@endphp
	@include('frontend.header')

	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 14px !important;">
				<li class="breadcrumb-item">
					<a href="/" style="color: #000000 !important; text-decoration: none;">Home</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="#" style="color: #000000 !important; text-decoration: underline;">{{ $product_category->name }}</a>
				</li>
			</ol>
		</nav>
		<hr class="singleline">
	</main>

	<main style="background-color:#ffffff;" class="products-head prod-list-pad">
		<div class="container" style="max-width: 100% !important;">
			<div class="row">
				<!--sidebar-->
				<div class="col-lg-2 checkersxx d-none d-lg-block">
					<div class="d-flex justify-content-between align-items-center he1" style="font-weight: 500 !important"><b>Filter Results</b>
						<a href="/products/{{ ($product_category->slug) ? $product_category->slug : $product_category->id }}" style="text-decoration: none;">
							<small class="stylecap he2 text-dark" style="font-weight:400 !important;">Clear All</small>
						</a>
					</div>
					<hr>
					<form action="/products/{{ ($product_category->slug) ? $product_category->slug : $product_category->id }}" method="POST" id="filter-form" class="mb-5">
						@csrf
						@php
							$a = 0;
						@endphp
						@if (count($filters['Brand']) > 1)
						<div class="card mb-3">
							<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">BRAND</div>
							<div class="card-body">
								@foreach ($filters['Brand'] as $brand)
								@php
									$a++;
									$filter_attr = Str::slug('brand', '-');
									$filter_values = explode('+', request()->brand);
									$status = (in_array($brand, $filter_values)) ? 'checked' : '';
								@endphp
								<div class="form-check">
									<input type="checkbox" class="form-check-input product-cb-filter" id="{{ 'cbb' . $a }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $brand }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
									<label class="form-check-label" for="{{ 'cbb' . $a }}" style="font-size: 0.8rem;">{{ $brand }}</label>
								</div>
							@endforeach
							</div>
						</div>
						@endif
						@php
							$x = 0;
						@endphp
						@foreach ($filters as $id => $row)
						@php
							$filter_attr = Str::slug($id, '-');
						@endphp
						@if ($id != 'Brand')
						@if (count($row) > 1 || request()->$filter_attr)
						<div class="card mb-3">
							<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">{{ strtoupper($id) }}</div>
							<div class="card-body">
								@foreach ($row as $attr_val)
								@php
									$x++;
									$filter_values = explode('+', request()->$filter_attr);
									$status = (in_array($attr_val, $filter_values)) ? 'checked' : '';
								@endphp
								<div class="form-check" style="word-wrap:break-word !important;">
									<input type="checkbox" class="form-check-input product-cb-filter" id="{{ 'cb' . $x }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $attr_val }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
									<label class="form-check-label" for="{{ 'cb' . $x }}" style="font-size: 0.8rem;">{{ $attr_val }}</label>
								</div>
								@endforeach
							</div>
						</div>
						@endif
						@endif

						@endforeach
						<input type="hidden" name="sortby" value="{{ request()->sortby }}">
						<input type="hidden" name="sel_attr" value="{{ request()->sel_attr }}">
					</form>
				</div>
				<!--sidebar-->

				<!--products-->
				<div class="col-lg-10">
					<div class="row g-6">
						<form id="sortForm" class="d-inline-block">
							<a class="btn d-md-block d-lg-none filter-btn open-modal" data-target="#filterModal2" style="font-size: 0.75rem !important; float: left !important">
								<i class="fas fa-filter"></i>&nbsp; Filters
							</a>
							<div class="col-md-4 offset-md-8">
								
								<div class="d-flex justify-content-end prod-sort">
									<div>
										<a class="btn d-sm-block d-md-none open-modal" data-target="#filterModal2" style="font-size: 0.75rem !important; float: left !important; margin-top: 6%">
											<i class="fas fa-filter"></i>&nbsp; Filters
										</a>
									</div>
									<div class="p-2"><label class="mt-1 mb-1 mr-0 sort-by" style="font-size: 0.75rem;">Sort By</label></div>
									<div class="p-2">
										<select name="sortby" class="form-control form-control-sm" style="font-size: 0.75rem; display: inline-block;">
											<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended&nbsp;</option>
											<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
											<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
										</select></div>
									<div class="p-2" style="font-size: 1.3rem;">
										@if ((request()->order == 'desc'))
										<a href="{{ request()->fullUrlWithQuery(['order' => 'asc']) }}">
											<i class="fas fa-sort-amount-down-alt"></i>
										</a>
										@else
										<a href="{{ request()->fullUrlWithQuery(['order' => 'desc']) }}">
											<i class="fas fa-sort-amount-up-alt"></i>
										</a>
										@endif
									</div>
								  </div>
								<div class="row mb-2">
									<div class="modal right fade" id="filterModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
										<div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
											<div class="modal-content">
											<div class="modal-body">
												<div class="d-flex justify-content-between align-items-center" style="font-weight: 500 !important;  margin: 20px !important"><b>Filter Results</b>
													<a href="/products/{{ $product_category->slug }}" style="text-decoration: none;">
														<small class="stylecap he2 text-dark" style="font-weight:400 !important; padding-right: 10px;">Clear All</small>
													</a>
												</div>
												<hr>
												<form action="/products/{{ $product_category->slug }}" method="POST" id="filter-form">
													@csrf
													@php
														$a = 0;
													@endphp
													@if (count($filters['Brand']) > 1)
													<div class="card mb-3 m-3" style="width: 85% !important">
														<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">BRAND</div>
														<div class="card-body">
															@foreach ($filters['Brand'] as $brand)
															@php
																$a++;
																$filter_attr = Str::slug('brand', '-');
																$filter_values = explode('+', request()->brand);
																$status = (in_array($brand, $filter_values)) ? 'checked' : '';
															@endphp
															<div class="form-check">
																<input type="checkbox" class="form-check-input product-cb-filter" id="{{ 'cbb' . $a }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $brand }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
																<label class="form-check-label" for="{{ 'cbb' . $a }}" style="font-size: 0.8rem;">{{ $brand }}</label>
															</div>
														@endforeach
														</div>
													</div>
													@endif
													@php
														$x = 0;
													@endphp
													@foreach ($filters as $id => $row)
													@php
														$filter_attr = Str::slug($id, '-');
													@endphp
													@if ($id != 'Brand')
													@if (count($row) > 1 || request()->$filter_attr)
													<div class="card mb-3 m-3" style="width: 85% !important">
														<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">{{ strtoupper($id) }}</div>
														<div class="card-body">
															@foreach ($row as $attr_val)
															@php
																$x++;
																$filter_values = explode('+', request()->$filter_attr);
																$status = (in_array($attr_val, $filter_values)) ? 'checked' : '';
															@endphp
															<div class="form-check">
																<input type="checkbox" class="form-check-input product-cb-filter" id="{{ 'cb' . $x }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $attr_val }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
																<label class="form-check-label" for="{{ 'cb' . $x }}" style="font-size: 0.8rem;">{{ $attr_val }}</label>
															</div>
															@endforeach
														</div>
													</div>
													@endif
													@endif
							
													@endforeach
													<input type="hidden" name="sortby" value="{{ request()->sortby }}">
													<input type="hidden" name="sel_attr" value="{{ request()->sel_attr }}">
												</form>
											</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>

						<div class="row animated animatedFade1InUp fadeInUp mx-auto">
							@php
								$col = '4';
							@endphp
							@forelse ($products_arr as $loop_count => $item)
								@include('frontend.product_details_card')
							@empty
								<h4 class="text-center text-muted p-5 text-uppercase">No products found</h4>
							@endforelse
						</div>
						
					</div>
				</div>
				<!--products-->
			</div>
		</div>
		{{-- pagination --}}
		<div class="container" style="max-width: 100% !important;">
			<div class="row">
				{{ $products->withQueryString()->links('frontend.product_pagination') }}
			</div>
		</div>
	</main>

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/page_css/product_list.min.css') }}">
@endsection

@section('script')
<script>
	(function() {
		$(document).on('change', 'select[name="sortby"]', function(){
			window.location.href = $(this).find(':selected').data('loc');
		});

		$(document).on('change', 'select[name="order"]', function(){
			window.location.href = $(this).find(':selected').data('loc');
		});

		$(document).on('change', '.product-cb-filter', function(){
			$('#filter-form').find('input[name="sel_attr"]').eq(0).val($(this).data('attrname'));
			$('#filter-form').submit();
		});
	})();
</script>
@endsection
