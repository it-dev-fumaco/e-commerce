@extends('frontend.layout', [
  'namePage' => $page_meta->page_title,
  'activePage' => 'homepage'
])

@section('meta')
<meta name="description" content="{{ $page_meta->meta_description }}">
	<meta name="keywords" content="{{ $page_meta->meta_keywords }}" />
    <meta name="title" content="{{ $page_meta->page_name }}"/>

  <meta property="og:url" content="https://www.fumaco.com" />
	<meta property="og:title" content="Fumaco" />
  @if ($image_for_sharing)
	<meta property="og:image" content="{{ $image_for_sharing }}" />
	@endif
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
      </ol>

      <div class="carousel-inner">
          @forelse($onsale_carousel_data as $banner_image)
            <div class="carousel-item {{ $loop->first ? "active" : ""}}" style="background: black;">
              <picture>
                <source srcset="{{ asset('/assets/site-img/'. explode(".", $banner_image)[0] .'.webp') }}" type="image/webp" style="object-fit: cover;opacity: 1;">
                <source srcset="{{ asset('/assets/site-img/'. $banner_image) }}" type="image/jpeg" style="object-fit: cover;opacity: 1;">
                <img src="{{ asset('/assets/site-img/'. $banner_image) }}" alt="{{ Str::slug(explode(".", $banner_image)[0], '-') }}" style="object-fit: cover;opacity: 1;">
              </picture>
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
              <source srcset="{{ asset('/storage/journals/'. explode(".", $carousel->fumaco_image1)[0] .'.webp') }}" type="image/webp" style="object-fit: cover;opacity: 0.6;">
              <source srcset="{{ asset('/storage/journals/'. $carousel->fumaco_image1) }}" type="image/jpeg" style="object-fit: cover;opacity: 0.6;">
              <img src="{{ asset('/storage/journals/'. $carousel->fumaco_image1) }}" alt="{{ Str::slug(explode(".", $carousel->fumaco_image1)[0], '-') }}" style="object-fit: cover;opacity: 0.6;height: 100% !important; width: 100% !important">
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
              <div class="hover-container">
                @php
                $image = $b->{'blogprimayimage-home'} ? '/storage/journals/'.$b->{'blogprimayimage-home'} : '/storage/no-photo-available.png';
                $image_webp = $b->{'blogprimayimage-home'} ? '/storage/journals/'.explode(".", $b->{'blogprimayimage-home'})[0] .'.webp' : '/storage/no-photo-available.png';
              @endphp
            
              <picture>
                <source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
                <source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top">
                <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $b->{'blogprimayimage-home'})[0], '-') }}" class="card-img-top hover">
              </picture>
              </div>
              
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
  @if(count($best_selling_arr) > 0)
  <div class="container marketing" style=" position: relative !important">
    <section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important; text-transform: uppercase;">Most Popular</h4>
        </div>
      </div>
    </section>
      <div class="album py-5">
        <div class="container">
            <section class="regular slider">
            @foreach($best_selling_arr as $bs)
              <div class="col-md-4 col-lg-3 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 best-selling-card">
                <div class="card shadow-sm">
                  <div class="equal-column-content">
                    @php
                    $img_bs = ($bs['image']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. $bs['image'] : '/storage/no-photo-available.png';
                    $img_bs_webp = ($bs['image']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. explode(".", $bs['image'])[0] . '.webp' : '/storage/no-photo-available.png';
                    @endphp
                    <div class="hover-container product-card" style="position: relative">
                      <div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
                        @if($bs['is_new_item'])
                        <div class="col-12 mb-2">
                          <span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px !important">
                            &nbsp;<b>New</b>&nbsp;
                          </span>
                        </div><br />
                        @endif
                   
                        @if ($bs['is_discounted'])
                          <div class="col-12">
                            <span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; width: 100%">
                              &nbsp;<b>{{ $bs['discount_display'] }}</b>&nbsp;
                            </span>
                          </div>
												@endif
                      </div>

                      <div class="overlay-bg"></div>
                      <div class="btn-container">
                        <a href="/product/{{ ($bs['slug']) ? $bs['slug'] : $bs['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
                      </div>
  
                      <picture>
                        <source srcset="{{ asset($img_bs_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($img_bs) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($img_bs) }}" alt="{{ Str::slug(explode(".", $bs['image'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
                      </picture>
                    </div>
                    <div class="card-body d-flex flex-column">
                      <div class="text ellipsis">
                        <a href="/product/{{ ($bs['slug']) ? $bs['slug'] : $bs['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 100px;">{{ $bs['item_name'] }}</a>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
                        @if($bs['is_discounted'] == 1)
											  {{ $bs['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $bs['default_price'] }}</s>
												@else
                        {{ $bs['default_price'] }}
												@endif
                      </p>
                    </div>
                    <div class="mx-auto" style="width: 90%;">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group stylecap">
                          @for ($i = 0; $i < 5; $i++)
                          @if ($bs['overall_rating'] <= $i)
                          <span class="fa fa-star starcolorgrey"></span>
                          @else
                          <span class="fa fa-star" style="color: #FFD600;"></span>
                          @endif
                          @endfor
                        </div>
                        <small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $bs['total_reviews'] }} Reviews )</small>
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
            </section>
        </div>
      </div>
  </div>
  @endif
  @if(count($on_sale_arr) > 0)
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
          <section class="regular slider">
          @foreach($on_sale_arr as $os)
           @php
            $img_os = ($os['image']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. $os['image'] : '/storage/no-photo-available.png';
            $img_os_webp = ($os['image']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. explode(".", $os['image'])[0] . '.webp' : '/storage/no-photo-available.png';
          @endphp
              <div class="col-md-4 col-lg-3 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 on-sale-card">
                <div class="card shadow-sm">
                  <div class="equal-column-content">
                    
                    <div class="hover-container product-card" style="position: relative !important;">
                      <div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
                        @if ($os['is_new_item'])
                        <div class="col-12 mb-2">
                          <span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px !important">
                            &nbsp;<b>New</b>&nbsp;
                          </span>
                        </div><br />
                        @endif

                        @if ($os['is_discounted'])
                          <div class="col-12">
                            <span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; width: 100%">
                              &nbsp;<b>{{ $os['discount_display'] }}</b>&nbsp;
                            </span>
                          </div>
												@endif
                      </div>
                      
                      <div class="btn-container">
                        <a href="/product/{{ ($os['slug']) ? $os['slug'] : $os['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
                      </div>
                      <div class="overlay-bg"></div>
                      <picture>
                        <source srcset="{{ asset($img_os_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($img_os) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($img_os) }}" alt="{{ Str::slug(explode(".", $os['image'])[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
                      </picture>
                    </div>
                    <div class="card-body d-flex flex-column">
                      <div class="text ellipsis">
                        <a href="/product/{{ ($os['slug']) ? $os['slug'] : $os['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-decoration: none !important; text-transform: none !important; color:#0062A5 !important; min-height: 100px;">{{ $os['item_name'] }}</a>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; min-height: 30px">
                        @if($os['is_discounted'] == 1)
                          {{ $os['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $os['default_price'] }}</s>
                        @else
                          {{ $os['default_price'] }}
                        @endif
                      </p>
                    </div>
                    <div class="mx-auto" style="width: 90%;">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group stylecap">
                          @for ($i = 0; $i < 5; $i++)
                          @if ($os['overall_rating'] <= $i)
                          <span class="fa fa-star starcolorgrey"></span>
                          @else
                          <span class="fa fa-star" style="color: #FFD600;"></span>
                          @endif
                          @endfor
                        </div>
                        <small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $os['total_reviews'] }} Reviews )</small>
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
            </section>
      </div>
    </div>
  </div>
  @endif
  @if (Session::has('accounts'))
    @php
      $accounts = Session::get('accounts');
    @endphp
    @if ($accounts)
      <div id="multiple-accounts-msg" class="col-12 col-md-3">
        <div class="card bg-white" style="box-shadow: 2px 2px 8px;">
          <div class="card-title p-0"><span id="close-accounts-msg" class="p-0">&times;</span></div>
          <div class="card-body pt-0">
            The email you provided has an existing account for: <br>
              {{ $accounts }}
          </div>
        </div>
      </div>
    @endif
  @endif
@endsection
@section('style')

{{-- <link rel="stylesheet" type="text/css" href="{{ asset('/page_css/homepage.min.css') }}"> --}}
<link rel="preload" href="{{ asset('/page_css/homepage.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('/page_css/homepage.min.css') }}"></noscript>

{{-- <link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick.css') }}"> --}}
<link rel="preload" href="{{ asset('/slick/slick.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('/slick/slick.css') }}"></noscript>

{{-- <link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick-theme.css') }}"> --}}
<link rel="preload" href="{{ asset('/slick/slick-theme.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('/slick/slick-theme.css') }}"></noscript>

@endsection
@section('script')
  <script src="{{ asset('/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    $('#myCarousel').css('margin-top', $('#navbar').height());

    $(document).ready(function() {
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

      setTimeout(function() {
        $('#multiple-accounts-msg').fadeOut();
      }, 5000);

      $('#close-accounts-msg').click(function(){
        $('#multiple-accounts-msg').fadeOut();
      });

      // Product Image Hover
      $('.hover-container').hover(function(){
        $(this).children('.btn-container').slideToggle('fast');
      });

      
    });
  </script>
@endsection