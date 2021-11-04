@extends('frontend.layout', [
    'namePage' => 'Transaction Failed',
    'activePage' => 'checkout_failed'
])

@section('content')
<main style="background-color:#0062A5;">
  <div id="myCarousel" class="carous1el slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="height: 13rem !important;">
        <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
        <div class="container">
          <div class="carousel-caption text-start"
            style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
            <center>
              <h3 class="carousel-header-font">TRANSACTION FAILED</h3>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
	<div class="container">
		<div class="row m-5">
			<div class="col-md-8 offset-md-2">
				<div class="row m-5">
					
					<div class="col-md-12 text-center">
						<p style="color:#186EA9 !important; font-size:1.3rem !important; margin: 0;" class="fumacoFont_card_title animated animatedFadeInUp fadeInUp">Transaction Failed.</p>
						<p style="color:#58595A !important; font-size:0.95rem !important; margin: 0;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">Your transaction failed, please try again or contact site support.</p>

                            <a href="/" class="btn btn-primary mt-5 fumacoFont_btn animated animatedFadeInUp fadeInUp">RETURN TO HOMEPAGE</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection
