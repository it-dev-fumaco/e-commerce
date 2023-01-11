<div class="shopping-cart-header">
    <div class="shopping-cart-total" style="font-size: 14pt; text-align: center !important; margin-left: 10px;">
        <span class="lighter-text">My Cart</span>
    </div>
</div> <!--end shopping-cart-header -->
<ul class="shopping-cart-items" style="min-height: 150px;">
    @php
        $subtotal = $discount_amount = $apply_price_rule = 0;
    @endphp
    @forelse ($cart_arr as $cart)
    <li class="clearfix">
        @php
            $img = ($cart['item_image']) ? '/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image'] : '/storage/no-photo-available.png';
            $img_webp = ($cart['item_image']) ? '/storage/item_images/'. $cart['item_code'] .'/gallery/preview/'. explode(".", $cart['item_image'])[0] . '.webp' : '/storage/no-photo-available.png';
            $amount = $cart['amount'];
        @endphp
        <picture>
            <source srcset="{{ asset($img_webp) }}" type="image/webp">
            <source srcset="{{ asset($img) }}" type="image/jpeg">
            <img src="{{ asset($img) }}" alt="{{ Str::slug(explode(".", $cart['alt'])[0], '-') }}" class="img-responsive" width="55" height="55">
        </picture>
        <span class="item-name">{{ $cart['item_description'] }}</span>
        @if ($price_rule && !isset($price_rule['Transaction']))
            @php
                $pr = [];
                $discount_amount = 0;
                if (isset($price_rule[$cart['item_code']])) {
                    $pr = $price_rule[$cart['item_code']];
                    switch ($pr['discount_type']) {
                        case 'Percentage':
                            $discount_amount = $cart['amount'] * ($pr['discount_rate'] / 100);
                            break;
                        default:
                            $discount_amount = $pr['discount_rate'] < $amount ? $pr['discount_rate'] : 0;
                            break;
                    }

                    $amount = $amount - $discount_amount;
                }

                $apply_price_rule = 1;
            @endphp
        @endif
        <span class="item-price">₱ {{ number_format($amount, 2, '.', ',') }}</span>
        @if ($discount_amount)
            <del class="text-muted">
                <span style="font-size: 8pt;">₱ {{ number_format($cart['amount'], 2, '.', ',') }}</span>
            </del>
            <br>
        @endif
        <span class="item-quantity">Qty: {{ $cart['quantity'] }} {{ $cart['stock_uom'] }}</span>
        <span class="item-price m-1" style="display: inline-block; float: right;">
            <a href="#" class="remove-cart-btn" href="#" role="button" data-id="{{ $cart['item_code'] }}" style="color: #ABB0BE;"><i class="fas fa-trash-alt"></i></a>
        </span>
        @php
            $apr = [];
            $subtotal += $amount;
            if ($applicable_price_rule && isset($applicable_price_rule[$cart['item_code']])) {
                $apr = collect($applicable_price_rule[$cart['item_code']])->where('range_from', '>', $cart['quantity'])->first();
            }
        @endphp
        @if ($apr && !isset($applicable_price_rule['Transaction']))
            {!! $discount_amount ? '<br/>' : '<br/><br/>' !!}
            @switch($apr['based_on'])
                @case('Order Qty')
                    <small class="item-detail text-success">Add at least <b>{{ $apr['range_from'] - $cart['quantity'] }}</b> more and get a {{ $apr['discount_rate'] }} discount</small>
                    @break
                @default
                    <small class="item-detail text-success">Reach at least {{ number_format($apr['range_from']) }} on this item and get a {{ $apr['discount_rate'] }} discount</small>
                    @break
            @endswitch
        @endif
        @if ($cart['insufficient_stock'])
        <small class="item-detail text-danger">Insufficient Stock</small>
        @endif
    </li>
    @empty
    <li class="clearfix text-center m-2">
        <span class="text-muted">Cart is empty</span>
    </li>
    @endforelse
</ul>
<div style="text-align: right; margin: 10px; font-size: 10pt;">
    @isset($price_rule['Transaction'])
        @php
            $pr = $price_rule['Transaction'];
            switch ($pr['discount_type']) {
                case 'Percentage':
                    $discount_amount = $subtotal * ($pr['discount_rate'] / 100);
                    break;
                default:
                    $discount_amount = $pr['discount_rate'] < $subtotal ? $pr['discount_rate'] : 0;
                    break;
            }
            $subtotal = $subtotal - $discount_amount;
            $apply_price_rule = 1;
        @endphp
    @endisset
    <span class="text-danger">Total: ₱ {{ number_format($subtotal, 2, '.', ',') }}</span>
    @if ($apply_price_rule)
    <br>
        <del class="text-muted" style="font-size: 8pt;">₱ {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}</del>
    @endif
    @isset($applicable_price_rule['Transaction'])
        <br>
        @php
            $apr = $applicable_price_rule['Transaction'];
        @endphp
        @foreach ($apr as $item)
            @php
                $based_on = $item['based_on'] == 'Order Qty' ? 'quantity' : 'amount';
                $transaction_qty = collect($cart_arr)->sum($based_on);
            @endphp
            @if ($transaction_qty < $item['range_from'])
                @switch($item['based_on'])
                    @case('Order Qty')
                        <small class="item-detail text-success">Add at least <b>{{ $item['range_from'] - $transaction_qty }}</b> more and get a {{ $item['discount_rate'] }} discount</small>
                        @break
                    @default
                        <small class="item-detail text-success">Get a {{ $item['discount_rate'] }} discount when you reach {{ $item['range_from'] }}</small>
                        @break
                @endswitch

                @break
            @endif
        @endforeach
    @endisset
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
        <a href="/cart" class="cart-btn btn btn-outline-primary fumacoFont_card_readmore btn-sm mx-auto" role="button" style="width: 100% !important; padding: 5px 15px;"><i class="fas fa-shopping-cart"></i> Checkout</a>
    </div>
</div>
@if (Auth::check())
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col text-center">
        <a href="/myorders" class="cart-btn fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important;"><i class="fas fa-box" style="margin-right: 2%;"></i> My Orders</a>
    </div>
</div>
@else
<div class="d-flex flex-row justify-content-between">
    <div class="p-1 col text-center">
       <a href="/track_order" class="cart-btn fumacoFont_card_readmore mx-auto" role="button" style="width: 100% !important; font-size: 10pt !important;"><i class="fas fa-box" style="margin-right: 2%;"></i> Track Order</a>
    </div>
</div>
@endif

<style>
.cart-btn{
    border: none !important;
    box-shadow: 1px solid rgba(0,0,0,0) !important;
    outline: none !important;
}
.cart-btn:focus {
    border: none !important;
    box-shadow: 1px solid rgba(0,0,0,0) !important;
    outline: none !important;
}
.co-btn:disabled,
.co-btn[disabled]{
    border: 1px solid #999999 !important;
    box-shadow: 1px solid rgba(0,0,0,0) !important;
    background-color: #cccccc !important;
    color: #404040 !important;
    cursor: not-allowed !important;
    outline: none !important;
}
</style>