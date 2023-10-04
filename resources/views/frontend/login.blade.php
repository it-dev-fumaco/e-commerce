@extends('frontend.layout', [
  'namePage' => 'Login',
  'activePage' => 'login'
])

@section('content')
@php
    $page_title = 'SIGN IN';
@endphp
@include('frontend.header')

<main style="background-color:#ffffff;" class="products-head">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
              <br>
                <div id="logreg-forms" style="box-shadow: unset !important;">
                    

                    <form class="form-signin" action="/login" method="POST" style="max-width: 600px !important; border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                        @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="login-fb"></div>
                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! session()->get('error') !!}
                        </div>
                    @endif
                    @if (session()->has('resend'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        Email has been resent
                    </div>
                    @endif
                        @csrf
                        <h4 style="color: #404040; border-bottom: 2px solid  #e59866 ; padding-bottom: 8px; text-align: center;">Sign In</h4>
                        <center>
                        <div class="col-lg-7" style="text-align: left;">
                            <br>
                            <label for="InputUsername" class="login_1" style="padding-bottom:10px;">Email address <span class="text-danger">*</span><br></label>
                            <label for="InputUsername" class="login_1">&nbsp;</label>
                            <br>

                            <input type="email" id="username" name="username" class="form-control" required="" autofocus="" value="{{ old('username') }}">
                            <br>
                            <label for="InputPassword" class="login_1" style="padding-bottom:10px;">Password <span class="text-danger">*</span></label>
                            <label for="InputPassword" class="login_1">&nbsp;</label>
                            <br>

                            <input type="password" id="password" name="password" class="form-control" required="">

                            <br>
                            <input type="submit" class="btn btn-primary" style="display: block; width: 100%;" value="LOGIN">
                            
                            <a href="{{ route('password.request') }}" id="forgot_pswdx" class="forgot-1">Forgot password?</a>
                            <p style=" font-size: 1rem !important; margin-top: 12px;">
                                <span style="display: inline-block; color:  #616a6b ">New member? </span> <a href="/signup" class="forgot-1" style="display: inline-block; font-size: 1rem !important;">Create new account.</a>
                            </p>
                            <hr>
                            <small class="text-muted"> or sign in with</small>
                            <div class="effect">
                              <div class="buttons">checkLoginState
                                {{-- <a href="#" class="fb" title="Sign in with Facebook" onclick="triggerLogin();"><i class="fa fa-facebook" aria-hidden="true"></i></a> --}}
                                <a href="#" class="fb" title="Sign in with Facebook" onclick="checkLoginState();"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                {{-- <a href="/login/facebook" class="fb" title="Sign in with Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a> --}}
                                <a href="{{ route('google.login') }}" class="g-plus" title="Sign in with Google">
                                  <img src="{{ asset('assets/google.svg') }}" width="25">
                                </a>
                                <a href="{{ route('linkedin.login') }}" class="in" title="Sign in with Linked In"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                {{-- <a href="#" class="tw" title="Sign in with Apple"><i class="fab fa-apple" aria-hidden="true"></i></a> --}}
                              </div>
                            </div>
                            <label for="InputPassword" class="status-1"></label>
                          </div>
                      </center>
                  </form>
                  <form action="/reset/password/" class="form-reset" style="border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                      <input type="email" id="resetEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                      <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                      <a href="#" id="cancel_reset"><i class="fas fa-angle-left"></i> Back</a>
                  </form>
                  <br>
              </div>
          </div>
      </div>
  </div>
</main>

<main style="background-color:#ffffff;">
  <br>
  <br>
  <br>
</main>

<main style="background-color:#ffffff;">
  <br>
  <br>
  <br>
</main>

<main style="background-color:#ffffff;">
  <br>
  <br>
  <br>
</main>

<main style="background-color:#ffffff;">
  <br>
  <br>
  <br>
</main>

<style>
.effect {
  width: 100%;
}
.effect .buttons {
  display: flex;
  justify-content: center;
}
.effect a {
  text-align: center;
  margin: 3px 8px;
  text-decoration: none !important;
  color: white !important;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  font-size: 20px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 0 7px 0 #404040;
}
.effect a i {
  position: relative;
  z-index: 3;
}
.effect a.fb {
  background-color: #3b5998;
}
.effect a.tw {
  background-color: #aeb5c5;
}
.effect a.g-plus {
  background-color: #fff;
}
.effect a.in {
  background-color: #007bb6;
}
</style>
@endsection

@section('script')
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
@endsection

@section('style')
<style>
  .reg_link{
      font-size: 9pt;
      white-space: nowrap !important;
      display: inline-block !important;
  }

  /* sign in FORM */
  #logreg-forms{

      margin:0vh auto;
      background-color:#ffffff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    transition: all 0.3s cubic-bezier(.25,.8,.25,1);
  }
  #logreg-forms form {
      width: 100%;
      max-width: 410px;
      padding: 15px;
      margin: auto;
  }
  #logreg-forms .form-control {
      position: relative;
      box-sizing: border-box;
      height: auto;
      padding: 10px;
      font-size: 16px;
  }
  #logreg-forms .form-control:focus { z-index: 2; }
  #logreg-forms .form-signin input[type="email"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
  }
  #logreg-forms .form-signin input[type="password"] {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
  }

  #logreg-forms .social-login{
      width:390px;
      margin:0 auto;
      margin-bottom: 14px;
  }
  #logreg-forms .social-btn{
      font-weight: 100;
      color:white;
      width:190px;
      font-size: 0.9rem;
  }

  #logreg-forms a{
      display: block;
      padding-top:10px;
      color:lightseagreen;
      /* text-decoration: none !important;
      text-transform: none !important;
      color: #000; */
  }

  #logreg-form .lines{
      width:200px;
      border:1px solid red;
  }

  .btn-linkedin {
	  background: #0E76A8;
	}
	.btn-linkedin:link, .btn-linkedin:visited {
	  color: #fff;
	}
	.btn-linkedin:active, .btn-linkedin:hover {
	  background: #084461;
	  color: #fff;
	}

  #logreg-forms button[type="submit"]{ margin-top:10px; }

  #logreg-forms .facebook-btn{  background-color:#3C589C; }

  #logreg-forms .google-btn{ background-color: #DF4B3B; }

  #logreg-forms .form-reset, #logreg-forms .form-signup{ display: none; }

  #logreg-forms .form-signup .social-btn{ width:210px; }

  #logreg-forms .form-signup input { margin-bottom: 2px;}

  .form-signup .social-login{
      width:210px !important;
      margin: 0 auto;
  }

  /* Mobile */

  @media screen and (max-width:500px){
      #logreg-forms  .social-login{
          width:200px;
          margin:0 auto;
          margin-bottom: 10px;
      }
      #logreg-forms  .social-btn{
          font-size: 1.3rem;
          font-weight: 100;
          color:white;
          width:200px;
          height: 56px;

      }
      #logreg-forms .social-btn:nth-child(1){
          margin-bottom: 5px;
      }
      #logreg-forms .social-btn span{
          display: none;
      }
      #logreg-forms  .facebook-btn:after{
          content:'Facebook';
      }

      #logreg-forms  .google-btn:after{
          content:'Google+';
      }

  }

  .products-head {
    margin-top: 10px !important;
    padding-left: 40px !important;
    padding-right: 40px !important;
    }
    .he1 {
      font-weight: 300 !important;
      font-size: 12px !important;
    }
    .he2 {
      font-weight: 200 !important;
      font-size: 10px !important;
    }
    .btmp {
          margin-bottom: 15px !important;
    }

    .caption_1 {
      font-weight: 400 !important;
      font-size: 14px !important;
    }

    .caption_2 {
      font-weight: 200 !important;
      font-size: 10px !important;
    }



    .order-font {

      font-weight: 200 !important;
      font-size: 14px !important;


      }


      .order-font-sub {

        font-weight: 200 !important;
        font-size: 10px !important;


        }



        .order-font-sub-b {

          font-weight: 300 !important;
          font-size: 14px !important;


          }

          .tbls{

            vertical-align: center !important;

          }


          .login_1 {
            font-weight: 500 !important;
            font-size: 13px !important;
          }


          .login_2 {
            font-weight: 400 !important;
            font-size: 14px !important;
            color: #655f5f !important;
          }


          .form-signin .active {
            border-bottom: 3px solid #dc6f12;
          }


          .forgot-1 {
            font-weight: 500 !important;
            font-size: 13px !important;
            color: #655f5f !important;
          }


          .status-1{
            font-weight: 200 !important;
            font-size: 16px !important;
            color: red !important;
          }


    </style>
@endsection
