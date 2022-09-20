@extends('frontend.layout', [
	'namePage' => 'View Order',
	'activePage' => 'view_order'
])

@section('content')
	@php
		$page_title = 'MY ORDERS';
	@endphp
	@include('frontend.header')
	<main style="background-color:#ffffff;" class="products-head">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<br><br>
					<center><h3>Order Details</h3></center>
					<br><br>
				</div>
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<a href="/myorders" class="btn btn-primary" role="button">BACK TO LIST TRANSACTION</a>
					<hr>
				</div>
				<div class="col-lg-12" style="padding-left: 15%; padding-right: 15%;">
					<table class="table">
						<thead>
							<tr style="font-size: 16px;">
								<th class="text-center">ORDER #</th>
								<th></th>
								<th class="text-center">ITEM DESCRIPTION</th>
								<th class="text-center">QTY</th>
								<th class="text-center">PRICE</th>
								<th class="text-center">TOTAL</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr style="font-size: 10pt;">
                        <td class="text-center">{{ $item['order_number'] }}</td>
						<td>
							@php
								$img = '/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image'];
								$webp = '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'.explode('.', $item['image'])[0].'.webp';
							@endphp
							<picture>
								<source srcset="{{ asset($webp) }}" type="image/webp">
								<source srcset="{{ asset($img) }}" type="image/jpeg">
								<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55" loading="lazy">
							</picture>
							{{-- <img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55"> --}}
                        </td>
                        <td>{{ $item['item_name'] }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-center">P {{ number_format($item['price'], 2) }}</td>
                        <td class="text-center">P {{ number_format($item['amount'], 2) }}</td>
                     </tr>
							@endforeach
						</tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>
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
	.btmp {
		margin-bottom: 15px !important;
	}
	.caption_1, .order-font {
		font-weight: 200 !important;
		font-size: 14px !important;
	}
	.caption_2, .he2, .order-font-sub {
		font-weight: 200 !important;
		font-size: 10px !important;
	}
	.order-font-sub-b {
		font-weight: 300 !important;
		font-size: 14px !important;
	}
</style>

@endsection
