<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Fumaco CMS | Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{ asset('/assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="{{ asset('/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('/assets/admin/dist/css/adminlte.min.css') }}">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo mb-4">
				<a href="/admin">
					<img src="{{ asset('/assets/admin/logo-md.png') }}" alt="Fumaco" class="img-responsive">
				</a>
			</div>
			@if(session()->has('info'))
			<div class="row">
				<div class="col">
					<div class="alert alert-info fade show text-center" role="alert">
						{{ session()->get('info') }}
					  </div>
				</div>
			</div>
			@endif
			@if(session()->has('d_info'))
			<div class="row">
				<div class="col">
					<div class="alert alert-warning fade show text-center" role="alert">
						{{ session()->get('d_info') }}
					  </div>
				</div>
			</div>
			@endif
			@if(session()->has('error'))
			<div class="row">
				<div class="col">
					<div class="alert alert-danger fade show text-center" role="alert">
						{{ session()->get('error') }}
					  </div>
				</div>
			</div>
			@endif
			@if(count($errors->all()) > 0)
			<div class="row">
				<div class="col">
					<div class="alert alert-warning fade show text-center" role="alert">
						@foreach ($errors->all() as $error)
							{{ $error }}
						@endforeach 
					</div>
				</div>
			</div>
			@endif
			<div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">Sign in your account</p>
					<form action="/admin/login_user" method="POST" autocomplete="off">
						@csrf
						<div class="input-group mb-3">
							<input type="email" name="username" class="form-control" placeholder="Email" value="{{ old('username') }}" required>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-envelope"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" class="form-control" placeholder="Password" name="password" required>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="remember" name="remember">
									<label for="remember">Remember Me</label>
								</div>
							</div>
							<div class="col-4">
								<input type="submit" class="btn btn-primary btn-block" value="Sign In">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>