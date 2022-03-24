@extends('frontend.layout', [
    'namePage' => 'OTP Form',
    'activePage' => 'otp_form'
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
              <h3 class="carousel-header-font">OTP Verification</h3>
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
            <div class="col-md-4 mx-auto">
                <div class="form-group mt-5">
                    @if(session()->has('error'))
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-danger fade show text-center" role="alert">
                                    {{ session()->get('error') }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="/password/verify_otp" method="post">
                        @csrf
                        <input type="text" name="username" id="email" value="{{ $email }}" hidden readonly>
                        <label>OTP is sent your Mobile Number</label><br><br>
                        <input type="text" class="form-control" name="otp" placeholder="OTP" required><br>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
                    </form>
                    <br>
                    <p>Didn't receive a code? <span id="resend-otp" style="cursor: pointer; color: #0062A5">Resend</span></p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('#resend-otp').click(function(){
                var data = {
                    '_token' : "{{ csrf_token() }}",
                    'username' : $('#email').val(),
                    'otp' : 'on'
                }
                $.ajax({
                    type:'post',
                    data: data,
                    url:'/password/email',
                    success: function (response) {
                        console.log('Success');
                    }
                });
            });
        });
    </script>
@endsection