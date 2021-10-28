<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fumaco Inc. is the Philippine’s premiere lighting solutions powerhouse. It has manufacturing, import, distribution and sales capabilities of high quality lighting fixtures. The company employs industry experts and engineers to provide clients with utmost support for various lighting services.">
    <meta name="author" content="Fumaco Website">
    <meta name="keywords" content="FUMACO, Lighting, Philippines, Philippine, Leading, Luminaire, Manufacturing, ISO, Quality, light" />
    <title>{{ $namePage }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/assets/icon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('/assets/icon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('/assets/icon/favicon-32x32.png') }}" sizes="32x32">

    <link href="{{ asset('/assets/dist/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <script src="https://kit.fontawesome.com/ec0415ab92.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="{{ asset('/assets/fumaco.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @if($activePage == 'contact')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XHTGRGDC35"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-XHTGRGDC35');
    </script>
    <style>

      .fumacoFont1 {
          font-family: 'poppins', sans-serif !important; font-weight:400 !important; font-size: 1.75rem!important;
      }
      .fumacoFont2 {
          font-family: 'poppins', sans-serif !important; font-weight:200 !important;
      }
      .fumacoFont_btn {
          font-family: 'poppins', sans-serif !important; font-weight:200 !important; font-size: 16px !important;
      }
      .fumacoFont_card_title {
          /* font-family: 'Montserrat', sans-serif !important; font-weight:600 !important; font-size: 16px !important; */
          font-family: 'poppins', sans-serif !important; font-weight:600 !important; font-size: 16px !important;
      }
      .fumacoFont_card_caption {
          /* font-family: 'Roboto', sans-serif !important; font-weight:200 !important; font-size: 16px !important; */
          font-family: 'poppins', sans-serif !important; font-weight:300 !important; font-size: 16px !important;
      }
      .fumacoFont_card_readmore {
          font-family: 'poppins', sans-serif !important; font-weight:200 !important; font-size: 16px !important; text-decoration: none !important;
      }
      .fumacoFont_card_price {
          font-family: 'poppins', sans-serif !important; font-weight:600 !important; font-size: 16px !important; text-decoration: none !important;
      }
      .carousel-item > img {
          position: absolute !important;
          top: 0 !important;
          left: 0 !important;
          max-width: 100% !important;
          height: 100% !important;
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
          font-family: "Trebuchet MS",Helvetica,sans-serif;
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

      .abt_standard{
        font-family: 'poppins', sans-serif !important;
        /* font-weight: 200 !important; */
        /* font-size: 17px !important; */
        text-decoration: none !important;
        letter-spacing: 0.1em;
      }
      @media (max-width: 575.98px) {
        header{
          min-height: 50px;
        }
        .breadcrumb{
          font-size: 8pt !important;
          font-weight: 500;
        }
        .track-order-eta{
          text-align: left !important;
        }
      }

      @media (max-width: 767.98px) {
        header{
          min-height: 50px;
        }
        .breadcrumb{
          font-size: 8pt !important;
          font-weight: 500;
        }
        .track-order-eta{
          text-align: left !important;
        }
      }
      .user-icon{
        font-size: 24px;
      }
      .search-bar{
        width: 400px !important;
      }
      @media (max-width: 1199.98px) {/* tablet */
        .nav-item, .searchstyle{
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

    </style>
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
  </head>
  <body>
    <div class="spinner-wrapper">
      <div class="spinner"></div>
    </div>
    <header>
      <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light" style="padding-left: 10px; padding-right: 10px; padding-bottom:0px; border-bottom: 1px solid #e4e4e4;">
        <div class="container-fluid">
          <a class="navbar-brand" href="/" id="navbar-brand">
            <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="">
          </a>
          {{-- Mobile Icons --}}
          <a class="d-md-none d-lg-none d-xl-none" style="color: #000; margin-left: 10px !important" href="/login">
            <i class="far fa-user user-icon" style=""></i>
          </a>

          <a class="d-md-none d-lg-none d-xl-none" href="/cart" style="text-decoration: none !important; margin-left: 10px !important">
            <div class="" style="width: 50px !important; padding: 0 !important;">
              <i class="fa" style="font-size:24px; color:#126cb6;">&#xf07a;</i><span class="badge badge-warning count-cart-items" id="lblCartCount" style="font-size: 12px; background: #ff0000; color: #fff; padding: 4px 7px; vertical-align: top; margin-left: -10px;display: unset !important; font-weight: 500 !important; border-radius: 1rem !important; margin-top: -15px;">0</span>
            </div>
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          {{-- Mobile Icons --}}

          <div class="collapse navbar-collapse nav_fumaco_res nav-a" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0 navbar-header">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">PRODUCTS</a>
                <ul class="dropdown-menu dropdown-menu-light navbar-header" style="font-weight: 300 !important;" aria-labelledby="navbarDarkDropdownMenuLink" id="product-category-dropdown">
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/about">ABOUT US</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/journals">BLOGS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/contact">CONTACT</a>
              </li>
            </ul>
            <form class="d-none d-xl-block" action="/" method="GET">
              <div class="input-group mb-0 searchbar" style="width: 400px !important;">
                <input type="text" placeholder="Search" name="s" value="{{ request()->s }}" class="form-control searchstyle" aria-label="Text input with dropdown button">
                  <button class="btn btn-outline-secondary searchstyle" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle navbar-header" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Welcome, {{ (Auth::check()) ? Auth::user()->f_name : 'Guest' }}</a>
                <ul class="dropdown-menu dropdown-menu-light navbar-header" aria-labelledby="navbarDarkDropdownMenuLink" style="right: 14px !important; left: auto !important;">
                  <li>
                    <a class="dropdown-item" style="font-weight: 300 !important;" href="/cart">
                      <img src="{{ asset('/assets/site-img/icon/nav11.jpg') }}" alt="cart" width="30">&nbsp;&nbsp;My Cart <span class="badge badge-primary count-cart-items" style="background-color:#186eaa; vertical-align: top;">0</span>
                    </a>
                  </li>
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
            </ul>

          </div>
          {{-- Cart Icon --}}
          <a class="d-none d-md-block d-lg-block d-xl-block" style="text-decoration: none !important" href="/cart">
            <div class="" style="width: 50px !important; padding: 0 !important; margin-right: -20px !important">
              <i class="fa" style="font-size:24px; color:#126cb6;">&#xf07a;</i><span class="badge badge-warning count-cart-items" id="lblCartCount" style="font-size: 12px; background: #ff0000; color: #fff; padding: 4px 7px; vertical-align: top; margin-left: -10px;display: unset !important; font-weight: 500 !important; border-radius: 1rem !important; margin-top: -15px;">0</span>
            </div>
          </a>
          {{-- Cart Icon --}}
          <div class="d-sm-block d-md-none d-lg-none d-xl-none test" style="width: 100% !important">
            <div class="col-md-12">
              <form action="/" method="GET">
                <div class="input-group mb-0 searchbar" style="width: 100% !important;">
                  <input type="text" placeholder="Search" name="s" value="{{ request()->s }}" class="form-control searchstyle" aria-label="Text input with dropdown button">
                  {{-- <select class="custom-select form-control" name="by" style="max-width: 115px !important;">
                    <option value="all" {{ request()->by == 'all' ? 'selected' : '' }}>All</option>
                    <option value="products" {{ request()->by == 'products' ? 'selected' : '' }}>Products</option>
                    <option value="blogs" {{ request()->by == 'blogs' ? 'selected' : '' }}>Blogs</option>
                  </select> --}}
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

    {{-- <div id="cookieConsent">
      <p style="text-align: justify; padding-bottom: -10px !important; padding-top: 0px !important;">Fumaco Web Site uses cookies to ensure you get the best experience while browsing the site.<br>By continued use, you agree to our privacy policy and accept our use of such cookies. For further information, click <a href="privacy_policy" target="_blank">More info</a>. <a class="cookieConsentOK">I AGREE</a></p>
    </div> --}}
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
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/privacy_policy" style="text-decoration: none;     color: #0062A5;">Privacy Policy</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;"><a href="/terms_condition" style="text-decoration: none;     color: #0062A5;">Terms & Conditions</a></td>
                  <td class="tdfooter footer2nd" style="border-style: unset !important;">&nbsp;</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-lg-5" style="text-align: left !important;">
            <h6 class="footer1st" style="color:#ffffff !important;">PRODUCTS</h6>
            <table class="table" style="border-style: unset !important;">
              <tbody style="font-size: 12px; color: #ffffff; border-style: unset !important;" id="product-category-footer"></tbody>
            </table>
          </div>
          <div class="col-lg-4" style="text-align: right !important;">
            <h6 class="footer1st" style="color:#ffffff !important;">SUBSCRIBE TO OUR NEWSLETTER</h6>
            <form action="/subscribe" method="POST">
              @csrf
              <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email Address" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
                <button class="input-group-text" id="basic-addon2">Subscribe</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <br>
      <br>
    </main>
  </footer>
  <script src="{{ asset('/assets/dist/js/bootstrap.bundle.js') }}"></script>
  <script>
    $(document).ready(function() {
      websiteSettings();
      productCategories();
      countCartItems();
      countWishItems();
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
              // for navbar dropdown
              l += '<li><a class="dropdown-item" style="font-weight: 300 !important;" href="/products/' + d.id +'">' +
              '<img src="{{ asset("assets/site-img/icon/") }}/' + d.image + '" alt="' + d.name +'" width="30">' + d.name +'</a></li>';
              // for footer links
              f += '<tr style="border-style: unset !important;">' +
                '<td class="tdfooter footer2nd" style="border-style: unset !important;">' +
                '<a style="text-decoration:none; color: #0062A5;" href="/products/' + d.id +'">' + d.name +'</a>' +
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
