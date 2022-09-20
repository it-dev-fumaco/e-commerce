@extends('frontend.layout', [
  'namePage' => 'Search Results',
  'activePage' => 'search_result'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active results-banner">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
				<div class="container">
					<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<div class="row justify-content-md-center">
							<div class="col-md-8">
								<h3 class="carousel-header-font text-center results-count-head"><b>{{ $results->total() }} result(s) found</b></h3>
								<form action="/" method="GET" class="d-none d-md-block">
									<div class="input-group mb-3">
										<input type="text" class="form-control search-page-autocomplete" id="search-page-form-input" placeholder="Search" name="s" value="{{ request()->s }}">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary btn-light rounded-right" type="submit"><i class="fas fa-search"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<main style="background-color:#ffffff; min-height: 600px; width: 100% !important;" class="products-head">
	<div class="col-10 mx-auto">
		<div id="search-page-container" class="container"></div>
	</div>
	<div class="container-fluid">
		@if(request()->s != null)
		<div class="col-md-10 col-lg-8 mx-auto">
			<div class="row">
				<div class="col-md-6 mt-4 mb-2 results-count">
					<h4><b>Search Results</b></h4>
					<small>Showing {{ $results->firstItem() . ' - ' . $results->lastItem() }} out of {{ $results->total() }}</small>
				</div>
				<div class="col-md-6 mt-4 mb-2">
					<div class="d-flex justify-content-end">
						<div>
							<a class="btn {{ count($products) > 0 ? 'd-sm-block d-lg-none' : 'd-none' }}" data-toggle="modal" data-target="#rightModal" style="font-size: 0.75rem !important; float: left !important; margin-top: 6%; white-space: nowrap !important;">
								<i class="fas fa-filter"></i>&nbsp; Filters
							</a>
						</div>
						<div class="p-2"><label class="mt-1 mb-1 mr-0" style="font-size: 0.75rem; white-space: nowrap !important">Sort By</label></div>
						<div class="p-2 {{ count($products) > 0 ? null : 'd-none' }}">
							<select name="sortby" class="form-control form-control-sm" style="font-size: 0.75rem; display: inline-block;">
								<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
								<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
								<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
							</select>
						</div>
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
				</div>		
			</div>
			<div class="row">
				<div class="d-inline-block">
					{{-- Filters(Responsive) Start --}}
					<div class="modal right fade" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-body">
									<div class="d-flex justify-content-between align-items-center" style="font-weight: 500 !important;  margin: 20px !important"><b>Filter Results</b>
										<a href="/?s={{ request()->s }}" style="text-decoration: none;">
											<small class="stylecap he2 text-dark" style="font-weight:400 !important;">Clear All</small>
										</a>
									</div>
									<hr>
									@php
										$a = 0;
										$x = 0;
									@endphp
									@if (count($filters['Brand']) > 1)
										<div class="card mb-3">
											<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">BRAND</div>
											<div class="card-body">
												@foreach ($filters['Brand'] as $brand)
													@php
														$a++;
														$filter_attr = Str::slug('brand', '-');
														$filter_values = request()->brand ? request()->brand : [];
														$status = (in_array($brand, $filter_values)) ? 'checked' : '';
													@endphp
													<div class="form-check">
														<input type="checkbox" class="form-check-input product-cb-filter attrib-checkbox" id="{{ 'cbb' . $a }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $brand }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
														<label class="form-check-label" for="{{ 'cbb' . $a }}" style="font-size: 0.8rem;">{{ $brand }}</label>
													</div>
												@endforeach
											</div>
										</div>
									@endif
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
															$fullUrl = request()->fullUrlWithQuery([ $filter_attr => $attr_val]);
														@endphp
														<div class="form-check">
															<input type="checkbox" class="form-check-input product-cb-filter attrib-checkbox" id="{{ 'cb' . $x }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $attr_val }}" data-attrname="{{ $filter_attr }}" {{ $status }}/>
															<label class="form-check-label" for="{{ 'cb' . $x }}" style="font-size: 0.8rem;">{{ $attr_val }}</label>
														</div>
													@endforeach
												</div>
											</div>
											@endif
										@endif
									@endforeach
								</div>
							</div>
						</div>
					</div>
					{{-- Filters(Responsive) End --}}
				 </div>
			</div>
		</div>
		
		@endif
		@if (count($results) < 1)
			<h4 class="text-center text-muted mt-5 text-uppercase">No search result(s) found</h4>
		@endif
		@if(count($products) > 0)
		<div class="row">
			@if ($filter_count > 0 or count($filters['Brand']) > 1)
			<div class="d-none {{ request()->s == '' ? '' : 'd-xl-block' }} col-1">&nbsp;</div>
			<div class="d-none col-lg-3 col-xl-2 {{ request()->s == '' ? '' : 'd-lg-block' }}">
				<!--sidebar-->
				<div class="col-12 checkersxx">
					<div class="d-flex justify-content-between align-items-center he1" style="font-weight: 500 !important"><b>Filter Results</b>
						<a href="/?s={{ request()->s }}" style="text-decoration: none;">
							<small class="stylecap he2 text-dark" style="font-weight:400 !important;">Clear All</small>
						</a>
					</div>
					<hr>
					@php
						$a = 0;
						$x = 0;
					@endphp
					@if (count($filters['Brand']) > 1)
						<div class="card mb-3">
							<div class="card-header text-white font-weight-bold" style="font-size: 0.75rem; background-color: rgb(0, 98, 165);">BRAND</div>
							<div class="card-body">
								@foreach ($filters['Brand'] as $brand)
									@php
										$a++;
										$filter_attr = Str::slug('brand', '-');
										$filter_values = request()->brand ? request()->brand : [];
										$status = (in_array($brand, $filter_values)) ? 'checked' : '';
									@endphp
									<div class="form-check">
										<input type="checkbox" class="form-check-input product-cb-filter attrib-checkbox" id="{{ 'cbb' . $a }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $brand }}" data-attrname="{{ $filter_attr }}" {{ $status }}>
										<label class="form-check-label" for="{{ 'cbb' . $a }}" style="font-size: 0.8rem;">{{ $brand }}</label>
									</div>
								@endforeach
							</div>
						</div>
					@endif
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
											$fullUrl = request()->fullUrlWithQuery([ $filter_attr => $attr_val]);
										@endphp
										<div class="form-check">
											<input type="checkbox" class="form-check-input product-cb-filter attrib-checkbox" id="{{ 'cb' . $x }}" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $attr_val }}" data-attrname="{{ $filter_attr }}" {{ $status }}/>
											<label class="form-check-label" for="{{ 'cb' . $x }}" style="font-size: 0.8rem;">{{ $attr_val }}</label>
										</div>
									@endforeach
								</div>
							</div>
							@endif
						@endif
					@endforeach
				</div>
				<!--sidebar-->
			</div>
			@endif

			@php
				$mx_auto = '';
				if($filter_count == 0 and count($filters['Brand']) < 2){
					$mx_auto = 'mx-auto';
				}
			@endphp
			<div class="col-lg-9 col-xl-8 {{ $mx_auto }}">
				<div class="row">
				@if (count($recently_added_arr) > 0)
					<div class="col-12 text-center">
						<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp results-head" style="color:#000000 !important;">RECENTLY ADDED PRODUCT(S)</h4>
					</div>
					@foreach ($recently_added_arr as $item)
					<!-- Mobile view Start -->
					<div class="d-block d-md-none animated animatedFadeInUp fadeInUp">
						<div class="card">
							<div class="pt-2" style="position: absolute; top: 0; left: 0; z-index: 10;">
								<div class="col-12">
									@if ($item['is_discounted'])
										<div class="col-12">
											<span class="text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px; padding: 2px">
												&nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
											</span>
										</div>
									@endif
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-4">
										@php
											$image = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
											$image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.png';
										@endphp              
										<picture>
											<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
											<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top"> 
											<img src="{{ asset($image) }}" alt="{{ $item['item_code'] }}" class="card-img-top hover" loading="lazy">
										</picture>
									</div>
									<div class="col-8">
										<div class="text ellipsis mb-1">
											<a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="card-text mob-prod-text-concat" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important; font-weight: 500 !important">{{ $item['item_name'] }}</a>
										</div>
										<p class="card-text fumacoFont_card_price" style="color:#000000 !important; font-size: 7pt">
											@if($item['is_discounted'])
												{{ $item['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
											@else
											{{ $item['default_price'] }}
											@endif
										</p>
										<div class="d-flex justify-content-between align-items-center">
											<div class="btn-group stylecap">
												@for ($i = 0; $i < 5; $i++)
													@if ($item['overall_rating'] <= $i)
														<span class="fa fa-star starcolorgrey"></span>
													@else
														<span class="fa fa-star" style="color: #FFD600;"></span>
													@endif
												@endfor
											</div>
											<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $item['total_reviews'] }} Reviews )</small>
										</div>
										<br/>
										@if ($item['on_stock'] == 1)
											<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
										@else
											<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
										@endif
									</div>
								</div>								
							</div>
						</div>
					</div>
					<!-- Mobile view end -->

					<!-- Desktop/Tablet view start -->
					<div class="d-none d-md-block col-4 animated animatedFadeInUp fadeInUp equal-height-columns">
						<div class="card mb-4">
							<div class="equal-column-content">
								<div class="hover-container product-card" style="position: relative;">
									<div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
										@if ($item['is_new_item'])
										<div class="col-12 mb-2">
											<span class="p-1 text-center" style="background-color: #438539; font-size: 9pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
											&nbsp;<b>New</b>&nbsp;
											</span>
										</div>
										<br />
										@endif
										
										@if ($item['is_discounted'])
											<div class="col-12">
												<span class="p-1 text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
													&nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
												</span>
											</div>
										@endif
									</div>
									<div class="overlay-bg"></div>
									<div class="btn-container">
										<a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
									</div>
									@php
									$image = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
									$image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.png';
									@endphp              
									<picture>
										<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
										<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top"> 
										<img src="{{ asset($image) }}" alt="{{ $item['item_code'] }}" class="card-img-top hover" loading='lazy'>
									</picture>
								</div>
								
								<div class="card-body d-flex flex-column">
									<div class="text ellipsis">
										<a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 98px; font-weight: 500 !important">{{ $item['item_name'] }}</a>
									</div>
									<p class="card-text fumacoFont_card_price" style="color:#000000 !important;">
										@if($item['is_discounted'])
											{{ $item['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
										@else
										{{ $item['default_price'] }}
										@endif
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="btn-group stylecap">
											@for ($i = 0; $i < 5; $i++)
												@if ($item['overall_rating'] <= $i)
													<span class="fa fa-star starcolorgrey"></span>
												@else
													<span class="fa fa-star" style="color: #FFD600;"></span>
												@endif
											@endfor
										</div>
										<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $item['total_reviews'] }} Reviews )</small>
									</div>
								</div>
							</div>
							<br/>
							@if ($item['on_stock'] == 1)
								<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $item['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
							@else
								<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $item['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
							@endif
						</div>
					</div>
					<!-- Desktop/Tablet view end -->
					@endforeach
				@endif

				<div class="col-12 text-center">
					<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp results-head" style="color:#000000 !important;">{{ request()->s == null ? 'FEATURED PRODUCT(S)' : 'PRODUCT(S)' }}</h4>
				</div>
				@foreach ($products as $product)
					<!-- Mobile view start -->
					<div class="d-block d-md-none animated animatedFadeInUp fadeInUp mb-2">
						<div class="card">
							<div class="pt-2" style="position: absolute; top: 0; left: 0; z-index: 10;">
								@if($product['is_new_item'])
								<div class="col-12 mb-1 {{ $product['is_new_item'] == 1 ? '' : 'd-none' }}">
									<span class="text-center" style="background-color: #438539; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
									&nbsp;<b>New</b>&nbsp;
									</span>
								</div>
								@endif
								@if ($product['is_discounted'])
								<div class="col-12">
									<span class="text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
										&nbsp;<b>{{ $product['discount_display'] }}</b>&nbsp;
									</span>
								</div>
								@endif
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-4">
										@php
											$image = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
											$image_webp = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.explode(".", $product['image'])[0] .'.webp' : '/storage/no-photo-available.png';
										@endphp              
										<picture>
											<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
											<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top"> 
											<img src="{{ asset($image) }}" alt="{{ $product['item_code'] }}" class="card-img-top hover" loading="lazy">
										</picture>
									</div>
									<div class="col-8">
										<div class="text ellipsis mb-1">
											<a href="/product/{{ $product['slug'] ? $product['slug'] : $product['item_code'] }}" class="card-text mob-prod-text-concat" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important; font-weight: 500 !important">{{ $product['item_name'] }}</a>
										</div>
										<p class="card-text fumacoFont_card_price" style="color:#000000 !important; font-size: 7pt">
											@if($product['is_discounted'])
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
										<br/>
										@if ($product['on_stock'] == 1)
											<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 100% !important;" data-item-code="{{ $product['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
										@else
											<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 100% !important;" data-item-code="{{ $product['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
										@endif
									</div>
								</div>								
							</div>
						</div>
					</div>
					<!-- Mobile view end --> 

					<!-- Desktop/Tablet view start -->
					<div class="d-none d-md-inline col-4 animated animatedFadeInUp fadeInUp equal-height-columns">
						<div class="card mb-4">
							<div class="equal-column-content">
								<div class="hover-container product-card" style="position: relative;">
									<div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
										<div class="col-12 mb-2 {{ $product['is_new_item'] == 1 ? '' : 'd-none' }}">
										<span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
										&nbsp;<b>New</b>&nbsp;
										</span>
									</div><br class="{{ $product['is_new_item'] == 1 ? '' : 'd-none' }}"/>
										@if ($product['is_discounted'])
											<div class="col-12">
												<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
													&nbsp;<b>{{ $product['discount_display'] }}</b>&nbsp;
												</span>
											</div>
											@endif
										
									</div>
									<div class="overlay-bg"></div>
									<div class="btn-container">
										<a href="/product/{{ $product['slug'] ? $product['slug'] : $product['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
									</div>
									@php
										$image = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
										$image_webp = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.explode(".", $product['image'])[0] .'.webp' : '/storage/no-photo-available.png';
									@endphp              
									<picture>
										<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
										<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top"> 
										<img src="{{ asset($image) }}" alt="{{ $product['item_code'] }}" class="card-img-top hover" loading="lazy">
									</picture>
								</div>
								
								<div class="card-body d-flex flex-column">
									<div class="text ellipsis">
										<a href="/product/{{ $product['slug'] ? $product['slug'] : $product['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 98px; font-weight: 500 !important">{{ $product['item_name'] }}</a>
									</div>
									<p class="card-text fumacoFont_card_price" style="color:#000000 !important;">
										@if($product['is_discounted'])
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
							<br/>
							@if ($product['on_stock'] == 1)
							<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $product['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
							@else
							<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $product['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
							@endif
						</div>
					</div>
					<!-- Desktop/Tablet view end -->
				@endforeach
				</div>
			</div>
		</div>
		@endif
		@if(count($blogs) > 0)
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">BLOG(S)</h4>
				</div>
				@foreach($blogs as $blog)
				<div class="col-lg-4 d-flex align-items-stretch animated animatedFadeInUp fadeInUp">
					<div class="card mb-4" style="border: 0px solid rgba(0, 0, 0, 0.125) !important;">
						@php
							$image = ($blog['image']) ? '/storage/journals/'.$blog['image'] : '/storage/no-photo-available.png';
							$image_webp = ($blog['image']) ? '/storage/journals/'.explode(".", $blog['image'])[0] .'.webp' : '/storage/no-photo-available.png';
						@endphp
					
						<picture>
							<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
							<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top">
							<img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $blog['image'])[0], '-') }}" class="card-img-top" loading='lazy'>
						</picture>
						<div class="card-body align-items-stretch p-2">
							<a href="blog/{{ $blog['blog_slug'] ? $blog['blog_slug'] : $blog['id'] }}" style="text-decoration: none !important;">
								<p style="color:#373b3e !important;" class="abt_standard fumacoFont_card_title">{{ $blog['title'] }}</p>
							</a>
							<div class="blog-text ellipsis">
								<p class="blog-text-concat abt_standard">{{ $blog['caption'] }}</p>
							</div>
							
							<a href="/blog/{{ $blog['blog_slug'] ? $blog['blog_slug'] : $blog['id'] }}" class="text-concat mx-auto read-more">Read More</a>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div style="float: right;">
					{{ $results->withQueryString()->links('pagination::bootstrap-4') }}
				</div>
			</div>
		</div>
  	</div>
</main>
<style>
	
</style>
@endsection

@section('style')
<style>
    .text {
      position: relative;
      font-size: 16px;
      width: 100%;
    }
	.blog-text {
      position: relative;
      font-size: 12px !important;
      width: 100%;
    }

    .text-concat {
		position: relative;
		display: inline-block;
		word-wrap: break-word;
		overflow: hidden;
		max-height: 5.6em;
		line-height: 1.5em;
		text-align: left;
		font-size: 16px !important;
	}
	
	.mob-prod-text-concat {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2; /* number of lines to show */
				line-clamp: 2; 
		-webkit-box-orient: vertical;
	}

	.blog-text-concat {
		position: relative;
		display: inline-block;
		word-wrap: break-word;
		overflow: hidden;
		max-height: 4.8em;
		line-height: 1.5em;
		text-align: left;
		font-weight: 300 !important;
		color:#404040 !important;
	}

    .text.ellipsis::after {
      position: absolute;
      right: -12px; 
      bottom: 4px;
    }
	.read-more{
		text-transform: none !important;
		text-decoration: none !important;
		font-size: 12px !important;
		color: #000 !important;
		border-bottom: 1px solid #404040;
	}
	.abt_standard{
		font-family: 'poppins', sans-serif !important;
		text-decoration: none !important;
	}
	.prod_desc{
        font-size: 16px !important;
        font-weight: 500 !important;
        text-align: left !important;
    }
	.overlay-bg{
		position: absolute !important;
		background-color: rgba(255,255,255,0.2) !important;
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
	
	.btn-container{
		width: 100%;
		position: absolute; 
		top: 50%; 
		left: 0; 
		z-index: 9; 
		display: none; 
		text-align: center;
	}

	.view-products-btn{
		z-index: 2;
		text-align: center;
		background-color: #0062A5;
		color:#fff;
		font-size:13px;
		letter-spacing:2px;
		text-transform:uppercase;
		padding:8px 20px;
		font-weight:400;
		transition:all .15s ease-in;
	}

	.view-products-btn:hover{
		background-color: #f8b878; 
    	color: black;
	}

	.product-card:hover .overlay-bg{ 
		transition:all .15s ease-in !important;
		opacity: 1 !important;
	}
	
	.hover{
      transition: .5s;
    }

    .hover:hover, .hover-container:hover img {
      -ms-transform: scale(0.95); /* IE 9 */
      -webkit-transform: scale(0.95); /* Safari 3-8 */
      transform: scale(0.95); 
    }

	#search-page-container{
        position: absolute !important;
        background-color: #fff;
		top: 220px !important;
        z-index: 999 !important;
		width: 100% !important;
    }
	
	.modal .modal-dialog {
		position: fixed;
		margin: auto;
		width: 80%;
		height: 100%;
		transform: translate3d(0%, 0, 0);
	}
	.modal .modal-content {
		height: 100%;
		overflow-y: auto;
	}
	.modal .modal-body {
		padding: 15px 15px 80px;
	}
	.modal.right.fade .modal-dialog {
		right: -320px;
		transition: opacity 0.1s linear, right 0.1s ease-out;
	}
	.modal.right.fade.show .modal-dialog {
		right: 0;
	}

	/* ----- MODAL STYLE ----- */
	.modal-content {
		border-radius: 0;
		border: none;
	}
	.modal-header {
		border-bottom-color: #eeeeee;
		background-color: #fafafa;
	}

	.results-banner{
		height: 17rem !important;
	}
	@media (max-width: 575.98px) {
		.results-count{
			text-align: center !important;
		}
		.results-head{
			font-size: 14pt !important;
		}
		.results-count-head{
			font-size: 15pt !important;
		}
		.results-banner{
			height: 13rem !important; 
		}

	}
  	@media (max-width: 767.98px) {
		.results-count{
			text-align: center !important;
		}
		.results-head{
			font-size: 14pt !important;
		}
		.results-count-head{
			font-size: 15pt !important;
		}
		.results-banner{
			height: 13rem !important; 
		}
	}

	@media (max-width: 1199.98px) {/* tablet */
      .price-card{
        min-height: 80px !important;
      }
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

  })();

  // Product Image Hover
  $('.hover-container').hover(function(){
      $(this).children('.btn-container').slideToggle('fast');
    });

	$('.search-page-autocomplete').keyup(function(){
        var data = {
          'search_term': $(this).val(),
          'type': 'desktop'
        }
        
        $.ajax({
          type:'GET',
          data: data,
          url:'/search',
          success: function (autocomplete_data) {
            if(autocomplete_data){
              $("#search-page-container").show();
              $('#search-page-container').html(autocomplete_data);
            }
          }
        });
    });

	$(document).mouseup(function(e) 
      {
          var desk_container = $("#search-page-container");
          // if the target of the click isn't the container nor a descendant of the container
          if (!desk_container.is(e.target) && desk_container.has(e.target).length === 0) 
          {
              desk_container.hide();
          }
    });

	$('body').on('scroll', function (e){
		$("#search-page-container").hide();
	});

	$('.attrib-checkbox').change(function(){
		var is_brand = '';
		if($(this).data('attrname') == 'brand'){
			is_brand = '[]';
		}

		var selected_attrib = $(this).data('attrname') + is_brand + '=' + $(this).val();

		if($(this).is(":checked")){
			var url = document.URL + '&' + encodeURI(selected_attrib);
		}else{
			var url = document.URL.replace('&'+encodeURI(selected_attrib), '');
		}
		window.location.href=url;
	});

</script>
@endsection
