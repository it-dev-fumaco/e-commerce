@extends('backend.layout', [
	'namePage' => 'Order Status',
	'activePage' => 'order_status'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Order Status</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Edit Order Status</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-6">
						<div class="card card-primary">
							<div class="card-body">
                                @if(session()->has('success'))
                                    <div class="alert alert-success fade show" role="alert">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="alert alert-warning fade show" role="alert">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif
                                <form action="/admin/order/status/{{ $status->order_status_id }}/edit" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9"><h4>Edit Order Status</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Status Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="status_name" value="{{ $status->status }}" placeholder="Status Name" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label>Status Description  <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="status_description" value="{{ $status->status_description }}" placeholder="Status Description" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label><input type="checkbox" name="update_stocks" {{ $status->update_stocks == 1 ? 'checked' : '' }}> Updates Stock</label>
                                        </div>
                                    </div>
                                    <div class="float-right font-italic">
                                        <small>Last modified by: {{ $status->last_modified_by }} - {{ $status->last_modified_at }}</small><br>
                                        <small>Created by: {{ $status->created_by }} - {{ $status->created_at }}</small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection