@extends('frontend.layout', [
    'namePage' => 'Forgot Password',
    'activePage' => 'forgot_password'
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
              <h3 class="carousel-header-font" style="text-transform: uppercase;">Choose Verification Method</h3>
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
      <div class="col-md-6 m-5">
        @if (!request('username'))
          <div class="container-fluid">
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
            {{-- <form method="POST" action="{{ route('password.email') }}"> --}}
            <form method="get" action="{{ route('password.reset_options') }}">
              {{-- @csrf --}}
              <div class="form-group">
                <input id="email" type="email" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="email" autofocus placeholder="Email Address">
                @error('username')
                <span class="invalid-feedback" style="margin-top: 12px; display: block;" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <label for="email" style="font-size: 0.75rem; display: block; margin-top: 10px;">{{ __("Please enter your registered email address.") }}</label>
                <button type="submit" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp" style="display: block !impportant; width: 100%;">Reset Password</button>
              </div>
            </form>
          </div>
        @else
          <div class="container-fluid">
            <form action="{{ route('password.email') }}" method="post" id="reset-form">
              @csrf
              <div class="form-check p-2">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="reset-link" id="email-reset">Send password reset link to {{ $info_arr['email'] }}
                  <input type="text" name="username" value="{{ $info_arr['email'] }}" readonly hidden>
                </label>
              </div>
              @if ($info_arr['phone'])
                <div class="form-check p-2">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="otp" id="otp-reset">Send an OTP to {{ substr($info_arr['phone'], 0, 2).'*****'.substr($info_arr['phone'], 7) }}
                    <input type="text" name="phone" value="{{ $info_arr['phone'] }}" readonly hidden>
                  </label>
                </div>
              @endif
              <br/>
              <button type="submit" class="btn btn-primary w-100" id="submit-btn">Proceed</button>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>
</main>
@endsection

@section('script')
<script>
  $(document).ready(function(){
    if(!$('#email-reset').is(":checked") && !$('#otp-reset').is(":checked")){
      $('#submit-btn').prop('disabled', true);
    }

    $('#email-reset').click(function(){
      if($('#email-reset:checked')){
        $('#otp-reset').prop('checked', false);
        $('#submit-btn').prop('disabled', false);
      }
    });

    $('#otp-reset').click(function(){
      if($('#otp-reset:checked')){
        $('#email-reset').prop('checked', false);
        $('#submit-btn').prop('disabled', false);
      }
    });
  });
</script>
@endsection
