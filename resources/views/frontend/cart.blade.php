@extends('frontend.layout', [
    'namePage' => 'Shopping Cart',
    'activePage' => 'cart'
])

@section('content')
@php
    $page_title = 'SHOPPING CART';
@endphp
@include('frontend.header')

<main style="background-color:#ffffff;" class="products-head">
    <nav>
        <ol class="breadcrumb" style="white-space: nowrap !important">
            <li class="breadcrumb-item">
                <a href="#" style="color: #000000 !important; text-decoration: underline;">Shopping Cart</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#" style="color: #928d8d !important; text-decoration: none;">Billing & Shipping Address</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="#" style="color: #928d8d !important; text-decoration: none;">Place Order</a>
            </li>
        </ol>
    </nav>
</main>

<main style="background-color:#ffffff;" class="products-head">
    <div class="container marketing">
        <br>
        <div class="row"></div>
    </div>
    <div class="container" style="max-width: 100% !important; min-height: 600px;">
        <div class="row">
            <div class="col-lg-10 animated animatedFadeInUp fadeInUp mx-auto">
                @if(session()->has('error'))
                    <div class="alert alert-warning">
                    {!! session()->get('error') !!}
                    </div>
                @endif
                <table class="table animated animatedFadeInUp fadeInUp" id="cart-items">
                    <thead>
                        <tr>
                            <th class="he1x">Product</th>
                            <th class="he1x"></th>
                            <th class="he1x d-none d-sm-table-cell">Price</th>
                            <th class="he1x d-none d-sm-table-cell">Quantity</th>
                            <th class="he1x d-none d-sm-table-cell">Total</th>
                            <th class="he1x" style="width: 5px !important"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart_arr as $cart)
                        <tr class="he2x2">
                            <td class="tbl-qtr">
                                @php
                                    $c_src = '/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image'];
                                @endphp
                                <picture>
                                    <source srcset="{{ asset(explode('.', $c_src)[0].'.webp') }}" type="image/webp">
                                    <source srcset="{{ asset($c_src) }}" type="image/jpeg"> 
                                    <img src="{{ asset('/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="{{ Str::slug($cart['alt'], '-') }}" width="55" height="55">
                                </picture>
                            </td>
                            <td class="tbls tbl-half" style="width:37% !important;"><a href="/product/{{ $cart['slug'] }}" style="text-decoration: none !important; color: #000;">{{ $cart['item_description'] }}</a>
                                <br/>{{-- for mobile --}}
                            <span class="formatted-price d-md-none d-lg-none d-xl-none"><br/><b>₱ {{ number_format($cart['price'], 2, '.', ',') }}</b></span>
                            <br/><br/>
                            <span class="d-md-none d-lg-none d-xl-none">Quantity<br/>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-left-minus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> - </a>
                                    </span>
                                    <div>&nbsp;</div>
                                    <input type="text" name="res_quantity[]" class="form-control input-number mobile-quantity" value="{{ $cart['quantity'] }}" min="1" max="{{ $cart['stock_qty'] }}" style="width: 5px !important; text-align: center !important;" data-id="{{ $cart['item_code'] }}" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)">
                                    <div>&nbsp;</div>
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-right-plus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> + </a>
                                    </span>
                                    @if ($cart['insufficient_stock'] || $cart['quantity'] > $cart['stock_qty'])
                                        <small class="text-danger d-block m-2 stock-status">Insufficient Stock</small>
                                    @else
                                        <small class="text-success d-block m-2 stock-status">Available :  {{ $cart['stock_qty'] }}</small>
                                    @endif
                                </div>
                            </span>{{-- for mobile --}}
                            </td>
                            <td class="tbls d-none d-sm-table-cell"><p style="white-space: nowrap !important;">₱ <span class="formatted-price">{{ number_format($cart['price'], 2, '.', ',') }}</span></p><span class="price d-none">{{ $cart['price'] }}</span></td>
                            <td class="tbls d-none d-sm-table-cell text-center">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-left-minus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> - </a>
                                    </span>
                                    <div>&nbsp;</div>
                                    <input type="text" name="quantity[]" class="form-control input-number " value="{{ $cart['quantity'] }}" min="1" max="{{ $cart['stock_qty'] }}" style="width: 4px !important; text-align: center !important;" data-id="{{ $cart['item_code'] }}" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)">
                                    <div>&nbsp;</div>
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-right-plus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> + </a>
                                    </span>
                                </div>
                                @if ($cart['insufficient_stock'] || $cart['quantity'] > $cart['stock_qty'])
                                <small class="text-danger d-block m-2 stock-status">Insufficient Stock</small>
                                @else
                                <small class="text-success d-block m-2 stock-status">Available :  {{  $cart['quantity'].$cart['stock_qty'] }}</small>
                                @endif
                            </td>
                            <td class="tbls d-none d-sm-table-cell"><p style="white-space: nowrap !important;">₱ <span class="formatted-amount">{{ number_format($cart['amount'], 2, '.', ',') }}</span></p><span class="amount d-none">{{ $cart['amount'] }}</span></td>
                            <td class="tbls tbl-qtr">
                                <a class="btn btn-sm btn-outline-primary remove-from-cart-btn no-border" href="#" role="button" data-id="{{ $cart['item_code'] }}">&#x2715;</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td style="border-bottom: 0; padding: 10px 0;" colspan="7">
                                <div class="alert alert-danger alert-dismissible fade show font-responsive" role="alert">Your cart is empty!</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <table class="table">
                    <tr>
                        <td class="col-md-8">&nbsp;</td>
                        <td class="col-md-2">Total</td>
                        <td><small class="text-muted stylecap he1x" id="cart-subtotal">₱ {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}</small></td>
                    </tr>
                </table>
                <br/>
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
                @endphp
                <div class="row">
                    <div class="col-md-6 d-none d-md-block">
                        <div class="card-body col-md-8 mx-auto">
                            <a href="/" class="btn btn-secondary no-border" style="width:100% !important;" role="button"><i class="fas fa-angle-left"></i> CONTINUE SHOPPING</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body col-md-8 mx-auto">
                            <button id="checkout-btn" class="btn btn-outline-primary no-border" role="button" style="width:100% !important;" {{ (count($cart_arr) > 0) ? '' : 'disabled' }}>PROCEED TO CHECKOUT</button>
                        </div>
                    </div>
                    <div class="col-md-6 d-md-none">
                        <div class="card-body col-md-8 mx-auto">
                            <a href="/" class="btn btn-secondary no-border" style="width:100% !important;" role="button"><i class="fas fa-angle-left"></i> CONTINUE SHOPPING</a>
                        </div>
                    </div>
                </div>
                <br><br><br>
                @if($cross_sell_arr)
                    <div class="container-fluid p-0 mb-3">
                        <h4>Frequently Bought Together</h4>
                        <br/>
                        <div class="row regular" style="min-height: 300px">
                            @foreach($cross_sell_arr as $item)
                                @include('frontend.product_details_card')
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-12">&nbsp;&nbsp;</div>
        </div>
    </div>
