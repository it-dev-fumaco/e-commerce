@extends('frontend.layout', [
    'namePage' => 'Shopping Cart',
    'activePage' => 'cart'
])

@section('content')
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
</style>

<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
                <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
                <div class="container">
                    <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                        <center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<main style="background-color:#ffffff;" class="products-head">
    <div class="container marketing">
        <br>
        <div class="row"></div>
    </div>
    <div class="container" style="max-width: 100% !important;">
        <div class="row">
            <div class="col-lg-8 animated animatedFadeInUp fadeInUp">
                <table class="table animated animatedFadeInUp fadeInUp" id="cart-items">
                    <thead>
                        <tr>
                            <th class="he1x">Product</th>
                            <th class="he1x"></th>
                            <th class="he1x">Price</th>
                            <th class="he1x">Quantity</th>
                            <th class="he1x"></th>
                            <th class="he1x">Total</th>
                            <th class="he1x"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart_arr as $cart)
                        <tr class="he2x2">
                            <td>
                                <img src="{{ asset('/item/images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="" width="55" height="55">
                            </td>
                            <td class="tbls" style="width:40% !important;">{{ $cart['item_description'] }}</td>
                            <td class="tbls">P <span class="formatted-price">{{ number_format($cart['price'], 2) }}</span><span class="price d-none">{{ $cart['price'] }}</span></td>
                            <td class="tbls">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-left-minus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> - </a>
                                    </span>
                                    <div>&nbsp;</div>
                                    <input type="text" name="quantity[]" class="form-control input-number " value="{{ $cart['quantity'] }}" min="1" max="10" style="width: 5px !important; text-align: center !important;" data-id="{{ $cart['item_code'] }}">
                                    <div>&nbsp;</div>
                                    <span class="input-group-btn">
                                        <a href="#" class="quantity-right-plus btn btn-number" style="background-color: #ccc !important; height: 100% !important; border-radius: 0px !important;"> + </a>
                                    </span>
                                </div>
                            </td>
                            <td class="tbls">&nbsp;</td>
                            <td class="tbls">P <span class="formatted-amount">{{ number_format($cart['amount'], 2) }}</span><span class="amount d-none">{{ $cart['amount'] }}</span></td>
                            <td class="tbls">
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
                <br><br><br>
            </div>
            <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
                <div class="card" style="background-color: #f4f4f4 !important; border-radius: 0rem !important;">
                    <div class="card-body he1x" style="padding-bottom: 0px !important;">Cart Total<hr></div>
                    <div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">P {{ number_format(collect($cart_arr)->sum('amount'), 2) }}</small>
                        </div>
                        <hr>
                    </div>
                    <div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">Shipping
                         <div class="form-check">
                             <label class="form-check-label" for="shipradio">&nbsp;</label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_fee" id="Free Shipping" value="0" data-name="Free Delivery" required checked>
                                    <label class="form-check-label" for="Free Shipping">Free Shipping</label>
                                </div>
                                <small class="text-muted stylecap he1x">P 0.00</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_fee" id="Standard" value="500" data-name="Standard Delivery" required>
                                    <label class="form-check-label" for="Standard">Standard</label>
                                </div>
                                <small class="text-muted stylecap he1x">P 500.00</small>
                            </div>
                            <hr>
                        </div>
                        <div class="card-body he1x">
                            <div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Total <small class="text-muted stylecap he1x" style="color:#FF9D00 !important;" id="grand-total">0.00</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <button id="checkout-btn" class="btn btn-outline-primary" role="button" style="width:100% !important;" {{ (count($cart_arr) > 0) ? '' : 'disabled' }}>PROCEED TO CHECKOUT</button>
                        </div>
                    </div>
                    <br>
                    <a href="/" class="btn btn-outline-primary" role="button" style="width:100% !important;">CONTINUE SHOPPING</a>
                </div>
                <div class="col-lg-12">&nbsp;&nbsp;</div>
            </div>
        </div>
    </main>
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
            
            var current_qty = input_name.val();
            if (current_qty > 1) {
                current_qty--;
                input_name.val(current_qty);
                updateAmount(row);
                updateCart('decrement', id, current_qty);
            }
        });

        $(document).on('click', '.quantity-right-plus', function(e){
            e.preventDefault();
            var row = $(this).closest('tr');
            var input_name = row.find('input[name="quantity[]"]').eq(0);
            var id = input_name.data('id');

            var current_qty = input_name.val();
            current_qty++;
            input_name.val(current_qty);
            updateAmount(row);
            updateCart('increment', id, current_qty);
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

                        $('#checkout-btn').attr('disabled', true);
                    }

                    updateTotal();
                }
            });
        });

        $('#checkout-btn').click(function(e){
            e.preventDefault();
            var data = {
                'shipping_fee': $("input[name='shipping_fee']:checked").val(),
                'shipping_name': $("input[name='shipping_fee']:checked").data('name'),
                '_token': "{{ csrf_token() }}",
            }

            $.ajax({
                type:'POST',
                url:'/addshipping',
                data: data,
                error: function () {
                    alert('An error occured.');
                }
            });
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

            $('#cart-subtotal').text('P ' + total.toFixed(2));
            $('#grand-total').text('P ' + total.toFixed(2));
        }

        function updateAmount(row) {
            var price = row.find('.price').eq(0).text();
            var qty = row.find('input[name="quantity[]"]').eq(0).val();
            var amount = (price * qty).toFixed(2);
            row.find('.amount').eq(0).text(amount);
            row.find('.formatted-amount').eq(0).text(amount);
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