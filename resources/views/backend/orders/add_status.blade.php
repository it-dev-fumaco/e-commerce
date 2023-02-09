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
							<li class="breadcrumb-item active">Add Order Status</li>
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
                                <form action="/admin/order/status/add" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9"><h4>Add Order Status</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Status Name *</label>
                                            <input type="text" class="form-control" name="status_name" placeholder="Status Name" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label>Status Description *</label>
                                            <input type="text" class="form-control" name="status_description" placeholder="Status Description" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label><input type="checkbox" name="update_stocks"> Updates Stock</label>
                                        </div>
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