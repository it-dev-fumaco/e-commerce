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
                <li class="breadcrumb-item"><a href="/" style="color: #000000 !important; text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item active"><a href="#" style="color: #000000 !important; text-decoration: underline;">{{ $product_category->name }}</a></li>
            </ol>
        </nav>
        <hr class="singleline">
    </main>
    
    <main style="background-color:#ffffff;" class="products-head">
        <div class="container marketing">
            <br>
        </div>
        <div class="container" style="max-width: 100% !important;">
            <div class="row">
                <!--sidebar-->
                <div class="col-lg-2 checkersxx">
                    <div class="d-flex justify-content-between align-items-center he1">Filters<small class="text-muted stylecap he2" style="color:#c4cad0 !important; font-weight:100 !important;">Clear All</small></div>
                    <hr>
                    <form action="products?id={{ $product_category->id }}">
                        <div class="form-group">
                            <label for="avg" class="he1">LUMEN</label>
                            <p class="he2">Lumen Range 0 - 6000</p>
                        </div>
                        <input type="range" min="1" max="100" value="50" name="P1">
                        <br>
                        <div class="form-group">
                            <label for="avg" class="he1">WATTAGE</label>
                            <p class="he2">Wattage Range 0 - 10000</p>
                        </div>
                        <input type="range" min="1" max="100" value="50" name="P2">
                        <br>
                        <div class="form-group">
                            <label for="avg" class="he1">PRICE</label>
                            <p class="he2">Price Range 0 - 200,000</p>
                        </div>
                        <input type="range" min="1" max="200000" value="50" name="P3">
                        <input type="hidden" value="{{ $product_category->id }}" name="id">
                        <br><br>
                        <div class="form-group">
                            <input type="submit" class="btn btn-outline-dark btn-sm" value="Update">
                            <br><br><br>
                        </div>
                    </form>
                </div>
                <!--sidebar-->
                
                <!--products-->
                <div class="col-lg-10">
                    <div class="row g-6">
                        @if (count($products_arr) > 0)
                            @foreach ($products_arr as $product)
                            <div class="col-md-4 btmp animated animatedFadeInUp fadeInUp equal-height-columns">
                                <div class="card">
                                    <div class="equal-column-content">
                                        <img src="{{ asset('/item/images/'.$product['item_code'].'/gallery/preview/'.$product['image']) }}" class="card-img-top">
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
                            @endforeach
                        @else
                            <center>No Product(s) Available</center>
                        @endif
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