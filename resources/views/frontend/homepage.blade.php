@extends('frontend.layout', [
  'namePage' => $page_meta->page_title,
  'activePage' => 'homepage'
])

@section('meta')
<meta name="description" content="{{ $page_meta->meta_description }}">
	<meta name="keywords" content="{{ $page_meta->meta_keywords }}" />
@endsection

@section('content')
  <main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
      <ol class="carousel-indicators">
        @php
            $carousel_count = count($carousel_data) + count($onsale_carousel_data);
        @endphp
        @for ($i = 0; $i < $carousel_count; $i++)
          <li data-bs-target="#myCarousel" data-bs-slide-to="{{$i}}" class="{{ $i == 0 ? "active" : "" }}"></li>
        @endfor
        {{-- @foreach($carousel_data as $key => $carousel)
          <li data-bs-target="#myCarousel" data-bs-slide-to="{{$key}}" class="{{ $loop->first ? "active" : "" }}"></li>
        @endforeach --}}
      </ol>

      <div class="carousel-inner">
          @forelse($onsale_carousel_data as $onsale)
            <div class="carousel-item {{ $loop->first ? "active" : ""}}" style="background: black;">
              <picture>
                <source srcset="{{ asset('/assets/site-img/'. explode(".", $onsale->banner_image)[0] .'.webp') }}" type="image/webp" style="object-fit: cover;opacity: 1;">
                <source srcset="{{ asset('/assets/site-img/'. $onsale->banner_image) }}" type="image/jpeg" style="object-fit: cover;opacity: 1;">
                <img src="{{ asset('/assets/site-img/'. $onsale->banner_image) }}" alt="{{ Str::slug(explode(".", $onsale->banner_image)[0], '-') }}" style="object-fit: cover;opacity: 1;">
              </picture>

              {{-- <div class="container">
                <div class="carousel-caption text-start">
                  <h3 class="carousel-header-font fumacoFont1">{{ $carousel->fumaco_title }}</h3>
                  <div class="text ellipsis">
                    <p class="carousel-caption-font fumacoFont2 carousel-text-concat" style="text-align: left; text-justify: left; letter-spacing: 1px;">{{ $string }}</p>
                  </div>
                  <p><a class="btn btn-lg btn-primary btn-fumaco fumacoFont_btn" href="{{ $carousel->fumaco_url }}"role="button">{{ $carousel->fumaco_btn_name }}</a></p>
                </div>
              </div> --}}
            </div>
            @empty
          @endforelse
        @foreach ($carousel_data as $carousel)
          @php
            $string = strip_tags($carousel->fumaco_caption);
            if (strlen($string) > 250) {

              // truncate string
              $stringCut = substr($string, 0, 180);
              $endPoint = strrpos($stringCut, ' ');

              //if the string doesn't contain any space then it will cut without word basis.
              $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
              $string .= '...';
            }
            $active = '';
            if(count($onsale_carousel_data) == 0){
              if($loop->first){
                $active = 'active';
              }
            }
          @endphp
          <div class="carousel-item {{ $active }}" style="background: black;">
            <picture>
              <source srcset="{{ asset('/assets/site-img/'. explode(".", $carousel->fumaco_image1)[0] .'.webp') }}" type="image/webp" style="object-fit: cover;opacity: 0.6;">
              <source srcset="{{ asset('/assets/site-img/'. $carousel->fumaco_image1) }}" type="image/jpeg" style="object-fit: cover;opacity: 0.6;">
              <img src="{{ asset('/assets/site-img/'. $carousel->fumaco_image1) }}" alt="{{ Str::slug(explode(".", $carousel->fumaco_image1)[0], '-') }}" style="object-fit: cover;opacity: 0.6;">
            </picture>

            <div class="container">
              <div class="carousel-caption text-start">
                <h3 class="carousel-header-font fumacoFont1">{{ $carousel->fumaco_title }}</h3>
                <div class="text ellipsis">
                  <p class="carousel-caption-font fumacoFont2 carousel-text-concat" style="text-align: left; text-justify: left; letter-spacing: 1px;">{{ $string }}</p>
                </div>
                <p><a class="btn btn-lg btn-primary btn-fumaco fumacoFont_btn" href="{{ $carousel->fumaco_url }}"role="button">{{ $carousel->fumaco_btn_name }}</a></p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="container marketing">
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h4 class="fw-light font-b2 fumacoFont1">LATEST ARTICLES</h4>
          </div>
        </div>
      </section>
      <div class="row">
        @foreach($blogs as $b)
          <div class="col-lg-4 pr-md-1 animated animatedFadeInUp fadeInUp equal-height-columns" style="text-align: left !important; position: relative !important">
            <div class="equal-column-content">
              <picture>
                <source srcset="{!!  asset('/storage/journals/'. explode(".", $b->{'blogprimayimage-home'})[0] .'.webp') !!}" type="image/webp" class="img-responsive card-img-top" style="width: 100% !important;">
                <source srcset="{!!  asset('/storage/journals/'. $b->{'blogprimayimage-home'}) !!}" type="image/jpeg" class="img-responsive card-img-top" style="width: 100% !important;">
                <div class="hover-container"><img src="{!!  asset('/storage/journals/'. $b->{'blogprimayimage-home'}) !!}" alt="{{ Str::slug(explode(".", $b->{'blogprimayimage-home'})[0], '-') }}" class="img-responsive card-img-top hover" style="width: 100% !important;"></div>
              </picture>
              <br><br>
              <h5 class="font-style-thin fumacoFont_card_title article-title" style="font-family: 'poppins', sans-serif !important; color:#ffffff !important; line-height: 26px !important; font-size: 18px !important;">{{ $b->blogtitle }}</h5>
              <p class="abt_standard align-bottom" style="font-size: 14px; margin-left: 0rem !important; color:#ffffff !important; font-weight: 300">
                <a href="/blog/{{ $b->slug ? $b->slug : $b->id }}" style="color:#ffffff !important; text-decoration: none !important; text-transform: none !important">{{ substr($b->blog_caption, 0, 100) }}...</a>
              </p>
            </div>
            <p class="abt_standard font-style-thin" style="margin-left: 0rem !important; color:#ffffff !important;"><a href="/blog/{{ $b->slug ? $b->slug : $b->id }}" class="fumacoFont_card_readmore"><span style="color:#ffffff !important;font-size: 14px; position: absolute; bottom: 0 !important;">Read More &#x2192;</span></a></p>
          </div>
        @endforeach
      </div>
    </div>
  </main>
  <div class="container marketing" style=" position: relative !important">
    <section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">BEST SELLING ITEMS</h4>
        </div>
      </div>
    </section>
      <div class="album py-5">
        <div class="container">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 overflow-auto flex-row flex-nowrap scroll-pane" id="best-selling-container" style="min-height: 10px;">
            @foreach($best_selling_arr as $bs)
              <div class="col-md-4 col-lg-3 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 best-selling-card">
                <div class="card shadow-sm">
                  <div class="equal-column-content">
                    @php
                    $img_bs = ($bs['bs_img']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. $bs['bs_img'] : '/storage/no-photo-available.png';
                    $img_bs_webp = ($bs['bs_img']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. explode(".", $bs['bs_img'])[0] . '.webp' : '/storage/no-photo-available.png';
                    @endphp
                    <div class="hover-container product-card" style="position: relative">
                      <div class="overlay-bg"></div>
                      <div class="btn-container">
                        <a href="/product/{{ ($bs['slug']) ? $bs['slug'] : $bs['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
                      </div>
  
                      <picture>
                        <source srcset="{{ asset($img_bs_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($img_bs) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($img_bs) }}" alt="{{ Str::slug(explode(".", $bs['bs_img'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
                      </picture>
                    </div>
                    


                    <div class="card-body">
                      <div class="text ellipsis">
                        <a href="/product/{{ ($bs['slug']) ? $bs['slug'] : $bs['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 100px;">{{ $bs['item_name'] }}</a>
                      </div>
                      <p class="card-text fumacoFont_card_price price-card d-none d-md-block d-lg-none" style="color:#000000 !important; ">
                        @if ($bs['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$bs['new_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}</s>&nbsp;&nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $bs['discount'] }}% OFF</span>
                        @else
                        ₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}
                        @endif
                      </p>
                      <p class="card-text fumacoFont_card_price d-sm-block d-md-none d-lg-block" style="color:#000000 !important; ">
                        @if ($bs['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$bs['new_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}</s>&nbsp;&nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $bs['discount'] }}% OFF</span>
                        @else
                        ₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}
                        @endif
                      </p>
                    </div>
                    <div class="mx-auto" style="width: 90%;">
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
                  @if ($bs['on_stock'] == 1)
                  <a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $bs['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
                  @else
                  <a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $bs['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      {{-- Scroll --}}
      <button type="button" class="scroll-control bs-control bs-prev prev-btn d-sm-block d-md-none d-lg-block"><i class="fas fa-chevron-left scroll-btn"></i></button>
      <button type="button" class="scroll-control bs-control bs-next next-btn d-sm-block d-md-none d-lg-block"><i class="fas fa-chevron-right scroll-btn"></i></button>

      <button type="button" class="scroll-control bs-control bs-prev tab-prev-btn prev-btn d-none d-md-block d-lg-none"><i class="fas fa-chevron-left scroll-btn"></i></button>
      <button type="button" class="scroll-control bs-control bs-next tab-next-btn next-btn d-none d-md-block d-lg-none"><i class="fas fa-chevron-right scroll-btn"></i></button>
      
  </div>
  <div class="container marketing" style="position: relative">
    <section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">ON SALE</h4>
        </div>
      </div>
    </section>

    <div class="album py-5">
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 overflow-auto flex-row flex-nowrap scroll-pane" id="on-sale-container" style="min-height: 10px;">
          @foreach($on_sale_arr as $os)
           @php
            $img_os = ($os['os_img']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. $os['os_img'] : '/storage/no-photo-available.png';
            $img_os_webp = ($os['os_img']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. explode(".", $os['os_img'])[0] . '.webp' : '/storage/no-photo-available.png';
          @endphp
              <div class="col-md-4 col-lg-3 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 on-sale-card">
                <div class="card shadow-sm">
                  <div class="equal-column-content">
                    
                    <div class="hover-container product-card" style="position: relative !important">
                      <div class="btn-container">
                        <a href="/product/{{ ($os['slug']) ? $os['slug'] : $os['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
                      </div>
                      <div class="overlay-bg"></div>
                      <picture>
                        <source srcset="{{ asset($img_os_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($img_os) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($img_os) }}" alt="{{ Str::slug(explode(".", $os['os_img'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
                      </picture>
                    </div>
                      

                    <div class="card-body">
                      <div class="text ellipsis">
                        <a href="/product/{{ ($os['slug']) ? $os['slug'] : $os['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-decoration: none !important; text-transform: none !important; color:#0062A5 !important; min-height: 100px;">{{ $os['item_name'] }}</a>
                      </div>
                      <p class="card-text fumacoFont_card_price price-card d-none d-md-block d-lg-none" style="color:#000000 !important; min-height: 30px">
                        @if ($os['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$os['new_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}</s>
                        @else
                        ₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}
                        @endif
                        &nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $os['discount_percent'] }}% OFF</span>
                      </p>
                      <p class="card-text fumacoFont_card_price d-sm-block d-md-none d-lg-block" style="color:#000000 !important; min-height: 30px">
                        @if ($os['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$os['new_price']), 2) }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}</s>
                        @else
                        ₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}
                        @endif
                        &nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $os['discount_percent'] }}% OFF</span>
                      </p>
                    </div>
                    <div class="mx-auto" style="width: 90%;">
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
                      <br/>
                    </div>
                  </div>
                  <br/>&nbsp;
                  @if ($os['on_stock'] == 1)
                  <a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $os['item_code'] }}"><i class="fas fa-shopping-cart d-inline-block" style="margin-right: 3%;"></i> Add to Cart</a>
                  @else
                  <a href="/login" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 90% !important; margin-bottom: 20px" data-item-code="{{ $os['item_code'] }}"><i class="far fa-heart d-inline-block" style="margin-right: 3%;"></i> Add to Wishlist</a>
                  @endif
                </div>
              </div>
            @endforeach
        </div>
      </div>
    </div>
    {{-- Scroll --}}
    <button id="os-prev" type="button" class="scroll-control os-control prev-btn"><i class="fas fa-chevron-left scroll-btn"></i></button>
    <button id="os-next" type="button" class="scroll-control os-control next-btn"><i class="fas fa-chevron-right scroll-btn"></i></button>
    
  </div>

