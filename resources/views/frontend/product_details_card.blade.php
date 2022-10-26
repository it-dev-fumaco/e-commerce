<div class="col-md-4 col-lg-{{ isset($col) ? $col : '3' }} animated animatedFadeInUp fadeInUp equal-height-columns mb-3 best-selling-card">
  <!-- {{ $item['item_code'] }} -->
  <div class="card shadow-sm">
    <div class="equal-column-content">
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
        <div class="overlay-bg"></div>
        <div class="btn-container">
          <a href="/product/{{ ($item['slug']) ? $item['slug'] : $item['item_code'] }}" class="view-products-btn btn" role="button"><i class="fas fa-search"></i>&nbsp;View Product</a>
        </div>

        <picture>
          <source srcset="{{ asset($img_webp) }}" type="image/webp">
          <source srcset="{{ asset($img) }}" type="image/jpeg">
          <img src="{{ asset($img) }}" alt="{{ Str::slug($item['alt'], '-') }}" class="img-responsive hover" style="width: 100% !important; height: 100% !important" loading="lazy">
        </picture>
      </div>
      <div class="card-body d-flex flex-column">
        <div class="text ellipsis">
          <a href="/product/{{ ($item['slug']) ? $item['slug'] : $item['item_code'] }}" class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="text-transform: none !important; text-decoration: none !important; color:#0062A5 !important;  min-height: 100px;">{{ $item['item_name'] }}</a>
        </div>
        <p class="card-text fumacoFont_card_price" style="color:#000000 !important; ">
          @if($item['is_discounted'] == 1)
          {{ $item['discounted_price'] }}&nbsp;<s style="color: #c5c5c5;">{{ $item['default_price'] }}</s>
          @else
          {{ $item['default_price'] }}
          @endif
        </p>
      </div>
      <div class="mx-auto" style="width: 90%;">
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
      </div>
    </div>
    @if ($item['on_stock'])
      <div class="row col-11 mx-auto text-center mt-5">
        <a href="#" class="btn btn-outline-primary text-center w-100 p-2 add-to-cart" role="button" style="font-weight: 600; margin-bottom: 20px; font-size: 10pt;" data-item-code="{{ $item['item_code'] }}">Add to Cart</a>
      </div>
    @else
      <div class="row col-11 mx-auto text-center">
        <span class="mt-2 mb-2" style="font-weight: 600; color: #F50000">Out of Stock</span>
        <div class="col-5 p-1">
          <a href="#" class="btn btn-outline-primary text-center w-100 p-2 notify-me" role="button" style="font-weight: 600; margin-bottom: 14px; margin-top: 3px; font-size: 10pt;" data-logged="{{ Auth::check() ? 1 : 0 }}" data-item-code="{{ $item['item_code'] }}">Notify me</a>
        </div>
        <div class="col-7 p-1">
          <a href="/login" class="btn w-100 text-center w-100 p-2 {{ Auth::check() ? 'add-to-wishlist' : '' }} btn-hover" role="button" data-item-code="{{ $item['item_code'] }}" style="background-color: #E6F0F8; color: #0F6EB5; margin-bottom: 14px; margin-top: 3px; font-weight: 600; font-size: 10pt;">
            Add to Wishlist
          </a>
        </div>
      </div>
    @endif
  </div>
</div>