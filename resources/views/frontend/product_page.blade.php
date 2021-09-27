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
    </style>

    <link type="text/css" rel="stylesheet" href="{{ asset('/assets/loading.css') }}" />
    <script type="text/javascript" src="{{ asset('/assets/loading.js') }}"></script>
    
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
	<main style="background-color:#ffffff;">
        <div class="container marketing">
            <div class="single_product" style="padding-bottom: 0px !important;">
                <div class="container-fluid" style=" background-color: #fff; padding: 11px;">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="xzoom-container" style="width: 100% !important;">
                                <img style="width: 100% !important;" class="xzoom4 imgx" id="xzoom-fancy" src="{{ asset('/item/images/'. $product_images[0]->idcode.'/gallery/preview/'. $product_images[0]->imgprimayx) }}" xoriginal="{{ asset('/item/images/'. $product_images[0]->idcode.'/gallery/original/'. $product_images[0]->imgoriginalx) }}" />
                                <br><br>
                                <div class="xzoom-thumbs">
                                    @foreach ($product_images as $image)
                                    <a href="{{ asset('/item/images/'. $image->idcode.'/gallery/original/'. $image->imgoriginalx) }}">
                                        <img class="xzoom-gallery4" width="60" src="{{ asset('/item/images/'. $image->idcode.'/gallery/preview/'. $image->imgprimayx) }}">
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 order-3">
                            <div class="product_description">
                                <div class="message_box" style="margin:10px 0px;">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your cart!</div>
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
                            <s class="product_discount" style="display: {{ ($product_details->f_discount_trigger) ? 'inline' : 'none' }} !important;">
                                <span style='color:black; '>₱ {{ $product_details->f_original_price }}<span>
                            </s>
                            <span class="product_price fumacoFont_item_price">₱ {{ number_format(str_replace(",","",$product_details->f_price), 2) }}</span>
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
                            <p class="card-text">QTY&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;   <input type="number" value="1" id="quantity" name="quantity" min="1" max="{{ $product_details->f_qty }}" style="width: 70px;" onchange="get_cnt()"></p>
	                        <p class="card-text">In-Stocks :
                                @if($product_details->f_qty < 1)
                                <span style='color:red;';>Not Available</span>
                                @else
                                Available
                                @endif
                                &nbsp;&nbsp; <i class="fas fa-bell"></i>
                            </p>
                        </div>
                        <hr class="singleline">
                        <div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-xs-6"><span class="product_options"></span></div>
                                <div class="col-xs-6">
                                    <div class="form-check form-check-inline"></div>
                                </div>
                            </div>
                        </div>
                        <div class="order_info d-flex flex-row">
                            <form action="#">
                        </div>
	                    <div class="row" id="product_details">
                            <div class="col-xs-6">
                                <a onclick="LoadView1()" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}" href="#" role="button"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                                <a class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important; {{ ($product_details->f_qty < 1) ? 'display: none;' : '' }}" href="#" role="button"><i class="fas fa-wallet"></i> Buy Now</a>
                                <a class="btn btn-lg btn-outline-primary fumacoFont_card_readmore" style="padding: 1rem 1.5rem !important; color: #ffffff;background-color: #0062A5;border-color: #7cc;border-radius: 0 !important;" href="#" role="button"><i class="fas fa-heart"></i> Add to Wish List</a>

	                            <div class="message_box" style="margin:10px 0px;">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your wishlist!</div>
                                </div>
                            </div>
                            <div class="row"><br></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<main  style="background-color:#ffffff;" class="products-head2" style=" margin-top: 0px !important; padding-left: 40px !important; padding-right: 40px !important; margin-left: : 40px !important; margin-right: 40px !important;">
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
                                    <tbody style="border-style: inset !important;" class="fumacoFont_collapse_caption">
                                        @foreach ($attributes as $attr)
                                        <tr>
                                            <td>{{ $attr->attribute_name }}</td>
                                            <td>{{ $attr->attribute_value }}</td>
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
                            <p class="card-text fumacoFont_collapse_caption">{!! $product_details->f_full_description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<main style="background-color:#ffffff;">
  <br><br><br><br><br>
</main>

<script src="{{ asset('/item/js/foundation.min.js') }}"></script>
<script src="{{ asset('/item/js/setup.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/dist/xzoom.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/item/dist/xzoom.css') }}" media="all" />
<script type="text/javascript" src="{{ asset('/item/hammer.js/1.0.5/jquery.hammer.min.js') }}"></script>
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/fancybox/source/jquery.fancybox.css') }}" />
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/magnific-popup/css/magnific-popup.css') }}" />
<script type="text/javascript" src="{{ asset('/item/fancybox/source/jquery.fancybox.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/magnific-popup/js/magnific-popup.js') }}"></script>

@endsection