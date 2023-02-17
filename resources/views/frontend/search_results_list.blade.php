@if (count($recently_added_arr) > 0)
<div class="col-12 text-center">
    <h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp results-head" style="color:#000000 !important;">RECENTLY ADDED PRODUCT(S)</h4>
</div>
@foreach ($recently_added_arr as $item)
    <!-- Mobile view Start -->
    <div class="d-block d-md-none animated animatedFadeInUp fadeInUp">
        <div class="card">
            <div class="pt-2" style="position: absolute; top: 0; left: 0; z-index: 10;">
                <div class="col-12">
                    @if ($item['is_discounted'])
                        <div class="col-12">
                            <span class="text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px; padding: 2px">
                                &nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        @php
                            $image = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
                            $image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
                        @endphp              
                        <div style="position: relative;">
                            @if (!$item['on_stock'])
                                <div class="overlay" style="display: flex; justify-content: center; align-items: center">Out of Stock</div>
                            @endif
                            <picture>
                                <source srcset="{{ asset($image_webp) }}" type="image/webp">
                                <source srcset="{{ asset($image) }}" type="image/jpeg"> 
                                <img src="{{ asset($image) }}" alt="{{ Str::slug($item['alt'], '-') }}" class="card-img-top hover" loading="lazy">
                            </picture>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="text ellipsis mb-1">
                            <a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="card-text mob-prod-text-concat" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important; font-weight: 500 !important; font-size: 9pt;">{{ $item['item_name'] }}</a>
                        </div>
                        <p class="card-text fumacoFont_card_price" style="color:#000000 !important; font-size: 9pt !important">
                            @if($item['is_discounted'])
                                {{ $item['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
                            @else
                            {{ $item['default_price'] }}
                            @endif
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group stylecap">
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($item['overall_rating'] <= $i)
                                        <span class="fa fa-star starcolorgrey"></span>
                                    @else
                                        <span class="fa fa-star" style="color: #FFD600;"></span>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $item['total_reviews'] }} Reviews )</small>
                        </div>
                        <br/>
                        @if ($item['on_stock'] == 1)
                            <a href="#" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto add-to-cart" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}">
                                Add to Cart
                            </a>
                        @else
                            <a href="#" class="btn btn-outline-primary text-center w-100 p-2 notify-me border-0" role="button" style="font-weight: 600; font-size: 10pt; margin-bottom: 5px;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">
                                Notify me
                            </a>
                            <a href="/login" class="btn btn-outline-primary mx-auto border-0 {{ Auth::check() ? 'add-to-wishlist' : '' }}" role="button" style="width: 100% !important;" data-item-code="{{ $item['item_code'] }}">
                                Add to Wishlist
                            </a>
                        @endif
                    </div>
                </div>								
            </div>
        </div>
    </div>
    <!-- Mobile view end -->

    <!-- Desktop/Tablet view start -->
    <div class="d-none d-md-block">
        @php
            $col = '4';
        @endphp
        @include('frontend.product_details_card')
    </div>
    <!-- Desktop/Tablet view end -->
@endforeach
@endif