</main>

<div id="custom-overlay" style="display: none;">
	<div class="custom-spinner"></div>
	<br/>
	Loading...
</div>
  <!-- Modal -->
<div class="modal fade" id="stockLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i> Notice</h5>
            <button type="button" class="close clear-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center">
            <p>Desired quantity exceeds available stocks.</p>
        </div>
      </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick-theme.css') }}">
<style type="text/css">

  html, body {
    margin: 0;
    padding: 0;
  }

  * {
    box-sizing: border-box;
  }

  .slick-slide {
    margin: 0px 20px;
  }

  .slick-slide img {
    width: 100%;
  }

  .slick-slide {
    transition: all ease-in-out .3s;
    opacity: .2;
  }

  .slick-active {
    opacity: .5;
  }

  .slick-current, .slick-slide  {
    opacity: 1;
  }

    .text{
      position: relative;
      font-size: 16px !important;
      width: 100%;
    }

    .text-concat {
      position: relative;
      display: inline-block;
      word-wrap: break-word;
      overflow: hidden;
      max-height: 4.5em;
      line-height: 1.6em;
      text-align: left;
      font-size: 16px !important;
    }

    .overlay-bg{
      position: absolute !important;
      background-color: rgba(255,255,255,0.3) !important;
      width: 100%;
      height: 100%;
      top: 0;
      z-index: 1;
      transition:all .15s ease-in;
      opacity: 0;
    }
	.product-card{
		position:relative;
		margin: 0 auto;
		transition:all .15s ease-in !important;
	}

    .btn-container{
        position:absolute;
        bottom:40%;
        left:0;
        right:0;
        background-color:rgba(0,0,0,0);
        overflow:hidden;
        width:100%;
        height:0;
        transition:.5s;
        display:flex;
        justify-content:center;
        align-items:center
    }
		
    .hover-container:hover .btn-container{height:50px}

	.view-products-btn{
		z-index: 2;
		text-align: center;
		background-color: #0062A5;
		color:#fff;
		font-size:13px;
		letter-spacing:2px;
		text-transform:uppercase;
		padding:8px 20px;
		font-weight:400;
		transition:all .15s ease-in;
        position: absolute;
	}

	.view-products-btn:hover{
        background-color: #f8b878;
        color: black;
	}

	.product-card:hover .overlay-bg{
		transition:all .15s ease-in !important;
		opacity: 1 !important;
	}

    .hover{
      transition: .5s;
    }

    .hover:hover, .hover-container:hover img {
      -ms-transform: scale(0.95); /* IE 9 */
      -webkit-transform: scale(0.95); /* Safari 3-8 */
      transform: scale(0.95);
    }

    .products-head {
        margin-top: 10px !important;
        padding-left: 40px !important;
        padding-right: 40px !important;
    }
    .he1 {
        font-weight: 300 !important;
        font-size: 12px !important;
    }
    .he2 {
        font-weight: 200 !important;
        font-size: 10px !important;
    }
    .he1x {
        font-weight: 600 !important;
        font-size: 14px !important;
    }
    .he2x {
        font-weight: 200 !important;
        font-size: 12px !important;
    }
    .he2x2 {
        font-weight: 500 !important;
        font-size: 12px !important;
    }
    .btmp {
        margin-bottom: 15px !important;
    }
    .tbls {
        padding-bottom: 25px !important;
        padding-top: 25px !important;
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
    #custom-overlay {
		background: #ffffff;
		color: #666666;
		position: fixed;
		height: 100%;
		width: 100%;
		z-index: 5000;
		top: 0;
		left: 0;
		float: left;
		text-align: center;
		padding-top: 25%;
		opacity: .80;
	}
	.custom-spinner {
		margin: 0 auto;
		height: 64px;
		width: 64px;
		animation: rotate 0.8s infinite linear;
		border: 5px solid firebrick;
		border-right-color: transparent;
		border-radius: 50%;
	}
    .clear-btn{
        border: none;
        background-color: rgba(0,0,0,0);
    }
    .slick-slide{margin:0 20px}.slick-slide img{width:100%}.slick-slide{transition:all ease-in-out .3s;opacity:.2}.slick-active,.slick-active i{opacity:1;color:#0062A5!important}.slick-current,.slick-slide{opacity:1}
	@keyframes rotate {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
    @media (max-width: 575.98px) {
        .tbl-qtr{
            width: 20% !important;
        }
        .tbl-half{
            width: 60% !important;
        }
        .breadcrumb{
            font-size: 8pt !important;
            font-weight: 500;
        }
        .font-responsive{
            font-size: 10pt !important;
        }
    }

    @media (max-width: 767.98px) {
        .tbl-qtr{
            width: 20% !important;
        }
        .tbl-half{
            width: 60% !important;
        }
        .breadcrumb{
            font-size: 8pt !important;
            font-weight: 500;
        }
        .font-responsive{
            font-size: 10pt !important;
        }
    }
</style>
@endsection

@section('script')
<script src="{{ asset('/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    $(document).ready(function() {
     

        updateTotal();
        $(document).on('click', '.quantity-left-minus', function(e){
            e.preventDefault();
            var row = $(this).closest('tr');
            var input_name = row.find('input[name="quantity[]"]').eq(0);
            var id = input_name.data('id');
            var max = input_name.attr('max');

            var res_input_name = row.find('input[name="res_quantity[]"]').eq(0);

            var current_qty = input_name.val();
            if (parseInt(current_qty) > 1) {
                current_qty--;
                input_name.val(current_qty);
                res_input_name.val(current_qty);
                updateAmount(row);
                updateCart('decrement', id, current_qty);
            }

            if (parseInt(current_qty) > parseInt(max)) {
                row.find('.stock-status').eq(0).removeClass('text-success').addClass('text-danger').text('Insufficient Stock');
            } else {
                row.find('.stock-status').eq(0).removeClass('text-danger').addClass('text-success').text('Available : ' + max);
            }
        });

        $(document).on('click', '.quantity-right-plus', function(e){
            e.preventDefault();
            var row = $(this).closest('tr');
            var input_name = row.find('input[name="quantity[]"]').eq(0);
            var max = input_name.attr('max');
            var id = input_name.data('id');

            var res_input_name = row.find('input[name="res_quantity[]"]').eq(0);

            var current_qty = input_name.val();
            if (parseInt(current_qty) < parseInt(max)) {
                current_qty++;
                input_name.val(current_qty);
                res_input_name.val(current_qty);
                updateAmount(row);
                updateCart('increment', id, current_qty);
            }

            if (parseInt(current_qty) > parseInt(max)) {
                row.find('.stock-status').eq(0).removeClass('text-success').addClass('text-danger').text('Insufficient Stock');
            } else {
                row.find('.stock-status').eq(0).removeClass('text-danger').addClass('text-success').text('Available : ' + max);
            }
        });

        $(document).on('change', 'input[name="shipping_fee"]', function(){
            updateTotal();
        });

        $(document).on('click', '.remove-from-cart-btn', function(e){
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
                    tr.closest("tr").remove();
                    var countTr = $('#cart-items tbody tr').length;
                    if(countTr < 1) {
                        tr = '<tr>' +
                            '<td style="border-bottom: 0; padding: 10px 0;" colspan="7">' +
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">Your cart is empty!</div>' +
                            '</td>' +
                        '</tr>';

                        $('#cart-items tbody').append(tr);
                        $('#ship_blk').hide();
                        $('#checkout-btn').attr('disabled', true);
                    }

                    updateTotal();
                }
            });
        });

        $('#checkout-btn').click(function(e){
            e.preventDefault();
            $('#custom-overlay').fadeIn();
            window.location.href = "{{ $action }}";
        });

        $('input[name="quantity[]"]').change(function(){
            var row = $(this).closest('tr');
            var input_name = row.find('input[name="quantity[]"]').eq(0);
            var id = input_name.data('id');
            var max = input_name.attr('max');

            var current_qty = input_name.val();
            var type = 'desk';

            if(parseInt($(this).val()) <= parseInt(max)){
                $('#checkout-btn').prop('disabled', false);
                updateAmount(row);
                updateCart('manual', id, current_qty);
            }else if(parseInt($(this).val()) > parseInt(max)){
                $('#checkout-btn').prop('disabled', true);
                $('#stockLimitModal').modal('show');
            }
        });

        $('.mobile-quantity').change(function(){
            console.log($(this).val());
            var row = $(this).closest('tr');
            var input_name = row.find('.mobile-quantity').eq(0);
            var id = input_name.data('id');
            var max = input_name.attr('max');

            var current_qty = $(this).val();
            var type = 'mobile'

            if(parseInt($(this).val()) <= parseInt(max)){
                $('#checkout-btn').prop('disabled', false);
                updateAmount(row, type);
                updateCart('manual', id, current_qty);
            }else if(parseInt($(this).val()) > parseInt(max)){
                $('#checkout-btn').prop('disabled', true);
                $('#stockLimitModal').modal('show');
            }
        });


        function updateTotal() {
            var subtotal = 0;
            $('#cart-items tbody tr').each(function(){
                var amount = $(this).find('.amount').eq(0).text();
                subtotal += parseFloat(amount);
            });

            var shipping_fee = $("input[name='shipping_fee']:checked").val();
            var total = parseFloat(shipping_fee) + subtotal;

            total = (isNaN(total)) ? 0 : total;
            subtotal = (isNaN(subtotal)) ? 0 : subtotal;

            $('#cart-subtotal').text('P ' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
            $('#grand-total').text('P ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
        }

        function updateAmount(row, type) {
            var price = row.find('.price').eq(0).text();
            if(type == 'mobile'){
                var qty = row.find('input[name="res_quantity[]"]').eq(0).val();
            }else{
                var qty = row.find('input[name="quantity[]"]').eq(0).val();
            }
            var amount = (price * qty).toFixed(2);

            row.find('.amount').eq(0).text(amount);
            row.find('.formatted-amount').eq(0).text(parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
            updateTotal();
        }

        function updateCart(type, id, quantity) {
            var data = {
                'id': id,
                'quantity': quantity,
                '_token': "{{ csrf_token() }}",
                'type': type
            }

            $.ajax({
                type:'PATCH',
                url:'/updatecart',
                data: data,
            });
        }

        // Product Image Hover
        $('.hover-container').hover(function(){
            $(this).children('.btn-container').slideToggle('fast');
        });
    });
</script>
@endsection
