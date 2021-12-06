@extends('frontend.layout', [
  'namePage' => 'Login',
  'activePage' => 'login'
])

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
            <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
                <div class="container">
                    <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                        <center><h3 class="carousel-header-font">LOGIN</h3></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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
                        {{-- <ul class="nav nav-tabs">
                            <li class="nav-item active" style="width: 50%;">
                            <a class="nav-link" href="#"><center><span class="login_2">Sign in</span></center></a>
                            </li>

                            {{-- <li class="nav-item" style="width: 50%;">
                            <a class="nav-link" href="#" type="button" id="btn-signup"><center><span class="login_2">Register</span></center></a>
                            </li> --}}
                        {{-- </ul> --}}
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
                            <a href="{{ route('facebook.login') }}" class="btn mt-2 text-white align-middle" style="display: block; width: 100%; background-color:  #115cf3;">
                                <i class="fab fa-facebook mr-3" style="font-size: 1.3rem;"></i> <span style="font-size: 14px;">Sign in with Facebook</span> 
                            </a>
                            {{-- <a href="{{ route('google.login') }}" class="btn text-white mt-2" style="display: block; width: 100%; background-color:  #fffff;">
                                <i class="fab fa-google mr-2"></i> Login with Google
                            </a> --}}
                            {{-- <a href="{{ route('linkedin.login') }}" class="btn text-white mt-2" style="display: block; width: 100%; background-color: #0c6caf;">
                                <i class="fab fa-linkedin mr-2"></i> Login with LinkedIn
                            </a> --}} 

                            <a href="{{ route('google.login') }}" class="btn text-dark login-with-google-btn mt-2">
                                <img src="{{ asset('assets/google.svg') }}" width="23" class="m-1"> Sign in with Google
                            </a>
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

 .login-with-google-btn {
     display: block;
     width: 100%;
    transition: background-color 0.3s, box-shadow 0.3s;
    border: none;
    border-radius: 3px;
    box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.25);
    color: #757575;
    font-size: 14px;
    font-weight: 500;
    background-color: white;
  }
  .login-with-google-btn:hover {
    box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25);
  }
  .login-with-google-btn:active {
    background-color: #eeeeee;
  }
  .login-with-google-btn:focus {
    outline: none;
    box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25), 0 0 0 3px #c8dafc;
  }
 </style>

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
        #logreg-forms{

        }

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