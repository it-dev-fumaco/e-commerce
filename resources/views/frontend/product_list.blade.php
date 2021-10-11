@extends('frontend.layout', [
    'namePage' => 'Products',
    'activePage' => 'product_page'
])

@section('content')
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
			font-size: 14px;
			width: 100%;
		}

		.text-concat {
			position: relative;
			display: inline-block;
			word-wrap: break-word;
			overflow: hidden;
			max-height: 4.8em;
			line-height: 1.2em;
			text-align:justify;
		}

		.text.ellipsis::after {
			position: absolute;
			right: -12px;
			bottom: 4px;
		}
	</style>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
	<script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>

	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
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

	<main style="background-color:#ffffff;" class="products-head">
		<div class="container marketing"><br></div>
		<div class="container" style="max-width: 100% !important;">
			<div class="row">
				<!--sidebar-->
				<div class="col-lg-2 checkersxx">
					<div class="d-flex justify-content-between align-items-center he1">Filters
						<a href="/products/{{ $product_category->id }}" style="text-decoration: none;">
							<small class="stylecap he2 text-dark" style="font-weight:100 !important;">Clear All</small>
						</a>
					</div>
					<hr>
					<form action="/products/{{ $product_category->id }}" method="POST" id="filter-form" class="mb-5">
					@csrf
						@php
								$x = 0;
						@endphp
						@foreach ($filters as $id => $row)
						<h6 class="mt-3"><small>{{ strtoupper($id) }}</small></h6>
						@foreach ($row as $attr_val)
						@php
							$x++;
							$filter_attr = Str::slug($id, '-');
							$filter_values = explode('+', request()->$filter_attr);
							$status = (in_array($attr_val, $filter_values)) ? 'checked' : '';
						@endphp
						<div class="form-check">
							<input type="checkbox" class="form-check-input product-cb-filter" id="{{ 'cb' . $x }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $attr_val }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
							<label class="form-check-label" for="{{ 'cb' . $x }}" style="font-size: 0.8rem;">{{ $attr_val }}</label>
						</div>
						@endforeach
						<hr>
						@endforeach
						<input type="hidden" name="sortby" value="{{ request()->sortby }}">
					</form>
				</div>
				<!--sidebar-->

					<!--products-->
					<div class="col-lg-10">

						<div class="row g-6">
							<form id="sortForm" class="d-inline-block">
							<div class="col-md-4 offset-md-8">
									<div class="row mb-2">

										<div class="col-md-6" style="text-align: right;">
											<label class="m-2"><small>Sort By</small></label>
										</div>
										<div class="col-md-6">
											<select name="sortby" class="form-control">
													<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
													<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
													<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
											</select>
										</div>
									</div>
							</div>
						</form>

							@forelse ($products_arr as $product)
							<div class="col-md-4 btmp animated animatedFadeInUp fadeInUp equal-height-columns">
									<div class="card">
										<div class="equal-column-content">
											@php
												$image = ($product['image']) ? '/item/images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
											@endphp
											<img src="{{ asset($image) }}" class="card-img-top">
											<div class="card-body">
													<div class="text ellipsis">
														<p class="card-text fumacoFont_card_title text-concat" style="color:#0062A5 !important; height: 80px;">{{ $product['item_name'] }}</p>
													</div>
													<p class="card-text fumacoFont_card_price" style="color:#000000 !important;">
														@if($product['is_discounted'])
														<s style="color: #c5c5c5;">₱ {{ $product['price'] }}</s>₱ {{ $product['discounted_price'] }}
														@else
														₱ {{ $product['price'] }}
														@endif
													</p>
													<div class="d-flex justify-content-between align-items-center">
														<div class="btn-group stylecap">
															<span class="fa fa-star checked starcolor"></span>
															<span class="fa fa-star checked starcolor"></span>
															<span class="fa fa-star checked starcolor"></span>
															<span class="fa fa-star starcolorgrey"></span>
															<span class="fa fa-star starcolorgrey"></span>
														</div>
														<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( 0 Reviews )</small>
													</div>
											</div>
											<div class="card-body">
													<a href="/product/{{ $product['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
											</div>
										</div>
									</div>
							</div>
							@empty
							<h4 class="text-center text-muted p-5 text-uppercase">No products found</h4>
							@endforelse
						</div>
					</div>
					<!--products-->
			</div>
		</div>

		{{-- pagination --}}
		<div class="container" style="max-width: 100% !important;">
			<div class="row">
					{{ $products->links('frontend.product_pagination') }}
			</div>
		</div>
	</main>
@endsection

@section('script')
<script>
  (function() {
   $(document).on('change', 'select[name="sortby"]', function(){
		window.location.href = $(this).find(':selected').data('loc');
   });

	$(document).on('change', '.product-cb-filter', function(){
		$('#filter-form').submit();
	});
  })();

</script>
@endsection
