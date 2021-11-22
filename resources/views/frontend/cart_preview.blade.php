<div class="shopping-cart-header">
    <i class="fa fa-shopping-cart cart-icon"></i><span class="badge badge-danger">{{ collect($cart_arr)->sum('quantity') }}</span>
    <div class="shopping-cart-total">
        <span class="lighter-text">Cart Total:</span>
        <span class="main-color-text total">₱ {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}</span>
    </div>
</div> <!--end shopping-cart-header -->
<ul class="shopping-cart-items">
    @forelse ($cart_arr as $cart)
    <li class="clearfix">
        @php
            $img = ($cart['item_image']) ? '/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image'] : '/storage/no-photo-available.png';
            $img_webp = ($cart['item_image']) ? '/storage/item_images/'. $cart['item_code'] .'/gallery/preview/'. explode(".", $cart['item_image'])[0] . '.webp' : '/storage/no-photo-available.png';
        @endphp
        <picture>
            <source srcset="{{ asset($img_webp) }}" type="image/webp" class="img-responsive" width="55" height="55">
            <source srcset="{{ asset($img) }}" type="image/jpeg" class="img-responsive" width="55" height="55">
            <img src="{{ asset($img) }}" alt="{{ Str::slug(explode(".", $cart['item_image'])[0], '-') }}" class="img-responsive" width="55" height="55">
        </picture>
        <span class="item-name">{{ $cart['item_description'] }}</span>
        <span class="item-price">₱ {{ number_format($cart['price'], 2, '.', ',') }}</span>
        <span class="item-quantity">Quantity: {{ $cart['quantity'] }}</span>
        @if ($cart['insufficient_stock'])
        <small class="item-detail text-danger">Insufficient Stock</small>
        @else
        <small class="item-detail text-success">Available :  {{ $cart['stock_qty'] }}</small>
        @endif
    </li>
    @empty
    <li class="clearfix text-center">
        <span class="text-muted">Cart is empty</span>
    </li>
    @endforelse
</ul>
  @php
  $action = '';
  if(Auth::check()){//member
      if($bill_address > 0 and $ship_address > 0){
          $action = '/setdetails';
      }else if($ship_address < 1){
          $action = '/checkout/billing';
      }else if($bill_address < 1){
          $action = '/checkout/set_billing_form';
      }else{
          $action = '/checkout/billing';
      }
  }else{// guest
      $action = '/checkout/billing';
  }

  if(collect($cart_arr)->sum('insufficient_stock')) {
      $disabled_co = 'disabled';
  } else {
    $disabled_co = '';
  }
@endphp
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col">
        <a href="/cart" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;"><i class="fas fa-shopping-cart"></i> View Cart</a>
    </div>
    <div class="p-1 col">
        <a href="{{ $action }}" class="co-btn btn btn-outline-primary fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;" {{ (count($cart_arr) > 0) ? '' : 'disabled' }} {{ $disabled_co }}>Checkout <i class="fa fa-chevron-right"></i></a>
    </div>
</div>
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col">
        <a href="/track_order" class="btn btn-outline-primary fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;"><i class="fas fa-box"></i> Track Order</a>
    </div>
</div>
<style>
.co-btn:disabled,
.co-btn[disabled]{
    border: 1px solid #999999 !important;
    background-color: #cccccc !important;
    color: #404040 !important;
    cursor: not-allowed !important;
}
</style>