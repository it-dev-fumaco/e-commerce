@extends('frontend.layout', [
    'namePage' => 'Transaction Failed',
    'activePage' => 'checkout_failed'
])

@section('content')
@php
	$page_title = 'Transaction Failed';
@endphp
@include('frontend.header')

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
