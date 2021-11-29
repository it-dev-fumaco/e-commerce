<div class="shopping-cart-header">
    <div class="shopping-cart-total" style="font-size: 14pt; text-align: center !important; margin-left: 10px;">
        <span class="lighter-text">My Cart</span>
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
        <span class="item-price">₱ {{ number_format($cart['amount'], 2, '.', ',') }}</span>
        <span class="item-quantity">Qty: {{ $cart['quantity'] }} {{ $cart['stock_uom'] }}</span>
        <span class="item-price m-1" style="display: inline-block; float: right;">
            <a href="#" class="remove-cart-btn" href="#" role="button" data-id="{{ $cart['item_code'] }}" style="color: #ABB0BE;"><i class="fas fa-trash-alt"></i></a>
        </span>
        @if ($cart['insufficient_stock'])
        <small class="item-detail text-danger">Insufficient Stock</small>
        @endif
    </li>
    @empty
    <li class="clearfix text-center">
        <span class="text-muted">Cart is empty</span>
    </li>
    @endforelse
</ul>
<div class="text-danger" style="text-align: right; margin: 10px; font-size: 10pt;">
    Total: ₱ {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}
</div>
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
        <a href="/cart" class="btn btn-outline-primary fumacoFont_card_readmore btn-sm mx-auto" role="button" style="width: 100% !important; background-color:  #85929e "><i class="fas fa-shopping-cart"></i> View Cart</a>
    </div>
    <div class="p-1 col">
        <a href="{{ $action }}" class="co-btn btn btn-outline-primary btn-sm fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;" {{ (count($cart_arr) > 0) ? '' : 'disabled' }} {{ $disabled_co }}>Checkout <i class="fa fa-chevron-right"></i></a>
    </div>
</div>
@if (Auth::check())
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col text-center">
        <a href="/myorders" class="fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;"><i class="fas fa-box" style="margin-right: 2%;"></i> My Orders</a>
    </div>
</div>
@else
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col text-center">
       <a href="/track_order" class="fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important; font-size: 10pt !important;"><i class="fas fa-box" style="margin-right: 2%;"></i> Track Order</a>
    </div>
</div>
@endif

<style>
.co-btn:disabled,
.co-btn[disabled]{
    border: 1px solid #999999 !important;
    background-color: #cccccc !important;
    color: #404040 !important;
    cursor: not-allowed !important;
}
</style>