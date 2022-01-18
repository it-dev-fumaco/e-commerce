<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @hasSection('meta')
      @yield('meta')
    @else
      <meta name="description" content="Fumaco Inc. is the Philippine’s premiere lighting solutions powerhouse. It has manufacturing, import, distribution and sales capabilities of high quality lighting fixtures. The company employs industry experts and engineers to provide clients with utmost support for various lighting services.">
      <meta name="keywords" content="FUMACO, Lighting, Philippines, Philippine, Leading, Luminaire, Manufacturing, ISO, Quality, light"  />
    @endif
    <meta name="author" content="Fumaco Website">
    <title>{{ $namePage }}</title>
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
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="{{ asset('/assets/fumaco.css') }}" rel="stylesheet">
    @if ($activePage != 'error_page')
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NZJWSRR');</script>
        <!-- End Google Tag Manager -->

    @endif
            <!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>
<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

    <script>
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

    @if ($activePage == 'login')
    <script>
      function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
        if (response.status === 'connected') {   // Logged into your webpage and Facebook.
          loginUser();
        }
      }

      function checkLoginState() {               // Called when a person is finished with the Login Button.
        FB.getLoginStatus(function(response) {   // See the onlogin handler
          statusChangeCallback(response);
        });
      }

      window.fbAsyncInit = function() {
        FB.init({
          appId      : '435536724607670',
          cookie     : true,                     // Enable cookies to allow the server to access the session.
          xfbml      : true,                     // Parse social plugins on this webpage.
          version    : 'v12.0'           // Use this Graph API version for this call.
        });
      };

      function loginUser () {
        FB.api('/me?fields=id,email,first_name,last_name', function(response) {
          var data = {
            'id': response.id,
            'email': response.email,
            'first_name': response.first_name,
            'last_name': response.last_name,
            '_token': "{{ csrf_token() }}",
          }

          $.ajax({
            type:'POST',
            url:'/facebook/login',
            data: data,
            success: function (res) {
              if (res.status == 200) {
                window.location.href="{{ route('website') }}";
              } else {
                $('#login-fb').removeClass('d-none').text(res.message);
              }
            }
          });
        });
      }

      function triggerLogin() {
        FB.login(function(response) {
          checkLoginState();
        }, {scope: 'email'});
      }
    </script>
    @endif

    <style>
      * {
        -webkit-overflow-scrolling: touch !important;
      }
      html,body{
        width: 100% !important;
        height: 100% !important;
        margin: 0px !important;
        padding: 0px !important;
        overflow-x: hidden !important;
          font-family: 'poppins', sans-serif !important;
          scroll-behavior: smooth;
      }
      .fumacoFont1 {
          font-family: 'poppins', sans-serif !important; font-weight:400 !important; font-size: 1.75rem!important;
      }
      .fumacoFont2 {
          font-family: 'poppins', sans-serif !important; font-weight:300 !important; padding-top: 10px !important;
      }
      .fumacoFont_btn {
          font-family: 'poppins', sans-serif !important; font-weight:200 !important; font-size: 16px !important;
      }
      .fumacoFont_card_title {
          font-family: 'poppins', sans-serif !important; font-weight:500 !important; font-size: 20px !important;
      }
      .fumacoFont_card_caption {
          font-family: 'poppins', sans-serif !important; font-weight:300 !important; font-size: 16px !important;
      }
      .fumacoFont_card_readmore {
          font-family: 'poppins', sans-serif !important; font-weight:200 !important; font-size: 16px !important; text-decoration: none !important;
      }
      .fumacoFont_card_price {
          font-family: 'poppins', sans-serif !important; font-weight:600 !important; font-size: 16px !important; text-decoration: none !important;
      }
      /* homepage */
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
          color: #ffffff;
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
      /*Cookie Consent Begin*/
      #cookieConsent {
          background-color: rgb(28 54 72);
          min-height: 66px;
          font-size: 14px;
          color: #ccc;
          line-height: 26px;
          padding: 8px 0 8px 30px;
          font-family: "Poppins",Helvetica,sans-serif;
          position: fixed;
          bottom: 0;
          left: 0;
          right: 0;
          display: none;
          z-index: 9999;
          padding-right: 20px;
      }
      #cookieConsent a {
          color: #78c1e4;
          text-decoration: none;
      }
      #closeCookieConsent {
          float: right;
          display: inline-block;
          cursor: pointer;
          height: 20px;
          width: 20px;
          margin: -15px 0 0 0;
          font-weight: bold;
      }
      #closeCookieConsent:hover {
          color: #FFF;
      }
      #cookieConsent a.cookieConsentOK {
          background-color: #ffffff;
          color: #0062a5;
          display: inline-block;
          border-radius: 15px;
          padding: 0 50px;
          cursor: pointer;
          float: right;
          margin: 0 80px 0 20px;
      }
      #cookieConsent a.cookieConsentOK:hover {
          background-color: #feefc6;
      }
      /*Cookie Consent End*/
      @media only screen and (max-width: 600px) {
          #cookieConsent a.cookieConsentOK {
              background-color: #ffffff;
              color: #0062a5;
              display: inline-block;
              border-radius: 15px;
              padding: 0 50px;
              cursor: pointer;
              float: left;
              margin: 0 80px 0 0px;
              margin-top: 20px;
          }
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
      .mbl-welcome{
        display: none !important;
      }
      .user-icon{
        font-size: 24px;
      }
      .search-bar{
        width: 400px !important;
      }

      .payment-icons{
        width: 60px !important;
        height: 35px !important;
        background-color: #fff !important;
        border-radius: 7px;
      }
      @media (max-width: 575.98px) {
        header{
          min-height: 50px;
        }
      }

      @media (max-width: 767.98px) {
        header{
          min-height: 50px;
        }
      }

      @media (max-width: 1199.98px) {/* tablet */
        .nav-item, .searchstyle, .welcome-msg {
          font-size: 12px !important;
          margin: 0 !important;
        }
        .user-icon{
          font-size: 20px !important;
        }
        .nav-a{
          padding: 0 !important;
        }
        .search-bar{
          width: auto !important;
        }
      }

      @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {/* portrait tablet */
        /* Product List Page */
     .mob-srch{
          display: inline-block !important;
        }
        .pc-cart, .search-bar{
          display: none !important;
        }
        .mb-cart{
          display: inline-block !important;
        }
      }

      @media only screen and (min-device-height : 427.98px) and (max-device-height : 767.98px) and (orientation : landscape) {/* landscape mobile */
        /* Product List Page */
       .mob-srch{
          display: inline-block !important;
        }
        .pc-cart, .search-bar{
          display: none !important;
        }
        .mb-cart{
          display: inline-block !important;
        }
      }

      #shopping-cart {
        background: white;
        width: 320px;
        position: absolute;
        top: 80px;
        right: 13px;
        border-radius: 3px;
        padding: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26) !important;
        -webkit-transition: all 0.2s ease;
        transition: all 0.2s ease;
        opacity: 0;
        -webkit-transform-origin: right top 0;
        -webkit-transform: scale(0);
        transform-origin: right top 0;
        transform: scale(0);
      }
      #shopping-cart.active {
        opacity: 1;
        -webkit-transform-origin: right top 0;
        -webkit-transform: scale(1);
        transform-origin: right top 0;
        transform: scale(1);
      }
      #shopping-cart .shopping-cart-header {
        border-bottom: 1px solid #E8E8E8;
        padding-bottom: 15px;
      }
      /* #shopping-cart .shopping-cart-header .shopping-cart-total {
        float: right;
      } */
      #shopping-cart .shopping-cart-items {
        padding: 5px;
        list-style: none;
      }
      #shopping-cart .shopping-cart-items li {
        margin-bottom: 10px;
      }
      #shopping-cart .shopping-cart-items picture {
        float: left;
        margin-right: 12px;
        max-width: 70px;
        max-height: 70px;
      }
      #shopping-cart .shopping-cart-items .item-name {
        display: block;
        font-size: 14px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
      }
      #shopping-cart .shopping-cart-items .item-price {
        color: #404040;
        margin-right: 8px;
        font-size: 12px;
      }
      #shopping-cart .shopping-cart-items .item-quantity {
        color: #ABB0BE;
        font-size: 12px;
      }

      #shopping-cart .badge-danger {
        background-color: red;
        font-size: 13px;
        margin: 5px;
      }

      #shopping-cart .shopping-cart-items .item-detail {
      display: block;
      font-size: 11px !important;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
    }

      #shopping-cart:after {
        bottom: 100%;
        left: 89%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-bottom-color: white;
        border-width: 8px;
        margin-left: -8px;
      }

      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }
      .btn:focus, a:focus, .btn-check:focus + label{
        box-shadow: none !important;
        border: none !important;
      }
    </style>
    @yield('style')
    @if($activePage == 'contact')
      {!! ReCaptcha::htmlScriptTagJsApi() !!}
    @endif
  </head>
  <body>
    <div class="spinner-wrapper">
      <div class="spinner"></div>
    </div>
    <header>
      <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light" style="padding-left: 20px; padding-right: 20px; padding-bottom:0px; border-bottom: 1px solid #e4e4e4;">
        <div class="container-fluid">
          <a class="navbar-brand d-none d-md-block" href="/" id="navbar-brand">
            <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="" width="155" height="54">
          </a>
          {{-- Mobile Icons --}}
          <div class="row justify-content-between">
            <div class="col d-md-none">
              <a class="navbar-brand" href="/" id="navbar-brand">
                <img src="{{ asset('/assets/site-img/logo-sm.png') }}" style="width: 100%" />
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
                <ul class="dropdown-menu dropdown-menu-light navbar-header" style="font-weight: 300 !important;" aria-labelledby="navbarDarkDropdownMenuLink" id="product-category-dropdown">
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
            </ul>
            <form class="d-none d-lg-block search-bar" action="/" method="GET">
              <div class="input-group mb-0 searchbar search-bar">
                <input type="text" placeholder="Search" name="s" value="{{ request()->s }}" class="form-control searchstyle" aria-label="Text input with dropdown button">
                  <button class="btn btn-outline-secondary searchstyle" type="submit"><i class="fas fa-search"></i></button>
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
                      <img src="{{ asset('/assets/site-img/icon/nav12.jpg') }}" alt="mywishlist" width="30">&nbsp;&nbsp;Wishlist <span class="badge badge-primary count-wish-items" style="background-color:#186eaa; vertical-align: top;">0</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/myorders">
                      <img src="{{ asset('/assets/site-img/icon/nav13.jpg') }}" alt="myorders" width="30">&nbsp;&nbsp;My Orders
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/myprofile/account_details">
                      <img src="{{ asset('/assets/site-img/icon/nav14.jpg') }}" alt="myprofile" width="30">&nbsp;&nbsp;My Profile
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/logout">
                      <img src="{{ asset('/assets/site-img/icon/nav15.jpg') }}" alt="logout" width="30">&nbsp;&nbsp;Log Out
                    </a>
                  </li>
                  @else
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/track_order">
                      <img src="{{ asset('/assets/site-img/icon/nav13.jpg') }}" alt="trackorder" width="30">&nbsp;&nbsp;Track My Order
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/login">
                      <img src="{{ asset('/assets/site-img/icon/nav15.jpg') }}" alt="login" width="30">&nbsp;&nbsp;Login | Sign Up
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
              <form action="/" method="GET">
                <div class="input-group mb-0 searchbar" style="width: 100% !important;">
                  <input type="text" placeholder="Search" name="s" value="{{ request()->s }}" class="form-control searchstyle" aria-label="Text input with dropdown button">
                    <button class="btn btn-outline-secondary searchstyle" type="submit"><i class="fas fa-search"></i></button>
                </div>
              </form><br/>
            </div>
          </div>

        </div>

      </nav>
    </header>

  @yield('content')
  <footer>
    <main style="background-color:#0062A5;"><br></main>
    @include('cookieConsent::index')
    <main style="background-color:#000000;">
      <div class="container marketing">
        <section class="py-5 text-center container" style="padding-top: 0rem !important; padding-bottom: 3rem !important;">
        </section>
        <div class="row">
          <div class="col-lg-3" style="text-align: left !important;">
            <h6 class="footer1st" style="color:#ffffff !important;">ABOUT FUMACO</h6>
            <table class="table" style="border-style: unset !important;">
              <tbody style="font-size: 12px; color: #ffffff; border-style: unset !important;">
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/about" style="text-decoration: none; color: #0062A5;">Company Info</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/journals" style="text-decoration: none; color: #0062A5;">News</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
                <tr id="policy-pages-footer"></tr>{{-- Policy Pages --}}
              </tbody>
            </table>
          </div>
          <div class="col-lg-5" style="text-align: left !important;">
            <h6 class="footer1st" style="color:#ffffff !important;">PRODUCTS</h6>
            <table class="table" style="border-style: unset !important;">
              <tbody style="font-size: 12px; color: #ffffff; border-style: unset !important;" id="product-category-footer"></tbody>
            </table>
          </div>
          <div class="col-lg-4">
            <div class="col-md-12" style="text-align: right !important;">
              <h6 class="footer1st" style="color:#ffffff !important;">SUBSCRIBE TO OUR NEWSLETTER</h6>
              <form action="/subscribe" method="POST">
                @csrf
                <div class="input-group mb-3">
                  <input type="email" class="form-control" name="email" placeholder="Email Address" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
                  <button class="input-group-text" id="basic-addon2">Subscribe</button>
                </div>
              </form>
            </div>
            <br/>
            <br/>
            <br/>
            <div class="col-md-12" style="text-align: left !important;">
              <h6 class="footer1st" style="color:#ffffff !important;">WE ACCEPT</h6>
              <div class="row" style="padding-left:1% !important">
                  @php
                    $payment_method = array('mastercard2', 'visa', 'gcash2', 'grabpay2');
                  @endphp
                  @foreach($payment_method as $img)
                    @php
                      $image = '/storage/payment_method/'.$img.'.png';
                      $image_webp = '/storage/payment_method/'.$img.'.webp';
                    @endphp
                    <div class="d-inline m-2 payment-icons" style="position: relative !important"><picture>
                      <source srcset="{{ asset($image_webp) }}" type="image/webp" style="object-fit: cover;">
                      <source srcset="{{ asset($image) }}" type="image/jpeg" style="object-fit: cover;">
                      <img src="{{ asset($image) }}" style="object-fit: cover; max-height: 100%;max-width: 90%;width: auto;height: auto;position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;">
                    </picture></div>
                  @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <br>
    </main>
  </footer>
  <script src="https://kit.fontawesome.com/ec0415ab92.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  @if($activePage == 'contact')
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>

  <script src="{{ asset('/assets/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    $(document).ready(function() {


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

      @if (in_array($activePage, ['homepage', 'product_page', 'search_result', 'product_list', 'cart']))
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
                btn.addClass('add-to-cart').html('<i class="fas fa-shopping-cart"></i> Add to Cart');
              }, 1800);

              countCartItems();
            }
          });
        });

        $(document).on('click', '.add-to-wishlist', function(e){
          e.preventDefault();
          var btn = $(this);
          btn.removeClass('add-to-wishlist').text('Adding . . .');
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
                btn.addClass('add-to-wishlist').html('<i class="far fa-heart"></i> Add to Wishlist');
              }, 1800);
            }
          });
        });
      @endif
      websiteSettings();
      productCategories();
      countCartItems();
      countWishItems();
      policyPages();
      //Preloader
      preloaderFadeOutTime = 800;
      function hidePreloader() {
          var preloader = $('.spinner-wrapper');
          preloader.fadeOut(preloaderFadeOutTime);
      }
      hidePreloader();

      setTimeout(function () {
          $("#cookieConsent").fadeIn(200);
      }, 2000);
      $("#closeCookieConsent, .cookieConsentOK").click(function() {
          $("#cookieConsent").fadeOut(200);
      });

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
                '<a style="text-decoration:none; color: #0062A5;" href="'+ link +'" >' + d.page_title +'</a>' +
              '</td></tr>';
            });


            $('#policy-pages-footer').append(f);
          }
        });
      }

      // set product category dropdown in navbar and links in footer
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
              // for navbar dropdown
              l += '<li><a class="dropdown-item" style="font-weight: 300 !important;" href="' + link +'" ' + target + '>' +
              '<img src="{{ asset("assets/site-img/icon/") }}/' + d.image + '" alt="' + d.name +'" width="30">' + d.name +'</a></li>';
              // for footer links
              f += '<tr style="border-style: unset !important;">' +
                '<td class="tdfooter footer2nd" style="border-style: unset !important;">' +
                '<a style="text-decoration:none; color: #0062A5;" href="'+ link +'" ' + target + '>' + d.name +'</a>' +
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
            // $('title').text(response.set_sitename);
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
    });
  </script>

  @yield('script')
</body>
</html>
