@extends('frontend.layout', [
    'namePage' => $product_category->name,
    'activePage' => 'product_list'
])

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
									<div class="col-md-6 pr-1" style="text-align: right;">
										{{-- <a class="btn d-sm-block d-md-none" data-toggle="modal" data-target="#filterModal2" style="font-size: 0.75rem !important; float: left !important">
											<i class="fas fa-filter"></i>&nbsp; Filters
										</a> --}}
										{{-- <label class="mt-1 mb-1 mr-0" style="font-size: 0.75rem;">Sort By</label> --}}
									</div>
									<div class="col-md-6" style="padding: 0; float: right !important; min-width: 120px !important; padding-right: 5%;">
										{{-- <select name="sortby" class="form-control form-control-sm" style="font-size: 0.75rem; display: inline-block;">
											<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
											<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
											<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
										</select> --}}
									</div>
									<div class="col-md-3 d-sm-block d-md-none filter-slide">
										<div class="modal fade" id="filterModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
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
							</div>
						</form>

						<div class="row animated animatedFade1InUp fadeInUp mx-auto">
							@forelse ($products_arr as $product)
							<div class="col-md-4 mb-3 btmp mb-pad" 	>
								
								{{-- <div class="col-md-4 btmp animated animatedFadeInUp fadeInUp equal-height-columns"> --}}
							{{-- <a class="product-card" href="/product/{{ ($product['slug']) ? $product['slug'] : $product['item_code'] }}" style="text-decoration: none !important; text-transform: none !important; position: relative"> --}}
								<div class="card">
									<div class="equal-column-content product-card hover-container">
										<div class="overlay-bg"></div>
										<a href="/product/{{ ($product['slug']) ? $product['slug'] : $product['item_code'] }}" class="view-products-btn btn">View</a>

										@php
											$image = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
											$image_webp = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.explode(".", $product['image'])[0] .'.webp' : '/storage/no-photo-available.png';
										@endphp
									
											<picture>
												<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
												<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top">
												<img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $product['image'])[0], '-') }}" class="card-img-top hover">
											 </picture>
								
										<div class="card-body">
											<div class="text ellipsis">
												<p class="card-text fumacoFont_card_title text-concat prod-desc" style="color:#0062A5 !important; min-height: 100px; font-size: 16px !important; font-weight: 500 !important;">{{ $product['item_name'] }}</p>
											</div>
											<p class="card-text fumacoFont_card_price price-card d-none d-md-block d-lg-none" style="color:#000000 !important;">
												@if($product['is_discounted'])
												₱ {{ number_format(str_replace(",","",$product['discounted_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$product['price']), 2) }}</s>
												&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red; display: {{ ($product['on_sale']) ? 'inline' : 'none' }} !important;">{{ $product['discount_percent'] }}% OFF</span>
												@else
												₱ {{ number_format(str_replace(",","",$product['price']), 2) }}
												@endif
											</p>
											<p class="card-text fumacoFont_card_price d-sm-block d-md-none d-lg-block" style="color:#000000 !important;">
												@if($product['is_discounted'])
												₱ {{ number_format(str_replace(",","",$product['discounted_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$product['price']), 2) }}</s>
												&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red; display: {{ ($product['on_sale']) ? 'inline' : 'none' }} !important;">{{ $product['discount_percent'] }}% OFF</span>
												@else
												₱ {{ number_format(str_replace(",","",$product['price']), 2) }}
												@endif
											</p>
											<div class="d-flex justify-content-between align-items-center">
												<div class="btn-group stylecap">
													<span class="fa fa-star starcolorgrey"></span>
													<span class="fa fa-star starcolorgrey"></span>
													<span class="fa fa-star starcolorgrey"></span>
													<span class="fa fa-star starcolorgrey"></span>
													<span class="fa fa-star starcolorgrey"></span>
												</div>
												<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( 0 Reviews )</small>
											</div>
										</div>
										<div class="card-body">
											{{-- <a href="/product/{{ $product['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a> --}}
										</div>
									</div>
									<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto mt-3 add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $product['item_code'] }}"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
								</div>
							{{-- </a> --}}
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
<style>
	.pagination {
		display: flex;
		padding-left: 0;
		list-style: none;
	}
	.checkersxx {
		display: block;
	}
	@media only screen and (max-width: 768px) {
		.pagination {
				display: -webkit-inline-box;
				padding-left: 0;
				list-style: none;
		}
		.checkersxx {
				display: none;
		}
	}
	.pagex_link {
		color: #5f6773 !important;
		text-decoration: none !important;
	}
	.products-head {
		margin-top: 10px !important;
		padding-left: 40px !important;
		padding-right: 40px !important;
	}
	.he1 {
		font-weight: 300 !important;
		font-size: 12px !important;
	}
	.he2 {
		font-weight: 200 !important;
		font-size: 10px !important;
	}
	.btmp {
		margin-bottom: 15px !important;
	}
	/* Animation */
	@keyframes fadeInUp {
		from {
				transform: translate3d(0,40px,0)
		}
		to {
				transform: translate3d(0,0,0);
				opacity: 1
		}
	}
	@-webkit-keyframes fadeInUp {
		from {
				transform: translate3d(0,40px,0)
		}
		to {
				transform: translate3d(0,0,0);
				opacity: 1
		}
	}
	.animated {
		animation-duration: 1s;
		animation-fill-mode: both;
		-webkit-animation-duration: 1s;
		-webkit-animation-fill-mode: both
	}
	.animatedFadeInUp {
		opacity: 0
	}
	.fadeInUp {
		opacity: 0;
		animation-name: fadeInUp;
		-webkit-animation-name: fadeInUp;
	}

	.text {
		position: relative;
		font-size: 16px !important;
		width: 100%;
	}

	.text-concat {
		position: relative;
		display: inline-block;
		word-wrap: break-word;
		overflow: hidden;
		max-height: 4.5em;
      	line-height: 1.6em;
		text-align: left;
		font-size: 16px !important;
	}

	.text.ellipsis::after {
		position: absolute;
		right: -12px;
		bottom: 4px;
	}
	.prod-sort{
		margin-right: 5%;
	}

	.hover{
      transition: .5s;
    }

    .hover:hover {
      -ms-transform: scale(0.95); /* IE 9 */
      -webkit-transform: scale(0.95); /* Safari 3-8 */
      transform: scale(0.95); 
    }

	/*Required*/
@media (max-width: 576px){.modal-dialog.modal-dialog-slideout {width: 80%}}
.modal-dialog-slideout {min-height: 100%; margin: 0 0 0 auto ;background: #fff;}
/* .modal.fade .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(-100%,0);transform: translate(-100%,0);} */
.modal.fade .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(100%, 0);transform: translate(100%, 0);}
.modal.fade.show .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(0,0);transform: translate(0,0);flex-flow: column;}
.modal-dialog-slideout .modal-content{border: 0;}

@media (max-width: 575.98px) {
	.sort-by{
	  white-space: nowrap !important;
	}
	.mb-pad{
	  padding: 0 !important;
	}
	.prod-sort{
	  margin-right: 0 !important;
	}
	.prod-list-pad{
	  padding: 5% !important;
	}
	.filter-btn, .filter-slide{
	  display: inline-block !important;
	}
  }

  @media (max-width: 767.98px) {
	.sort-by{
	  white-space: nowrap !important;
	}
	.mb-pad{
	  padding: 0 !important;
	}
	.prod-sort{
	  margin-right: 0 !important;
	}
	.prod-list-pad{
	  padding: 5% !important;
	}
	.filter-btn, .filter-slide{
	  display: inline-block !important;
	}
  }
  @media (max-width: 1199.98px) {/* tablet */
	.mb-pad{
	  padding: 10px !important;
	}
	.filter-btn, .filter-slide{
	  display: none !important;
	}
	.price-card{
		min-height: 50px !important;
	}
  }

  @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {/* portrait tablet */
	.sort-by{
	  white-space: nowrap !important;
	}
	.filter-btn, .filter-slide{
	  display: inline-block !important;
	}
  }

  @media only screen and (min-device-height : 427.98px) and (max-device-height : 767.98px) and (orientation : landscape) {/* landscape mobile */
	.sort-by{
	  white-space: nowrap !important;
	}
	.filter-btn, .filter-slide{
	  display: inline-block !important;
	}
  }
</style>
<style>

	.overlay-bg{
		position: absolute !important;
		background-color: rgba(0,0,0,0.2) !important;
		width: 100%;
		height: 100%;
		top: 0;
		z-index: 1;
		transition:all .15s ease-in;
		opacity: 0;
	}
	.product-card{
		position:relative;
		margin: 0 auto;
		transition:all .15s ease-in !important;
	}
	.view-products-btn{
		position: absolute;
		z-index: 2;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right: 0;
		top: 50%;
		width: 50%;
		text-align: center;
		background-color: #0062A5;
		display: none;
		color:#fff;
		font-size:13px;
		letter-spacing:2px;
		text-transform:uppercase;
		padding:8px 20px;
		font-weight:400;
		transition:all .15s ease-in;
		
	}

	.view-products-btn:hover{
		color: #fff;
		background-color: #000;
	}

	.product-card:hover .view-products-btn{ 
		display:block;
	}
	.product-card:hover .overlay-bg{ 
		/* display:block !important; */
		transition:all .15s ease-in !important;
		opacity: 1 !important;
	}

	.hover-container:hover img{
		-ms-transform: scale(0.95); /* IE 9 */
      	-webkit-transform: scale(0.95); /* Safari 3-8 */
      	transform: scale(0.95); 
	}
</style>
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
