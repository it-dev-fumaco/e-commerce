@extends('frontend.layout', [
  'namePage' => 'Search Results',
  'activePage' => 'search_result'
])

@section('content')
<style>
    .text {
      position: relative;
      font-size: 14px;
      width: 100%;
    }

    .text-concat {
      position: relative;
      display: inline-block;
      word-wrap: break-word;
      overflow: hidden;
      max-height: 4.8em;
      line-height: 1.2em;
      text-align:justify;
    }

    .text.ellipsis::after {
      position: absolute;
      right: -12px; 
      bottom: 4px;
    }
</style>
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
				<div class="container">
					<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<h3 class="carousel-header-font text-center">SEARCH RESULT</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<main style="background-color:#ffffff; min-height: 600px;" class="products-head">
	<div class="container">
		@if(request()->s != null)
		<div class="row">
			<div class="col-md-12 mt-4 mb-2">
				<h5>Search result(s) for: <span style="font-style: italic;"><b>{{ request()->s }}</b></span></h5>
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
			<div class="col-md-4 animated animatedFadeInUp fadeInUp">
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
								<p class="card-text fumacoFont_card_title text-concat" style="color:#0062A5 !important; height: 80px;">{{ $product['item_name'] }}</p>
							</div>
							<p class="card-text fumacoFont_card_price" style="color:#000000 !important;">
								@if($product['is_discounted'])
								<s style="color: #c5c5c5;">₱ {{ number_format(str_replace(",","",$product['original_price']), 2) }}</s> ₱ {{ number_format(str_replace(",","",$product['discounted_price']), 2) }} <span class="badge badge-danger" style="vertical-align: middle;background-color: red; display: {{ ($product['on_sale']) ? 'inline' : 'none' }} !important;">{{ $product['discount_percent'] }}% OFF</span>
								@else
								₱ {{ number_format(str_replace(",","",$product['original_price']), 2) }}
								@endif
							</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="btn-group stylecap">
									<span class="fa fa-star checked starcolor"></span>
									<span class="fa fa-star checked starcolor"></span>
									<span class="fa fa-star checked starcolor"></span>
									<span class="fa fa-star starcolorgrey"></span>
									<span class="fa fa-star starcolorgrey"></span>
								</div>
								<small class="text-muted stylecap" style="color:#c4cad0 !important; font-weight:100 !important;">( 0 Reviews )</small>
							</div>
						</div>
						<div class="card-body">
							<a href="/product/{{ $product['item_code'] }}" class="btn btn-outline-primary fumacoFont_card_readmore" role="button" style="width:100% !important;">View</a>
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
						<p style="color:#000 !important; font-size: 10pt !important; font-weight: 300;" class="abt_standard m-0">{{ $blog['publish_date'] }} | {{ $blog['comment_count'] }} Comment(s)</p>
						<a href="blog?id={{ $blog['id'] }}" style="text-decoration: none !important;">
							<p style="color:#373b3e !important;" class="abt_standard fumacoFont_card_title">{{ $blog['title'] }}</p>
						</a>
						<a href="blog?id={{ $blog['id'] }}" class="text-decoration-none text-muted">Read More →</a>
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
@endsection

@section('script')

@endsection