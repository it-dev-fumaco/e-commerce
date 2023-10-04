@extends('backend.layout', [
	'namePage' => 'Settings',
	'activePage' => 'email_setup'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Settings</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Email Setup</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col-md-6">
					@if(session()->has('success'))
						<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
							{{ session()->get('success') }}
						</div>
					@endif
					@if(session()->has('error'))
						<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
							{{ session()->get('error') }}
						</div>
					@endif
					@if(count($errors->all()) > 0)
						<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
							@foreach ($errors->all() as $error)
								{{ $error }}
							@endforeach 
						</div>
					@endif
					<!-- general form elements -->
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Email Setup</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form action="/admin/email_setup/save" id="email-form" method="POST" autocomplete="off">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="driver">Driver</label>
									<input type="text" class="form-control" id="driver" name="driver" value="{{ ($details) ? $details->driver : old('driver') }}" required>
								</div>
								<div class="form-group">
									<label for="host">Host</label>
									<input type="text" class="form-control" id="host" name="host" value="{{ ($details) ? $details->host : old('host') }}" required>
								</div>
								<div class="form-group">
									<label for="port">Port</label>
									<input type="text" class="form-control" id="port" name="port" value="{{ ($details) ? $details->port : old('port') }}" required>
								</div>
								<div class="form-group">
									<label for="encryption">Encryption</label>
									<input type="text" class="form-control" id="encryption" name="encryption" value="{{ ($details) ? $details->encryption : old('encryption') }}" required>
								</div>
								<div class="form-group">
									<label for="username">Username</label>
									<input type="text" class="form-control" id="username" name="username" value="{{ ($details) ? $details->username : old('username') }}" required>
								</div>
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="form-control" id="password" name="password" value="{{ ($details) ? $details->password : old('password') }}" required>
								</div>
								<div class="form-group">
									<label for="address">Sender Email Address</label>
									<input type="text" class="form-control" id="address" name="address" value="{{ ($details) ? $details->address : old('address') }}" required>
								</div>
								<div class="form-group">
									<label for="name">Sender Name</label>
									<input type="text" class="form-control" id="name" name="name" value="{{ ($details) ? $details->name : old('name') }}" required>
								</div>
								<div class="float-right font-italic">
									<small>Last modified by: {{ ($details) ? $details->last_modified_by . ' - ' . $details->modified_date : '' }}</small>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer text-right">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#sendTestMail"><i class="fas fa-save"></i>&nbsp;Send Test E-mail</button>
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Save</button>

								<div class="modal fade text-center" id="sendTestMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header bg-primary">
												<h5 class="modal-title" id="exampleModalLabel">Send Test E-mail</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												Test E-mail will be sent to <b>{{ Auth::user()->username }}</b>. Continue?
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary send-test-mail">
													Send
													<div class="spinner-border spinner-border-sm d-none" role="status">
														<span class="sr-only">Loading...</span>
													</div>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				<!-- /.card -->
				</div>
				<div class="col-md-6">
					@if(session()->has('success_1'))
						<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
							{{ session()->get('success_1') }}
						</div>
					@endif
					@if(session()->has('error_1'))
						<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
							{{ session()->get('error_1') }}
						</div>
					@endif
					<!-- general form elements -->
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Email Recipients</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form action="/admin/email_recipients/save" method="POST" autocomplete="off">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="recipients">Email Recipients <small class="font-italic">(separated by comma " , ")</small></label>
									<textarea class="form-control" name="email_recipients" id="recipients" rows="4">{{ old('email_recipients') }}{{ ($details) ? $details->email_recipients : old('email_recipients') }}</textarea>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			function showNotification(color, message, icon){
				$.notify({
				  icon: icon,
				  message: message
				},{
				  type: color,
				  timer: 500,
				  z_index: 1060,
				  placement: {
					from: 'top',
					align: 'center'
				  }
				});
			}

			const sendTestMail = () => {
				$('.spinner-border').removeClass('d-none')
				$.ajax({
					type:'POST',
					url:'/admin/email_setup/send_test_mail',
					data: $('#email-form').serialize(),
					success: (response) => {
						const status = response.success ? 'success' : 'danger'
						showNotification(status, response.message, "fa fa-info")
						$('.spinner-border').addClass('d-none')
						$('#sendTestMail').modal('hide')
					},
					error: (xhr) => {
						showNotification("danger", 'An error occured. E-mail not sent!', "fa fa-info")
						$('.spinner-border').addClass('d-none')
					}
				});
			}

			$(document).on('click', '.send-test-mail', (e) => {
				e.preventDefault()

				sendTestMail()
			})
		});
	</script>
@endsection