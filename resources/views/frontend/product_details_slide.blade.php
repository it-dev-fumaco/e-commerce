<a href="/product/{{ ($item['slug']) ? $item['slug'] : $item['item_code'] }}" style="cursor: pointer; text-decoration: none; text-transform: none;">
<div class="col-6 animated animatedFadeInUp fadeInUp equal-height-columns mb-3 best-selling-card p-1" style="width: 45% !important;">
    <!-- {{ $item['item_code'] }} -->
    <div class="card shadow-sm w-100">
      <div class="equal-column-content w-100">
        @php
        $img = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
        $img_webp = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'. explode(".", $item['image'])[0] . '.webp' : '/storage/no-photo-available.png';
        @endphp
        <div class="hover-container product-card" style="position: relative">
          <div class="pt-2" style="position: absolute; top: 0; right: 0; z-index: 10;">
            @if($item['is_new_item'])
            <div class="col-12 mb-2">
              <span class="p-1 text-center" style="background-color: #438539; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; min-width: 80px !important">
                &nbsp;<b>New</b>&nbsp;
              </span>
            </div><br />
            @endif
       
            @if ($item['is_discounted'])
              <div class="col-12">
                <span class="p-1 text-center" style="background-color: #FF0000; font-size: 10pt; border-radius: 20px 0 0 20px; color: #fff; float: right !important; width: 100%">
                  &nbsp;<b>{{ $item['discount_display'] }}</b>&nbsp;
                </span>
              </div>
            @endif
          </div>

          @if (!$item['on_stock'])
            <div class="out-of-stock-container">
                <span style="font-size: 9pt;">Out of Stock</span>
            </div>
          @endif
  
          <picture>
            <source srcset="{{ asset($img_webp) }}" type="image/webp">
            <source srcset="{{ asset($img) }}" type="image/jpeg">
            <img src="{{ asset($img) }}" alt="{{ Str::slug($item['alt'], '-') }}" class="img-responsive products-card-img" style="width: 100% !important; {{ !$item['on_stock'] ? 'opacity: 0.5;' : null }}" loading="lazy">
          </picture>
        </div>
        <div class="card-body d-flex flex-column">
          <div class="text ellipsis">
            <span class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 85px;">{{ $item['item_name'] }}</span>
          </div>
          <p class="card-text fumacoFont_card_price" style="color:#000000 !important; font-size: 10pt !important;">
            @if($item['is_discounted'] == 1)
            {{ $item['discounted_price'] }}&nbsp;<br class="d-md-none"><s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
            @else
            {{ $item['default_price'] }}<br/><br/>
            @endif
          </p>
        </div>
      </div>
      @if ($item['on_stock'])
        <div class="row col-11 mx-auto text-center mb-2 p-1">
            <a href="#" class="btn btn-outline-primary text-center w-100 p-2 add-to-cart no-border" role="button" style="font-weight: 600; font-size: 10pt;" data-item-code="{{ $item['item_code'] }}">
                Add to Cart
            </a>
        </div>
      @else
        <div class="row col-11 mx-auto text-center mb-2">
            <div class="col-6 col-md-5 p-1">
                <a href="#" class="d-md-none btn btn-outline-primary border-0 text-center w-100 p-2 notify-me no-border" role="button" style="font-weight: 600; font-size: 10pt;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">
                    <i class="fas fa-bell"></i>
                </a>
            </div>
            <div class="col-6 col-md-7 p-1">
                <a href="/login" class="btn w-100 text-center w-100 p-2 {{ Auth::check() ? 'add-to-wishlist' : '' }} btn-hover" role="button" data-item-code="{{ $item['item_code'] }}" style="background-color: #E6F0F8; color: #0F6EB5; font-weight: 600; font-size: 10pt; border-radius: 0 !important;">
                    <i class="fas fa-heart"></i>
                </a>
            </div>
        </div>
      @endif
    </div>
  </div>
</a>