@extends('backend.layout', [
	'namePage' => 'Product Attribute Settings',
	'activePage' => 'product_attribute_settings'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Product Attribute Settings</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item"><a href="/admin/product/list">Products</a></li>
						<li class="breadcrumb-item active">Attribute Settings</li>
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
				<div class="col-md-4">
					<div class="card">
						<!-- /.card-header -->
						<div class="card-body">
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
							<form action="/admin/product/settings" method="GET">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group row">
											<div class="col-md-8">
											<input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
											</div>
											<div class="col-md-4">
												<button type="submit" class="btn btn-primary">Search</button>
											</div>
										</div>
									</div>
								</div>
							</form>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">Category</th>
                              			<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($list as $item)
									<tr>
										<td class="text-center">{{ $item->name }}</td>
										<td class="text-center">
											<a href="{{ request()->fullUrlWithQuery(['cat_id' =>  $item->id, 'parent' => $item->name]) }} " class="btn btn-secondary btn-sm">Edit</a>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="2" class="text-center">No records found.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
							<div class="float-right mt-4">
								{{ $list->withQueryString()->links('pagination::bootstrap-4') }}
							</div>
						</div>
					  	<!-- /.card-body -->
					</div>
				  <!-- /.card -->
				</div>
				@if (isset(request()->parent))
				<div class="col-md-8">
					<div class="card">
						<!-- /.card-header -->
						<form action="/admin/attribute_status/{{ request()->cat_id }}/update" method="POST">
							@csrf
							<div class="card-body">
								@if(session()->has('attr_success'))
									<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
										{!! session()->get('attr_success') !!}
									</div>
								@endif
								@if(session()->has('attr_error'))
									<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
										{!! session()->get('attr_error') !!}
									</div>
								@endif
								<h4 class="text-center">{{ 'Attibutes of '. request()->parent }}</h4>
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">Attribute Name</th>
											<th class="text-center">Show in Website</th>
										</tr>
									</thead>
									<tbody>
										@forelse ($attributes as $i => $attr)
										<input type="hidden" name="attribute_name[]" value="{{ $attr->attribute_name }}">
										
										<tr>
											<td class="text-center">{{ $attr->attribute_name }}</td>
											<td class="text-center">
												<div class="custom-control custom-checkbox">
													<input type="hidden" name="show_in_website[]" value="{{ $attr->status }}">
													<input class="custom-control-input cb-siw" type="checkbox" id="cb{{ $i }}" {{ $attr->status == 1 ? 'checked' : '' }}>
													<label for="cb{{ $i }}" class="custom-control-label"></label>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="2" class="text-center">No records found.</td>
										</tr>
										@endforelse
									</tbody>
								</table>
								<div class="m-3 text-center">
									<button type="submit" class="btn btn-primary btn-lg">UPDATE</button>
								</div>
							</div>
						</form>
					  	<!-- /.card-body -->
					</div>
				  <!-- /.card -->
				</div>
				@endif
         </div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection

@section('script')
<script>
	 (function() {
		$(document).on('change', '.cb-siw', function(){
			var siw = ($(this).prop('checked')) ? 1 : 0;
			$(this).closest('tr').find('input[name="show_in_website[]"]').eq(0).val(siw);
		});
  	})();
</script>
@endsection