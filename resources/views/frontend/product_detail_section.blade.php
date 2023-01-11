<div class="col-12 p-0 m-0">
    <form action="/product_actions" method="POST" autocomplete="off">
        @csrf
        <main class="prod-main" style="background-color:#ffffff;">
            <div class="container marketing">
                <div class="single_product prod-main" style="padding-bottom: 0px !important;">
                    <div class="container-fluid" style=" background-color: #fff; padding: 11px;">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="xzoom-container" style="width: 100% !important;">
                                    @php
                                        $src = (count($product_images) > 0) ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/preview/'. $product_images[0]->imgprimayx : '/storage/no-photo-available.png';
                                        $xoriginal = (count($product_images) > 0)  ? '/storage/item_images/'. $product_images[0]->idcode.'/gallery/original/'. $product_images[0]->imgoriginalx : '/storage/no-photo-available.png';
                                        $alt = $product_details->image_alt ? $product_details->image_alt : $product_details->f_item_name;
                                    @endphp
                                    <img style="width: 100% !important;" alt="{{ isset($product_images[0]) ? Str::slug($alt, '-') : '' }}" class="xzoom4 imgx" id="xzoom-fancy" src="{{ asset($src) }}" xoriginal="{{ asset($xoriginal) }}" />
                                    <br><br>
                                    <div class="xzoom-thumbs">
                                        @foreach ($product_images as $image)
                                        <a href="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/original/'. $image->imgoriginalx) }}" style="text-transform: none !important; text-decoration: none !important;">
                                            <img class="xzoom-gallery4" width="60" src="{{ asset('/storage/item_images/'. $image->idcode.'/gallery/preview/'. $image->imgprimayx) }}" alt="{{ Str::slug($alt, '-') }}" loading='lazy' />
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 order-3">
                                <div class="product_description">
                                    <div class="message_box" style="margin:10px 0px;">
                                        @if ($message = Session::get('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $message }}</div>
                                        @endif
                                    </div>
                                    <div class="product_name fumacoFont_item_title">{{ $product_details->f_name_name }}</div>
                                    <div class="product-rating">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group stylecap">
                                                @for ($i = 0; $i < 5; $i++)
                                                @if ($product_details_array['overall_rating'] <= $i)
                                                <span class="fa fa-star starcolorgrey"></span>
                                                @else
                                                <span class="fa fa-star" style="color: #FFD600;"></span>
                                                @endif
                                                @endfor
                                                <span style="color:#000000 !important; font-weight:200 !important;">&nbsp;&nbsp;( {{ $product_details_array['total_reviews'] }} Reviews )</span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row p-0">
                                            <div style="font-size: 16pt; padding: 3px 8px;">
                                                <a target="_blank" href="mailto:?subject=Check this out!&body=Hi, I found this product and thought you might like it {{ \Request::fullUrl() }}" class="m-0" style="color: #f49332;"><i class="far fa-envelope m-0"></i></a>
                                            </div>
                                            <div class="pt-2">
                                                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0&appId=435536724607670&autoLogAppEvents=1" nonce="oVFop3CH"></script>
                                                <div class="fb-like" data-href="{{ \Request::fullUrl() }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @if($product_details_array['is_discounted'] == 1)
                                        <span class="product_price fumacoFont_item_price">{{ $product_details_array['discounted_price'] }}</span>
                                        <s class="product_discount"><span style='color:black;'>{{ $product_details_array['default_price'] }}<span></s>
                                        @else
                                        <span class="product_price fumacoFont_item_price">{{ $product_details_array['default_price'] }}</span>
                                        @endif
                                        @if ($product_details_array['is_discounted'])
                                        <span class="badge badge-danger" style="margin-left: 8px; vertical-align: middle;background-color: red;">{{ $product_details_array['discount_display'] }}</span>
                                        @endif
                                    </div>
                                    <br class="d-md-none"/>
                                    <div>
                                        <span class="prod-font-size">{!! $product_details->f_caption !!}</span>
                                        <p class="card-text fumacoFont_card_caption">
                                            <ul style="margin-top: -12px !important;">
                                                <li>
                                                    <a href="#product_details" style="text-decoration: none;">
                                                        <span style="text-decoration: none;color: #1a6ea9 !important;font-size: 12px !important; font-weight: 400 !important;">See more products details</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </p>
                                        <input type="hidden" name="item_code" value="{{ $product_details->f_idcode }}">
                                        <p class="card-text custom-status">QTY&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;   <input type="number" value="1" id="quantity" name="quantity" min="1" max="{{ ($product_details->f_qty > 0) ? $product_details->f_qty : 1 }}" style="width: 70px;"> <span class="d-inline-block" style="margin-left: 10px;">{{ $product_details->f_stock_uom  }}</span></p>
                                        <p class="card-text custom-status">
                                            @if ($product_details_array['on_stock'])
                                                <span style='color:green;'>Available</span>
                                            @else
                                                <span style='color:red;'>Not Available</span>
                                            @endif
                                            &nbsp;&nbsp; <i class="fas fa-bell"></i>
                                        </p>
                                    </div>
                                    <hr class="singleline">
                                    @php
                                        $variant_diff = array_diff(array_keys($variant_attr_arr), array_keys($attributes->toArray()));
                                    @endphp
                                    @if (count($variant_diff) > 0)
                                    <div class="alert alert-danger fade show mb-0" role="alert"></div>
                                    @endif
                                    @foreach ($variant_attr_arr as $attr => $row)
                                    @if (count($row) > 1)
                                    @php
                                        $x = 0;
                                        $opt_name = preg_replace('/\s+/', '', strtolower($attr));
                                        $tmp = $attributes_arr->toArray();
                                    @endphp
                                    <label>{{ $attr }} : </label><br class="d-md-none"/>
                                    <div class="btn-group" role="group" aria-label="Select Variants" style="display: unset !important;">
                                        @foreach ($row as $attr_value => $items)
                                        @php
                                            $x++;
                                            $is_active = [1];
                                        @endphp
                                        @if (isset($attributes[$attr]))
                                        @php
                                            if (array_key_exists($attr, $tmp)) {
                                                $tmp[$attr] = $attr_value;
                                            }
                                            $variant_available = false;
                                        @endphp
                                            @if(count($is_active) > 0)
                                            @foreach ($variant_combinations as $combi)
                                            @php
                                                if (!$variant_available)
                                                $v_diff = array_diff_assoc(array_values($tmp), array_values($combi->toArray()));
                                                if (count($v_diff) <= 0) {
                                                    $variant_available = true;
                                                }
                                            @endphp
                                            @endforeach
                                            @if ($variant_available)
                                            <input type="radio" class="btn-check attr-radio" {{ ($attributes[$attr] == $attr_value) ? 'checked' : '' }} name="{{ $opt_name }}" id="{{ $opt_name . $x }}" autocomplete="off" value="{{ $attr_value }}" data-attribute="{{ Str::slug($attr, '-') }}" {{ (count($is_active) > 0) ? '' : 'disabled' }}>
                                            <label class="btn btn-outline-{{ (count($is_active) > 0) ? 'info' : 'secondary' }} btn-sm mb-2 mt-2" for="{{ $opt_name . $x }}">{{ $attr_value }}</label>
                                            @else
                                            <input type="radio" class="btn-check attr-radio" {{ ($attributes[$attr] == $attr_value) ? 'checked' : '' }} name="{{ $opt_name }}" id="{{ $opt_name . $x }}" autocomplete="off" value="{{ $attr_value }}" data-attribute="{{ Str::slug($attr, '-') }}" {{ (count($is_active) > 0) ? '' : 'disabled' }}>
                                            <label class="btn btn-outline-{{ (count($is_active) > 0) ? 'info' : 'secondary' }} btn-sm mb-2 mt-2" for="{{ $opt_name . $x }}"><del>{{ $attr_value }}</del></label>
                                            @endif
                                            @else
                                            <input type="radio" class="btn-check attr-radio" {{ ($attributes[$attr] == $attr_value) ? 'checked' : '' }} name="{{ $opt_name }}" id="{{ $opt_name . $x }}" autocomplete="off" value="{{ $attr_value }}" data-attribute="{{ Str::slug($attr, '-') }}" {{ (count($is_active) > 0) ? '' : 'disabled' }}>
                                            <label class="btn btn-outline-{{ (count($is_active) > 0) ? 'info' : 'secondary' }} btn-sm mb-2 mt-2" for="{{ $opt_name . $x }}"><del>{{ $attr_value }}</del></label>
                                            @endif
                                        @else
                                        Error in Variants.
                                        @endif
                                        @endforeach
                                    </div><br>
                                    @endif
                                    @endforeach
                                    <div class="row mt-5" id="product_details">
                                        <div class="col-xs-6">
                                            <div class="row p-0" id="action-buttons">
                                                @if ($product_details_array['on_stock'])
                                                    <div class="col-12 col-xl-3 p-0">
                                                        <button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore product-btn no-border w-100" name="addtocart" value="1"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                                                    </div>
                                                    <div class="col-12 col-xl-3 p-0 pt-2 p-xl-2 pt-xl-0">
                                                        <button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore product-btn no-border w-100" name="buynow" value="1"><i class="fas fa-wallet"></i> Buy Now</button>
                                                    </div>
                                                @else
                                                    <div class="col-12 col-xl-3 p-0">
                                                        <button type="button" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore product-btn no-border notify-me w-100" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $product_details_array['item_code'] }}">Notify Me</button>
                                                    </div>
                                                    <div class="col-12 col-xl-3 p-0 pt-2 p-xl-2 pt-xl-0">
                                                        <button type="submit" class="btn btn-lg btn-outline-primary fumacoFont_card_readmore product-btn no-border w-100" name="addtowishlist" value="1"><i class="fas fa-heart"></i> Add to Wish List</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row"><br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </form>
</div>
<div class="col-12 p-0 m-0">
    <br>
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button  class="btn btn-link collapsed fumacoFont_collapse_title abt_standard" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseOne">PRODUCT DETAIL</button>
                </h2>
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body prod_standard p-0">
                    <p class="card-text">
                        <table class="table">
                            <tbody style="border-style: inset !important;">
                                @foreach ($filtered_attributes as $attr => $value)
                                <tr>
                                    <td>{{ $attr }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if (count($product_details_array['bundle_items']) > 0)
                            <tbody style="border-style: inset !important;">
                                @foreach ($product_details_array['bundle_items'] as $bundle)
                                <tr>
                                    <td style="width: 60%;">{!! $bundle->item_description !!}</td>
                                    <td class="text-center" style="width: 40%;">{{ $bundle->qty . ' ' . $bundle->uom }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        </table>
                    </p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed fumacoFont_collapse_title abt_standard" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo">ADDITIONAL INFORMATION</button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                @if ($product_details->f_featured_image)
                <div class="card-body prod_standard">
                    @php
                        $f_img = $product_details->f_featured_image ? '/storage/item_images/'. $product_details->f_idcode .'/gallery/featured/'. $product_details->f_featured_image : '/storage/no-photo-available.png';
                        $f_img_webp = $product_details->f_featured_image ? '/storage/item_images/'. $product_details->f_idcode .'/gallery/featured/'. explode(".", $product_details->f_featured_image)[0] .'.webp' : '/storage/no-photo-available.png';
                    @endphp
                    <picture>
                        <source srcset="{{ asset($f_img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                        <source srcset="{{ asset($f_img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                        <img src="{{ asset($f_img) }}" alt="{{ Str::slug(explode(".", $product_details->f_featured_image)[0], '-') }}" class="img-responsive" style="width: 100% !important;" loading="lazy">
                    </picture>
                </div>
                @endif
                <div class="card-body prod_standard">
                    <p class="card-text">{!! $product_details->f_full_description !!}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading3">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed fumacoFont_collapse_title abt_standard" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo">PRODUCT REVIEW(S)</button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse show" aria-labelledby="heading3" data-parent="#accordionExample">
                <div class="card-body prod_standard">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex flex-row justify-content-center">
                                <div class="text-center text-white bg-warning rounded d-block" style="border: 1px solid; padding: 10px 20px;">
                                    <h1 class="d-block display-3"><b>{{ (number_format($product_details_array['overall_rating'], 1))  }}</b></h1>
                                    <small class="d-block">out of 5</small>
                                </div>
                            </div>
                            <div class="d-block text-center m-1" style="font-size: 15pt;">
                                @for ($i = 0; $i < 5; $i++)
                                @if ($product_details_array['overall_rating'] <= $i)
                                <span class="fa fa-star starcolorgrey"></span>
                                @else
                                <span class="fa fa-star" style="color: #FFD600;"></span>
                                @endif
                                @endfor
                            </div>
                            <div class="d-block text-center p-2">{{ $product_details_array['total_reviews'] . ' Review(s)' }}</div>
                            @if (Auth::check() && $is_ordered > 0)
                            <form action="/submit_review" method="POST" id="review-form">
                                @csrf
                                <input type="hidden" name="item_code" value="{{ $product_details->f_idcode }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="m-3"><i class="far fa-edit"></i> Write a review</h5>
                                        <div class="d-flex flex-row">
                                            <div class="p-2 col text-center">
                                                <div class="avatar">
                                                    <div class="avatar__letters">
                                                        {{ substr(Auth::user()->f_name, 0, 1) . substr(Auth::user()->f_lname, 0, 1) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2 col-10">
                                                <b>{{ Auth::user()->f_name . ' ' . Auth::user()->f_lname }}</b>
                                                <div class="d-flex flex-row align-items-center">
                                                    <div class="rating">
                                                        <input type="radio" name="rating" value="5" id="5" required>
                                                        <label for="5">&star;</label>
                                                        <input type="radio" name="rating" value="4" id="4">
                                                        <label for="4">&star;</label>
                                                        <input type="radio" name="rating" value="3" id="3">
                                                        <label for="3">&star;</label>
                                                        <input type="radio" name="rating" value="2" id="2">
                                                        <label for="2">&star;</label>
                                                        <input type="radio" name="rating" value="1" id="1">
                                                        <label for="1">&star;</label>
                                                    </div>
                                                    <div style="margin-left: 10px;" class="text-danger d-none" id="rating-alert"><small>Please rate</small></div>
                                                </div>
                                                <textarea class="form-control caption_1" rows="3" name="message" placeholder="Message">{{ old('message') }}</textarea>
                                                <button type="button" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp" id="submit-review">Submit Review</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @forelse ($product_reviews as $product_review)
                            <div class="d-flex flex-row">
                                <div class="p-2 col text-center">
                                    <div class="avatar">
                                        <div class="avatar__letters">
                                            {{ substr($product_review->f_name, 0, 1) . substr($product_review->f_lname, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 col-10">
                                    @php
                                        $d1 = strtotime(\Carbon\Carbon::parse($product_review->created_at)->format('Y-m-d H:i:s'));
                                        $d2 = strtotime(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
                                        $totalSecondsDiff = abs($d1-$d2);
                                        $totalMinutesDiff = $totalSecondsDiff/60;
                                        $totalHoursDiff   = $totalSecondsDiff/60/60;

                                        if (round($totalHoursDiff) > 24) {
                                            $duration = \Carbon\Carbon::parse($product_review->created_at)->format("d M Y");
                                        } elseif (round($totalMinutesDiff) >= 60) {
                                            $duration = ((round($totalHoursDiff) > 1) ? round($totalHoursDiff) . ' hours ago' : 'an hour ago');
                                        } elseif ($totalMinutesDiff >= 1) {
                                            $duration = (($totalMinutesDiff > 1) ? round($totalMinutesDiff) . ' minutes ago' : 'a minute ago');
                                        } else {
                                            $duration = round($totalSecondsDiff) . ' seconds ago';
                                        }
                                    @endphp
                                    <span class="d-block">
                                        <b>{{ $product_review->f_name . ' ' . $product_review->f_lname }}</b>
                                        <div style="float: right;">
                                            <span class="text-muted d-block mb-1" style="font-size: 8pt;">{{ $duration }}</span>
                                        </div>
                                    </span>
                                    <div class="d-block mb-3">
                                        @for ($i = 0; $i < 5; $i++)
                                        @if ($product_review->rating <= $i)
                                        <span class="fa fa-star starcolorgrey"></span>
                                        @else
                                        <span class="fa fa-star" style="color: #FFD600;"></span>
                                        @endif
                                        @endfor
                                    </div>
                                    <p style="font-size: 13px;">{{ $product_review->message }}</p>
                                </div>
                            </div>
                            @empty
                            <h6 class="text-muted text-center p-5">No reviews yet.</h6>
                            @endforelse
                            <div style="float: right;">
                                {{ $product_reviews->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/fancybox/source/jquery.fancybox.css') }}" />
<link type="text/css" rel="stylesheet" media="all" href="{{ asset('/item/magnific-popup/css/magnific-popup.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/item/dist/xzoom.css') }}" media="all" />
<link type="text/css" rel="stylesheet" href="{{ asset('/assets/loading.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/page_css/product_page.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick-theme.css') }}">

<script type="text/javascript" src="{{ asset('/assets/loading.js') }}"></script>
<script src="{{ asset('/item/js/foundation.min.js') }}"></script>
<script src="{{ asset('/item/js/setup.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/dist/xzoom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/hammer.js/1.0.5/jquery.hammer.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/fancybox/source/jquery.fancybox.js') }}"></script>
<script type="text/javascript" src="{{ asset('/item/magnific-popup/js/magnific-popup.js') }}"></script>
<script src="{{ asset('/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>