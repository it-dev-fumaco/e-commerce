@extends('frontend.layout', [
    'namePage' => 'Error Page',
    'activePage' => 'error_page'
])

@section('content')
@php
	$page_title = 'Server Error';
@endphp
@include('frontend.header')

<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
	<div class="container">
		<div class="row m-5">
			<div class="col-md-8 offset-md-2">
				<div class="row m-5">
					<div class="col-md-4">
						<h2 class="headline text-warning" style="font-size: 4rem; text-align: right;"> 500</h2>
					</div>
					<div class="col-md-8">
						<p style="color:#186EA9 !important; font-size:1.3rem !important; margin: 0;" class="fumacoFont_card_title animated animatedFadeInUp fadeInUp">Internal Server Error!</p>
						<p style="color:#58595A !important; font-size:0.95rem !important; margin: 0;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">Unfortunately we're having trouble loading the page you are looking for. Please come back in a while.</p>
                        <button onClick="window.location.reload();" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">Refresh Page</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection
