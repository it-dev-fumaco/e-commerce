@php
    $col = '4';
@endphp
@forelse ($products_arr as $loop_count => $item)
    @include('frontend.product_details_card')
@empty
    <h4 class="text-center text-muted p-5 text-uppercase">No products found</h4>
@endforelse
<div class="container" style="max-width: 100% !important;">
    <div class="row" id="products-list-pagination">
        {{ $products->withQueryString()->links('frontend.product_pagination') }}
    </div>
</div>