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
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
					<div class="container">
						<div class="carousel-caption text-start mx-auto" style="bottom: 1rem !important;">
							<center><h3 class="carousel-header-font">{{ $product_category->name }}</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

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
				<!--sidebar-->

				<!--products-->
				<div class="col-lg-10">
					<div class="row g-6">
						<form id="sortForm" class="d-inline-block">
							<a class="btn d-md-block d-lg-none filter-btn" data-toggle="modal" data-target="#filterModal2" style="font-size: 0.75rem !important; float: left !important">
								<i class="fas fa-filter"></i>&nbsp; Filters
							</a>
							<div class="col-md-4 offset-md-8">
								
								<div class="d-flex justify-content-end prod-sort">
									<div>
										<a class="btn d-sm-block d-md-none" data-toggle="modal" data-target="#filterModal2" style="font-size: 0.75rem !important; float: left !important; margin-top: 6%">
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
									{{-- <div class="col-md-6 pr-1" style="text-align: right;">
									
									</div>
									<div class="col-md-6" style="padding: 0; float: right !important; min-width: 120px !important; padding-right: 5%;">
									
									</div> --}}
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
							@forelse ($products_arr as $product)
							<div class="col-md-4 mb-3 btmp mb-pad">
								<div class="card">
									<div class="equal-column-content">
										@php
											$image = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
											$image_webp = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.explode(".", $product['image'])[0] .'.webp' : '/storage/no-photo-available.png';
										@endphp

										<div class="hover-container product-card" style="position: relative">
											<div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
												@if ($product['is_new_item'])
												<div class="col-12 mb-2">
													<span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
													&nbsp;<b>New</b>&nbsp;
													</span>
												</div><br />	
												@endif
												
												@if ($product['is_discounted'])
												<div class="col-12">
													<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
														&nbsp;<b>{{ $product['discount_display'] }}</b>&nbsp;
													</span>
												</div>
												@endif
											</div>

											<div class="btn-container">
												<a href="/product/{{ ($product['slug']) ? $product['slug'] : $product['item_code'] }}" class="view-products-btn btn"><i class="fas fa-search"></i>&nbsp;View Product</a>
											</div>
											<div class="overlay-bg"></div>
											<picture>
												<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
												<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top">
												<img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $product['image'])[0], '-') }}" class="card-img-top hover" style="min-height: 230px">
											</picture>
										</div>
										<div class="card-body d-flex flex-column">
											<div class="d-flex text ellipsis">
												<a href="/product/{{ ($product['slug']) ? $product['slug'] : $product['item_code'] }}" class="card-text fumacoFont_card_title text-concat prod-desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important; min-height: 100px; font-size: 16px !important; font-weight: 500 !important;">{{ $product['item_name'] }}</a>
											</div>
											<p class="card-text fumacoFont_card_price product_price_card" style="color:#000000 !important;">
												@if($product['is_discounted'] == 1)
												{{ $product['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $product['default_price'] }}</s>
												@else
												{{ $product['default_price'] }}
												@endif
											</p>
											<div class="d-flex justify-content-between align-items-center">
												<div class="btn-group stylecap">
													@for ($i = 0; $i < 5; $i++)
													@if ($product['overall_rating'] <= $i)
													<span class="fa fa-star starcolorgrey"></span>
													@else
													<span class="fa fa-star" style="color: #FFD600;"></span>
													@endif
													@endfor
												</div>
												<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $product['total_reviews'] }} Reviews )</small>
											</div>
										</div>
									</div>
									@if ($product['on_stock'] == 1)
										<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $product['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 2%;"></i> Add to Cart</a>
										@else
										<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $product['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 2%;"></i> Add to Wishlist</a>
										@endif
								</div>
							</div>
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

  // Product Image Hover
  $('.hover-container').hover(function(){
      $(this).children('.btn-container').slideToggle('fast');
    });

</script>
@endsection
