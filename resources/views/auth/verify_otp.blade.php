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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
									<span class="fa fa-mobile-phone" style="font-size: 25px"></span>
								</div>
							</div>
						</div>
                        <input type="text" name="user_id" value="{{ $user_id }}" hidden readonly>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
					</form>
                    <p class="pt-3">Didn't get the code? <span id="resend" style="color: #1E1E83; cursor: pointer">Resend</span>&nbsp;<span id="countdown" class="d-none"></span></p>
                    <p id="resend-error" style="color: red; display: none">An error occured, please try again.</p>
				</div>
			</div>
		</div>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
        <script>
            $(document).ready(function(){
                $('#resend').click(function(){
                    $(this).css('color', '#666666');
                    $('#countdown').removeClass('d-none');
                    $('#resend-error').css('display', 'none');

                    $.ajax({
                        type:"GET",
                        url:"/admin/resend_otp",
                        success:function(response){
                            console.log('OTP Sent');
                        },
                        error : function(data) {
                            $(this).css('color', '#1E1E83');
                            $('#countdown').addClass('d-none');
                            $('#resend-error').css('display', 'block');
                        }
                    });

                    var timer2 = "10:01";
                    var interval = setInterval(function() {
                        var timer = timer2.split(':');
                        //by parsing integer, I avoid all extra string processing
                        var minutes = parseInt(timer[0], 10);
                        var seconds = parseInt(timer[1], 10);
                        --seconds;
                        minutes = (seconds < 0) ? --minutes : minutes;
                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        $('#countdown').html("("+minutes + ':' + seconds+")");
                        if (minutes < 0) clearInterval(interval);
                        //check if both minutes and seconds are 0
                        if ((seconds <= 0) && (minutes <= 0)){
                            clearInterval(interval);
                            $(this).css('color', '#1E1E83');
                            $('#countdown').addClass('d-none');
                        }
                        timer2 = minutes + ':' + seconds;
                    }, 1000);
                });
            });
        </script>
	</body>
</html>