<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
  <head>
    <title>{{ $namePage }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @hasSection('meta')
      @yield('meta')
    @else
      <meta name="description" content="Fumaco Inc. is the Philippine’s premiere lighting solutions powerhouse. It has manufacturing, import, distribution and sales capabilities of high quality lighting fixtures. The company employs industry experts and engineers to provide clients with utmost support for various lighting services.">
      <meta name="keywords" content="FUMACO, Lighting, Philippines, Philippine, Leading, Luminaire, Manufacturing, ISO, Quality, light"  />
    @endif
    <meta name="author" content="Fumaco Website">
    @if (Str::startsWith($current = url()->current(), 'https://www'))
      <link rel="canonical" href="{{ $current }}">
    @else
      <link rel="canonical" href="{{ str_replace('https://', 'https://www.', $current) }}">
    @endif
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/assets/icon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('/assets/icon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('/assets/icon/favicon-32x32.png') }}" sizes="32x32">
    
    @if (!in_array($activePage, ['homepage']))
    <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endif
    <link href="{{ asset('/assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"></noscript>

    <link rel="preload" href="{{ asset('/assets/fumaco.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('/assets/fumaco.css') }}"></noscript>

    <link rel="preload" href="{{ asset('/page_css/layout.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('/page_css/layout.min.css') }}"></noscript>
    @yield('style')

    @if (!in_array($activePage, ['homepage', 'product_page']))
      <link rel="stylesheet" href="{{ asset('assets/minified-css/jquery-ui.min.css') }}">
    @endif

    <style>
      .slick-dots li.slick-active,.slick-active,.slick-active i{opacity:1;color:#0062A5!important}
      .slick-dots li{position:relative;display:inline-block;width:7px!important;height:7px!important;margin:0 5px;padding:0;cursor:pointer}
      .slick-dots li button{width:7px!important;height:7px!important;padding:5px;color:transparent;border:0;background:0 0}
      .flip{transform:rotate(-180deg)!important;-moz-transform:rotate(180deg)!important;-webkit-transform:rotate(180deg)!important;-o-transform:rotate(180deg)!important;-ms-transform:rotate(180deg)!important}
      button:focus,a,button,input:focus{outline:0!important;-webkit-box-shadow:none!important;box-shadow:none!important}
      .no-border{border:none!important}
      .products-card-img{min-height:300px!important}
      .slick-dots {bottom:-40px;}
      .out-of-stock-container{width:100%;position:absolute;top:50%;left:0;z-index:9;text-align:center;font-weight:700;color:#000;}
      .brand-slide-container{min-height:170px!important;}
      .brand-slide .slick-slide {margin: 0 60px !important;}
      .brand-slide .slick-list {margin: 0 -60px !important;}
      .brand-slide .slick-next{right:-95px!important}
      .brand-slide .slick-prev{left:-95px!important}
      #notify-me-alert{font-size: 12pt;}
      @media (max-width: 1199.98px) {
        .products-card-img{min-height:200px!important}
        .brand-slide .slick-next, .slick-next{right:-45px!important}
        .brand-slide .slick-prev, .slick-prev{left:-45px!important}
        .brand-slide .slick-slide {margin: 0 10px !important;}
        .brand-slide .slick-list {margin: 0 -10px !important;}
        #notify-me-alert{font-size: 9pt;}
      }
      @media (max-width: 575.98px){ /* normal mobile */
        .products-card-img{min-height:175px!important}
        .brand-slide-container{min-height:90px!important;}
        .brand-slide .slick-next, .slick-next{right:-45px!important}
        .brand-slide .slick-prev, .slick-prev{left:-45px!important}
        .brand-slide .slick-slide {margin: 0 10px !important;}
        .brand-slide .slick-list {margin: 0 -10px !important;}
        #product-category-dropdown{max-height: 45vh !important;}
        .category-menu{font-size: 9pt !important;}
        #notify-me-alert{font-size: 9pt;}
      }
    	@media (max-width: 369.98px){ /* extra small screens (J2, etc.) */
        .mob-srch{padding-top: 10px;}
        #notify-me-alert{font-size: 9pt;}
      }
    </style>
    @if (in_array($activePage, ['product_page']))
    <style>
      .card {
        position: relative !important;
        display: flex !important;
        flex-direction: column !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
        background-color: #fff !important;
        background-clip: border-box !important;
        border-radius: .25rem !important;
        padding: 10px !important;
      }
    </style>
    @endif

    @if (env('APP_ENV') != 'local')
    @if ($activePage != 'error_page')
    <!-- Google Tag Manager -->
    <script async>
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-NZJWSRR');
    </script>
    <!-- End Google Tag Manager -->
    @endif
    @endif

            <!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>
<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

    <script async>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "276044345867555");
      chatbox.setAttribute("attribution", "biz_inbox");

      window.fbAsyncInit = function() {
        FB.init({
          xfbml : true,
          version : 'v12.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
  
    @if(in_array($activePage, ['contact', 'signup', 'blog']))
      {!! ReCaptcha::htmlScriptTagJsApi([
        'callback_then' => 'captchaSuccess',
        'callback_catch' => 'captchaFail'
      ]) !!}
    @endif
  </head>
  <body>
    @if ($activePage != 'product_page')
      <div class="spinner-wrapper">
        <div class="spinner"></div>
      </div>
    @endif
    @if (session()->has('success'))
      <div role="alert" id="element" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="position: absolute; left: 40px; top: 40px; z-index: 9999 !important">
        <div class="toast-header">
          <strong class="mr-auto">Fumaco Inc.</strong>
        </div>
        <div class="toast-body">
          {{ session()->get('success') }}
        </div>
      </div>
    @endif
    @if (session()->has('error'))
      <div role="alert" id="element" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="position: absolute; left: 40px; top: 40px; z-index: 9999 !important">
        <div class="toast-header">
          <strong class="mr-auto">Fumaco Inc.</strong>
        </div>
        <div class="toast-body">
          {{ session()->get('error') }}
        </div>
      </div>
    @endif
    <header style="min-height: 1px;">
      <nav id="navbar" class="navbar navbar-expand-lg navbar-light fixed-top bg-light" style="padding-left: 20px; padding-right: 20px; padding-bottom:0px; border-bottom: 1px solid #e4e4e4;">
        <div class="container-fluid">
          <a class="navbar-brand d-none d-md-block" href="/" id="navbar-brand">
            <picture>
              <source srcset="{{ asset('/assets/site-img/logo-sm.webp') }}" type="image/webp">
              <source srcset="{{ asset('/assets/site-img/logo-sm.png') }}" type="image/jpeg">
              <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="Fumaco" width="155" height="54">
            </picture>
          </a>
          {{-- Mobile Icons --}}
          <div class="row justify-content-between">
            <div class="col d-md-none">
              <a class="navbar-brand" href="/" id="navbar-brand">
                <picture>
                  <source srcset="{{ asset('/assets/site-img/logo-sm.webp') }}" type="image/webp">
                  <source srcset="{{ asset('/assets/site-img/logo-sm.png') }}" type="image/jpeg">
                  <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="Fumaco" style="width: 100%; height: 100%" >
                </picture>
              </a>
            </div>
            <div class="col">
              <div class="d-flex justify-content-end">
                @if (!Auth::check())
                  <a class="nav-link d-md-block d-lg-none" href="/login"><i class="far fa-user" style="font-size:24px; color: #404040;"></i></a>
                @else
                  <div class="d-md-none" style="width: 55px; height: 1px">&nbsp;</div>
                @endif
                <a class="d-md-block d-lg-none mb-cart" href="/cart" style="text-decoration: none !important; !important; padding-top: 8px">
                  <div class="" style="width: 50px !important; padding: 0 !important;">
                    <i class="fa" style="font-size:24px; color:#126cb6;">&#xf07a;</i><span class="badge badge-warning count-cart-items" id="lblCartCount" style="font-size: 12px; background: #ff0000; color: #fff; padding: 4px 7px; vertical-align: top; margin-left: -10px;display: unset !important; font-weight: 500 !important; border-radius: 1rem !important; margin-top: -15px;">0</span>
                  </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" style="float: right !important">
                  <span class="navbar-toggler-icon"></span>
                </button>
              </div>
            </div>
          </div>
          {{-- Mobile Icons --}}
          <div class="collapse navbar-collapse nav_fumaco_res nav-a" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0 navbar-header">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">PRODUCTS</a>
                <ul class="dropdown-menu dropdown-menu-light navbar-header overflow-auto" style="font-weight: 300 !important;" aria-labelledby="navbarDarkDropdownMenuLink" id="product-category-dropdown">
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/about" style="white-space: nowrap !important">ABOUT US</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/journals">BLOGS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/contact">CONTACT</a>
              </li>
              @if (cache('has_clearance_sale'))
              <li class="nav-item text-left text-md-center">
                <a class="nav-link" href="/clearance_sale">CLEARANCE</a>
              </li>
              @endif
            </ul>
            <form class="d-none d-lg-block search-bar" id="desk-search-bar-form" action="/" method="GET" autocomplete="off">
              <div class="input-group mb-0 searchbar search-bar">
                <input type="text" placeholder="Search" name="s" value="{{ request()->s }}" id="desk-search-form-input" class="form-control searchstyle autocomplete-search" aria-label="Text input with dropdown button">
                <input type="text" name="name" style="display: none;">
                <button class="btn btn-outline-secondary searchstyle" type="submit"><i class="fas fa-search"></i></button>
                <div id="desk-search-container" class="container mx-auto"></div>
              </div>
            </form>
            <ul class="navbar-nav d-lg-inline-block">
              @if (!Auth::check())
              <li class="nav-item d-none d-lg-block">
                <a class="nav-link" href="/login" style="margin-left: 10px; margin-right: 5px;"><i class="far fa-user" style="font-size:24px; color: #404040;"></i></a>
              </li>
              @else
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle navbar-header welcome-msg" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Welcome, {{ (Auth::check()) ? Auth::user()->f_name : 'Guest' }}</a>
                <a class="nav-link dropdown-toggle navbar-header mbl-welcome" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="far fa-user" style="font-size: 20px"></i></a>
                <ul class="dropdown-menu dropdown-menu-light navbar-header" aria-labelledby="navbarDarkDropdownMenuLink" style="right: 14px !important; left: auto !important;">
                  @if(Auth::check())
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/mywishlist">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav12.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav12.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav12.jpg') }}" alt="myorders" width="30">
                      </picture>Wishlist <span class="badge badge-primary count-wish-items" style="background-color:#186eaa; vertical-align: top;">0</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/myorders">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav13.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav13.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav13.jpg') }}" alt="myorders" width="30">
                      </picture>My Orders
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/myprofile/account_details">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav14.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav14.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav14.jpg') }}" alt="myprofile" width="30">
                      </picture>My Profile
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/logout">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav15.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav15.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav15.jpg') }}" alt="logout" width="30">
                      </picture>Log Out
                    </a>
                  </li>
                  @else
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/track_order">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav13.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav13.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav13.jpg') }}" alt="trackorder" width="30">
                      </picture>Track My Order
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/login">
                      <picture>
                        <source srcset="{{ asset('/assets/site-img/icon/nav15.webp') }}" type="image/webp">
                        <source srcset="{{ asset('/assets/site-img/icon/nav15.jpg') }}" type="image/jpeg">
                        <img src="{{ asset('/assets/site-img/icon/nav15.jpg') }}" alt="login" width="30">
                      </picture>Login | Sign Up
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
            </ul>
          </div>
          {{-- Cart Icon --}}
            <a class="d-none d-lg-block pc-cart cart-icon" style="text-decoration: none !important" href="/cart" id="cart">
              <div  style="width: 50px !important; padding: 0 !important; margin-right: -20px !important">
                <i class="fa" style="font-size:24px; color:#126cb6;">&#xf07a;</i><span class="badge badge-warning count-cart-items" id="lblCartCount" style="font-size: 12px; background: #ff0000; color: #fff; padding: 4px 7px; vertical-align: top; margin-left: -10px;display: unset !important; font-weight: 500 !important; border-radius: 1rem !important; margin-top: -15px;">0</span>
              </div>
            </a>
            <div id="shopping-cart"></div>
          {{-- Cart Icon --}}
          <div class="d-md-block d-lg-none mob-srch" style="width: 100% !important">
            <div class="col-md-12">
              <form action="/" id="mob-search-bar-form" method="GET">
                <div class="input-group mb-0 searchbar" id="mob-search-bar-form" style="width: 100% !important;">
                <input type="text" name="name" style="display: none;">
                <input type="text" id='mob-autocomplete-search' placeholder="Search" name="s" value="{{ request()->s }}" class="form-control searchstyle" aria-label="Text input with dropdown button">
                    <button class="btn btn-outline-secondary searchstyle" type="submit"><i class="fas fa-search"></i></button>
                </div>
              <div id="mob-search-container" class="container"></div>
              <div class="col-12">&nbsp;</div>
            </form>
            </div>
          </div>

        </div><br/>

      </nav>
      </header>
  @yield('content')
  <footer>
    @include('cookieConsent::index')
    <main style="background-color:#0C0C0C;">
      <div class="col-12 col-xl-10 mx-auto marketing">
        <div class="row p-4">
          <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <picture>
              <source srcset="{{ asset('/assets/site-img/logo-sm.webp') }}" type="image/webp">
              <source srcset="{{ asset('/assets/site-img/logo-sm.png') }}" type="image/jpeg">
              <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="Fumaco" class="d-md-none" width="90">
              <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="Fumaco" class="d-none d-md-inline" width="140">
            </picture>
            <br>
            <div id="contact-container">
              <span class="d-block mb-2" style="color: #9B999B; font-size: 10pt;"><i class="fa fa-envelope" style="color: #0062A5"></i> &nbsp;<span id="contact-email"></span></span>
              <span class="d-block mb-2" style="color: #9B999B; font-size: 10pt;"><i class="fa fa-phone-alt" style="color: #0062A5"></i> &nbsp;<span id="contact-phone"></span></span>
            </div>
          </div>

          <div class="col-12 col-md-4 col-lg-2 mt-4 mt-md-2" style="text-align: left !important;">
            <h6 class="footer1st font-weight-bold" style="color:#ffffff !important; font-weight: 500 !important;">ABOUT FUMACO</h6>
            <table class="table" style="border-style: unset !important;">
              <tbody style="font-size: 12px; color: #ffffff; border-style: unset !important;">
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/about" style="text-decoration: none; color: #9B999B;">Company Info</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/journals" style="text-decoration: none; color: #9B999B;">News</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
                <tr id="policy-pages-footer"></tr>{{-- Policy Pages --}}
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/track_order" style="text-decoration: none; color: #9B999B;">Track My Order</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="col-12 col-md-4 col-lg-3 mt-4 mt-md-2" style="text-align: left !important;">
            <h6 class="footer1st font-weight-bold" style="color:#ffffff !important; font-weight: 500 !important;">PRODUCTS</h6>
            <table class="table" style="border-style: unset !important;">
              <tbody style="font-size: 12px; color: #ffffff; border-style: unset !important;" id="product-category-footer"></tbody>
            </table>
          </div>

          <div class="col-12 col-lg-4 col-xl-5 mt-lg-2">
            <h6 class="footer1st d-md-none" style="color:#ffffff !important; text-align: left; font-weight: 500 !important">SUBSCRIBE TO NEWSLETTER</h6>
            <h6 class="footer1st d-none d-md-block" style="color:#ffffff !important; text-align: right; font-weight: 500 !important">SUBSCRIBE TO NEWSLETTER</h6>
            <form action="/subscribe" method="POST">
              @csrf
              <div class="input-group mb-3">
                <input type="hidden" name="g-recaptcha-response" id="recaptcha_v3-subscribe">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1" style="height: 100% !important; border-radius: 0 !important"><i class="fas fa-envelope-open" style="color: #0062A5; font-size: 20px;"></i></span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1" required>
                <div class="input-group-append">
                  <button type="submit" class="input-group-text" id="basic-addon2" style="height: 100% !important; border-radius: 0 !important">Subscribe</button>
                </div>
              </div>
            </form>

            <div class="col-lg-12 mt-4" style="text-align: left !important;">
              <h6 class="footer1st" style="color:#ffffff !important; font-weight: 500 !important;">WE ACCEPT</h6>
              <div class="row" style="padding-left:1% !important">
                @php
                  $payment_method = array('mastercard2', 'visa', 'gcash2', 'grabpay2');
                @endphp
                @foreach($payment_method as $img)
                  @php
                    $image = '/storage/payment_method/'.$img.'.png';
                    $image_webp = '/storage/payment_method/'.$img.'.webp';
                  @endphp
                  <div class="d-inline m-2 payment-icons" style="position: relative !important">
                    <picture>
                      <source srcset="{{ asset($image_webp) }}" type="image/webp" style="object-fit: cover;">
                      <source srcset="{{ asset($image) }}" type="image/jpeg" style="object-fit: cover;">
                      <img src="{{ asset($image) }}" style="object-fit: cover; max-height: 100%;max-width: 90%;width: auto;height: auto;position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;" loading="lazy">
                    </picture>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
    </main>
    <div class="container-fluid text-center p-2" style="background-color: #252525;">
      <span style="font-size: 10pt; color: #9B999B;">
        Fumaco Lights &copy; 2021
      </span>
    </div>
  </footer>
  <div class="alert alert-success alert-dismissible mx-auto col-11 col-md-5 text-center fade" id="notify-me-alert" role="alert" style="position: absolute; top: 20px; left: 50%; display: inline-block; -webkit-transform: translateX(-50%); transform: translateX(-50%);">
    We will notify you via email once stock is available
  </div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <script src="https://kit.fontawesome.com/ec0415ab92.js"></script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  @if($activePage == 'contact')
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @else
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.api_site_key') }}"></script>
  <script> 
    grecaptcha.ready(function() {
      grecaptcha.execute("{{ config('recaptcha.api_site_key') }}", {action: 'homepage'}).then(function(token) {
        if(token) {
          $("#recaptcha_v3-subscribe").val(token); 
        } 
      });
    });
  </script> 
  @endif
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js' defer></script>
  <script src="{{ asset('/assets/dist/js/bootstrap.bundle.min.js') }}"></script>
  @if (!in_array($activePage, ['homepage', 'product_page']))
  <script src="{{ asset('assets/minified-js/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('assets/minified-js/jquery-ui.min.js') }}"></script>
  @endif
  @if (in_array($activePage, ['product_page']))
  <script type="text/javascript" src="{{ asset('/assets/loading.js') }}"></script>
  <script src="{{ asset('/item/js/foundation.min.js') }}"></script>
  <script src="{{ asset('/item/js/setup.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/item/dist/xzoom.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/item/hammer.js/1.0.5/jquery.hammer.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/item/fancybox/source/jquery.fancybox.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/item/magnific-popup/js/magnific-popup.js') }}"></script>
  <script src="{{ asset('/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>
  @endif
  <script>
    $(document).ready(function() {
      $('#element').toast('show')
      websiteSettings();
      productCategories();
      countCartItems();
      countWishItems();
      policyPages();
      contactInfo();
      //Preloader
      preloaderFadeOutTime = 800;
      function hidePreloader() {
          var preloader = $('.spinner-wrapper');
          preloader.fadeOut(preloaderFadeOutTime);
      }

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
        var filter_form = '#filter-form2';
      }else{ // desktop
        var filter_form = '#filter-form';
      }

      
      hidePreloader();

      setTimeout(function () {
          $("#cookieConsent").fadeIn(200);
      }, 2000);

      @if (in_array($activePage, ['homepage', 'cart']))
      $(".regular").slick({
        dots: true,
        customPaging: function(slider, i) {
          return '<a href="#"><i class="fas fa-circle" style="font-size: 8pt !important; color: rgba(0,0,0,0);-webkit-text-stroke:.5px #0062A5!important;"></i></a>';
        },
        arrows: true,
        infinite: true,
        dots: false,
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
              touchMove: true,
              dots: false,
              arrows: true,
              customPaging: function(slider, i) {
                return '<a href="#"><i class="fas fa-circle" style="font-size: 1pt !important; color: rgba(0,0,0,0);-webkit-text-stroke:.5px #0062A5!important;"></i></a>';
              },
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
              dots: false,
              arrows: true
            }
          },
          {
            breakpoint: 575.98,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: false,
              arrows: true,
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: false,
              arrows: true,
            }
          }
        ]
      });
      @endif
      $('.alert').alert();
      // set product category dropdown in navbar and links in dooter
      function productCategories() {
        $('#product-category-dropdown').empty();
        $('#product-category-footer').empty();
        $.ajax({
          type:'GET',
          url:'/categories',
          success: function (response) {
            var l = '';
            var f = '';
            $(response).each(function(i, d){
              var slug = "";
              if(d.slug){
                slug = d.slug;
              }else{
                slug = d.id;
              }
              var link = (d.external_link) ? d.external_link : '/products/' + slug ;
              var target = (d.external_link) ? 'target="_blank"' : '';
              var is_new = d.is_new == 1 ? '' : 'd-none';
              // for navbar dropdown
              l += '<li><a class="dropdown-item category-menu" style="font-weight: 300 !important;" href="' + link +'" ' + target + '>' +
              '<img src="{{ asset("assets/site-img/icon/") }}/' + d.image + '" alt="' + d.name +'" width="30" loading="lazy">' + d.name +
              '&nbsp;<span class="badge alert-primary ' + is_new + '" style="font-size: 8pt !important;">New</span></a></li>';
              // for footer links
              f += '<tr style="border-style: unset !important;">' +
                '<td class="tdfooter footer2nd" style="border-style: unset !important;">' +
                '<a style="text-decoration:none; color: #9B999B;" href="'+ link +'" ' + target + '>' + d.name +'</a>' +
              '</td></tr>';
            });

            $('#product-category-footer').append(f);
            $('#product-category-dropdown').append(l);
          }
        });
      }

      function websiteSettings() {
        $.ajax({
          type:'GET',
          url:'/website_settings',
          success: function (response) {
            $('#navbar-brand').attr('href', response.set_value);
          }
        });
      }

      function countCartItems() {
        $.ajax({
          type:'GET',
          url:'/countcartitems',
          success: function (response) {
            $('.count-cart-items').text(response);
          }
        });
      }

      function countWishItems() {
        $.ajax({
          type:'GET',
          url:'/countwishlist',
          success: function (response) {
            $('.count-wish-items').text(response);
          }
        });
      }

      // policy pages
      function policyPages() {
        $('#policy-pages-footer').empty();
        $.ajax({
          type:'GET',
          url:'/policy_pages',
          success: function (response) {
            var f = '';
            $(response).each(function(i, d){
              var link = '/pages/' + d.slug;
              // for footer links
              f += '<tr style="border-style: unset !important;">' +
                '<td class="tdfooter footer2nd" style="border-style: unset !important;">' +
                '<a style="text-decoration:none; color: #9B999B;" href="'+ link +'" >' + d.page_title +'</a>' +
              '</td></tr>';
            });

            $('#policy-pages-footer').append(f);
          }
        });
      }

      // contact info
      function contactInfo() {
        $.ajax({
          type:'GET',
          url:'/contact_information',
          success: function (response) {
            if(response){
              $('#contact-email').text(response.office_email);
              $('#contact-phone').text(response.office_phone);
            }
          }
        });
      }

      $(document).on('click', '.remove-cart-btn', function(e){
            e.preventDefault();
            var tr = $(this);
            var data = {
                'id': $(this).data('id'),
                '_token': "{{ csrf_token() }}",
            }

            $.ajax({
                type:'DELETE',
                url:'/removefromcart',
                data: data,
                success: function (response) {
                  countCartItems();
                  loadcart();
                }
            });
        });

        function loadcart() {
          var preloader = '<div class="text-center"><div class="spinner-border text-muted m-3 text-center"></div></div>';
        $('#shopping-cart').html(preloader);
          $.ajax({
          type:"GET",
          url:"/cart",
          success:function(response){
            $('#shopping-cart').html(response);
          }
        });
        }
      $('#cart').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("#shopping-cart").toggleClass("active");

        loadcart();
      });

      $(document).click(function() {
        var $item = $("#shopping-cart");
        if (!$item.is(event.target) && !$item.has(event.target).length) {
          if ($item.hasClass("active")) {
            $item.removeClass("active");
          }
        }
      });

      @if (in_array($activePage, ['homepage', 'product_page', 'search_result', 'product_list', 'cart', 'clearance_sale']))
        $(document).on('click', '.add-to-cart', function(e){
          e.preventDefault();
          var btn = $(this);
          btn.removeClass('add-to-cart').text('Adding . . .');
          var data = {
            'item_code': $(this).data('item-code'),
            'quantity': 1,
            '_token': '{{ csrf_token() }}',
            'addtocart': 1
          }

          $.ajax({
            type:"POST",
            url:"/product_actions",
            data: data,
            success:function(response){
              setTimeout(function() {
                // btn.addClass('add-to-cart').html('<i class="fas fa-shopping-cart"></i> Add to Cart');
                btn.addClass('add-to-cart').html('Add to Cart');
              }, 300);

              countCartItems();
            }
          });
        });

        $(document).on('click', '.add-to-wishlist', function(e){
          e.preventDefault();
          var btn = $(this);
          if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
            var btn_fill = '<i class="fas fa-heart"><i>';
            var loading = '<div class="spinner-border spinner-border-sm text-primary"></div>';
          }else{ // desktop
            var loading = 'Adding...';
            var btn_fill = 'Add to Wishlist';
          }
          btn.removeClass('add-to-wishlist').html(loading);
          var data = {
            'item_code': $(this).data('item-code'),
            'quantity': 1,
            '_token': '{{ csrf_token() }}',
            'addtowishlist': 1
          }

          $.ajax({
            type:"POST",
            url:"/product_actions",
            data: data,
            success:function(response){
              setTimeout(function() {
                btn.addClass('add-to-wishlist').html(btn_fill);
              }, 1800);
            }
          });
        });

        $(document).on('click', '.notify-me', function() {
          var btn = $(this);
          if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
            var btn_fill = '<i class="fas fa-bell" style="color: #fff !important;"><i>';
            var loading = '<div class="spinner-border spinner-border-sm"></div>';
          }else{ // desktop
            var loading = 'Adding...';
            var btn_fill = 'Notify Me';
          }
          $(this).html(loading);
          if($(this).data('logged')){
            $.ajax({
              type:'get',
              url:'/notify_me',
              data: {
                item_code: $(this).data('item-code')
              },
              success: function (response) {
                btn.html(btn_fill);
                $('#notify-me-alert').addClass('show');
                $('#notify-me-alert').css('z-index', 1500);
                setTimeout(function(){
                    $('#notify-me-alert').removeClass('show');
                    $('#notify-me-alert').css('z-index', 0);
                  }, 2000);
              }
            });
          }else{
            window.location.href = '/login';
          }
        });
      @endif

      $('.autocomplete-search').keyup(function(){
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
              $("#desk-search-container").show();
              $("#desk-search-container").addClass('border border-secondary');
              $('#desk-search-container').html(autocomplete_data);
            }
          }
        });
      });

      $(document).mouseup(function(e) 
      {
          var desk_container = $("#desk-search-container");
          var mobile_container = $("#mob-search-container");

          // if the target of the click isn't the container nor a descendant of the container
          if (!desk_container.is(e.target) && desk_container.has(e.target).length === 0) 
          {
              desk_container.hide();
          }

          if (!mobile_container.is(e.target) && mobile_container.has(e.target).length === 0) 
          {
              mobile_container.hide();
          }
      });

      $('body').on('scroll', function (e){
        $("#desk-search-container").hide();
        $("#mob-search-container").hide();
      });

      $('#mob-autocomplete-search').keyup(function(){
        var data = {
          'search_term': $(this).val(),
          'type': 'mobile'
        }
        $.ajax({
          type:'GET',
          data: data,
          url:'/search',
          success: function (autocomplete_data) {
            if(autocomplete_data){
              $("#mob-search-container").show();
              $("#mob-search-container").addClass('border border-secondary');
              $('#mob-search-container').html(autocomplete_data);
            }
          }
        });
      });

      $(document).on('click', '.close-modal', function (){
        var modal = $(this).data('target');
        $(modal).modal('hide');
      });

      $("#closeCookieConsent, .cookieConsentOK").click(function() {
          $("#cookieConsent").fadeOut(200);
      });

      // Fix for "Does not use passive listeners to improve scrolling performance"
      jQuery.event.special.touchstart = {
          setup: function( _, ns, handle ) {
              this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
          }
      };
      jQuery.event.special.touchmove = {
          setup: function( _, ns, handle ) {
              this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
          }
      };
      jQuery.event.special.wheel = {
          setup: function( _, ns, handle ){
              this.addEventListener("wheel", handle, { passive: true });
          }
      };
      jQuery.event.special.mousewheel = {
          setup: function( _, ns, handle ){
              this.addEventListener("mousewheel", handle, { passive: true });
          }
      };

      $(document).on('click', '.open-modal', function (){
        $($(this).data('target')).modal('show');
      });

      $('#header-filler').css('margin-top', $('#navbar').height() + 'px');
    });
  </script>
  @yield('script')
</body>
</html>
