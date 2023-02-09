@extends('backend.layout', [
	'namePage' => 'Store',
	'activePage' => 'store_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Create New Store</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                  <li class="breadcrumb-item"><a href="/admin/store/list">Store</a></li>
						<li class="breadcrumb-item active">Create New Store</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<form action="/admin/store/save" method="POST">
				<div class="row">
					@csrf
					<!-- left column -->
					<div class="col-md-12">
						@if(session()->has('success'))
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								{!! session()->get('success') !!}
							</div>
						@endif
						@if(session()->has('error'))
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								{!! session()->get('error') !!}
							</div>
						@endif
						@if(count($errors->all()) > 0)
							<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
								@foreach ($errors->all() as $error)
								<span class="d-block">{{ $error }}</span>
								@endforeach
							</div>
						@endif
						<!-- general form elements -->
						<div class="card">
							<!-- /.card-header -->
							<div class="card-body">
								<h4>Store Registration</h4>
								<hr>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="store-name" class="form-label">* Store Name</label>
											<input type="text" class="form-control" id="store-name" name="store_name" value="{{ old('store_name') }}" required>
										</div>
										<div class="form-group">
											<label for="store-address">* Address</label>
											<textarea class="form-control" rows="3" id="store-address" name="address">{{ old('address') }}</textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="available-from" class="form-label">* Available From</label>
													<div class="input-group date" id="available-from" data-target-input="nearest">
														<input type="text" class="form-control datetimepicker-input" data-target="#available-from" name="available_from" value="{{ old('available_from') }}" required/>
														<div class="input-group-append" data-target="#available-from" data-toggle="datetimepicker">
															 <div class="input-group-text"><i class="far fa-clock"></i></div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="available-to" class="form-label">* Available To</label>
													<div class="input-group date" id="available-to" data-target-input="nearest">
														<input type="text" class="form-control datetimepicker-input" data-target="#available-to" name="available_to" value="{{ old('available_to') }}" required/>
														<div class="input-group-append" data-target="#available-to" data-toggle="datetimepicker">
															 <div class="input-group-text"><i class="far fa-clock"></i></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer text-center">
								<button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i>&nbsp;SUBMIT</button>
							</div>
						</div>
						<!-- /.card -->
					</div>
				</div>
			</form>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>
@endsection

@section('script')
<script>
	(function() {
		$('#available-from').datetimepicker({
			format: 'LT'
		});

		$('#available-to').datetimepicker({
			format: 'LT'
		});
	})();
</script>
@endsection
