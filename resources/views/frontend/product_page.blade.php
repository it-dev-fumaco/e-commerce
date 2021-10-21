@extends('frontend.layout', [
    'namePage' => 'Product',
    'activePage' => 'product_page'
])

@section('content')
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

	<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/fancybox/source/jquery.fancybox.css') }}" />
	<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/magnific-popup/css/magnific-popup.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('/item/dist/xzoom.css') }}" media="all" />
	<link type="text/css" rel="stylesheet" href="{{ asset('/assets/loading.css') }}" />

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
		<main style="background-color:#ffffff;">
			<div class="container marketing">
				<div class="single_product" style="padding-bottom: 0px !important;">
					<div class="container-fluid" style=" background-color: #fff; padding: 11px;">
						<div class="row">
							<div class="col-lg-4">
								<div class="xzoom-container" style="width: 100% !important;">
									@php
									$src = (count($product_images) > 0) ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/preview/'. $product_images[0]->imgprimayx : '/storage/no-photo-available.png';
									$xoriginal = (count($product_images) > 0)  ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/original/'. $product_images[0]->imgoriginalx : '/storage/no-photo-available.png';
									@endphp
									<img style="width: 100% !important;" class="xzoom4 imgx" id="xzoom-fancy" src="{{ asset($src) }}" xoriginal="{{ asset($xoriginal) }}" />
									<br><br>
									<div class="xzoom-thumbs">
										@foreach ($product_images as $image)
										<a href="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/original/'. $image->imgoriginalx) }}"><img class="xzoom-gallery4" width="60" src="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/preview/'. $image->imgprimayx) }}" /></a>
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
												<span class="fa fa-star checked starcolor"></span>
												<span class="fa fa-star checked starcolor"></span>
												<span class="fa fa-star checked starcolor"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span class="fa fa-star starcolorgrey"></span>
												<span style="color:#000000 !important; font-weight:200 !important;">&nbsp;&nbsp;( 0 Reviews )</span>
											</div>
										</div>
										<div id="fb-root"></div>
										<script>
											(function(d, s, id) {
												var js, fjs = d.getElementsByTagName(s)[0];
												if (d.getElementById(id)) return;
												js = d.createElement(s); js.id = id;
												js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
												fjs.parentNode.insertBefore(js, fjs);
											}(document, 'script', 'facebook-jssdk'));
										</script>
										<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0&appId=974569840046115&autoLogAppEvents=1" nonce="1VBl9fa6"></script>
										<!-- Your share button code -->
										<div class="fb-like" data-href="{{ \Request::fullUrl() }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
									</div>
									<div>
										@if ($product_details->f_discount_trigger)
										<s class="product_discount">
											<span style='color:black; '>₱ {{ number_format(str_replace(",","",$product_details->f_original_price), 2) }}<span>
										</s>
										<span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_details->f_price), 2) }}</span>
										@else
										<span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_details->f_original_price), 2) }}</span>
										@endif
										<span class="badge badge-danger" style="vertical-align: middle;background-color: red; display: {{ ($product_details->f_discount_trigger) ? 'inline' : 'none' }} !important;">{{ $product_details->f_discount_percent }}% OFF</span>
									</div>
									<div>
										<p class="card-text fumacoFont_card_caption">{!! $product_details->f_caption !!}
											<ul style="margin-top: -15px !important;">
												<li>
													<a href="#product_details" style="text-decoration: none;">
														<span style="text-decoration: none;color: #1a6ea9 !important;font-size: 13px !important; font-weight: 400 !important;">See more products details</span>
													</a>
												</li>
											</ul>
										</p>
										<input type="hidden" name="item_code" value="{{ $product_details->f_idcode }}">
										<p class="card-text">QTY&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;   <input type="number" value="1" id="quantity" name="quantity" min="1" max="{{ ($product_details->f_qty > 0) ? $product_details->f_qty : 1 }}" style="width: 70px;"></p>
										<p class="card-text">In-Stocks :
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
									<label style="margin-left: 3%;">{{ $attr }} : </label>
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
										<div class="col-xs-6">
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" name="addtocart" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}" value="1"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" name="buynow" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}"  value="1"><i class="fas fa-wallet"></i> Buy Now</button>
											@if($product_details->f_qty < 1)
											<button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important;" name="addtowishlist" value="1"><i class="fas fa-heart"></i> Add to Wish List</button>
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
		<main  style="background-color:#ffffff;" class="products-head2" style=" margin-top: 0px !important; padding-left: 40px !important; padding-right: 40px !important; margin-left: : 40px !important; margin-right: 40px !important;">
			<div class="container">
				<div class="row">
					<br>
					<div class="col-lg-12">
						<br>
						<div class="accordion" id="accordionExample">
							<div class="card">
								<div class="card-header" id="headingOne">
									<h2 class="mb-0">
										<button  class="btn btn-link collapsed fumacoFont_collapse_title" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseOne">PRODUCT DETAIL</button>
									</h2>
								</div>
								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
									<div class="card-body">
										<p class="card-text">
											<table class="table">
												<tbody style="border-style: inset !important;">
													@foreach ($attributes as $attr => $value)
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
										<button class="btn btn-link collapsed fumacoFont_collapse_title" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo">ADDITIONAL INFORMATION</button>
									</h2>
								</div>
								<div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
									<div class="card-body">
										<p class="card-text">{!! $product_details->f_full_description !!}</p>
									</div>
								</div>
							</div>

                    @if (count($related_products) > 0)
							<section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
								<div class="row py-lg-5">
									<div class="col-lg-6 col-md-8 mx-auto">
										<h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">RELATED PRODUCT(S)</h4>
									</div>
								</div>
							</section>

							<div class="album py-5">
								<div class="container">
									<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
										@foreach($related_products as $rp)
										<div class="col animated animatedFadeInUp fadeInUp equal-height-columns">
											<div class="card shadow-sm">
												<div class="equal-column-content" style="border: 1px solid  #d5dbdb  ;">
													@php
														$img = ($rp['image']) ? '/storage/item_images/'. $rp['item_code'] .'/gallery/preview/'. $rp['image'] : '/storage/no-photo-available.png';
														$img_webp = ($rp['image']) ? '/storage/item_images/'. $rp['item_code'] .'/gallery/preview/'. explode(".", $rp['image'])[0] .'.webp' : '/storage/no-photo-available.png';
													@endphp

													
