<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fumaco CMS | {{ $namePage }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('/assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-angular.min.js" integrity="sha512-KT0oYlhnDf0XQfjuCS/QIw4sjTHdkefv8rOJY5HHdNEZ6AmOh1DW/ZdSqpipe+2AEXym5D0khNu95Mtmw9VNKg==" crossorigin="anonymous"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('/assets/admin/logo-md.png') }}" alt="FumacoLogo" height="45" width="160">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <p class="mt-2 mb-0">You are logged in as <b>{{ Auth::user()->account_name }}</b></p>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Messages Dropdown Menu -->
      {{-- <li class="nav-item dropdown" hidden>
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li> --}}
      <!-- Notifications Dropdown Menu -->
      {{-- <li class="nav-item dropdown" hidden>
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> --}}
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="btn btn-outline-secondary" href="/admin/logout" role="button"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link text-center pb-0">
      <span class="brand-text font-weight-light mb-2">
         <picture>
            <source srcset="{{ asset('/assets/site-img/logo-sm.webp') }}" type="image/webp">
            <source srcset="{{ asset('/assets/site-img/logo-sm.png') }}" type="image/jpeg">
            <img src="{{ asset('/assets/site-img/logo-sm.png') }}" alt="Fumaco" width="155" height="54">
          </picture>
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link {{ ($activePage == 'admin_dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-header {{ Auth::user()->user_type == 'Sales Admin' ? 'd-none' : ''  }}">CONTENT MANAGEMENT</li>
          @php
              $pages = ['pages_list', 'home_crud', 'privacy_policy', 'terms_condition', 'contact_us', 'about_us', 'about_us_sponsors'];
          @endphp
          <li class="nav-item {{ (in_array($activePage, $pages) ? 'menu-open' : '') }} {{ Auth::user()->user_type == 'Sales Admin' ? 'd-none' : ''  }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-book"></i>
              <p>Pages <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/pages/home" class="nav-link {{ $activePage == 'home_crud' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Home Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/pages/about" class="nav-link {{ $activePage == 'about_us' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>About Us</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/pages/about/sponsor/list" class="nav-link {{ $activePage == 'about_us_sponsors' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sponsors/Partners</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/pages/contact" class="nav-link {{ $activePage == 'contact_us' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contact Us</p>
                </a>
              </li>
              <li class="nav-item" id="policy-pages"></li>{{-- Policy Pages --}}
            </ul>
          </li>
          @php
              $blog_pages = ['subscribers_list', 'blog_list', 'blog_comments_list'];
          @endphp
          <li class="nav-item {{ (in_array($activePage, $blog_pages) ? 'menu-open' : '') }} {{ Auth::user()->user_type == 'Sales Admin' ? 'd-none' : ''  }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $blog_pages) ? 'active' : '') }}">
              <i class="nav-icon fab fa-blogger"></i>
              <p>Blog Content<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/blog/list" class="nav-link {{ $activePage == 'blog_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/blog/comments" class="nav-link {{ $activePage == 'blog_comments_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog Comments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/blog/subscribers" class="nav-link {{ $activePage == 'subscribers_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscriber</p>
                </a>
              </li>
            </ul>
          </li>
          @php
            $product_pages = ['add_product_form', 'product_list', 'view_product_form', 'product_attribute_settings', 'product_category', 'product_comparison', 'product_category_settings', 'product_reviews'];
            $media_pages = ['list_media', 'add_media'];
            $order_pages = ['Order List', 'cancelled', 'completed', 'payment_status', 'order_status', 'order_status_sequence', 'payment_status_list'];
            $category_pages = ['product_category'];
            $customer_pages = ['customers_list'];
            $reports_pages = ['items_on_cart'];
          @endphp
          <li class="nav-header">PRODUCT CATALOGUE</li>
          <li class="nav-item {{ (in_array($activePage, $product_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $product_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Products <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/product/list" class="nav-link {{ $activePage == 'product_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/category/list" class="nav-link {{ $activePage == 'product_category' ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Category List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/products/compare/list" class="nav-link {{ $activePage == 'product_comparison' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product Comparison</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/product/settings" class="nav-link {{ $activePage == 'product_attribute_settings' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/product/reviews" class="nav-link {{ $activePage == 'product_reviews' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product Reviews</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">CUSTOMERS</li>
          <li class="nav-item {{ (in_array($activePage, $customer_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $customer_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Customer <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/customer/list" class="nav-link {{ (in_array($activePage, $customer_pages) ? 'active' : '') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer List</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">ORDERS</li>
          <li class="nav-item {{ (in_array($activePage, $order_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $order_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-dolly-flatbed"></i>
              <p>Orders <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/order/list/Order Placed" class="nav-link {{ $activePage == 'Order List' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/payment/check_payment_status" class="nav-link {{ $activePage == 'payment_status' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Check Payment Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/order/status_list" class="nav-link {{ $activePage == 'order_status' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/order/sequence_list" class="nav-link {{ $activePage == 'order_status_sequence' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order Status Sequence</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/payment/status_list" class="nav-link {{ $activePage == 'payment_status_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Status</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">SHIPPING</li>
          <li class="nav-item {{ (in_array($activePage, ['shipping_list', 'store_list', 'holiday_list']) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, ['shipping_list', 'store_list', 'holiday_list'])) ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>Shipping <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/shipping/list" class="nav-link {{ $activePage == 'shipping_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Shipping List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/store/list" class="nav-link {{ $activePage == 'store_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Store Location</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/holiday/list" class="nav-link {{ $activePage == 'holiday_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Holiday List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="/admin/payment_method/list" class="nav-link {{ $activePage == 'payment_method' ? 'active' : '' }}">
              <i class="fas fa-file-invoice-dollar nav-icon"></i>
              <p>Payment Method</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/admin/bank_account/list" class="nav-link {{ $activePage == 'bank_account' ? 'active' : '' }}">
              <i class="fas fa-university nav-icon"></i>
              <p>Bank Accounts</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/admin/price_list" class="nav-link {{ $activePage == 'pricelist' ? 'active' : '' }}">
              <i class="fas fa-tags nav-icon"></i>
              <p>Manage Price List</p>
            </a>
          </li>
          {{-- <li class="nav-header">MEDIA FILES</li>
          <li class="nav-item {{ (in_array($activePage, $media_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $media_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-photo-video"></i>
              <p>Files <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/media/list" class="nav-link {{ $activePage == 'list_media' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Files</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/media/add" class="nav-link {{ $activePage == 'add_media' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Media</p>
                </a>
              </li>
            </ul>
          </li> --}}
          @php
              $marketing_pages = ['pricing_rule', 'on_sale_list', 'vouchers_list', 'social_images'];
          @endphp
          <li class="nav-header">MARKETING</li>
          <li class="nav-item {{ (in_array($activePage, $marketing_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $marketing_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-photo-video"></i>
              <p>Promotions <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/marketing/pricing_rule/list" class="nav-link {{ $activePage == 'pricing_rule' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Price Rule/Discounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/marketing/on_sale/list" class="nav-link {{ $activePage == 'on_sale_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>On Sale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/marketing/voucher/list" class="nav-link {{ $activePage == 'vouchers_list' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vouchers/Coupons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/marketing/social/images" class="nav-link {{ $activePage == 'social_images' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Social Images</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">REPORTS</li>
          <li class="nav-item">
            <a href="/admin/items_on_cart" class="nav-link {{ $activePage == 'items_on_cart' ? 'active' : '' }}">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Items on Cart</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/search/list" class="nav-link {{ $activePage == 'search_terms_list' ? 'active' : '' }}">
              <i class="fas fa-search nav-icon"></i>
              <p>Search Terms</p>
            </a>
          </li>

          @if (Auth::user()->user_type == 'System Admin')
          <li class="nav-header">SYSTEM SETTINGS</li>
          @php
            $settings_pages = ['erp_api_setup', 'payment_api_setup', 'google_api_setup', 'sms_api_setup', 'transportify_api_setup'];
          @endphp
          <li class="nav-item {{ (in_array($activePage, $settings_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $settings_pages) ? 'active' : '') }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>API Management <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/api_setup/payment" class="nav-link {{ $activePage == 'payment_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGHL Payment API</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/api_setup/erp" class="nav-link {{ $activePage == 'erp_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fumaco Backend</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/api_setup/google" class="nav-link {{ $activePage == 'google_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Google API</p>
                </a>
              </li>
              <li class="nav-item">
                <a href=" " class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Social Media API</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/api_setup/sms" class="nav-link {{ $activePage == 'sms_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SMS Gateway API</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/api_setup/transportify" class="nav-link {{ $activePage == 'transportify_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transportify API</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item {{ (in_array($activePage, ['email_setup']) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, ['email_setup']) ? 'active' : '') }}">
              <i class="nav-icon fas fa-cogs"></i>

              <p>Email Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/email_setup" class="nav-link {{ $activePage == 'email_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email Setup</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href=" " class="nav-link {{ $activePage == 'erp_api_setup' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email Templates</p>
                </a>
              </li> --}}
            </ul>
          </li>

          <li class="nav-item">
            <a href="/admin/system_logs" class="nav-link {{ $activePage == 'system_logs' ? 'active' : '' }}">
              <i class="fas fa-file nav-icon"></i>
              <p>System Logs</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/admin/export_images" class="nav-link {{ $activePage == 'export_images' ? 'active' : '' }}">
              <i class="fas fa-photo-video nav-icon"></i>
              <p>Export Images</p>
            </a>
          </li>
          @endif
          @php
              $user_mgt_pages = ['admin_list', 'change_password']
          @endphp
          <li class="nav-header">USER MANAGEMENT</li>
          <li class="nav-item {{ (in_array($activePage, $user_mgt_pages) ? 'menu-open' : '') }}">
            <a href="#" class="nav-link {{ (in_array($activePage, $user_mgt_pages) ? 'active' : '') }}">
                <i class="fas fa-users"></i>
              <p>Users <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->user_type == 'System Admin')
                <li class="nav-item">
                  <a href="/admin/user_management/list" class="nav-link {{ $activePage == 'admin_list' ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Admin</p>
                  </a>
                </li>
              @endif
              <li class="nav-item">
                <a href="/admin/user_management/change_pass" class="nav-link {{ $activePage == 'change_password' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
 @yield('content')
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('/assets/admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('/assets/admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('/assets/admin/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('/assets/admin/plugins/sparklines/sparkline.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('/assets/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/assets/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('/assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('/assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('/assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/assets/admin/dist/js/adminlte.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('/assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
  $(document).ready(function() {
    policyPages();
    function policyPages() {
  // policy pages
      $('#policy-pages').empty();
      $.ajax({
        type:'GET',
        url:'/admin/pages/list',
        success: function (response) {
          var active = '';
          var f = '';
          var activePage = {!! str_replace("'", "\'", json_encode($activePage)) !!};

          $(response).each(function(i, d){
            var link = '/admin/pages/edit/' + d.page_id;
            if(activePage == d.slug){
              active = 'active';
            }else{
              active = '';
            }
            f += '<a href="' + link + '" class="nav-link ' + active + '">' +
                    '<i class="far fa-circle nav-icon"></i>' +
                    '<p>' + d.page_title + '</p>' +
                  '</a>'
          });

          $('#policy-pages').append(f);
        }
      });
    }

    $(document).on('show.bs.modal', '.modal', function (event) {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    });
  });
</script>
@yield('script')
</body>
</html>
