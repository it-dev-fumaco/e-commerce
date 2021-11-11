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

        @foreach($carousel_data as $key => $carousel)
          <li data-bs-target="#myCarousel" data-bs-slide-to="{{$key}}" class="{{ $loop->first ? "active" : "" }}"></li>
        @endforeach
      </ol>

      <div class="carousel-inner">
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
          @endphp
          <div class="carousel-item {{ $loop->first ? "active" : ""}}" style="background: black;">
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
                <source srcset="{!!  asset('/assets/site-img/'. explode(".", $b->{'blogprimayimage-home'})[0] .'.webp') !!}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                <source srcset="{!!  asset('/assets/site-img/'. $b->{'blogprimayimage-home'}) !!}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                <div class="hover-container"><img src="{!!  asset('/assets/site-img/'. $b->{'blogprimayimage-home'}) !!}" alt="{{ Str::slug(explode(".", $b->{'blogprimayimage-home'})[0], '-') }}" class="img-responsive hover" style="width: 100% !important;"></div>
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
  <div class="container marketing">
    <section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">BEST SELLING ITEMS</h4>
        </div>
      </div>
    </section>
      <div class="album py-5">
        <div class="container">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($best_selling_arr as $bs)
              <div class="col animated animatedFadeInUp fadeInUp equal-height-columns">
                <div class="card shadow-sm">
                  <div class="equal-column-content">

@php
$img_bs = ($bs['bs_img']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. $bs['bs_img'] : '/storage/no-photo-available.png';
$img_bs_webp = ($bs['bs_img']) ? '/storage/item_images/'. $bs['item_code'] .'/gallery/preview/'. explode(".", $bs['bs_img'])[0] . '.webp' : '/storage/no-photo-available.png';
@endphp

                    <picture>
                      <source srcset="{{ asset($img_bs_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                      <source srcset="{{ asset($img_bs) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                      <img src="{{ asset($img_bs) }}" alt="{{ Str::slug(explode(".", $bs['bs_img'])[0], '-') }}" class="img-responsive" style="width: 100% !important;">
                    </picture>


                    <div class="card-body">
                      <div class="text ellipsis">
                        <p class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="color:#0062A5 !important;  min-height: 80px;">{{ $bs['item_name'] }}</p>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
                        @if ($bs['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$bs['new_price']), 2) }}&nbsp;&nbsp;<s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}</s>&nbsp;&nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $bs['discount'] }}% OFF</span>
                        @else
                        ₱ {{ number_format(str_replace(",","",$bs['orig_price']), 2) }}
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
                      {{-- <a href="/product/{{ $bs['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a> --}}
                    </div>
                  </div>
                  <a href="/product/{{ ($bs['slug']) ? $bs['slug'] : $bs['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto" role="button" style="width: 90% !important; margin-bottom: 20px">View</a>

                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
  </div>
  <div class="container marketing">
    <section class="py-5 text-center container" style="padding-bottom: 0rem !important;">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">ON SALE</h4>
        </div>
      </div>
    </section>

    <div class="album py-5">
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
          @foreach($on_sale_arr as $os)
           @php
            $img_os = ($os['os_img']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. $os['os_img'] : '/storage/no-photo-available.png';
            $img_os_webp = ($os['os_img']) ? '/storage/item_images/'. $os['item_code'] .'/gallery/preview/'. explode(".", $os['os_img'])[0] . '.webp' : '/storage/no-photo-available.png';
          @endphp
              <div class="col animated animatedFadeInUp fadeInUp equal-height-columns">
                <div class="card shadow-sm">
                  <div class="equal-column-content">


                      <picture>
                        <source srcset="{{ asset($img_os_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($img_os) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($img_os) }}" alt="{{ Str::slug(explode(".", $os['os_img'])[0], '-') }}" class="img-responsive" style="width: 100% !important;">
                      </picture>

                    <div class="card-body">
                      <div class="text ellipsis">
                        <p class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="color:#0062A5 !important; min-height: 80px;">{{ $os['item_name'] }}</p>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
                        @if ($os['is_discounted'])
                        ₱ {{ number_format(str_replace(",","",$os['new_price']), 2) }}&nbsp;&nbsp;<s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}</s>
                        @else
                        ₱ {{ number_format(str_replace(",","",$os['orig_price']), 2) }}
                        @endif
                        &nbsp;&nbsp;<span class="badge badge-danger" style="vertical-align: middle;background-color: red;">{{ $os['discount_percent'] }}% OFF</span>
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
                      {{-- <a href="/product/{{ $os['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a> --}}
                    </div>
                  </div>
                  <a href="/product/{{ ($os['slug']) ? $os['slug'] : $os['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto" role="button" style="width: 90% !important; margin-bottom: 20px">View</a>

                </div>
              </div>
            @endforeach
        </div>
      </div>
    </div>
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
      max-height: 5.5em;
      line-height: 1.4em;
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

    @media (max-width: 575.98px) {
      .article-title{
        min-height: auto !important
      }
      .prod_desc{
        font-size: 12px !important;
      }

    }
    @media (max-width: 767.98px) {
      .article-title{
        min-height: auto !important
      }
      .prod_desc{
        font-size: 12px !important;
      }
    }
    @media (max-width: 1199.98px) {/* tablet */
      .prod_desc{
        font-size: 16px !important;
      }
    }
  </style>
@endsection