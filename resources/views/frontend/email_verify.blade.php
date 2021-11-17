@extends('frontend.layout', [
  'namePage' => 'Verify your Email Address',
  'activePage' => 'email_verify'
])

@section('content')
<main style="background-color:#0062A5;">
	<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active" style="height: 13rem !important;">
				<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
				<div class="container">
					<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
						<h3 class="carousel-header-font text-center">VERIFY EMAIL ADDRESS</h3>
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
                        <p class="card-text m-4">An email has been sent to <b>{{ session()->get('email') }}</b>. Please check your email for a verification link.<br>If you did not receive the email then <a href="/resend_verification/{{ session()->get('email') }}">resend the verification email</a>.</p>

                        @if (session('resend'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Email has been resent
                        </div>
                        @endif
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')

@endsection
