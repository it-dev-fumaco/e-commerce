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
<main style="background-color:#ffffff; min-height: 600px; width: 100% !important; min-height: 80vh !important" class="products-head">
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
							<a class="btn {{ count($products) > 0 ? 'd-sm-block d-lg-none' : 'd-none' }} open-modal" data-target="#rightModal" style="font-size: 0.75rem !important; float: left !important; margin-top: 6%; white-space: nowrap !important;">
								<i class="fas fa-filter"></i>&nbsp; Filters
							</a>
						</div>
						<div class="p-2"><label class="mt-1 mb-1 mr-0" style="font-size: 0.75rem; white-space: nowrap !important">Sort By</label></div>
						<div class="p-2 {{ count($products) > 0 ? null : 'd-none' }} d-none4 d-md-inline">
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
									<div class="col-1 offset-11">
										<button type="button" class="btn close-modal" data-target="#rightModal">
											<i class="fa fa-remove" style="font-size: 20px; color: #BDBDBD;"></i>
										</button>
									</div>
									<form action="/" method="get" id="filter-form2">
										<div class="row">
											<div class="d-none">
												<input type="text" class="d-none" name="s" value="{{ request()->s ? request()->s : null }}">
											</div>
											<div class="col-6">
												<h5 style="color: #221E1F">Filter Results</h5>
												<span style="font-size: 9pt; color: #606166; font-weight: 600;">
													Results {{ $results->lastItem() }} (Out of {{ $results->total() }})
												</span>
											</div>
											<div class="col-6">
												<div class="row p-0">
													<div class="col-9 d-flex flex-row justify-content-center align-items-center p-0">
														<select name="sortby" class="form-control form-control-sm" style="font-size: 11pt; font-weight: 400; display: inline-block; color: #000; padding-top: 7px; padding-bottom: 7px;">
															<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
															<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
															<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
														</select>
													</div>
													<div class="col-3 p-0">
														<div class="p-2" style="font-size: 1.3rem;">
															@if ((request()->order == 'desc'))
															<a href="{{ request()->fullUrlWithQuery(['order' => 'asc']) }}" style="color: #000;">
																<i class="fas fa-sort-amount-down-alt"></i>
															</a>
															@else
															<a href="{{ request()->fullUrlWithQuery(['order' => 'desc']) }}" style="color: #000;">
																<i class="fas fa-sort-amount-up-alt"></i>
															</a>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 p-0">
												<div id="accordion" class="container-fluid p-0">
													@if (count($filters['Brand']) > 1)
														<div class="card text-left" style="border: none;">
															<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-brand2">
																<span class="panel-title" style="font-weight: 600; font-size: 10pt;">Brand</span>
																<i id="filter-brand2-arrow" class="fas fa-caret-up" style="position: absolute; right: 0;"></i>
															</div>
							
															<div id="filter-brand2" class="collapse show">
																<div class="card-body">
																	@foreach ($filters['Brand'] as $brand)
																		@php
																			$request_brand = isset(request()->attr['brand']) ? request()->attr['brand'] : [];
																			$status = (in_array($brand, $request_brand)) ? 'checked' : '';
																		@endphp
																		<div class="form-check">
																			<input class="form-check-input filter-checkbox" type="checkbox" name="attr[brand][]" value="{{ $brand }}" data-attr="brand" {{ $status }}>
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
						
															if(count($filters['Brand']) <= 1 && $loop->first || isset(request()->attr[$filter_attr])){
																$collapse = 'show';
																$arrow = 'up';
															}
														@endphp
														@if ($id != 'Brand')
															<div class="card text-left" style="border: none;">
																<div class="container p-2 filter-id collapse-btn" style="border-bottom: 1px solid #C9C9CB" data-target="#filter-{{ $filter_attr }}2">
																	<span class="panel-title" style="font-weight: 600; font-size: 10pt;">{{ $id }}</span>
																	<i id="filter-{{ $filter_attr }}2-arrow" class="fas fa-caret-{{ $arrow }}" style="position: absolute; right: 0;"></i>
																</div>
															
																<div id="filter-{{ $filter_attr }}2" class="collapse {{ $collapse }}">
																	<div class="card-body">
																		@foreach ($filter as $value)
																			@php
																				$request_filters = isset(request()->attr[$filter_attr]) ? request()->attr[$filter_attr] : [];
																				$status = (in_array($value, $request_filters)) ? 'checked' : '';
																			@endphp
																			<div class="form-check">
																				<input class="form-check-input filter-checkbox" type="checkbox" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $value }}" data-attr="{{ $filter_attr }}" {{ $status }}>
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
											</div>
										</div>
									</form>
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
			@if ($filter_count > 0 or count($request_brand) > 1)
			<div class="d-none {{ request()->s == '' ? '' : 'd-xl-block' }} col-1">&nbsp;</div>
			<div class="d-none col-lg-3 col-xl-2 {{ request()->s == '' ? '' : 'd-lg-block' }}">
				<!--sidebar-->
				<form action="/" method="get" id="filter-form">
					<input type="text" class="d-none" name="s" value="{{ request()->s ? request()->s : null }}">
					<div class="col-12 p-0">
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
													$request_brand = isset(request()->attr['brand']) ? request()->attr['brand'] : [];
													$status = (in_array($brand, $request_brand)) ? 'checked' : '';
												@endphp
												<div class="form-check">
													<input class="form-check-input filter-checkbox" type="checkbox" name="attr[brand][]" value="{{ $brand }}" data-attr="brand" {{ $status }}>
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

									if(count($filters['Brand']) <= 1 && $loop->first || isset(request()->attr[$filter_attr])){
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
														$request_filters = isset(request()->attr[$filter_attr]) ? request()->attr[$filter_attr] : [];
														$status = (in_array($value, $request_filters)) ? 'checked' : '';
													@endphp
													<div class="form-check">
														<input class="form-check-input filter-checkbox" type="checkbox" name="{{ 'attr[' .$filter_attr.'][]' }}" value="{{ $value }}" data-attr="{{ $filter_attr }}" {{ $status }}>
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
					</div>
				</form>
				<!--sidebar-->
			</div>
			@endif
			@php
				$mx_auto = '';
				if($filter_count == 0 and count($request_brand) < 2){
					$mx_auto = 'mx-auto';
				}
			@endphp
			<div class="col-lg-9 col-xl-8 {{ $mx_auto }}">
				<div class="row" id="products-list">
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
														$image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
													@endphp              
													<picture>
														<source srcset="{{ asset($image_webp) }}" type="image/webp">
														<source srcset="{{ asset($image) }}" type="image/jpeg"> 
														<img src="{{ asset($image) }}" alt="{{ Str::slug($item['item_name'], '-') }}" class="card-img-top hover" loading="lazy">
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
														<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}">
															Add to Cart
														</a>
													@else
														<center>
															<span class="mb-2" style="font-weight: 600; color: #F50000">Out of Stock</span>
														</center>
														<a href="#" class="btn btn-outline-primary text-center w-100 p-2 notify-me border-0" role="button" style="font-weight: 600; font-size: 10pt; margin-bottom: 5px;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">
															Notify me
														</a>
														<a href="/login" class="btn btn-outline-primary mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}">
															Add to Wishlist
														</a>
													@endif
												</div>
											</div>								
										</div>
									</div>
								</div>
								<!-- Mobile view end -->
								<!-- Desktop/Tablet view start -->
								<div class="d-none d-md-block">
									@include('frontend.product_details_card')
								</div>
								<!-- Desktop/Tablet view end -->
							@endforeach
						@endif
						<div class="col-12 text-center">
							<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp results-head" style="color:#000000 !important;">{{ request()->s == null ? 'FEATURED PRODUCT(S)' : 'PRODUCT(S)' }}</h4>
						</div>
						@foreach ($products as $item)
							<!-- Mobile view start -->
							<div class="d-block d-md-none animated animatedFadeInUp fadeInUp mb-2">
								<div class="card">
									<div class="pt-2" style="position: absolute; top: 0; left: 0; z-index: 10;">
										@if($item['is_new_item'])
										<div class="col-12 mb-1 {{ $item['is_new_item'] == 1 ? '' : 'd-none' }}">
											<span class="text-center" style="background-color: #438539; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
											&nbsp;<b>New</b>&nbsp;
											</span>
										</div>
										@endif
										@if ($item['is_discounted'])
										<div class="col-12">
											<span class="text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
												&nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
											</span>
										</div>
										@endif
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												@php
													$image = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
													$image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
												@endphp              
												<picture>
													<source srcset="{{ asset($image_webp) }}" type="image/webp">
													<source srcset="{{ asset($image) }}" type="image/jpeg"> 
													<img src="{{ asset($image) }}" alt="{{ Str::slug($item['alt'], '-') }}" class="card-img-top hover" loading="lazy">
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
													<a href="#" class="btn btn-outline-primary text-center w-100 p-2 add-to-cart" role="button" style="font-weight: 600; margin-bottom: 20px; font-size: 10pt;" data-item-code="{{ $item['item_code'] }}">Add to Cart</a>
												@else
													<center>
														<span style="font-weight: 600; color: #F50000">Out of Stock</span>
													</center>
													<a href="#" class="btn btn-outline-primary text-center w-100 p-2 notify-me border-0" role="button" style="font-weight: 600; font-size: 10pt; margin-bottom: 5px;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">
														Notify me
													</a>
													<a href="/login" class="btn w-100 text-center w-100 p-2 {{ Auth::check() ? 'add-to-wishlist' : '' }} btn-hover" role="button" data-item-code="{{ $item['item_code'] }}" style="background-color: #E6F0F8; color: #0F6EB5; font-weight: 600; font-size: 10pt;">
														Add to Wishlist
													</a>
												@endif
											</div>
										</div>								
									</div>
								</div>
							</div>
							<!-- Mobile view end --> 
							<!-- Desktop/Tablet view start -->
							<div class="col-4 d-none d-md-inline">
								@php
									$col = '12';
								@endphp
								@include('frontend.product_details_card')
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
									$image_webp = ($blog['image']) ? '/storage/journals/'.explode(".", $blog['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
								@endphp
							
								<picture>
									<source srcset="{{ asset($image_webp) }}" type="image/webp">
									<source srcset="{{ asset($image) }}" type="image/jpeg">
									<img src="{{ asset($image) }}" alt="{{ Str::slug($blog['title'], '-') }}" class="card-img-top" loading='lazy'>
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
		position:absolute;
		bottom:40%;
		left:0;
		right:0;
		background-color:rgba(0,0,0,0);
		overflow:hidden;
		width:100%;
		height:0;
		transition:.5s;
		display:flex;
		justify-content:center;
		align-items:center
	}

	.hover-container:hover .btn-container{height:50px}

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
		position: absolute;
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
		width: 100%;
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

	.filter-id{
		cursor: pointer;
	}
	.form-check-input:checked{
		background-color: green !important;
		border: 0;
	}
	.form-check-input:focus, .label::after, label.form-check-label:focus, .form-check-input::after, .form-check-input:not(:disabled):not(.disabled):active:focus {
		color: black;
		border: 1px solid #BFBFBF;
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

	$(document).on('click', '.collapse-btn', function (){
		var target = $(this).data('target');
		$(target + "-arrow").toggleClass('flip');

		$(target).collapse('toggle');
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

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
		var filter_form = '#filter-form2';
	}else{ // desktop
		var filter_form = '#filter-form';
	}

	$(document).on('click', '.filter-checkbox', function (){
		loadProducts(1);
	});

	$(document).on('click', '#products-list-pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadProducts(page);
	});

	// loadProducts(1);
	function loadProducts(page) {
		$.ajax({
			type: "GET",
			url: "/?s={{ request()->s }}&page=" + page,
			data: $(filter_form).serialize(),
			success: function (response) {
				$('#products-list').html(response);
			}
		});
	}
</script>
@endsection