@endsection
@section('style')
  <style>
    .text, .carousel-text {
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

    .carousel-text-concat {
      position: relative;
      display: inline-block;
      word-wrap: break-word;
      overflow: hidden;
      max-height: 4.8em;
      line-height: 1.4em;
      text-align: left;
    }

    .text.ellipsis::after {
      position: absolute;
      right: -12px;
      bottom: 4px;
    }
    .carousel-text.ellipsis::after {
      position: absolute;
      right: -12px;
    }
    .carousel-item > img {
      position: absolute !important;
      top: 0 !important;
      left: 0 !important;
      max-width: 100% !important;
      height: 100% !important;
    }
    .article-title{
      min-height: 55px !important
    }
    .abt_standard{
      font-family: 'poppins', sans-serif !important;
      text-decoration: none !important;
    }

    .hover{
      transition: .5s;
    }

    .hover:hover {
      -ms-transform: scale(0.95); /* IE 9 */
      -webkit-transform: scale(0.95); /* Safari 3-8 */
      transform: scale(0.95); 
    }

    .scoll-pane::-webkit-scrollbar { 
      display: none;  /* Safari and Chrome */
    }
    #best-selling-container, #on-sale-container{
      overflow: auto;
      outline: none;
      overflow-y: hidden;
      -ms-overflow-style: scroll;  /* IE 10+ */
      scrollbar-width: none; /* Firefox */
    }

    #best-selling-container::-webkit-scrollbar, #on-sale-container::-webkit-scrollbar{ /* Chrome */
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
    .scroll-btn{
      font-size: 30px;
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
    
    @media (max-width: 575.98px) {
      .article-title{
        min-height: auto !important
      }
      .prod_desc{
        font-size: 12px !important;
      }
      .prev-btn{
        left: 20px !important;
      }
      .next-btn{
        right: 20px !important;
      }
    }
    @media (max-width: 767.98px) {
      .article-title{
        min-height: auto !important
      }
      .prod_desc{
        font-size: 12px !important;
      }
      .prev-btn{
        left: 20px !important;
      }
      .next-btn{
        right: 20px !important;
      }
    }
    @media (max-width: 1199.98px) {
      .prod_desc{
        font-size: 16px !important;
      }
      
    }
  </style>
  <style>

  </style>
@endsection

@section('script')
  <script>
    // Product Image Hover
    $('.hover-container').hover(function(){
      $(this).children('.btn-container').slideToggle('fast');
    });

    // Best Selling
    $('.bs-next').click(function() {
      event.preventDefault();
      $('#best-selling-container').animate({
        scrollLeft: "+="+$('.best-selling-card').outerWidth()+"px"
      }, "slow");
    });

    $('.bs-prev').click(function() {
      event.preventDefault();
      $('#best-selling-container').animate({
        scrollLeft: "-="+$('.best-selling-card').outerWidth()+"px"
      }, "slow");
    });

    // On Sale
    $('#os-next').click(function() {
      event.preventDefault();
      $('#on-sale-container').animate({
        scrollLeft: "+="+$('.on-sale-card').outerWidth()+"px"
      }, "slow");
    });

    $('#os-prev').click(function() {
      event.preventDefault();
      $('#on-sale-container').animate({
        scrollLeft: "-="+$('.on-sale-card').outerWidth()+"px"
      }, "slow");
    });

    $(document).ready(function() {
      if ($("#best-selling-container").prop('scrollWidth') > $("#best-selling-container").width() ) {
        $('.bs-control').addClass('d-block');
      }else{
        $('.bs-control').addClass('d-none');
      }

      if ($("#on-sale-container").prop('scrollWidth') > $("#on-sale-container").width() ) {
        $('.os-control').addClass('d-block');
      }else{
        $('.os-control').addClass('d-none');
      }
    });
  </script>
@endsection