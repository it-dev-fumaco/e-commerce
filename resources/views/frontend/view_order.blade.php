@extends('frontend.layout', [
	'namePage' => 'View Order',
	'activePage' => 'view_order'
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
		.btmp {
			margin-bottom: 15px !important;
		}
		.caption_1 {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.caption_2 {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.order-font {
			font-weight: 200 !important;
			font-size: 14px !important;
      }
      .order-font-sub {
			font-weight: 200 !important;
			font-size: 10px !important;
      }
		.order-font-sub-b {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
	</style>

	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">MY ORDERS</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
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
									<img src="{{ asset('/storage/item_images/'.$item['item_code'].'/gallery/preview/'.$item['image']) }}" class="img-responsive" alt="" width="55" height="55">
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

@section('script')

@endsection
