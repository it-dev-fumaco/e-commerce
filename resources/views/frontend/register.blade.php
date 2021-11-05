@extends('frontend.layout', [
  'namePage' => 'Register',
  'activePage' => 'signup'
])

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
            <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
                <div class="container">
                    <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                        <center><h3 class="carousel-header-font">SIGN UP</h3></center>
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
              

                    <form action="user_register" class="form-signup1" method="post" style="max-width: 600px !important; border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                        @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                        @csrf
                        <h4 style="color: #404040; border-bottom: 2px solid  #e59866 ; padding-bottom: 8px; text-align: center;">Sign Up</h4>
                        <br>
                        <center>
                            <div class="col-lg-7" style="text-align: left;">
                                <div class="row">
                                    <label for="mobile_1" class="myprofile-font-form login_1">First Name : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control caption_1" id="fname" name="first_name" required>
                                </div>
                                <br/>
                                <div class="row">
                                    <label for="mobile_1" class="myprofile-font-form login_1">Last Name : <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control caption_1" id="lname" name="last_name" required>
                                </div>
                                <br/>
                                <div class="row">
                                    <label class="login_1">Email address <span class="text-danger">*</span></label>
                                    <input type="email" name="username" id="username" class="form-control caption_1" value="" required>
                                    <span class="help-block"></span>
                                </div>
                                <br/>
                                <div class="row">
                                    <label class="login_1">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control caption_1" value="" required>
                                    <span class="help-block"></span>
                                </div>
                                <br/>
                                <div class="row">
                                    <label class="login_1">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" id="confirm_password"  class="form-control caption_1" value="" required>
                                    <span class="help-block"></span>
                                </div>
                                <br>
                                <div class="row">
                                    <p style="font-size: 9pt; display: inline-block"><input type="checkbox" name="subscribe"> &nbsp;Yes, I want to receive email updates and notifications.</p>
                                    <p class="reg_link" style=""><input type="checkbox" id="terms_checkbox"> I agree to <a href="/pages/privacy_policy" style="display: inline-block !important;">Privacy Policy</a> and <a href="/pages/terms_condition" style="display: inline-block !important;">Terms</a>.</p>
                                    <input type="submit" id="reg_btn" class="btn btn-primary" value="REGISTER" disabled><br/>
                                </div>
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

</style>
<style>

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


            .active {
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
<script>

    function toggleResetPswd(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle() // display:block or none
        $('#logreg-forms .form-reset').toggle() // display:block or none
    }

    function toggleSignUp(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle(); // display:block or none
        $('#logreg-forms .form-signup').toggle(); // display:block or none
    }

    $(()=>{
        // Login Register Form
        $('#logreg-forms #forgot_pswd').click(toggleResetPswd);
        $('#logreg-forms #cancel_reset').click(toggleResetPswd);
        $('#logreg-forms #btn-signup').click(toggleSignUp);
        $('#logreg-forms #cancel_signup').click(toggleSignUp);
    })

    $(document).ready(function() {
        $('#terms_checkbox').click(function() {
            if($(this).prop("checked") == false) {
                $("#reg_btn").prop('disabled',true);
            }else{
                $("#reg_btn").prop('disabled',false);
            }
        });
    });

	</script>
@endsection
