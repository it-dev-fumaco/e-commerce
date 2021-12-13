@extends('frontend.layout', [
    'namePage' => ($product_details->url_title) ? $product_details->url_title : $product_details->f_name_name,
    'activePage' => 'product_page'
])

@section('meta')

@php
	$src = (count($product_images) > 0) ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/original/'. $product_images[0]->imgoriginalx : '/storage/no-photo-available.png';
	@endphp


<meta name="description" content="{{ $product_details->meta_description }}">
	<meta name="keywords" content="{{ $product_details->keywords }}" />


	<meta property="og:url" content="https://www.fumaco.com/product/{{ ($product_details->slug) ? $product_details->slug : $product_details->id }}" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $product_details->f_name_name }}" />
	<meta property="og:description" content="{{ $product_details->f_description }}" />
	<meta property="og:image" content="{{ asset($src) }}" />
	<meta property="og:image:width" content="600" >
	<meta property="og:image:height" content="315" >

@endsection

@section('content')
	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 14px !important;">
				<li class="breadcrumb-item"><a href="/" style="color: #000000 !important; text-decoration: none;">Home</a></li>
				<li class="breadcrumb-item active"><a href="/products/{{ $product_details->f_cat_id }}" style="color: #000000 !important; text-decoration: none;">{{ $product_details->f_category }}</a></li>
				<li class="breadcrumb-item active"><a href="#" style="color: #000000 !important; text-decoration: underline;">{{ $product_details->f_brand }}</a></li>
			</ol>
		</nav>
	</main>
	<br>
	<div class="container"></div>
	<form action="/product_actions" method="POST" autocomplete="off">
		@csrf
		<main class="prod-main" style="background-color:#ffffff;">
			<div class="container marketing">
				<div class="single_product prod-main" style="padding-bottom: 0px !important;">
					<div class="container-fluid" style=" background-color: #fff; padding: 11px;">
						<div class="row">
							<div class="col-lg-4">
								<div class="xzoom-container" style="width: 100% !important;">
									@php
									$src = (count($product_images) > 0) ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/preview/'. $product_images[0]->imgprimayx : '/storage/no-photo-available.png';
									$xoriginal = (count($product_images) > 0)  ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/original/'. $product_images[0]->imgoriginalx : '/storage/no-photo-available.png';
									@endphp
									<img style="width: 100% !important;" alt="{{ isset($product_images[0]) ? Str::slug(explode(".", $product_images[0]->imgprimayx)[0], '-') : '' }}" class="xzoom4 imgx" id="xzoom-fancy" src="{{ asset($src) }}" xoriginal="{{ asset($xoriginal) }}" />
									<br><br>
									<div class="xzoom-thumbs">
										@foreach ($product_images as $image)
										<a href="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/original/'. $image->imgoriginalx) }}"><img class="xzoom-gallery4" width="60" src="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/preview/'. $image->imgprimayx) }}" alt="{{ Str::slug(explode(".", $image->imgprimayx)[0], '-') }}" /></a>
										@endforeach
									</div>
								</div>
							</div>
							<div class="col-lg-8 order-3">
								<div class="product_description">
									<div class="message_box" style="margin:10px 0px;">
										@if ($message = Session::get('success'))
										<div class="alert alert-success alert-dismissible fade show" role="alert">{{ $message }}</div>
										@endif
									</div>
									<div class="product_name fumacoFont_item_title">{{ $product_details->f_name_name }}</div>
									<div class="product-rating">
										<div class="d-flex justify-content-between align-items-center">
											<div class="btn-group stylecap">
												<span class="fa fa-star starcolorgrey"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span style="color:#000000 !important; font-weight:200 !important;">&nbsp;&nbsp;( 0 Reviews )</span>
											</div>
										</div>
										<div class="d-flex flex-row p-0">
											<div style="font-size: 16pt; padding: 3px 8px;">
												<a target="_blank" href="mailto:?subject=Check this out!&body=Hi, I found this product and thought you might like it {{ \Request::fullUrl() }}" class="m-0" style=" color: #f49332;"><i class="far fa-envelope m-0"></i></a>
											</div>
											<div class="pt-2">
												<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0&appId=435536724607670&autoLogAppEvents=1" nonce="oVFop3CH"></script>
												<div class="fb-like" data-href="{{ \Request::fullUrl() }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
											</div>
										</div>
									</div>
									<div>
										@if ($product_details->f_discount_trigger)
										<span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_details->f_price), 2) }}</span>
										<s class="product_discount">
											<span style='color:black; '>₱ {{ number_format(str_replace(",","",$product_details->f_original_price), 2) }}<span>
										</s>
										@elseif($discount_from_sale == 1)
											<span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_price), 2) }}</span>
											<s class="product_discount">
												<span style='color:black; '>₱ {{ number_format(str_replace(",","",$product_details->f_original_price), 2) }}<span>
											</s>
											@if ($sale_discount_type == 'By Percentage')
												<span class="badge badge-danger" style="margin-left: 8px; vertical-align: middle;background-color: red; display: inline !important;">{{ $sale_discount_rate }}% OFF</span>
											@elseif($sale_discount_type == 'Fixed Amount') 
												<span class="badge badge-danger" style="margin-left: 8px; vertical-align: middle;background-color: red; display: inline !important;">₱ {{ number_format(str_replace(",","",$sale_discount_rate), 2) }} OFF</span>
											@endif
										@else
										<span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_details->f_original_price), 2) }}</span>
										@endif
										<span class="badge badge-danger" style="margin-left: 8px; vertical-align: middle;background-color: red; display: {{ ($product_details->f_discount_trigger) ? 'inline' : 'none' }} !important;">{{ $product_details->f_discount_percent }}% OFF</span>
									</div>
									<br class="d-md-none"/>
									<div><span class="prod-font-size">{!! $product_details->f_caption !!}</span>
										<p class="card-text fumacoFont_card_caption">
											<ul style="margin-top: -12px !important;">
												<li>
													<a href="#product_details" style="text-decoration: none;">
														<span style="text-decoration: none;color: #1a6ea9 !important;font-size: 12px !important; font-weight: 400 !important;">See more products details</span>
													</a>
												</li>
											</ul>
										</p>
										<input type="hidden" name="item_code" value="{{ $product_details->f_idcode }}">
										<p class="card-text">QTY&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;   <input type="number" value="1" id="quantity" name="quantity" min="1" max="{{ ($product_details->f_qty > 0) ? $product_details->f_qty : 1 }}" style="width: 70px;"></p>
										<p class="card-text">
											@if($product_details->f_qty < 1)
											<span style='color:red;';>Not Available</span>
											@else
											<span style='color:green;';>Available</span>
											@endif
											&nbsp;&nbsp; <i class="fas fa-bell"></i>
										</p>
									</div>
									<hr class="singleline">
									@php
										$variant_diff = array_diff(array_keys($variant_attr_arr), array_keys($attributes->toArray()));
									@endphp
									@if (count($variant_diff) > 0)
									<div class="alert alert-danger fade show mb-0" role="alert">Error in Variants.</div>
									@endif
									@foreach ($variant_attr_arr as $attr => $row)
									@if (count($row) > 1)
									@php
										$x = 0;
										$opt_name = preg_replace('/\s+/', '', strtolower($attr));
									@endphp
									<label>{{ $attr }} : </label><br class="d-md-none"/>
									<div class="btn-group" role="group" aria-label="Select Variants" style="display: unset !important;">
										@foreach ($row as $attr_value => $items)
										@php
											$x++;
											$is_active = [1];
										@endphp
										@if (isset($attributes[$attr]))
										<input type="radio" class="btn-check attr-radio" {{ ($attributes[$attr] == $attr_value) ? 'checked' : '' }} name="{{ $opt_name }}" id="{{ $opt_name . $x }}" autocomplete="off" value="{{ $attr_value }}" data-attribute="{{ Str::slug($attr, '-') }}" {{ (count($is_active) > 0) ? '' : 'disabled' }}>
										<label class="btn btn-outline-{{ (count($is_active) > 0) ? 'info' : 'secondary' }} btn-sm mb-2 mt-2" for="{{ $opt_name . $x }}">
											@if(count($is_active) > 0)
											{{ $attr_value }}
											@else
											<del>{{ $attr_value }}</del>
											@endif
										</label>
										@else
										error
										@endif
										@endforeach
									</div><br>
									@endif
									@endforeach
									<div class="row mt-5" id="product_details">
										<div class="col-xs-6 d-none d-md-block">
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" name="addtocart" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}" value="1"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" name="buynow" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}"  value="1"><i class="fas fa-wallet"></i> Buy Now</button>
											@if($product_details->f_qty < 1)
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important;" name="addtowishlist" value="1"><i class="fas fa-heart"></i> Add to Wish List</button>
											@endif
										</div>

										<div class="col-xs-6 d-md-none">
											<div class="col-sm-12">
												<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore col-sm-12" name="addtocart" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}; width: 100% !important" value="1"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
											</div><br/>
											<div class="col-md-12">
												<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore col-sm-12" name="buynow" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}; width: 100% !important"  value="1"><i class="fas fa-wallet"></i> Buy Now</button>
											</div>
											@if($product_details->f_qty < 1)
											<div class="col-md-12">
												<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore col-sm-12" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; width: 100% !important" name="addtowishlist" value="1"><i class="fas fa-heart"></i> Add to Wish List</button>
											</div>
											@endif
										</div>
										<div class="row"><br></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
		</form>
		<main  style="background-color:#ffffff; margin-top: 0px !important;" class="products-head2 prod-details" >
			<div class="container">
				<div class="row">
					<br>
					<div class="col-lg-12">
						<br>
						<div class="accordion" id="accordionExample">
							<div class="card">
								<div class="card-header" id="headingOne">
									<h2 class="mb-0">
										<button  class="btn btn-link collapsed fumacoFont_collapse_title abt_standard" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseOne">PRODUCT DETAIL</button>
									</h2>
								</div>
								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
									<div class="card-body prod_standard p-0">
										<p class="card-text">
											<table class="table">
												<tbody style="border-style: inset !important;">
													@foreach ($filtered_attributes as $attr => $value)
													<tr>
														<td>{{ $attr }}</td>
														<td>{{ $value }}</td>
													</tr>
													@endforeach
												</tbody>
											</table>
                              </p>
                           </div>
                        </div>
							</div>
							<div class="card">
								<div class="card-header" id="headingTwo">
									<h2 class="mb-0">
										<button class="btn btn-link collapsed fumacoFont_collapse_title abt_standard" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo">ADDITIONAL INFORMATION</button>
									</h2>
								</div>
								<div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
									<div class="card-body prod_standard">
										<p class="card-text">{!! $product_details->f_full_description !!}</p>
									</div>
								</div>
							</div>

							@if ($products_to_compare)
								<section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
									<div class="row py-lg-5">
										<div class="col-lg-6 col-md-8 mx-auto">
											<h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">COMPARE SIMILAR PRODUCTS</h4>
										</div>
									</div>
								</section>
								<div class="row">
									<section class="regular slider">
										@foreach ($compare_arr as $compare_product)
											<div class="col-md-{{ 12/count($compare_arr) }} equal-height-columns p-1">
												@php
													$compare_img = ($compare_product['item_image']) ? '/storage/item_images/'. $compare_product['item_code'] .'/gallery/original/'. $compare_product['item_image'] : '/storage/no-photo-available.png';
													$compare_img_webp = ($compare_product['item_image']) ? '/storage/item_images/'. $compare_product['item_code'] .'/gallery/original/'. explode(".", $compare_product['item_image'])[0] .'.webp' : '/storage/no-photo-available.png';
												@endphp
												<div class="hover-container product-card" style="position: relative;">
													<div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
														@if($compare_product['discounted_from_item'] == 1)
															<div class="col-12">
																<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
																	&nbsp;<b>{{ $compare_product['individual_discount_rate'] }}% OFF</b>&nbsp;
																</span>
															</div>
														@elseif($compare_product['discounted_from_sale'] == 1)
															<div class="col-12">
																<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
																	@if ($compare_product['sale_discount_type'] == 'By Percentage')
																		&nbsp;<b>{{ $compare_product['sale_discount_rate'] }}% OFF</b>&nbsp;
																	@elseif($compare_product['sale_discount_type'] == 'Fixed Amount')
																		&nbsp;<b>₱ {{ number_format($compare_product['sale_discount_rate'], 2, '.', ',') }} OFF</b>&nbsp;
																	@endif
																</span>
															</div>
														@endif
													</div>
													<div class="overlay-bg"></div>

													<div class="btn-container">
														<a href="/product/{{ ($compare_product['slug']) ? $compare_product['slug'] : $compare_product['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
													</div>
													<picture>
														<source srcset="{{ asset($compare_img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
														<source srcset="{{ asset($compare_img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
														<img src="{{ asset($compare_img) }}" alt="{{ Str::slug(explode(".", $compare_product['item_image'])[0], '-') }}" class="img-responsive hover" style="width: 100%" />
													</picture>
												</div>
												
												<span class="comparison-description">{{ $compare_product['product_name'] }}</span>
												<br/>&nbsp;
												<hr>
												<center>
												@if ($compare_product['discounted_from_item'] == 1)
													<span class="comparison-price" style="white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$compare_product['price']), 2) }}</span>&nbsp;<s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$compare_product['original_price']), 2) }}</s>
												@elseif ($compare_product['discounted_from_sale'] == 1)
													<span class="comparison-price" style="white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$compare_product['price']), 2) }}</span>
													<s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$compare_product['original_price']), 2) }}</s>
												@else
													₱ {{ number_format(str_replace(",","",$compare_product['original_price']), 2) }}
												@endif
												<br/><br/>
												@if($compare_product['on_stock'] == 1)
													<button class="btn btn-pill btn-outline-primary btn-sm add-to-cart comparison-add-to-cart" type="button" data-toggle="toast" data-item-code="{{ $compare_product['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block"></i>&nbsp;Add to Cart</button>
												@else
													<a href="/login" class="btn btn-pill btn-outline-primary btn-sm {{ Auth::check() ? 'add-to-wishlist' : '' }} comparison-add-to-cart" type="button" data-toggle="toast" data-item-code="{{ $compare_product['item_code'] }}"><i class="fas fa-heart d-inline-block"></i>&nbsp;Add to Wishlist</a>
												@endif
												<br/><br/>

												@foreach ($attribute_names as $attrib)
													<div class="col-12">
														<span style="font-size: 11pt" class="text-muted">{{ $attrib->attribute_name }}</span>
														<hr class="mt-1 mb-1"/>
														<span class="comparison-description">{{ $variant_attr_array[$attrib->attribute_name][$compare_product['item_code']] }}</span>
													</div>
													<br/>
												@endforeach
												</center>
											</div>
										@endforeach
									</section>
								</div>
							@endif
							
                    @if (count($related_products) > 0)
							<section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
								<div class="row py-lg-5">
									<div class="col-lg-6 col-md-8 mx-auto">
										<h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">RELATED PRODUCT(S)</h4>
									</div>
								</div>
							</section>

							<div class="album py-5" style="position: relative">
								<div class="container related-prod">
										<section class="regular slider">
										@foreach($related_products as $rp)
										<div class="col-md-4 col-lg-3 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 related-products-card">
											<div class="card shadow-sm" style="border: 1px solid  #d5dbdb; background-color: #fff;">
												<div class="equal-column-content">
													
													@php
														$img = ($rp['image']) ? '/storage/item_images/'. $rp['item_code'] .'/gallery/preview/'. $rp['image'] : '/storage/no-photo-available.png';
														$img_webp = ($rp['image']) ? '/storage/item_images/'. $rp['item_code'] .'/gallery/preview/'. explode(".", $rp['image'])[0] .'.webp' : '/storage/no-photo-available.png';
													@endphp

													<div class="hover-container product-card" style="position: relative">
														<div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
															<div class="col-12 mb-2 {{ $rp['is_new_item'] == 1 ? '' : 'd-none' }}">
																<span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
																&nbsp;<b>New</b>&nbsp;
																</span>
															</div><br class="{{ $rp['is_new_item'] == 1 ? '' : 'd-none' }}"/>
															@if ($rp['is_discounted'] == 1)
																<div class="col-12">
																	<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
																		&nbsp;<b>{{ $rp['discount_percent'] }}% OFF</b>&nbsp;
																	</span>
																</div>
															@elseif ($rp['is_discounted_from_sale'] == 1)
																<div class="col-12">
																	<span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px">
																		@if ($rp['sale_discount_type'] == 'By Percentage')
																			&nbsp;<b>{{ $rp['sale_discount_rate'] }}% OFF</b>&nbsp;
																		@else
																			&nbsp;<b>₱ {{ number_format($rp['sale_discount_rate'], 2, '.', ',') }} OFF</b>&nbsp;
																		@endif
																	</span>
																</div>
															@endif
														</div>
														<div class="overlay-bg"></div>

														<div class="btn-container">
															<a href="/product/{{ ($rp['slug']) ? $rp['slug'] : $rp['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
														</div>

														<picture>
															<source srcset="{{ asset($img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
															<source srcset="{{ asset($img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
															<img src="{{ asset($img) }}" alt="{{ Str::slug(explode(".", $rp['image'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
														</picture>
													</div>
													<div class="card-body d-flex flex-column">
														<div class="text ellipsis">
															<a href="/product/{{ ($rp['slug']) ? $rp['slug'] : $rp['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-decoration: none !important; text-transform: none !important; color:#0062A5 !important;  min-height: 100px;">{{ $rp['item_name'] }}</a>
														</div>

														<p class="card-text fumacoFont_card_price d-sm-block d-md-none d-lg-block" style="color:#000000 !important; ">
															@if ($rp['is_discounted'] == 1)
															<span style="white-space: nowrap !important">₱ {{ number_format(str_replace(",","",$rp['new_price']), 2) }}</span>&nbsp;<br class="d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$rp['orig_price']), 2) }}</s>
															@elseif($rp['is_discounted_from_sale'] == 1)
																₱ {{ number_format(str_replace(",","",$rp['sale_discounted_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$rp['orig_price']), 2) }}</s>
															@else
															₱ {{ number_format(str_replace(",","",$rp['orig_price']), 2) }}
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
														<br>
													</div>
												</div><br/>&nbsp;
												@if ($rp['on_stock'] == 1)
												<a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $rp['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
												@else
												<a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $rp['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
												@endif
											</div>
										</div>
										@endforeach
										</section>
								</div>
							</div>
						@endif
						</div>
					</div>
				</div>
			</div>
		</main>

		<main style="background-color:#ffffff;">
			<br><br><br><br><br>
		</main>
@endsection

@section('style')
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/fancybox/source/jquery.fancybox.css') }}" />
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/magnific-popup/css/magnific-popup.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/item/dist/xzoom.css') }}" media="all" />
<link type="text/css" rel="stylesheet" href="{{ asset('/assets/loading.css') }}" />
<style>
	
	._1yv {
			box-shadow: 0 0px 0px rgb(0 0 0 / 30%), 0 0 0 1px rgb(0 0 0) !important;
	}
	._3ixn {
			position: unset !important;
	}
	html {
		scroll-behavior: smooth;
	}
		.spinner-wrapper {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #2e343a;
			z-index: 999999;
			padding-top: 15%;
		}
		.spinner {
			width: 40px;
			height: 40px;
			background-color: #0062A5;
			margin: 100px auto;
			-webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
			animation: sk-rotateplane 1.2s infinite ease-in-out;
		}
		@-webkit-keyframes sk-rotateplane {
			0% { -webkit-transform: perspective(120px) }
			50% { -webkit-transform: perspective(120px) rotateY(180deg) }
			100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
		}
		@keyframes sk-rotateplane {
			0% {
					transform: perspective(120px) rotateX(0deg) rotateY(0deg);
					-webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
			} 50% {
					transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
					-webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
			} 100% {
					transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
					-webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
			}
		}
		.breadcrumb-item+.breadcrumb-item::before {
			content: ">"
		}
		.breadcrumb {
			display: -ms-flexbox;
			display: flex;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			padding: .1rem 0rem !important;
			margin-bottom: 0rem;
			list-style: none;
			background-color: #ffffff;
			border-radius: .25rem
		}
		.single_product {
			padding-top: 66px;
			padding-bottom: 140px;
			background-color: #ffffff;
			margin-top: 0px;
			padding: 17px
		}
		.product_name {
			font-size: 20px;
			font-weight: 400;
			margin-top: 0px
		}
		.badge {
			display: inline-block;
			padding: 0.50em .4em;
			font-size: 75%;
			font-weight: 700;
			line-height: 1;
			text-align: center;
			white-space: nowrap;
			vertical-align: baseline;
			border-radius: .25rem
		}
		.product-rating {
			margin-top: 10px
		}
		.rating-review {
			color: #5b5b5b
		}
		.product_price {
			display: inline-block;
			font-size: 30px;
			font-weight: 500;
			margin-top: 9px;
			clear: left
		}
		.product_discount {
			display: inline-block;
			font-size: 14px;
			font-weight: 400;
			margin-top: 9px;
			clear: left;
			margin-left: 10px;
			color: #0f6db7;
		}
		.product_saved {
			display: inline-block;
			font-size: 15px;
			font-weight: 200;
			color: #999999;
			clear: left
		}
		.singleline {
			margin-top: 1rem;
			margin-bottom: .40rem;
			border: 0;
			border-top: 1px solid rgba(0, 0, 0, .1)
		}
		.product_info {
			color: #4d4d4d;
			display: inline-block
		}
		.product_options {
			margin-bottom: 10px
		}
		.product_description {
			padding-left: 0px
		}
		.product_quantity {
			width: 104px;
			height: 47px;
			border: solid 1px #e5e5e5;
			border-radius: 3px;
			overflow: hidden;
			padding-left: 8px;
			padding-top: -4px;
			padding-bottom: 44px;
			float: left;
			margin-right: 22px;
			margin-bottom: 11px
		}
		.order_info {
			margin-top: 18px
		}
		.shop-button {
			height: 47px
		}
		.product_fav i {
			line-height: 44px;
			color: #cccccc
		}
		.product_fav {
			display: inline-block;
			width: 52px;
			height: 46px;
			background: #FFFFFF;
			box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
			border-radius: 11%;
			text-align: center;
			cursor: pointer;
			margin-left: 3px;
			-webkit-transition: all 200ms ease;
			-moz-transition: all 200ms ease;
			-ms-transition: all 200ms ease;
			-o-transition: all 200ms ease;
			transition: all 200ms ease
		}
		.br-dashed {
			border-radius: 5px;
			border: 1px dashed #dddddd;
			margin-top: 6px
		}
		.pr-info {
			margin-top: 2px;
			padding-left: 2px;
			margin-left: -14px;
			padding-left: 0px
		}
		.break-all {
			color: #5e5e5e
		}
		.image_selected {
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: calc(100% + 15px);
			height: 525px;
			-webkit-transform: translateX(-15px);
			-moz-transform: translateX(-15px);
			-ms-transform: translateX(-15px);
			-o-transform: translateX(-15px);
			transform: translateX(-15px);
			border: solid 1px #e8e8e8;
			box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			padding: 15px
		}
		.image_list li {
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			height: 165px;
			border: solid 1px #e8e8e8;
			box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1) !important;
			margin-bottom: 15px;
			cursor: pointer;
			padding: 15px;
			-webkit-transition: all 200ms ease;
			-moz-transition: all 200ms ease;
			-ms-transition: all 200ms ease;
			-o-transition: all 200ms ease;
			transition: all 200ms ease;
			overflow: hidden
		}
		@media (max-width: 390px) {
			.product_fav {
					display: none
			}
		}
		.bbb_combo {
			width: 100%;
			margin-right: 7%;
			padding-top: 21px;
			padding-left: 20px;
			padding-right: 20px;
			padding-bottom: 24px;
			border-radius: 5px;
			margin-top: 0px;
			text-align: -webkit-center
		}
		.bbb_combo_image {
			width: 170px;
			height: 170px;
			margin-bottom: 15px
		}
		.fs-10 {
			font-size: 10px
		}
		.step {
			background: #167af6;
			border-radius: 0.8em;
			-moz-border-radius: 0.8em;
			-webkit-border-radius: 6.8em;
			color: #ffffff;
			display: inline-block;
			font-weight: bold;
			line-height: 3.6em;
			margin-right: 5px;
			text-align: center;
			width: 3.6em;
			margin-top: 116px
		}
		.row-underline {
			content: "";
			display: block;
			border-bottom: 2px solid #3798db;
			margin: 0px 0px;
			margin-bottom: 20px;
			margin-top: 15px
		}
		.deal-text {
			margin-left: -10px;
			font-size: 25px;
			margin-bottom: 10px;
			color: #000;
			font-weight: 700
		}
		.padding-0 {
			padding-left: 0;
			padding-right: 0
		}
		.padding-2 {
			margin-right: 2px;
			margin-left: 2px
		}
		.vertical-line {
			display: inline-block;
			border-left: 3px solid #167af6;
			margin: 0 10px;
			height: 364px;
			margin-top: 4px
		}
		.p-rating {
			color: green
		}
		.combo-pricing-item {
			display: flex;
			flex-direction: column
		}
		.boxo-pricing-items {
			display: inline-flex
		}
		.combo-plus {
			margin-left: 10px;
			margin-right: 18px;
			margin-top: 10px
		}
		.add-both-cart-button {
			margin-left: 36px
		}
		.items_text {
			color: #b0b0b0
		}
		.combo_item_price {
			font-size: 18px
		}
		.p_specification {
			font-weight: 500;
			margin-left: 22px
		}
		.mt-10 {
			margin-top: 10px
		}
		.single_product {
			padding-top: 16px;
			padding-bottom: 140px
		}
		.image_list li {
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			height: 165px;
			border: solid 1px #e8e8e8;
			box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
			margin-bottom: 15px;
			cursor: pointer;
			padding: 15px;
			-webkit-transition: all 200ms ease;
			-moz-transition: all 200ms ease;
			-ms-transition: all 200ms ease;
			-o-transition: all 200ms ease;
			transition: all 200ms ease;
			overflow: hidden
		}
		.image_list li:last-child {
			margin-bottom: 0
		}
		.image_list li:hover {
			box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.3)
		}
		.image_list li img {
			max-width: 100%
		}
		.image_selected {
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: calc(100% + 15px);
			height: 525px;
			-webkit-transform: translateX(-15px);
			-moz-transform: translateX(-15px);
			-ms-transform: translateX(-15px);
			-o-transform: translateX(-15px);
			transform: translateX(-15px);
			border: solid 1px #e8e8e8;
			box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			padding: 15px
		}
		.image_selected img {
			max-width: 100%
		}
		.product_category {
			font-size: 12px;
			color: rgba(0, 0, 0, 0.5)
		}
		.product_rating {
			margin-top: 7px
		}
		.product_rating i {
			margin-right: 4px
		}
		.product_rating i::before {
			font-size: 13px
		}
		.product_text {
			margin-top: 27px
		}
		.product_text p:last-child {
			margin-bottom: 0px
		}
		.order_info {
			margin-top: 16px
		}
		.product_quantity {
			width: 182px;
			height: 50px;
			border: solid 1px #e5e5e5;
			border-radius: 5px;
			overflow: hidden;
			padding-left: 25px;
			float: left;
			margin-right: 30px
		}
		.product_quantity span {
			display: block;
			height: 50px;
			font-size: 16px;
			font-weight: 300;
			color: rgba(0, 0, 0, 0.5);
			line-height: 50px;
			float: left
		}
		.product_quantity input {
			display: block;
			width: 30px;
			height: 50px;
			border: none;
			outline: none;
			font-size: 16px;
			font-weight: 300;
			color: rgba(0, 0, 0, 0.5);
			text-align: left;
			padding-left: 9px;
			line-height: 50px;
			float: left
		}
		.quantity_buttons {
			position: absolute;
			top: 0;
			right: 0;
			height: 100%;
			width: 29px;
			border-left: solid 1px #e5e5e5
		}
		.quantity_inc,
		.quantity_dec {
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			flex-direction: column;
			align-items: center;
			width: 100%;
			height: 50%;
			cursor: pointer
		}
		.quantity_control i {
			font-size: 11px;
			color: rgba(0, 0, 0, 0.3);
			pointer-events: none
		}
		.quantity_control:active {
			border: solid 1px rgba(14, 140, 228, 0.2)
		}
		.quantity_inc {
			padding-bottom: 2px;
			justify-content: flex-end;
			border-top-right-radius: 5px
		}
		.quantity_dec {
			padding-top: 2px;
			justify-content: flex-start;
			border-bottom-right-radius: 5px
		}
		.products-head {
			margin-top: 110px !important;
			padding-left: 40px !important;
			padding-right: 40px !important;
		}
		.products-head2 {
			margin-top: 0px !important;
			padding-left: 40px !important;
			padding-right: 40px !important;
		}
		.card {
			position: relative;
			display: flex;
			flex-direction: column;
			min-width: 0;
			word-wrap: break-word;
			background-color: #fff;
			background-clip: border-box;
			border: 0 !important;
			border-radius: 0 !important;
		}
		.btn-link {
			font-weight: 200;
			color: #373B3E;
			text-decoration: none;
		}
		.product-item-head-caption {
			font-weight: 200 !important;
			font-size: 14px!important;
			letter-spacing: 1px !important;
		}
		.imgx {
			width: 100% !important;
		}
		.btn-outline-info {
			color: #0062a5 !important;
			border-color: #0062a5 !important;
		}
		.btn-check:checked + .btn-outline-info, .btn-check:active + .btn-outline-info, .btn-outline-info:hover, .btn-outline-info:active, .btn-outline-info.active, .btn-outline-info.dropdown-toggle.show {
			color: #fff !important;
			background-color: #0062a5 !important;
			border-color: #0062a5 !important;
		}
		.xzoom-preview {
			z-index: 99999999;
		}
		._3ixn {
			bottom: 0;
			left: 0;
			position: unset;
			right: 0;
			top: 0;
		}
		.text{
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

	.abt_standard{
		font-family: 'poppins', sans-serif !important;
		text-decoration: none !important;
	}
	#related-products-container{
      overflow: auto;
      outline: none;
      overflow-y: hidden;
      -ms-overflow-style: scroll;  /* IE 10+ */
      scrollbar-width: none; /* Firefox */
    }
	#related-products-container::-webkit-scrollbar{ /* Chrome */
      display: none;
    }
    .scroll-control{
      height: 80px;
      width: 80px;
      background-color: rgba(0,0,0,0);
      border-radius: 50%;
      color: rgba(0,0,0,0.2);
      border: none !important;
      text-transform: none !important;
      text-decoration: none !important;
      transition: .4s
    }

    .scroll-control:hover{
      color: #000;
    }

    .scroll-control:focus {
      outline: none;
      box-shadow: none;
      text-transform: none !important;
      text-decoration: none !important;
      border: none !important;
    }

    .prev-btn, .next-btn{
      position: absolute;
      top: 50%;
      bottom: 50%;
    }

    .prev-btn{
      left: -50px !important;
    }
    .next-btn{
      right: -50px !important;
    }

	.overlay-bg{
		position: absolute !important;
		background-color: rgba(255,255,255,0.3) !important;
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
		/* color: #fff;
		background-color: #000; */
		background-color: #f8b878; 
    color: black;
	}

	.product-card:hover .overlay-bg{ 
		transition:all .15s ease-in !important;
		opacity: 1 !important;
	}

	.hover-container:hover img{
		-ms-transform: scale(0.95); /* IE 9 */
      	-webkit-transform: scale(0.95); /* Safari 3-8 */
      	transform: scale(0.95); 
	}
	.hover{
      transition: .5s;
    }

    .hover:hover {
      -ms-transform: scale(0.95); /* IE 9 */
      -webkit-transform: scale(0.95); /* Safari 3-8 */
      transform: scale(0.95); 
    }

	.comparison-item-title{
		text-transform: none;
		text-decoration: none;
		color: #000;
		transition: .4s;
	}

	.comparison-description{
		/* font-size: 14px; */
		font-weight: 500;
	}

	.comparison-add-to-cart{
		border: none !important;
		border-radius: 25px !important;
		padding: 10px;
		width: 80%;
	}

	.learn-more{
		text-transform: none;
		text-decoration: none;
		transition: .4s;
	}
	@media (max-width: 575.98px) { /* Mobile */
        header{
          min-height: 50px;
        }
        .breadcrumb{
          font-size: 8pt !important;
          font-weight: 500;
        }
        .prod-details{
          padding: 10px !important;
		  font-size: 12px !important;
        }
		.fumacoFont_collapse_title{
			font-size: 12px !important;
			padding: 0 !important;
		}
		.prod-font-size, .fumacoFont_card_readmore{
			font-size: 12px !important;
		}
		html, body{
			font-size: 12px !important;
		}
		.fumacoFont_item_title, .fumacoFont_item_price{
			font-size: 16px !important;
		}
		.prod-main, .related-prod{
			padding: 0 !important;
		}
		.products-head{
			padding-left: 20px !important;
		}
		.prev-btn{
        	left: 10px !important;
		}
		.next-btn{
			right: 10px !important;
		}
		.comparison-add-to-cart{
			width: 100% !important;
			font-size: 10pt !important;
		}
		.comparison-price{
			font-size: 12pt;
		}
      }

      @media (max-width: 767.98px) { /* Mobile */
        header{
          min-height: 50px;
        }
        .breadcrumb{
          font-size: 8pt !important;
          font-weight: 500;
        }
        .prod-details{
          padding: 10px !important;
		  font-size: 12px !important;
        }
		.fumacoFont_collapse_title{
			font-size: 12px !important;
			padding: 0 !important;
		}
		.prod-font-size, .fumacoFont_card_readmore{
			font-size: 12px !important;
		}
		html, body{
			font-size: 12px !important;
		}
		.fumacoFont_item_title, .fumacoFont_item_price{
			font-size: 16px !important;
		}
		.prod-main, .related-prod{
			padding: 0 !important;
		}
		.products-head{
			padding-left: 20px !important;
		}
		.prod_desc{
			font-size: 16px !important;
			font-weight: 500 !important;
			text-align: left !important;
		}
		.prod_standard{
			font-family: 'poppins', sans-serif !important;
			font-weight: 300 !important;
			text-decoration: none !important;
		}
		.prev-btn{
			left: 10px !important;
		}
		.next-btn{
			right: 10px !important;
		}
		.comparison-add-to-cart{
			width: 100% !important;
			font-size: 10pt !important;
		}
		.comparison-price{
			font-size: 12pt;
		}
      }
	  	@media (max-width: 575.98px) {
			.price-card{
				min-height: 20px;
			}
			.slick-next{
				right: 20px !important;
			}
			.slick-prev{
				left: 20px !important;
			}
		}
    	@media (max-width: 767.98px) {
			.price-card{
				min-height: 20px;
			}
			.slick-next{
				right: 20px !important;
			}
			.slick-prev{
				left: 20px !important;
			}
		}

	 	 @media (max-width: 1199.98px) {/* tablet */
			.prod_desc{
				font-size: 16px !important;
			}
			.price-card{
				min-height: 80px;
			}
		}
	</style>
	<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick-theme.css') }}">
	<style type="text/css">
	  html, body {
		margin: 0;
		padding: 0;
	  }
	
	  * {
		box-sizing: border-box;
	  }
	
	  .slick-slide {
		margin: 0px 20px;
	  }
	
	  .slick-slide img {
		width: 100%;
	  }
	
	  .slick-prev:before,
	  .slick-next:before {
		background-color: rgba(255,255,255,0);
		border-radius: 50%;
		color: rgba(0,0,0,0.4);
		transition: .4s;
	  }
	
	  .slick-slide {
		transition: all ease-in-out .3s;
		opacity: .2;
	  }
	  
	  .slick-active {
		opacity: .5;
	  }
	
	  .slick-current, .slick-slide  {
		opacity: 1;
	  }
	</style>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('/assets/loading.js') }}"></script>
<script src="{{ asset('/item/js/foundation.min.js') }}"></script>
<script src="{{ asset('/item/js/setup.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/dist/xzoom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/hammer.js/1.0.5/jquery.hammer.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/fancybox/source/jquery.fancybox.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/magnific-popup/js/magnific-popup.js') }}"></script>
<script src="{{ asset('/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

<script>
   (function() {
		$(document).on('change', '.attr-radio', function(){
			var selected_attr = {};
			var selected_cb = $(this).data('attribute');
			$('.attr-radio:checked').each(function() {
				var lbl = $(this).data('attribute');
				var r_value = $(this).val();
				selected_attr[lbl] = r_value;
			});

			$.ajax({
				type:"POST",
				url:"/getvariantcode",
				data: {selected_cb: selected_cb, attr: selected_attr, _token: '{{ csrf_token() }}', parent: '{{ $product_details->f_parent_code }}', id: '{{ $product_details->f_idcode }}'},
				success:function(response){
					window.location.href = "/product/" + response;
				}
      	});
		});
  	})();

  $(document).ready(function() {
	// Product Image Hover
	$('.hover-container').hover(function(){
	  $(this).children('.btn-container').slideToggle('fast');
	});

	$(".regular").slick({
	  dots: false,
	  infinite: true,
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  touchMove: true,
	  responsive: [
	  {
		breakpoint: 1024,
		settings: {
		  slidesToShow: 3,
		  slidesToScroll: 1,
		  infinite: true,
		  dots: false
		}
	  },
	  {
		breakpoint: 600,
		settings: {
		  slidesToShow: 2,
		  slidesToScroll: 1
		}
	  },
	  {
		breakpoint: 480,
		settings: {
		  slidesToShow: 1,
		  slidesToScroll: 1
		}
	  },
	  {
		breakpoint: 575.98,
		settings: {
		  slidesToShow: 1,
		  slidesToScroll: 1
		}
	  }
	]
	});
  });
</script>
@endsection
