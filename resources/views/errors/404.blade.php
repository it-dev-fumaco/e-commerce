@extends('frontend.layout', [
    'namePage' => 'Error Page',
    'activePage' => 'error_page'
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
              <h3 class="carousel-header-font">PAGE NOT FOUND</h3>
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
					<div class="col-md-4">
						<h2 class="headline text-warning" style="font-size: 4rem; text-align: right;"> 404</h2>
					</div>
					<div class="col-md-8">
						<p style="color:#186EA9 !important; font-size:1.3rem !important; margin: 0;" class="fumacoFont_card_title animated animatedFadeInUp fadeInUp">Oops! Page not found.</p>
						<p style="color:#58595A !important; font-size:0.95rem !important; margin: 0;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">The page your are looking for might have been removed, <br> had its name changed or is temporarily unavailable.</p>
						<a href="/" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">RETURN TO HOMEPAGE</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection
