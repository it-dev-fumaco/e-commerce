@extends('frontend.layout', [
    'namePage' => $product_category->name,
    'activePage' => 'product_list',
	'category_id' => $category_id
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

	<main style="background-color:#ffffff; min-height: 80vh !important" class="products-head prod-list-pad">
		<div class="container" style="max-width: 100% !important;">
			<div class="row">
				<!--sidebar-->
				<div class="col-lg-2 checkersxx d-none d-lg-block">
					<div class="row">
						<div class="col-12 p-0">
							<h5 style="color: #221E1F">Filter Results</h5>
							<span style="font-size: 9pt; color: #606166; font-weight: 600;">Results {{ $products->lastItem() }} (Out of {{ $products->total() }})</span>
						</div>

						<div class="col-12 p-0">
							<form action="/products/{{ ($product_category->slug) ? $product_category->slug : $product_category->id }}" method="POST" id="filter-form">
								@csrf
								@php
									$a = 0;
								@endphp
								<div id="accordion" class="container-fluid p-0">
									@if (count($filters['Brand']) > 1)
										<div class="card text-left" style="border: none;">
											<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-brand">
												<span class="panel-title" style="font-weight: 600; font-size: 10pt;">Brand</span>
												<i id="filter-brand-arrow" class="fas fa-caret-up" style="position: absolute; right: 0;"></i>
											</div>
			
											<div id="filter-brand" class="collapse show">
												<div class="card-body">
													@foreach ($filters['Brand'] as $brand)
														@php
															$filter_values = explode('+', request()->brand);
															$status = (in_array($brand, $filter_values)) ? 'checked' : '';
														@endphp
														<div class="form-check">
															<input class="form-check-input filter-checkbox" type="checkbox" name="attr[brand][]" value="{{ $brand }}" {{ $status }}>
															<label class="form-check-label" style="font-size: 10pt; font-weight: 500">
																{{ $brand }}
															</label>
														</div>
													@endforeach
												</div>
											</div>
										</div>
									@endif
									@foreach ($filters as $id => $filter)
										@php
											$filter_attr = Str::slug($id, '-');
											$collapse = null;
											$arrow = 'down';
											$a++;
		
											if(count($filters['Brand']) > 1){
												if($loop->first){
													$collapse = 'show';
													$arrow = 'up';
												}
											}else{
												if($a < 3){
													$collapse = 'show';
													$arrow = 'up';
												}
											}

											if(request()->$filter_attr){
												$collapse = 'show';
												$arrow = 'up';
											}
										@endphp
										@if ($id != 'Brand')
											<div class="card text-left" style="border: none;">
												<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-{{ $filter_attr }}">
													<span class="panel-title" style="font-weight: 600; font-size: 10pt;">{{ $id }}</span>
													<i id="filter-{{ $filter_attr }}-arrow" class="fas fa-caret-{{ $arrow }}" style="position: absolute; right: 0;"></i>
												</div>
											
												<div id="filter-{{ $filter_attr }}" class="collapse {{ $collapse }}">
													<div class="card-body">
														@foreach ($filter as $value)
															@php
																$filter_values = explode('+', request()->$filter_attr);
																$status = (in_array($value, $filter_values)) ? 'checked' : '';
															@endphp
															<div class="form-check">
																<input class="form-check-input filter-checkbox" type="checkbox" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $value }}" {{ $status }}>
																<label class="form-check-label" style="font-size: 10pt; font-weight: 500">
																	{{ $value }}
																</label>
															</div>
														@endforeach
													</div>
												</div>
											</div>
										@endif
									@endforeach
								</div>
								<input type="hidden" name="sortby" value="{{ request()->sortby }}">
								<input type="hidden" name="sel_attr" value="{{ request()->sel_attr }}">
							</form>
						</div>
					</div>
				</div>
				<!--sidebar-->

				<!--products-->
				<div class="col-lg-10">
					<div class="row g-6">
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
												<div class="col-1 offset-11">
													<button type="button" class="btn close-modal" data-target="#filterModal2">
														<i class="fa fa-remove" style="font-size: 20px; color: #BDBDBD;"></i>
													</button>
												</div>
												<div class="row">
													<div class="col-6 col-md-12">
														<h5 style="color: #221E1F">Filter Results</h5>
														<span style="font-size: 9pt; color: #606166; font-weight: 600;">
															Results {{ $products->lastItem() }} (Out of {{ $products->total() }})
														</span>
													</div>
													<div class="col-12">
														<form action="/products/{{ ($product_category->slug) ? $product_category->slug : $product_category->id }}" method="POST" id="filter-form2">
															@csrf
															<div id="accordion2" class="container-fluid p-0">
																@if (count($filters['Brand']) > 1)
																	<div class="card text-left" style="border: none;">
																		<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-brand2">
																			<span style="font-weight: 600; font-size: 10pt;">Brand</span>
																			<i id="filter-brand2-arrow" class="fas fa-caret-up" style="position: absolute; right: 0;"></i>
																		</div>
										
																		<div id="filter-brand2" class="collapse show">
																			<div class="card-body">
																				@foreach ($filters['Brand'] as $brand)
																					@php
																						$filter_values = explode('+', request()->brand);
																						$status = (in_array($brand, $filter_values)) ? 'checked' : '';
																					@endphp
																					<div class="form-check">
																						<input class="form-check-input filter-checkbox" type="checkbox" name="attr[brand][]" value="{{ $brand }}" {{ $status }}>
																						<label class="form-check-label" style="font-size: 10pt; font-weight: 500">
																							{{ $brand }}
																						</label>
																					</div>
																				@endforeach
																			</div>
																		</div>
																	</div>
																@endif
																@foreach ($filters as $id => $filter)
																	@php
																		$filter_attr = Str::slug($id, '-');
																		$collapse = null;
																		$arrow = 'down';
									
																		if(count($filters['Brand']) <= 1 && $loop->first || request()->$filter_attr){
																			$collapse = 'show';
																			$arrow = 'up';
																		}
																	@endphp
																	@if ($id != 'Brand')
																		<div class="card text-left" style="border: none;">
																			<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-{{ $filter_attr }}2">
																				<span style="font-weight: 600; font-size: 10pt;">{{ $id }}</span>
																				<i id="filter-{{ $filter_attr }}2-arrow" class="fas fa-caret-{{ $arrow }}" style="position: absolute; right: 0;"></i>
																			</div>
																		
																			<div id="filter-{{ $filter_attr }}2" class="collapse {{ $collapse }}">
																				<div class="card-body">
																					@foreach ($filter as $value)
																						@php
																							$filter_values = explode('+', request()->$filter_attr);
																							$status = (in_array($value, $filter_values)) ? 'checked' : '';
																						@endphp
																						<div class="form-check">
																							<input class="form-check-input filter-checkbox" type="checkbox" name="attr[{{ $filter_attr }}][]" value="{{ $value }}" {{ $status }}>
																							<label class="form-check-label" style="font-size: 10pt; font-weight: 500">
																								{{ $value }}
																							</label>
																						</div>
																					@endforeach
																				</div>
																			</div>
																		</div>
																	@endif
																@endforeach
															</div>
															<input type="hidden" name="sortby" value="{{ request()->sortby }}">
															<input type="hidden" name="sel_attr" value="{{ request()->sel_attr }}">
														</form>
													</div>
												</div>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row animated animatedFade1InUp fadeInUp mx-auto" id="products-list">
							@forelse ($products_arr as $loop_count => $item)
								@php
									$col = '4';
								@endphp
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

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
			var filter_form = '#filter-form2';
		}else{ // desktop
			var filter_form = '#filter-form';
		}

		$(document).on('change', '.product-cb-filter', function(){
			$(filter_form).find('input[name="sel_attr"]').eq(0).val($(this).data('attrname'));
			$(filter_form).submit();
		});

		$(document).on('click', '.filter-checkbox', function (){
			loadProducts(1);
		});

		$(document).on('click', '#products-list-pagination a', function(event){
			event.preventDefault();
			var page = $(this).attr('href').split('page=')[1];
			loadProducts(page);
		});

		// loadProducts();
        function loadProducts(page) {
			$.ajax({
				type: "GET",
				url: "/products/{{ $category_id }}?page=" + page,
                data: $(filter_form).serialize(),
				success: function (response) {
                    $('#products-list').empty();
                    $('#products-list').html(response);
				}
			});
		}

		$(document).on('click', '.collapse-btn', function (){
			var target = $(this).data('target');
			$(target + "-arrow").toggleClass('flip');
			
			$(target).collapse('toggle');
		});
	})();
</script>
@endsection
