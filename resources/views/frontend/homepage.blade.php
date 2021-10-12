@extends('frontend.layout', [
  'namePage' => 'Home',
  'activePage' => 'homepage'
])

@section('content')
  <style>
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
            <img src="{{ asset('/assets/site-img/'. $carousel->fumaco_image1) }}" alt="{{ $carousel->fumaco_title }}" style="object-fit: cover;opacity: 0.6;">
            <div class="container">
              <div class="carousel-caption text-start">
                <h3 class="carousel-header-font fumacoFont1">{{ $carousel->fumaco_title }}</h3>
                <p class="carousel-caption-font fumacoFont2"style="text-align: left; text-justify: inter-word; letter-spacing: 1px;">{{ $string }}</p>
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
          <div class="col-lg-4 pr-md-1 animated animatedFadeInUp fadeInUp equal-height-columns" style="text-align: left !important;">
            <div class="equal-column-content">
              <img src="{!!  asset('/assets/site-img/'. $b->{'blogprimayimage-home'})  !!}" alt="{{ $b->blog_caption }}" class="img-responsive" style="width: 100% !important;">
              <br><br>
              <h5 class="font-style-thin fumacoFont_card_title" style="color:#ffffff !important; line-height: 26px !important;">{{ substr($b->blogtitle, 0, 39) }}</h5><br>
              <p class="font-style-thin-caption fumacoFont_card_caption" style="margin-left: 0rem !important; color:#ffffff !important;">
                <a href="blog?id={{ $b->id }}" style="color:#ffffff !important; text-decoration: none !important; text-transform: none !important">{{ substr($b->blog_caption, 0, 100) }}...</a>
              </p>
              <p class="font-style-thin" style="margin-left: 0rem !important; color:#ffffff !important;"><a href="blog?id={{ $b->id }}" class="fumacoFont_card_readmore"><span style="color:#ffffff !important;">Read More &#x2192;</span></a></p>
            </div>
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
                      $img = ($bs['bs_img']) ? '/storage/item/images/'. $bs['item_code'] .'/gallery/preview/'. $bs['bs_img'] : '/storage/no-photo-available.png';
                    @endphp
                    <img src="{{ asset($img) }}" alt="" class="img-responsive" style="width: 100% !important;">
                    <div class="card-body">
                      <div class="text ellipsis">
                        <p class="card-text product-head fumacoFont_card_title text-concat" style="color:#0062A5 !important;  height: 80px; ">{{ $bs['item_name'] }}</p>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
                        @if ($bs['is_discounted'])
                        <s style="color: #c5c5c5;">₱ {{ $bs['orig_price'] }}</s>&nbsp;&nbsp; ₱ {{ $bs['new_price'] }}
                        @else
                        ₱ {{ $bs['new_price'] }}
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
                      <a href="/product/{{ $bs['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
                    </div>
                  </div>
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
              <div class="col animated animatedFadeInUp fadeInUp equal-height-columns">
                <div class="card shadow-sm">
                  <div class="equal-column-content">
                    @php
                    $img_os = ($os['os_img']) ? '/storage/item/images/'. $os['item_code'] .'/gallery/preview/'. $os['os_img'] : '/storage/no-photo-available.png';
                  @endphp
                    <img src="{{ asset($img_os) }}" alt="" class="img-responsive" style="width: 100% !important;">
                    <div class="card-body">
                      <div class="text ellipsis">
                        <p class="card-text product-head fumacoFont_card_title text-concat" style="color:#0062A5 !important; height: 80px;">{{ $os['item_name'] }}</p>
                      </div>
                      <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
                        @if ($os['is_discounted'])
                        <s style="color: #c5c5c5;">₱ {{ $os['orig_price'] }}</s>&nbsp;&nbsp; ₱ {{ $os['new_price'] }}
                        @else
                        ₱ {{ $os['new_price'] }}
                        @endif</p>
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
                      <a href="/product/{{ $os['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
        </div>
      </div>
    </div>
  </div>
  
@endsection