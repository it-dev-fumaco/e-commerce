@extends('frontend.layout', [
  'namePage' => 'Search Results',
  'activePage' => 'search_result'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 17rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
				<div class="container">
				
							<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
								<div class="row justify-content-md-center">
									<div class="col-md-8">
								<h3 class="carousel-header-font text-center"><b>{{ $results->total() }} result(s) found</b></h3>
								<form action="/" method="GET">
								<div class="input-group mb-3">
									<input type="text" class="form-control" placeholder="Search" name="s" value="{{ request()->s }}">
									<div class="input-group-append">
									  <button class="btn btn-outline-secondary btn-light rounded-right" type="submit"><i class="fas fa-search"></i></button>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<main style="background-color:#ffffff; min-height: 600px; width: 100% !important" class="products-head">
	<div class="container">
		@if(request()->s != null)
		<div class="row">
			<div class="col-md-6 mt-4 mb-2">
				<h4><b>Search Results</b></h4>
				<small>Showing {{ $results->firstItem() . ' - ' . $results->lastItem() }} out of {{ $results->total() }}</small>
			</div>
			<div class="col-md-6 mt-4 mb-2">
				<div class="d-flex  justify-content-end">
					<div class="p-2"><label class="mt-1 mb-1 mr-0" style="font-size: 0.75rem;">Sort By</label></div>
					<div class="p-2">
						<select name="sortby" class="form-control form-control-sm" style="font-size: 0.75rem; display: inline-block;">
							<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
							<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
							<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
						</select></div>
					<div class="p-2" style="font-size: 1.3rem;">
						@if ((request()->order == 'desc'))
						<a href="{{ request()->fullUrlWithQuery(['order' => 'asc']) }}">
							<i class="fas fa-sort-amount-down-alt"></i>
						</a>
						@else
						<a href="{{ request()->fullUrlWithQuery(['order' => 'desc']) }}">
							<i class="fas fa-sort-amount-up-alt"></i>
						</a>
						@endif
					</div>
				  </div>
				{{-- <div class="row mb-2">
					<div class="col-md-9 pr-1" style="text-align: right;">
						<label class="mt-1 mb-1 mr-0" style="font-size: 0.75rem;">Sort By</label>
					</div>
					<div class="col-md-3" style="padding-left: 0;">
						<select name="sortby" class="form-control form-control-sm">
							<option value="Position" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Position']) }}" {{ (request()->sortby == 'Position') ? 'selected' : '' }}>Recommended</option>
							<option value="Product Name" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Product Name']) }}" {{ (request()->sortby == 'Product Name') ? 'selected' : '' }}>Product Name</option>
							<option value="Price" data-loc="{{ request()->fullUrlWithQuery(['sortby' => 'Price']) }}" {{ (request()->sortby == 'Price') ? 'selected' : '' }}>Price</option>
						</select>
					</div>
				</div> --}}
			</div>		
		</div>
		@endif
		@if (count($results) < 1)
			<h4 class="text-center text-muted mt-5 text-uppercase">No search result(s) found</h4>
		@endif
		@if(count($products) > 0)
		<div class="row">
			<div class="col-12 text-center">
				<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">{{ request()->s == null ? 'FEATURED PRODUCT(S)' : 'PRODUCT(S)' }}</h4>
			</div>
			@foreach ($products as $product)
			<div class="col-md-3 animated animatedFadeInUp fadeInUp equal-height-columns">
				<div class="card mb-4">
					<div class="equal-column-content">
						@php
						$image = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.$product['image'] : '/storage/no-photo-available.png';
						$image_webp = ($product['image']) ? '/storage/item_images/'.$product['item_code'].'/gallery/preview/'.explode(".", $product['image'])[0] .'.webp' : '/storage/no-photo-available.png';
						@endphp              
						<picture>
							<source srcset="{{ asset($image_webp) }}" type="image/webp" class="card-img-top">
							<source srcset="{{ asset($image) }}" type="image/jpeg" class="card-img-top"> 
							<img src="{{ asset($image) }}" alt="{{ $product['item_code'] }}" class="card-img-top">
						</picture>
						<div class="card-body">
							<div class="text ellipsis">
								<p class="card-text product-head fumacoFont_card_title text-concat prod_desc" style="color:#0062A5 !important;  min-height: 98px; font-weight: 500 !important">{{ $product['item_name'] }}</p>
							</div>
							<p class="card-text fumacoFont_card_price" style="color:#000000 !important;">
								@if($product['is_discounted'])
								₱ {{ number_format(str_replace(",","",$product['discounted_price']), 2) }} <s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$product['original_price']), 2) }}</s> <span class="badge badge-danger" style="vertical-align: middle;background-color: red; display: {{ ($product['on_sale']) ? 'inline' : 'none' }} !important;">{{ $product['discount_percent'] }}% OFF</span>
								@else
								₱ {{ number_format(str_replace(",","",$product['original_price']), 2) }}
								@endif
							</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group stylecap">
									<span class="fa fa-star starcolorgrey"></span>
									<span class="fa fa-star starcolorgrey"></span>
									<span class="fa fa-star starcolorgrey"></span>
									<span class="fa fa-star starcolorgrey"></span>
									<span class="fa fa-star starcolorgrey"></span>
								</div>
								<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( 0 Reviews )</small>
							</div>
						</div>
						<div class="card-body">
							<a href="/product/{{ $product['slug'] ? $product['slug'] : $product['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		@endif
		@if(count($blogs) > 0)
		<div class="row">
			<div class="col-12 text-center">
				<h4 class="mt-4 mb-3 fw-light bestsellinghead fumacoFont1 animated animatedFadeInUp fadeInUp" style="color:#000000 !important;">BLOG(S)</h4>
			</div>
			@foreach($blogs as $blog)
			<div class="col-lg-4 d-flex align-items-stretch animated animatedFadeInUp fadeInUp">
				<div class="card mb-4" style="border: 0px solid rgba(0, 0, 0, 0.125) !important;">
					<img class="card-img-top" src="{{ asset('/assets/journal/'. $blog['image']) }}" alt="">
					<div class="card-body align-items-stretch p-2">
						{{-- <p style="color:#000 !important; font-size: 10pt !important; font-weight: 300;" class="abt_standard m-0">{{ $blog['publish_date'] }} | {{ $blog['comment_count'] }} Comment(s)</p> --}}
						<a href="blog?id={{ $blog['id'] }}" style="text-decoration: none !important;">
							<p style="color:#373b3e !important;" class="abt_standard fumacoFont_card_title">{{ $blog['title'] }}</p>
						</a>
						<div class="blog-text ellipsis">
                            <p class="blog-text-concat abt_standard">{{ $blog['caption'] }}</p>
                          </div>
						
                          <a href="/blog?id={{ $blog['id'] }}" class="text-concat mx-auto read-more">Read More</a>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div style="float: right;">
					{{ $results->withQueryString()->links('pagination::bootstrap-4') }}
				</div>
			</div>
		</div>
  	</div>
