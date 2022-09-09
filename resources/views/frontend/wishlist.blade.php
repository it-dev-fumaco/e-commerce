@extends('frontend.layout', [
    'namePage' => 'My Wishlist',
    'activePage' => 'mywishlist'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
				<div class="container">
					<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<center><h3 class="carousel-header-font">WISHLIST</h3></center>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<main style="background-color:#ffffff; min-height: 600px;" class="products-head">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<br>
				<table class="table">
					<col style="width: 10%;">
					<col style="width: 60%;">
					<col style="width: 15%;">
					<col style="width: 15%;">
					<thead>
						<tr>
							<th class="order-font-sub-b">Products</th>
							<th class="order-font-sub-b">&nbsp;&nbsp;</th>
							<th class="order-font-sub-b text-center d-none d-sm-table-cell">Price</th>
							<th class="order-font-sub-b text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						@if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                        </div>
                    @endif

						  @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif

						@forelse ($wishlist_arr as $wishlist)
						<tr class="order-font">
							<td class="text-center">
								<img src="{{ asset('/storage/item_images/'.$wishlist['item_code'].'/gallery/preview/'.$wishlist['image']) }}" class="img-responsive" alt="" width="55" height="55">
							</td>
							<td class="tbls">{{ $wishlist['item_name'] }}<br/>&nbsp;
							<p class="d-lg-none d-xl-none"><b>Price:</b> P {{ number_format($wishlist['item_price'], 2) }}</p>
							</td>
							<td class="tbls text-center d-none d-sm-table-cell">P {{ number_format($wishlist['item_price'], 2) }}</td>
							<td class="tbls text-center">
								<a href="/product/{{ $wishlist['item_code'] }}" class="btn btn-success d-none d-lg-inline d-xl-inline" role="button" style="color: #fff; background-color: #1a6ea9; border-color: #1a6ea9; border-radius: 0rem;">View</a>&nbsp;
								<a href="/product/{{ $wishlist['item_code'] }}" class="btn btn-success d-lg-none d-xl-none" role="button" style="color: #fff; background-color: #1a6ea9; border-color: #1a6ea9; border-radius: 0rem;"><i class="fas fa-eye"></i></a>&nbsp;
								<button type="button" class="btn btn-danger rounded-0" data-toggle="modal" data-target="#rmw{{ $wishlist['wishlist_id'] }}">
									<i class="fas fa-trash-alt"></i>
								</button>
								<!-- Modal -->
								<div class="modal fade" id="rmw{{ $wishlist['wishlist_id'] }}" tabindex="-1" role="dialog" aria-labelledby="removeFromWishlistModal" aria-hidden="true">
									<form action="/mywishlist/{{ $wishlist['wishlist_id'] }}/delete" method="POST">
									@csrf
									@method('delete')
									<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
										<h5 class="modal-title" id="removeFromWishlistModal">Remove from Wishlist</h5>
										</div>
										<div class="modal-body">
										<p>Remove <b><i>{{ $wishlist['item_name'] }}</i></b> from your wishlist?</p>
										</div>
										<div class="modal-footer">
										<button type="submit" class="btn btn-primary">Confirm</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
									</div>
									</div>
								</form>
								</div>
							</td>
						</tr>
						@empty
						<tr>
							<td class="text-center border-bottom-0 text-muted p-3" colspan="4">Wishlist is empty.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				<div style="float: right;">
					{{ $wishlist_query->links('pagination::bootstrap-4') }}
				</div>
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
	.caption_1 {
		font-weight: 200 !important;
		font-size: 14px !important;
	}
	.caption_2, .he2, .order-font-sub{
		font-weight: 200 !important;
		font-size: 10px !important;
	}
	.order-font {
		font-weight: 500 !important;
		font-size: 14px !important;
	}
	.order-font-sub-b {
		font-weight: 300 !important;
		font-size: 14px !important;
	}
	.tbls{
		vertical-align: center !important;
	}
</style>
@endsection

