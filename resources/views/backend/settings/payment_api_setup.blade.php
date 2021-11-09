@extends('backend.layout', [
	'namePage' => 'Settings',
	'activePage' => 'payment_api_setup'
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
						<li class="breadcrumb-item active">EGHL Payment API Setup</li>
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
							<h3 class="card-title">EGHL Payment API Setup</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form action="/admin/api_setup/save" method="POST" autocomplete="off">
							@csrf
                            <input type="hidden" name="api_type" value="payment_api">
							<div class="card-body">
                                <div class="form-group">
                                    <label for="base-url">Base URL</label>
                                    <input type="text" class="form-control" id="base-url" name="base_url" value="{{ ($api_details) ? $api_details->base_url : old('base_url') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="merchant-type">Merchant Type</label>
                                    <input type="text" class="form-control" id="merchant-type" name="merchant_type" value="{{ ($api_details) ? $api_details->merchant_type : old('merchant_type') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="service-id">Service ID</label>
                                    <input type="text" class="form-control" id="service-id" name="service_id" value="{{ ($api_details) ? $api_details->service_id : old('service_id') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" id="password" name="password" value="{{ ($api_details) ? $api_details->password : old('password') }}" required>
                                </div>
								<div class="float-right font-italic">
									<small>Last modified by: {{ ($api_details) ? $api_details->last_modified_by . ' - ' . $api_details->last_modified_at : '' }}</small>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
				<!-- /.card -->
				</div>
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection