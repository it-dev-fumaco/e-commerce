@extends('frontend.layout', [
    'namePage' => 'Shopping Cart',
    'activePage' => 'cart'
])

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
                <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;">
                <div class="container">
                    <div class="carousel-caption text-start mx-auto" style="bottom: 1rem !important;">
                        <center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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
                            <th class="he1x d-none d-sm-table-cell"></th>
                            <th class="he1x d-none d-sm-table-cell">Total</th>
                            <th class="he1x"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart_arr as $cart)
                        <tr class="he2x2">
                            <td class="tbl-qtr">
                                <img src="{{ asset('/storage/item_images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="{{ Str::slug(explode(".", $cart['item_image'])[0], '-') }}" width="55" height="55">
                            </td>
                            <td class="tbls tbl-half" style="width:40% !important;"><a href="/product/{{ $cart['item_code'] }}" style="text-decoration: none !important; color: #000;">{{ $cart['item_description'] }}</a>
                                <br/>{{-- for mobile --}}
                            <span class="formatted-price d-md-none d-lg-none d-xl-none"><br/><b>P {{ number_format($cart['price'], 2, '.', ',') }}</b></span>
                            <br/><br/>
                            <span class="d-md-none d-lg-none d-xl-none">Quantity<br/>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-left-minus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> - </a>
                                    </span>
                                    <div>&nbsp;</div>
                                    <input type="text" name="res_quantity[]" class="form-control input-number " value="{{ $cart['quantity'] }}" min="1" max="{{ $cart['stock_qty'] }}" style="width: 5px !important; text-align: center !important;" data-id="{{ $cart['item_code'] }}">
                                    <div>&nbsp;</div>
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-right-plus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> + </a>
                                    </span>
                                </div>
                            </span>{{-- for mobile --}}
                            </td>
                            <td class="tbls d-none d-sm-table-cell"><p style="white-space: nowrap !important;">P <span class="formatted-price">{{ number_format($cart['price'], 2, '.', ',') }}</span></p><span class="price d-none">{{ $cart['price'] }}</span></td>
                            <td class="tbls d-none d-sm-table-cell text-center">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-left-minus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> - </a>
                                    </span>
                                    <div>&nbsp;</div>
                                    <input type="text" name="quantity[]" class="form-control input-number " value="{{ $cart['quantity'] }}" min="1" max="{{ $cart['stock_qty'] }}" style="width: 4px !important; text-align: center !important;" data-id="{{ $cart['item_code'] }}">
                                    <div>&nbsp;</div>
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-right-plus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> + </a>
                                    </span>
                                </div>
                                @if ($cart['insufficient_stock'])
                                <small class="text-danger d-block m-2 stock-status">Insufficient Stock</small>
                                @else
                                <small class="text-success d-block m-2 stock-status">Available :  {{ $cart['stock_qty'] }}</small>
                                @endif
                            </td>
                            <td class="tbls d-none d-sm-table-cell">&nbsp;</td>
                            <td class="tbls d-none d-sm-table-cell"><p style="white-space: nowrap !important;">P <span class="formatted-amount">{{ number_format($cart['amount'], 2, '.', ',') }}</span></p><span class="amount d-none">{{ $cart['amount'] }}</span></td>
                            <td class="tbls tbl-qtr">
                                <a class="btn btn-sm btn-outline-primary remove-from-cart-btn" href="#" role="button" data-id="{{ $cart['item_code'] }}">&#x2715;</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td style="border-bottom: 0; padding: 10px 0;" colspan="7">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">Your cart is empty!</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <table class="table">
                    <tr>
                        <td class="col-md-8">&nbsp</td>
                        <td class="col-md-2">Total</td>
                        <td><small class="text-muted stylecap he1x" id="cart-subtotal">P {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}</small></td>
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
                    <div class="col-md-6 d-none d-lg-block d-xl-block">
                        <div class="card-body col-md-8 mx-auto">
                            <a href="/" class="btn btn-secondary" style="width:100% !important;" role="button"><span style="font-size: 12pt; font-weight: 700">˂ </span> CONTINUE SHOPPING</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body col-md-8 mx-auto">
                            <button id="checkout-btn" class="btn btn-outline-primary" role="button" style="width:100% !important;" {{ (count($cart_arr) > 0) ? '' : 'disabled' }}>PROCEED TO CHECKOUT</button>
                        </div>
                    </div>
                    <div class="col-md-6 d-lg-none d-xl-none">
                        <div class="card-body col-md-8 mx-auto">
                            <a href="/" class="btn btn-secondary" style="width:100% !important;" role="button"><span style="font-size: 12pt; font-weight: 700">˂ </span> CONTINUE SHOPPING</a>
                        </div>
                    </div>
                </div>
                <br><br><br>
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

<style>
	
</style>
@endsection

@section('style')
<style>
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
    }
</style>
@endsection

@section('script')
<script>
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

        function updateAmount(row) {
            var price = row.find('.price').eq(0).text();
            var qty = row.find('input[name="quantity[]"]').eq(0).val();
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
    });
</script>
@endsection
