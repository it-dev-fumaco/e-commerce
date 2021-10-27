@extends('frontend.layout', [
  'namePage' => 'Newsletter Subscription',
  'activePage' => 'newsletter_subscription'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
				<div class="container">
					<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<h3 class="carousel-header-font text-center">THANK YOU</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
    <div class="container"><br/>&nbsp;
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card text-center" style="width: 100%">
                    <div class="card-body">
                        <h5 class="card-title">Thank You</h5>
                        <p class="card-text">Thank you for subscribing to our newsletter. You'll receive your first email within 24 hours.</p>
                        <br/>
                        <a href="/" class="btn btn-primary">RETURN TO HOMEPAGE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')

@endsection