</main>
<style>
	
</style>
@endsection

@section('style')
<style>
    .text {
      position: relative;
      font-size: 16px;
      width: 100%;
    }
	.blog-text {
      position: relative;
      font-size: 12px !important;
      width: 100%;
    }

    .text-concat {
		position: relative;
		display: inline-block;
		word-wrap: break-word;
		overflow: hidden;
		max-height: 5.6em;
		line-height: 1.5em;
		text-align: left;
		font-size: 16px !important;
	}

	.blog-text-concat {
		position: relative;
		display: inline-block;
		word-wrap: break-word;
		overflow: hidden;
		max-height: 4.8em;
		line-height: 1.5em;
		text-align: left;
		font-weight: 300 !important;
		color:#404040 !important;
	}

    .text.ellipsis::after {
      position: absolute;
      right: -12px; 
      bottom: 4px;
    }
	.read-more{
		text-transform: none !important;
		text-decoration: none !important;
		font-size: 12px !important;
		color: #000 !important;
		border-bottom: 1px solid #404040;
	}
	.abt_standard{
		font-family: 'poppins', sans-serif !important;
		text-decoration: none !important;
	}
	.prod_desc{
        font-size: 16px !important;
        font-weight: 500 !important;
        text-align: left !important;
    }
</style>
@endsection

@section('script')
<script>
  (function() {
   $(document).on('change', 'select[name="sortby"]', function(){
		window.location.href = $(this).find(':selected').data('loc');
   });

   $(document).on('change', 'select[name="order"]', function(){
		window.location.href = $(this).find(':selected').data('loc');
   });

  })();

</script>
@endsection