<div class="col-12 text-center">
<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp results-head" style="color:#000000 !important;">{{ request()->s == null ? 'FEATURED PRODUCT(S)' : 'PRODUCT(S)' }}</h4>
</div>
@forelse ($products as $item)
<!-- Mobile view start -->
<div class="d-block d-md-none animated animatedFadeInUp fadeInUp mb-2">
    <div class="card">
        <div class="pt-2" style="position: absolute; top: 0; left: 0; z-index: 10;">
            @if($item['is_new_item'])
            <div class="col-12 mb-1 {{ $item['is_new_item'] == 1 ? '' : 'd-none' }}">
                <span class="text-center" style="background-color: #438539; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
                &nbsp;<b>New</b>&nbsp;
                </span>
            </div>
            @endif
            @if ($item['is_discounted'])
            <div class="col-12">
                <span class="text-center" style="background-color: #FF0000; font-size: 9pt; border-radius: 0 20px 20px 0; color: #fff; min-width: 80px !important; padding: 2px">
                    &nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
                </span>
            </div>
            @endif
        </div>
        <div class="card-body pt-1 pb-0">
            <div class="row">
                <div class="col-4">
                    @php
                        $image = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
                        $image_webp = ($item['image']) ? '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
                    @endphp
                    <div style="position: relative;">
                        @if (!$item['on_stock'])
                            <div class="overlay" style="display: flex; justify-content: center; align-items: center">Out of Stock</div>
                        @endif
                        <picture>
                            <source srcset="{{ asset($image_webp) }}" type="image/webp">
                            <source srcset="{{ asset($image) }}" type="image/jpeg"> 
                            <img src="{{ asset($image) }}" alt="{{ Str::slug($item['alt'], '-') }}" class="card-img-top hover" loading="lazy">
                        </picture>
                    </div>
                </div>
                <div class="col-8">
                    <div class="text ellipsis mb-1">
                        <a href="/product/{{ $item['slug'] ? $item['slug'] : $item['item_code'] }}" class="card-text mob-prod-text-concat" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important; font-size: 9pt; font-weight: 500 !important">{{ $item['item_name'] }}</a>
                    </div>
                    <p class="card-text fumacoFont_card_price" style="color:#000000 !important; font-size: 9pt !important">
                        @if($item['is_discounted'])
                        {{ $item['discounted_price'] }}&nbsp;<br class="d-none d-md-block d-lg-none"/><s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
                        @else
                        {{ $item['default_price'] }}
                        @endif
                    </p>
                    <div class="d-flex justify-content-between align-items-center pb-2">
                        <div class="btn-group stylecap">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($item['overall_rating'] <= $i)
                                <span class="fa fa-star starcolorgrey"></span>
                                @else
                                <span class="fa fa-star" style="color: #FFD600;"></span>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( {{ $item['total_reviews'] }} Reviews )</small>
                    </div>
                    @if ($item['on_stock'] == 1)
                        <span class="text-success p-0" style="font-size: 8pt; font-weight: 600;">Available</span>
                        <a href="#" class="btn btn-outline-primary text-center w-100 p-2 add-to-cart mt-2" role="button" style="font-weight: 600; margin-bottom: 10px; font-size: 10pt;" data-item-code="{{ $item['item_code'] }}">Add to Cart</a>
                    @else
                        <a href="#" class="btn btn-outline-primary text-center w-100 p-2 notify-me border-0" role="button" style="font-weight: 600; font-size: 10pt; margin-bottom: 10px;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">
                            Notify me
                        </a>
                        <a href="/login" class="btn w-100 text-center w-100 p-2 mb-2 border-0 {{ Auth::check() ? 'add-to-wishlist' : '' }} btn-hover" role="button" data-item-code="{{ $item['item_code'] }}" style="background-color: #E6F0F8; color: #0F6EB5; font-weight: 600; font-size: 10pt;">
                            Add to Wishlist
                        </a>
                    @endif
                </div>
            </div>								
        </div>
    </div>
</div>
<!-- Mobile view end --> 

<!-- Desktop/Tablet view start -->
<div class="col-4 d-none d-md-inline">
    @php
        $col = '12';
    @endphp
    @include('frontend.product_details_card')
</div>
<!-- Desktop/Tablet view end -->
@empty
    <h4 class="text-center text-muted p-5 text-uppercase">No products found</h4>
@endforelse

@if (count($blogs) > 0)
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">BLOG(S)</h4>
        </div>
        @foreach($blogs as $blog)
        <div class="col-lg-4 d-flex align-items-stretch animated animatedFadeInUp fadeInUp">
            <div class="card mb-4" style="border: 0px solid rgba(0, 0, 0, 0.125) !important;">
                @php
                    $image = ($blog['image']) ? '/storage/journals/'.$blog['image'] : '/storage/no-photo-available.png';
                    $image_webp = ($blog['image']) ? '/storage/journals/'.explode(".", $blog['image'])[0] .'.webp' : '/storage/no-photo-available.webp';
                @endphp
            
                <picture>
                    <source srcset="{{ asset($image_webp) }}" type="image/webp">
                    <source srcset="{{ asset($image) }}" type="image/jpeg">
                    <img src="{{ asset($image) }}" alt="{{ Str::slug($blog['title'], '-') }}" class="card-img-top" loading='lazy'>
                </picture>
                <div class="card-body align-items-stretch p-2">
                    <a href="blog/{{ $blog['blog_slug'] ? $blog['blog_slug'] : $blog['id'] }}" style="text-decoration: none !important;">
                        <p style="color:#373b3e !important;" class="abt_standard fumacoFont_card_title">{{ $blog['title'] }}</p>
                    </a>
                    <div class="blog-text ellipsis">
                        <p class="blog-text-concat abt_standard">{{ $blog['caption'] }}</p>
                    </div>
                    
                    <a href="/blog/{{ $blog['blog_slug'] ? $blog['blog_slug'] : $blog['id'] }}" class="text-concat mx-auto read-more">Read More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<div class="row p-0">
    <div class="container-fluid d-none d-md-block">
        <div style="float: right;" class="products-list-pagination">
            {{ $results->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div style="font-size: 9pt;" class="products-list-pagination d-md-none">
        {{ $results->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>