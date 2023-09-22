<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Fumaco CMS | Verify OTP</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{ asset('/assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="{{ asset('/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('/assets/admin/dist/css/adminlte.min.css') }}">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            #resend{
                border: none !important;
                background-color: rgba(0,0,0,0) !important;
            }
            a, button, input:focus, *:focus{
                outline: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }
        </style>
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo mb-4">
				<a href="/admin">
					<img src="{{ asset('/assets/admin/logo-md.png') }}" alt="Fumaco" class="img-responsive">
				</a>
			</div>
            @if(session()->has('error'))
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger fade show text-center" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    </div>
                </div>
            @endif
			<div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">OTP has been sent to your mobile</p>
					<form action="/admin/verify_otp" method="POST" autocomplete="off">
						@csrf
						<div class="input-group mb-3">
							<input type="text" name="otp" class="form-control" placeholder="OTP" value="" required>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fa fa-mobile-phone" style="font-size: 24px"></span>
								</div>
							</div>
						</div>
                        <input type="text" name="user_id" value="{{ $user_id }}" hidden readonly>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
					</form>
                    <br>
                    <span class="pt-3">Didn't get the code? <a href="#" class="resend text-primary" style="cursor: pointer;text-transform: none; text-decoration: none;" data-channel="sms">Resend</a>&nbsp;<span id="countdown" class="d-none"></span></span><br>
                    <span><a href="#" data-toggle="modal" data-target="#sendAuthToEmailModal" style="text-transform: none; text-decoration: none;" data-channel="email">Resend via Email</a></span>
                    <p id="resend-error" class="text-danger d-none">An error occured, please try again.</p>
				</div>
			</div>
		</div>
        <!-- Modal -->
        <div class="modal fade" id="sendAuthToEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Resend Verification Code</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $email_arr = explode('@', Auth::user()->username);
                            $email = substr(Auth::user()->username, 0, 2).str_repeat("*", strlen($email_arr[0]) - 2).'@'.$email_arr[1];
                        @endphp
                        <span>Email verification code to {{ $email }}?</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary resend" data-channel="email">
                            <span class="email-text">Confirm</span>
                            <div class="spinner-border spinner-border-sm d-none spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
                const resendTimer = (duration, display) => {
                    var timer = duration, minutes, seconds;
                    setInterval(() => {
                        minutes = parseInt(timer / 60, 10)
                        seconds = parseInt(timer % 60, 10);
                        localStorage['time'] = (minutes > 0 ? (minutes * 60) : 0) + seconds

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        seconds = seconds < 10 ? "0" + seconds : seconds;

                        display.textContent = '(' + minutes + ":" + seconds + ')';

                        if (--timer < 0) {
                            timer = duration;
                            $('.resend').removeClass('text-secondary').addClass('text-primary');
                            $('#countdown').addClass('d-none');
                            localStorage['resend'] = 0;
                            localStorage['time'] = 1 * 60
                        }
                    }, 1000);
                }

                if('resend' in localStorage && localStorage['resend'] > 0){
                    $('#countdown').removeClass('d-none');
                    $('.resend').addClass('text-secondary').removeClass('text-primary');
                    resendTimer(localStorage['time'], document.querySelector('#countdown'))
                }

                $('.resend').click(function(){
                    var channel = $(this).data('channel');
                    var btn = $(this);
                    if(channel == 'sms'){
                        $(this).removeClass('text-primary').addClass('text-secondary');
                        $('#countdown').removeClass('d-none');
                        $('#resend-error').addClass('d-none');
                    }else{
                        $('.email-text').addClass('d-none');
                        $('.spinner').removeClass('d-none');
                    }

                    var sms_sent = 'resend' in localStorage ? localStorage['resend'] : 0;
                    if(channel == 'sms' && sms_sent > 0){
                        return false;
                    }

                    $.ajax({
                        type:"GET",
                        url:"/admin/resend_otp?channel=" + channel,
                        success:function(response){
                            if(response.status == 1){
                                if (channel == 'sms') {
                                    localStorage['resend'] = 1
                                    $('#countdown').removeClass('d-none');
                                    resendTimer(1 * 60, document.querySelector('#countdown'))
                                }
                            }else{
                                $('#resend-error').addClass('d-none');
                                if (channel == 'sms') {
                                    btn.removeClass('text-secondary').addClass('text-primary');
                                    btn.prop('disabled', false);
                                    $('#countdown').addClass('d-none');
                                    localStorage['resend'] = 0;
                                }
                            }
                            $('.email-text').removeClass('d-none');
                            $('.spinner').addClass('d-none');
                            $('#sendAuthToEmailModal').modal('hide');
                        },
                        error : function(data) {
                            $('#resend-error').removeClass('d-none');
                            if(channel == 'sms'){
                                btn.removeClass('text-secondary').addClass('text-primary');
                                btn.prop('disabled', false);
                                $('#countdown').addClass('d-none');
                                localStorage['resend'] = 0;
                            }else{
                                $('.email-text').removeClass('d-none');
                                $('.spinner').addClass('d-none');
                                $('#sendAuthToEmailModal').modal('hide');
                            }
                        }
                    });
                });
            });
        </script>
	</body>
</html>