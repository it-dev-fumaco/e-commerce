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
              <img src="{{ asset('/assets/site-img/'. $banner_image) }}" alt="{{ Str::slug(explode(".", $banner_image)[0], '-') }}" style="object-fit: cover;opacity: 1;" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
            </picture>
          </div>
          @empty
        @endforelse
        @foreach ($carousel_data as $carousel)
          @php
            $active = '';
            $lazy = 'lazy';
            if(count($onsale_carousel_data) == 0){
              if($loop->first){
                $active = 'active';
                $lazy = 'eager';
              }
            }

            $carousel_image = $carousel->fumaco_image1;
            $width = '1920px';
            $height = '720px';
            if((new \Jenssegers\Agent\Agent())->isTablet()){
              $carousel_image = $carousel->fumaco_image3 ? $carousel->fumaco_image3 : $carousel->fumaco_image1;
              $width = '1024px';
            }else if((new \Jenssegers\Agent\Agent())->isMobile()){
              $carousel_image = $carousel->fumaco_image2 ? $carousel->fumaco_image2 : $carousel->fumaco_image1;
              $width = '420px';
              $height = '640px';
            }
          @endphp
          <div class="carousel-item {{ $active }}" style="background: black;">
            <picture style="width: {{ $width }} !important; height: {{ $height }} !important;">
              <source srcset="{{ asset('/storage/journals/'. explode(".", $carousel_image)[0] .'.webp') }}" type="image/webp">
              <source srcset="{{ asset('/storage/journals/'. $carousel_image) }}" type="image/jpeg">
              <img src="{{ asset('/storage/journals/'. $carousel_image) }}" alt="{{ Str::slug(strip_tags($carousel->fumaco_title), '-') }}" style="object-fit: cover;opacity: 0.6;height: 100% !important; width: 100% !important" loading="{{ $lazy }}">
            </picture>

            <div class="container">
              <div class="carousel-caption text-start carousel-item-container">
                <h3 class="carousel-header-font fumacoFont1">{!! strip_tags($carousel->fumaco_title) !!}</h3>
                @if ($carousel->fumaco_caption)
                  <div class="text ellipsis">
                    <p class="carousel-caption-font fumacoFont2 carousel-text-concat" style="text-align: left; text-justify: left; letter-spacing: 1px;">{!! strip_tags($carousel->fumaco_caption) !!}</p>
                  </div>
                @endif
                <div style="text-align: {{ $carousel->btn_position }};">
                  <p><a class="btn btn-lg btn-primary btn-fumaco fum  acoFont_btn" href="{{ $carousel->fumaco_url }}"role="button">{{ $carousel->fumaco_btn_name }}</a></p>
                </div>
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
                <img src="{{ asset($image) }}" alt="{{ Str::slug($b->{'blogtitle'}, '-') }}" class="card-img-top hover" style="width: 100% !important; height: 100% !important" loading="lazy">
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
          <h4 class="fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important; text-transform: uppercase;">Best Selling</h4>
        </div>
      </div>
    </section>
      <div class="album py-5">
        <div class="container">
            <section class="regular slider">
              @foreach($best_selling_arr as $item)
                @include('frontend.product_details_card')
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
            @foreach($on_sale_arr as $item)
              @include('frontend.product_details_card')
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
    $(document).ready(function() {
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