<picture>
	<source srcset="{{ asset($img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
	<source srcset="{{ asset($img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;"> 
	<img src="{{ asset($img) }}" alt="{{ $rp['item_code'] }}" class="img-responsive" style="width: 100% !important;">
  </picture>

											
													<div class="card-body">
														<div class="text ellipsis">
															<p class="card-text product-head fumacoFont_card_title text-concat" style="color:#0062A5 !important;  height: 80px; ">{{ $rp['item_name'] }}</p>
														</div>
														<p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
															@if ($rp['is_discounted'])
															<s style="color: #c5c5c5;">₱ {{ $rp['orig_price'] }}</s>&nbsp;&nbsp; ₱ {{ $rp['new_price'] }}
															@else
															₱ {{ $rp['orig_price'] }}
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
														<br>
														<a href="/product/{{ $rp['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
													</div>
												</div>
											</div>
										</div>
										@endforeach
									</div>
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

@section('script')
<script type="text/javascript" src="{{ asset('/assets/loading.js') }}"></script>
<script src="{{ asset('/item/js/foundation.min.js') }}"></script>
<script src="{{ asset('/item/js/setup.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/dist/xzoom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/hammer.js/1.0.5/jquery.hammer.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/fancybox/source/jquery.fancybox.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/magnific-popup/js/magnific-popup.js') }}"></script>

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
</script>
@endsection
