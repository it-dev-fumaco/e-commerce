@extends('frontend.layout', [
    'namePage' => 'Reset Password',
    'activePage' => 'reset_password'
])

@section('content')
<main style="background-color:#0062A5;">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="height: 13rem !important;">
        <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
        <div class="container">
          <div class="carousel-caption text-start"
            style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
            <center>
              <h3 class="carousel-header-font">RESET PASSWORD</h3>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 m-5">
                @if (session('message'))
                <div class="alert alert-success" role="alert">
                  {{ session('message') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                  {{ session('error') }}
                </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group mb-3">
                        <input id="email" type="email" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $email ?? old('username') }}" required autocomplete="email" autofocus placeholder="Email Address">
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
                    </div>
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp" style="display: block !impportant; width: 100%;">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
