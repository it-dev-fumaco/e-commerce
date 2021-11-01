@extends('backend.layout', [
	'namePage' => 'Settings',
	'activePage' => 'google_api_setup'
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
						<li class="breadcrumb-item active">Google API Setup</li>
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
							<h3 class="card-title">Google API Setup</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form action="/admin/api_setup/save" method="POST" autocomplete="off">
							@csrf
							<input type="hidden" name="api_type" value="google_api">
							<div class="card-body">
								<div class="form-group">
									<label for="google-maps-api">Google Maps API</label>
									<input type="text" class="form-control" id="google-maps-api" name="google_maps_api" value="{{ ($gmap) ? $gmap->api_key : old('google_maps_api') }}" required>
								</div>
								<div class="form-group">
									<label for="google-analytics-api">Google Analytics API</label>
									<input type="text" class="form-control" id="google-analytics-api" name="google_analytics_api" value="{{ ($ganalytics) ? $ganalytics->api_key : old('google_analytics_api') }}" required>
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